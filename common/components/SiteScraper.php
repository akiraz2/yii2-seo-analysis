<?php
declare(strict_types=1);

namespace common\components;

use common\models\SitePage;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use yii\base\Component;
use yii\base\Exception;
use GuzzleHttp\Client as GuzzleClient;
use yii\helpers\HtmlPurifier;
use yii\helpers\StringHelper;


/**
 * Class SiteScraper
 * @package common\components
 */
class SiteScraper extends Component
{
    /** @var string $startUrl */
    public $startUrl;

    /** @var integer $snapshotId */
    public $snapshotId;

    /** @var int $countPages */
    public $countPages=0;

    /** @var Client $client */
    private $client;

    private $checkedUrl = [];

    private $crawler;

    private $urlList = [];


    /**
     *
     */
    public function init()
    {
        $client = new Client();
        $guzzle_client = new GuzzleClient([
            'timeout' => 60,
            'verify' => false
        ]);
        $client->setClient($guzzle_client);
        $this->client = $client;
    }

    /**
     *
     */
    public function start()
    {
        $this->checkConfig();
        $this->scrapePage($this->startUrl);
    }

    /**
     * @return Exception
     */
    private function checkConfig()
    {
        if (!$this->startUrl || !$this->snapshotId || !$this->client) {
            return new Exception('No start Url or snapshot ID or Client is null');
        }
        $this->startUrl = preg_replace("#/$#", "", $this->startUrl);
    }

    /**
     * @param string $url
     * @return void
     */
    private function scrapePage(string $url): void
    {
        $crawler = $this->client->request('GET', $url);
        var_dump($url.' '. $this->client->getResponse()->getHeader('Content-Type').' '. count($this->checkedUrl).' '. count($this->urlList));

        array_push($this->checkedUrl, $url);

        $result = preg_match('/[\w\-]+\.(jpg|png|gif|jpeg|xlsx|svg|docs|xls|doc)/', $url, $matches);
        if ($this->client->getResponse()->getStatus() !== 200
            || strpos($this->client->getResponse()->getHeader('Content-Type'), 'text/html') === false
            || (isset($matches[0]))
        ) {

            return;
        }

        $this->savePage($crawler, $url);

        $crawler->filter('a')->reduce(function (Crawler $node, $i) {
            // filters every other node
            $url_temp = $this->getLink($node);
            return !in_array($url_temp, $this->urlList);
        })->each(function ($node) use (&$url_list) {
            /** @var $node Crawler */
            array_push($this->urlList, $this->getLink($node));
        });

        $this->urlList = array_unique($this->urlList);
        $start_url = $this->startUrl;
        $this->urlList = array_filter($this->urlList, function ($element) use ($start_url) {
            return (strpos($element, $start_url) !== false) && (strpos($element, '#') === false) && !in_array($element,
                    $this->checkedUrl);
        });

        $this->urlList = array_values($this->urlList);

        for ($i = 0; $i < count($this->urlList); $i++) {
            $url = $this->urlList[$i];
            $this->scrapePage($url);
        }
    }

    /**
     * @param Crawler $crawler
     * @param string $url
     * @return int
     */
    private function savePage(Crawler $crawler, string $url)
    {
        if (SitePage::find()->where(['url' => $url])->exists()) {
            return false;
        }

        $model = new SitePage();
        $model->site_snapshot_id = $this->snapshotId;
        $model->url = $url;
        $model->status_code = $this->client->getResponse()->getStatus();
        $model->title = trim($crawler->filter('title')->text());
        $model->meta_description = $this->getTag($crawler, 'meta[name="description"]', 'content');
        $model->meta_keyword = $this->getTag($crawler, 'meta[name="keywords"]', 'content');
        $model->tag_h1 = $this->getTag($crawler, 'h1');
        $model->body = $crawler->html();
        $this->countPages++;
        return $model->save();
    }

    /**
     * @param Crawler $crawler
     * @param $name
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
     * @param Crawler $node
     * @return null|string|string[]
     */
    private function getLink(Crawler $node)
    {
        return preg_replace("#/$#", "", $node->link()->getUri());
    }
}