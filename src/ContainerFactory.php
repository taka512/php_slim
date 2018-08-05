<?php

namespace Taka512;

use Slim\Container;

class ContainerFactory
{
    private static $container;


	public static function getContainer()
	{
	    if (isset(self::$container)) {
            return self::$container;
		}
        self::$container = new Container(Env::getSetting());
        return self::$container;
	}
}
