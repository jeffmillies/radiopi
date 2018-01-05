<?php

class cache {
    static function set($key, $data) {
        $_SESSION[$key] = json_encode(['expire' => time() + 3600, 'data' => $data]);
//        return apcu_store($key,  json_encode(['expire' => time()+3600, 'data' => $data]));
        return true;
    }

    static function get($key) {
//        if (apcu_exists($key)) {
//            $raw = apcu_fetch($key);
//            $value = json_decode($raw, true);
//            if (json_last_error() !== JSON_ERROR_NONE) {
//                $value = $raw;
//            }
//        } else {
//            $value = ['expire' => time(), 'data' => []];
//        }
//
//        if (time() >= $value['expire']) {
//            $value = ['expire' => time(), 'data' => []];
//        }
        if (isset($_SESSION[$key])) {
            $raw = $_SESSION[$key];
            $value = json_decode($raw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $value = $raw;
            }
        } else {
            $value = ['expire' => time(), 'data' => []];
        }

        if (time() >= $value['expire']) {
            $value = ['expire' => time(), 'data' => []];
        }
        return $value['data'];
    }

    static function loadConfig() {
        $config = file::getConfig();
        foreach ($config as $key => $value) {
            self::set($key, $value);
        }
        return $config;
    }
}