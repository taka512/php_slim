<?php

namespace Taka512\Http\Scraper;

use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;

class ClientFactory
{
    public static function createGoutte(array $option = []): GoutteClient
    {
        $goutte = new GoutteClient();
        $goutte->setHeader('user-agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0');
        $goutte->setClient(new GuzzleClient([
            'base_uri' => $option['base_uri'] ?? null,
            RequestOptions::VERIFY => false,
            RequestOptions::COOKIES => true,
            RequestOptions::DEBUG => $option['debug'] ?? false,
        ]));

        return $goutte;
    }
}
