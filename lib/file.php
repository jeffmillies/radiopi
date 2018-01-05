<?php

class file {
    static $config = [
        'genre_list', 'channel_list'
    ];

    static function base() {
        return str_replace(['/lib'], [''], dirname(__FILE__));
    }

    static function save() {

    }

    static function config() {
        return self::base() . "/lib/radiopi.json";
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
        foreach (self::$config as $key) {
            $config[$key] = cache::get($key);
        }
        return file_put_contents(self::config(),
            json_encode($config)
        );
    }
}