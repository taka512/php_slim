<?php

namespace Taka512\Http;

use Goutte\Client as GoutteClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Panther\Client;

class ClientFactory
{
    public static function createGoutte(array $option = []): GoutteClient
    {
        $goutte = new GoutteClient(HttpClient::create([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0',
            ],
            'timeout' => 30,
        ]));

        return $goutte;
    }

    public static function createChrome(array $option = []): Client
    {
        return Client::createChromeClient(
            null,
            null,
            [],
            $option['base_uri'] ?? null
        );
    }
}
