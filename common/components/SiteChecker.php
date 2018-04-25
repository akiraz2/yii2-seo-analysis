<?php
/**
 * Created by PhpStorm.
 * User: user4957
 * Date: 24.04.2018
 * Time: 23:59
 */

namespace common\components;

use GuzzleHttp\Client;

class SiteChecker
{
    /** @var Client $client */
    private $client;

    public $url;

    /**
     * SiteChecker constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $guzzle_client = new Client([
            'timeout' => 10,
            'verify' => false // ssl cert
        ]);
        $this->client = $guzzle_client;
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function sendRequest()
    {
        $response = [
            'code' => 0,
            'message' => ''
        ];
        try {
            $content = $this->client->request('GET', $this->url, [
                //'debug' => true,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
                ]
            ]);
            if ($content->getStatusCode() == 200) {
                $response['code'] = $content->getStatusCode();
                $response['message'] = '';
                return $response;
            }
        } catch (\Exception $exception) {
            \Yii::error($exception->getMessage());
            $response['code'] = $exception->getCode();
            $response['message'] = $exception->getMessage();
            return $response;
        }

        return $response;
    }

}