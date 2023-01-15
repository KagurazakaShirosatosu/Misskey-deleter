<?php

require 'vendor/autoload.php';
use GuzzleHttp\Client;

define('MISSKEY_TOKEN'     , '<your token>');
define('MISSKEY_API'       , 'https://<the domain of your instance>/api/');
define('DELETE_DAYS_BEFORE', 30);
define('EXCLUDE_NOTES'     , [
    // note id here
]);

while(true) {
    main_handler();
    sleep(600);
}

function main_handler() {
    $list = getNotesList();
    deleteNotes($list);
}

function deleteNotes($notes) {
    $client = new Client(['base_uri' => MISSKEY_API]);
    foreach($notes as $note) {
        $req = $client->post('notes/delete', [
            'json' => [
                'i' => MISSKEY_TOKEN,
                'noteId' => $note
            ]
        ]);
        $resp = $req->getStatusCode();
        if ($resp == 204) {
            echo $note . ' DELETED.' . PHP_EOL;
        }
        sleep(2);
    }
}

function getNotesList($limit = 100) {
    $client = new Client(['base_uri' => MISSKEY_API]);
    $req = $client->post('users/notes', [
        'json' => [
            'i' => MISSKEY_TOKEN,
            'userId' => getID(),
            'includeReplies' => true,
            'includeMyRenotes' => true,
            'excludeNsfw' => false,
            'limit' => $limit,
            'sinceDate' => 0,
            'untilDate' => getTimestamp(DELETE_DAYS_BEFORE)
        ]
    ]);
    $list = [];
    $resp = json_decode($req->getBody());
    foreach ($resp as $note) {
        if ( ! in_array($note->id, EXCLUDE_NOTES) ) {
            array_push($list, $note->id);
        }
    }
    return $list;
}

function getTimestamp($when = 0) {
    return (int)(time() - 86400 * $when . '000');
}

function getID() {
    $client = new Client(['base_uri' => MISSKEY_API]);
    $req = $client->post('i', [
        'json' => [
            'i' => MISSKEY_TOKEN
        ]
    ]);
    $resp = json_decode($req->getBody());
    return $resp->id;
}