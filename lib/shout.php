<?php

class shout {

    static function getGenreList() {
        $key = 'genre_list';
        $data = cache::get($key);
        if (empty($data)) {
            $data = self::domGenreList(curl::get('https://shoutcast.com/'));
            cache::set($key, $data);
        }
        return $data;
    }

    static function getStationsByGenre($genre) {
        $channels = cache::get('channel_list');
        $key = 'station_list_' . str_replace(' ', '', $genre);
        $data = cache::get($key);
        if (empty($data)) {
            $data = json_decode(curl::post('https://shoutcast.com/Home/BrowseByGenre', ['genrename' => $genre]), true);
            cache::set($key, $data);
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
        $data = json_decode(curl::post('https://shoutcast.com/Search/UpdateSearch', ['query' => $search]), true);
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
        $m3u = curl::get("http://yp.shoutcast.com/sbin/tunein-station.m3u", ['id' => $station['ID']]);
        file_put_contents(mpc::$loc['playlist'] . "{$station['ID']}.m3u", $m3u);
        return ['name' => $station['ID'], 'content' => utf8_encode($m3u)];
    }

    static function deleteStationM3u($id) {
        unlink(mpc::$loc['playlist'] . $id . ".m3u");
        mpc::playlists();
        return true;
    }

    static function getSavedChannels() {
        $key = 'channel_list';
        $channels = cache::get($key);
        return $channels;
    }

    static function saveChannel($station) {
        $key = 'channel_list';
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
        }
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