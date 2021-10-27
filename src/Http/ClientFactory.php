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
        // https://github.com/symfony/panther/blob/main/src/Client.php#L70
        // https://github.com/symfony/panther/blob/main/src/ProcessManager/ChromeManager.php#L37
        // arguments = [
        //    '--headless',                      // PANTHER_NO_HEADLESS=true
        //    '--window-size=1200,1100',         // PANTHER_NO_HEADLESS=true
        //    '--disable-gpu',                   // PANTHER_NO_HEADLESS=true
        //    '--auto-open-devtools-for-tabs',   // PANTHER_DEVTOOLS=true
        //    '--no-sandbox',                    // PANTHER_NO_SANDBOX=true
        //    or PANTHER_CHROME_ARGUMENTS=xxx
        // ];
        // option = [
        //     'scheme' => 'http',
        //     'host' => '127.0.0.1',
        //     'port' => 9515,
        //     'path' => '/status',
        //     'chromedriver_arguments' => [],
        //     'capabilities' => [],
        // ];
        return Client::createChromeClient(
            null,
            null,
            [
                'capabilities' => $option['capabilities'] ?? [],
            ],
            $option['base_uri'] ?? null
        );
    }
}
