<?php

class file {
    static $config = [
        'genre_list', 'channel_list', 'channel_current'
    ];

    static function base() {
        return str_replace(['/lib'], [''], dirname(__FILE__));
    }

    static function save() {

    }

    static function config() {
        return self::base() . "/lib/radiopi.json";
    }

    static function capture($file, $data) {
        ob_start();
        include(self::base() . "/templates/" . $file);
        $contents = ob_get_contents();
        ob_end_clean();
        return trim($contents);
    }

    static function getConfig() {
        if (!file_exists(self::config())) {
            return [];
        }
        return json_decode(
            file_get_contents(self::config())
            , true);
    }

    static function setConfig() {
        $config = [];
        foreach (cache::write() as $key) {
            $config[$key] = cache::get($key);
        }
        if (cache::isLoaded() === false) {
            cache::loadConfig();
        }
        foreach (self::$config as $key) {
            if (!isset($config[$key])) {
                $config[$key] = cache::get($key);
            }
        }
        $result = file_put_contents(self::config(),
            json_encode($config)
        );
        cache::loadConfig();
        return $result;
    }
}