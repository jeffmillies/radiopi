<?php

class cache {
    static function set($key, $data, $write = true) {
        $_SESSION[$key] = json_encode(['expire' => time() + 3600, 'data' => $data]);
        if ($write) {
            self::write($key);
        }
        return true;
    }

    static function get($key) {
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

    static function isLoaded() {
        return (isset($_SESSION['loaded']) && $_SESSION['loaded']) ? true : false;
    }

    static function write($key = null) {
        if (!isset($_SESSION['write_cache']) || !is_array($_SESSION['write_cache'])) {
            $_SESSION['write_cache'] = [];
        }
        if ($key) {
            $_SESSION['write_cache'][] = $key;
        }
        return $_SESSION['write_cache'];
    }

    static function loadConfig() {
        $config = file::getConfig();
        foreach ($config as $key => $value) {
            self::set($key, $value, false);
        }
        self::set('loaded', true);
        return $config;
    }
}