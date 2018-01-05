<?php

class shout {
    static function curl($method = 'GET', $path = '', $params = []) {
        $query = http_build_query($params);
        $ch = curl_init();
        if ($method == 'GET') {
            $path .= "?" . $query;
        }
        $url = $path;
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);

        curl_close($ch);
        return $server_output;
    }

    static function getGenreList() {
        $key = 'genre_list';
        $data = cache::get($key);
        if (empty($data)) {
            $data = self::domGenreList(self::curl('GET', 'https://shoutcast.com/'));
            cache::set($key, $data);
            cache::set('write_cache', true);
        }
        return $data;
    }

    static function getStationsByGenre($genre) {
        $channels = cache::get('channel_list');
        $key = 'station_list_' . str_replace(' ', '', $genre);
        $data = cache::get($key);
        if (empty($data)) {
            $data = json_decode(self::curl('POST', 'https://shoutcast.com/Home/BrowseByGenre', ['genrename' => $genre]), true);
            cache::set($key, $data);
            cache::set('write_cache', true);
        }
        $channelIds = [];
        foreach ($channels as $channel) {
            if (isset($channel['ID'])) {
                $channelIds[] = $channel['ID'];
            }
        }
        foreach ($data as $index => $row) {
            $data[$index]['saved'] = (in_array($row['ID'], $channelIds) ? true : false);
        }
        return $data;

    }

    static function searchStations($search) {
        $data = json_decode(self::curl('POST', 'https://shoutcast.com/Search/UpdateSearch', ['query' => $search]), true);
        $key = 'channel_list';
        $channels = cache::get($key);
        $channelIds = [];
        foreach ($channels as $channel) {
            if (isset($channel['ID'])) {
                $channelIds[] = $channel['ID'];
            }
        }
        foreach ($data as $index => $row) {
            $data[$index]['saved'] = (in_array($row['ID'], $channelIds) ? true : false);
        }
        return $data;
    }

    static function getStationM3u($station) {
        $key = 'channel_list';
        $m3u = self::curl('GET', "http://yp.shoutcast.com/sbin/tunein-station.m3u", ['id' => $station['ID']]);
        file_put_contents(mpc::$loc['playlist'] . "{$station['ID']}.m3u", $m3u);
        $channels = cache::get($key);
        $addChannel = true;
        foreach ($channels as $channel) {
            if ($channel['ID'] == $station['ID']) {
                $addChannel = false;
            }
        }
        if ($addChannel) {
            $channels[] = $station;
            cache::set($key, $channels);
            cache::set('write_cache', true);
        }
        return ['name' => $station['ID'], 'content' => utf8_encode($m3u)];
    }

    static function deleteStationM3u($id) {
        unlink(mpc::$loc['playlist'] . $id . ".m3u");
        mpc::playlists();
        cache::set('write_cache', true);
        return true;
    }

    static function getSavedChannels() {
        $key = 'channel_list';
        $channels = cache::get($key);
        return $channels;
    }

    static function addSavedChannel($station) {

    }

    static function removeSavedChannel($station) {

    }

    static function domGenreList($html) {
        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        $list = [];
        $parent = 'Parent';
        foreach ($dom->getElementsByTagName('a') as $node) {
            if (stripos($node->getAttribute('href'), 'Genre?name=') === false) {
                continue;
            }
            if (stripos($node->parentNode->getAttribute('class'), 'b-gen') === false) {
                $parent = 'Parent';
            }
            $list[$parent][] = [
                'href' => $node->getAttribute('href'),
                'text' => $node->nodeValue,
                'parent' => $parent
            ];
            if (stripos($node->parentNode->getAttribute('class'), 'b-gen') === false) {
                $parent = $node->nodeValue;
            }
        }
        return $list;
    }
}