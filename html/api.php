<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

if (php_sapi_name() !== 'cli') {
    $request = $_REQUEST;
} else {
    $request = [];
    list($request['file'], $request['command'], $request['id']) = $argv;
}
$result = [
    'success' => false,
    'message' => '',
    'error' => 'Command not set'
];
if (isset($request['command'])) {
    $result = [
        'success' => true,
        'message' => '',
        'error' => false
    ];
    $basedir = str_replace(['/html'], [''], dirname(__FILE__));
    foreach (glob("$basedir/lib/*.php") as $filename) {
        include $filename;
    }
    cache::set('write_cache', false);

    switch ($request['command']) {
        case 'channels':
            $result['data'] = cache::get('channel_list');
            break;
        case 'play':
            $result['data'] = mpc::play((isset($request['id']) ? $request['id'] : null));
            cache::set('channel_current', mpc::playlist($request['id']));
            cache::set('write_cache', true);
            break;
        case 'stop':
            $result['data'] = mpc::stop();
            cache::set('channel_current', []);
            cache::set('write_cache', true);
            break;
        case 'next':
            break;
        case 'back':
            break;
        case 'genres':
            $result['data'] = shout::getGenreList();;
            break;
        case 'stations':
            $result['data'] = shout::getStationsByGenre($request['id']);
            $result['html'] = file::capture('results.php', $result['data']);
            break;
        case 'search':
            $result['data'] = shout::searchStations($request['id']);
            $result['html'] = file::capture('results.php', $result['data']);
            break;
        case 'save':
            $station = json_decode($request['id'], true);
            $result['data'] = shout::getStationM3u($station);
            shout::saveChannel($station);
            cache::set('write_cache', true);
            break;
        case 'listen':
            $station = json_decode($request['id'], true);
            shout::getStationM3u($station);
            $result['data'] = mpc::play($station['ID']);
            cache::set('channel_current', $station);
            cache::set('write_cache', true);
            break;
        case 'delete':
            $result['data'] = shout::deleteStationM3u($request['id']);
            cache::set('write_cache', true);
            break;
        default:
            $result = [
                'success' => false,
                'message' => '',
                'error' => "Unknown '{$request['command']}' command'"
            ];
            break;
    }
    if (cache::get('write_cache')) {
        file::setConfig();
    }
}
$message = json_encode($result);
$error = json_last_error_msg();
if ($error == 'No error') {
    echo $message;
} else {
    echo json_encode([
        'success' => false,
        'message' => $error,
        'error' => $error
    ]);
}