<?php

class mpc {
    static $loc = [
        'playlist' => '/var/lib/mpd/playlists/'
    ];

    static function playlists() {
        $files = array_diff(scandir(self::$loc['playlist']), ['..', '.']);
        $cache = cache::get('channel_list');
        $channels = [];
        $result = [];
        foreach ($cache as $channel) {
            if (isset($channel['ID'])) {
                $channels[$channel['ID']] = $channel;
            }
        }
        foreach ($files as $file) {
            $id = str_replace('.m3u', '', $file);
            if (isset($channels[$id])) {
                $result[] = $channels[$id];
            }
        }
        cache::set('channel_list', $result);
        return $result;
    }

    static function playlist($id) {
        $result = [];
        foreach (self::playlists() as $playlist) {
            if ($id == $playlist['ID']) {
                $result = $playlist;
                break;
            }
        }
        return $result;
    }

    static function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    static function play($station = null) {
        if ($station) {
            shell_exec("mpc stop");
            shell_exec("mpc clear");
            shell_exec("mpc load {$station}");
        }
        shell_exec("mpc play");
        shell_exec("mpc volume 100");
        sleep(1);
        return self::current();
    }

    static function stop() {
        return shell_exec("mpc stop");
    }

    static function current() {
        return shell_exec("mpc current");
    }

}