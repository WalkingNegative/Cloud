<?php

namespace core;

use Symfony\Component\Yaml\Yaml;

class Config
{
    private static $instance = null;
    private $config;

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }


    private function __construct()
    {
        $this->config = $this->parseYaml();
    }

    public static function singleton() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function parseYaml()
    {
        $yaml = __DIR__ . '/data/config.yaml';
        return Yaml::parseFile($yaml, Yaml::PARSE_OBJECT_FOR_MAP);
    }

    public function get($parameter)
    {
        $methods = explode('.', $parameter);

        return array_reduce($methods, function ($carry, $item) {
            return $carry->$item;
        }, $this->config);
    }
}