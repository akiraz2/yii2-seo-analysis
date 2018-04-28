<?php
declare(strict_types=1);

namespace common\components;

use common\models\SiteLog;
use common\models\SitePage;
use common\models\SiteSnapshot;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\DomCrawler\Crawler;
use yii\base\Component;
use yii\base\Exception;


/**
 * Class SiteScraper
 * @package common\components
 */
class SiteScraper extends Component
{
    const CMD_ACTIVE = 1;
    const CMD_PAUSE = 2;
    const CMD_STOP = -1;

    const STATUS_ACTIVE = 1;
    const STATUS_PAUSED = 2;
    const STATUS_STOPPED = 3;
    const STATUS_ENDED = 4;

    public $cmd;

    /** @var string $startUrl */
    public $startUrl;

    /** @var integer $snapshotId */
    public $snapshotId;

    /** @var int $countPages */
    public $countPages = 0;

    /** @var Client $client */
    private $client;
    /** @var array $checkedUrl list of parsed urls */
    private $checkedUrl = [];

    private $crawler;
    /** @var array $urlList url List in memory */
    private $urlList = [];
    /** @var SiteSnapshot $snapshot */
    private $snapshot;

    /**
     *  init component after construct
     *
     */
    public function init()
    {
        $client = new Client();
        $guzzle_client = new GuzzleClient([
            'timeout' => 30,
            'verify' => false // ssl cert
        ]);
        $client->setClient($guzzle_client);
        $this->client = $client;
    }

    /**
     *
     * @throws \yii\base\ExitException
     */
    public function start()
    {
        $this->checkConfig();
        $this->snapshot->updateAttributes([
            'count_pages' => 0,
            'error404' => 0,
            'error500' => 0,
            'redirect300' => 0,
            'status' => self::STATUS_ACTIVE
        ]);//обнуляем перед стартом
        $this->saveRobotsTxt();
        $this->saveSitemapXml();
        $this->scrapePage($this->startUrl);
        $this->snapshot->updateAttributes([
            'count_pages' => $this->countPages,
            'status' => self::STATUS_ENDED
        ]);//записываем конечное число страниц
    }

    /**
     * @return Exception
     */
    private function checkConfig()
    {
        if (!$this->startUrl || !$this->snapshotId || !$this->client) {
            return new Exception('No start Url or snapshot ID or Client is null');
        }

        if (($this->snapshot = SiteSnapshot::find()->where(['id' => $this->snapshotId])->one())) {
            return new Exception('Not found Site Snapshot with id=' . $this->snapshotId);
        }

        $this->startUrl = preg_replace("#/$#", "", $this->startUrl);
    }

    /**
     * Save robots.txt to DB for processing
     *
     */
    private function saveRobotsTxt()
    {
        $guzzle_client = new GuzzleClient([
            'timeout' => 30,
            'verify' => false // ssl cert
        ]);
        try {
            $content = $guzzle_client->request('GET', $this->startUrl . '/robots.txt');

            if ($content->getStatusCode() == 200) {
                $this->snapshot->updateAttributes(['robots_txt' => $content->getBody()]);
            }
        }
        catch (\Exception $exception) {
            SiteLog::create('robots-txt', $exception->getMessage(), $this->snapshotId);
        }
    }

