<?php

namespace App\Repositories;

use App\Repositories\Interfaces\PDRepositoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Illuminate\Support\Arr;

class PDRepository implements PDRepositoryInterface
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function parseProfileData(string $profileLink)
    {
		if (empty($profileLink)) {
			return false;
		}
        $response = $this->client->get($profileLink, ['http_errors' => false]);
        if($response->getStatusCode() !== 200) {
            return false;
        }
        $response = $response->getBody()->getContents();

        $response = preg_replace("/\r|\n|\t/", '', $response);
        $result = [];
        preg_match( '/class="ipsType_minorHeading">Посещение.*?<\/time>/s' , $response , $ipline);
        if (!empty($ipline)) {
            preg_match( '/<time datetime=\'.*?<\/time>/s' , $ipline[0] , $lastActive);
            $result = array('lastActive' => preg_replace('/(.*\'>)(.*?)(<\/time>)/', '$2', $lastActive[0]));
        }
        return $result;
    }

    public function parseProfileAvatar(string $profileLink)
    {
		if (empty($profileLink)) {
			return false;
		}
        $response = $this->client->get($profileLink, ['http_errors' => false]);
        if($response->getStatusCode() !== 200) {
            return false;
        }
        $response = $response->getBody()->getContents();

        $response = preg_replace("/\r|\n|\t/", '', $response);

        preg_match( '/id="elProfilePhoto".*?<\/a>/' , $response , $links );
        if(isset($links[0])/* && !preg_match('/style_images\/Prodota_Images/', $links[0])*/)
            return preg_replace('/(.*?src=\")(.*?)(\".*)/', '$2', $links[0]);
        else
            return false;
    }

    public function parseVotes(string $gameLink = '')
    {
        $gameLink = 'https://prodota.ru/forum/topic/218487/';
        $page = 1;
        $url = '';
        do {
            $response = $this->client->get($gameLink . 'page/' . $page, [
                'on_stats' => function (TransferStats $stats) use (&$url) {
                    $url = $stats->getEffectiveUri();
                }
            ])->getBody()->getContents();

            $response = preg_replace("/\r|\n|\t/", '', $response);
            //dd($response);
            preg_match_all( '/<article  id=".*?<\/article>/' , $response , $posts );
            dd($posts);
        } while ($page > Arr::last(array_filter(explode('/', $url->getPath()))));
    }
}