    /**
     * Save sitemap.xml to disk (webroot/sitemap/user_id/md5().xml)
     *
     * @return bool|int
     */
    private function saveSitemapXml()
    {
        $guzzle_client = new GuzzleClient([
            'timeout' => 30,
            'allow_redirects' => true,
            'verify' => false // ssl cert
        ]);

        try {
            $content = $guzzle_client->request('GET', $this->startUrl . '/sitemap.xml', [
                //'debug' => true,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
                ]
            ]);
            if ($content->getStatusCode() == 200) {
                return $this->snapshot->setSitemapXml($content->getBody());
            }
        } catch (\Exception $exception) {
            SiteLog::create('sitemap-xml', $exception->getMessage(), $this->snapshotId);
        }
        return false;
    }

    /**
     *  Scrape and process page
     *  may be need refactoring filter array (for cpu and memory optimization)
     *
     *
     * @param string $url
     * @return void
     * @throws \yii\base\ExitException
     */
    private function scrapePage(string $url): void
    {
        if ($this->countPages % 10 == 0) {
            $this->processCmd();
        }

        $time_start = microtime(true);
        $crawler = $this->client->request('GET', $url);
        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $this->updateStatusCode($url);

        var_dump($url . ' ' . $this->client->getResponse()->getHeader('Content-Type') . ' ' . count($this->checkedUrl) . ' ' . count($this->urlList) . ' ' . (int)($time * 1000));

        array_push($this->checkedUrl, $url);

        $result = preg_match('/[\w\-]+\.(jpg|png|gif|jpeg|xlsx|svg|docs|xls|doc)/', $url, $matches);
        if ($this->client->getResponse()->getStatus() !== 200
            || strpos($this->client->getResponse()->getHeader('Content-Type'), 'text/html') === false
            || (isset($matches[0]))
        ) {

            return;
        }

        $this->savePage($crawler, $url, $time);

        $crawler->filter('a')->reduce(function (Crawler $node, $i) {
            // filters every other node
            $url_temp = $this->getLink($node);
            $result = preg_match('/[\w\-]+\.(jpg|png|gif|jpeg|xlsx|svg|docs|xls|doc)/', $url_temp, $matches);
            return !isset($matches[0]) && !in_array($url_temp, $this->urlList);
        })->each(function ($node) use (&$url_list) {
            /** @var $node Crawler */
            array_push($this->urlList, $this->getLink($node));
        });

        $this->urlList = array_unique($this->urlList);
        $start_url = $this->startUrl;
        $this->urlList = array_filter($this->urlList, function ($element) use ($start_url) {
            return (strpos($element, $start_url) !== false) && (strpos($element, '#') === false)
                && !in_array($element, $this->checkedUrl);
        });

        $this->urlList = array_values($this->urlList);

        for ($i = 0; $i < count($this->urlList); $i++) {
            $url = $this->urlList[$i];
            $this->scrapePage($url);
        }
    }

    /**
     * @throws \yii\base\ExitException
     */
    private function processCmd()
    {
        switch ($this->cmd()) {

            case self::CMD_PAUSE:
                $this->snapshot->updateAttributes([
                    'status' => self::STATUS_PAUSED,
                    'count_pages' => $this->countPages
                ]);

                for ($i = 0; $i < \Yii::$app->params['maxPause']; $i++) {
                    sleep(\Yii::$app->params['pauseScraping']);
                    if ($this->cmd() != self::CMD_PAUSE) {
                        break;
                    }
                }

                $this->snapshot->updateAttributes(['status' => self::STATUS_ACTIVE, 'cmd' => self::CMD_ACTIVE]);

                //break;

            case self::CMD_STOP:
                $this->snapshot->updateAttributes(['status' => self::STATUS_ENDED, 'cmd' => self::CMD_STOP]);
                \Yii::$app->end();
                break;
        }
    }

    /**
     * @return int|mixed
     */
    public function cmd()
    {
        if (!empty($this->cmd)) {
            return call_user_func($this->cmd, $this);
        }
        return $this->cmd;
    }

    /**
     * Update site-snapshot`s counters error 404, 500, 300
     * @param string $url
     */
    private function updateStatusCode(string $url = '')
    {
        if ($this->client->getResponse()->getStatus() == 404) {
            $this->snapshot->updateCounters(['error404' => 1]);
            $message = \Yii::t('app-model', 'Error: {code}, Url: {url}', [
                'code' => 404,
                'url' => $url
            ]);
            SiteLog::create('scraper', $message, $this->snapshotId);
        }

        if ($this->client->getResponse()->getStatus() >= 500) {
            $this->snapshot->updateCounters(['error500' => 1]);
            $message = \Yii::t('app-model', 'Error: {code}, Url: {url}', [
                'code' => $this->client->getResponse()->getStatus(),
                'url' => $url
            ]);
            SiteLog::create('scraper', $message, $this->snapshotId);
        }

        if ($this->client->getResponse()->getStatus() >= 300 && $this->client->getResponse()->getStatus() < 400) {
            $this->snapshot->updateCounters(['redirect300' => 1]);
            $message = \Yii::t('app-model', 'Redirect: {code}, Url: {url}', [
                'code' => $this->client->getResponse()->getStatus(),
                'url' => $url
            ]);
            SiteLog::create('scraper', $message, $this->snapshotId);
        }
    }

    /**
     *  Save page to DB if $url not found in DB
     *
     * @param Crawler $crawler
     * @param string $url
     * @param float $time
     * @return int
     */
    private function savePage(Crawler $crawler, string $url, float $time = 0)
    {
        $this->updateCountPages();

        if (SitePage::find()->where(['url' => $url])->exists()) {
            return false;
        }

        $model = new SitePage();
        $model->site_snapshot_id = $this->snapshotId;
        $model->url = $url;
        $model->canonical = $this->getTag($crawler, 'link[rel="canonical"]', 'href');
        $model->status_code = $this->client->getResponse()->getStatus();
        $model->request_time = (int)($time * 1000);
        $model->title = trim($crawler->filter('title')->text());
        $model->meta_description = $this->getTag($crawler, 'meta[name="description"]', 'content');
        $model->meta_keyword = $this->getTag($crawler, 'meta[name="keywords"]', 'content');
        $model->tag_h1 = $this->getTag($crawler, 'h1');
        $model->og_main = $this->countOpengraph($crawler, 1);
        $model->og_option = $this->countOpengraph($crawler, 2);
        $model->body = $crawler->html();
        $model->validate();
        var_dump($model->getErrors());
        return $model->save();
    }

    /**
     * Periodic update counter of pages
     *
     */
    private function updateCountPages()
    {
        $this->countPages++;
        if ($this->countPages % 10 == 0) {
            $this->snapshot->updateAttributes(['count_pages' => $this->countPages]);
        }
    }

    /**
     * Get tag by name and attr(or text)
     *
     * @param Crawler $crawler
     * @param $name
     * @param string $attr
     * @return null|string
     */
    private function getTag(Crawler $crawler, $name, $attr = '')
    {
        $tags = $crawler->filter($name);
        if ($tags->count()) {
            return trim($attr ? $tags->attr($attr) : $tags->text());
        }
        return null;
    }

    /**
     * Count opengraph tags (count of main og tags = 4, optionally - 5)
     *
     * @param Crawler $crawler
     * @param int $main 1 is main og tags, 2 is optionally og tags
     * @return int
     */
    private function countOpengraph(Crawler $crawler, int $main = 0)
    {
        $og_counter = 0;
        $og_array = [];
        if ($main == 1) {
            $og_array = ['og:title', 'og:type', 'og:image', 'og:url'];
        } else {
            $og_array = ['og:description', 'og:site_name', 'og:locale', 'og:video', 'og:audio'];
        }

        for ($i = 0; $i < count($og_array); $i++) {
            $og_content = $this->getTag($crawler, 'meta[property="' . $og_array[$i] . '"]', 'content');
            if ($og_content && strlen($og_content) > 0) {
                $og_counter++;
            }
        }
        return $og_counter;
    }

    /**
     *  Cut slash trailing
     *
     * @param Crawler $node
     * @return null|string|string[]
     */
    private function getLink(Crawler $node)
    {
        return preg_replace("#/$#", "", $node->link()->getUri());
    }
}