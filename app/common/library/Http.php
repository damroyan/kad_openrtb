<?php

namespace Tizer;
class Http {
    static function put_json($url, array $data) {
        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->put($url, ['headers' => ['Expect'=>'', 'Content-Type'=>'application/json'], 'body' => json_encode($data)]);
            return $res->getStatusCode() == 200;
        }
        catch (\GuzzleHttp\Exception\TransferException $e) {
            return false;
        }
    }

    static function get_body($url, $params) {
        $client = new \GuzzleHttp\Client();

        $res = $client->get($url, ['query' => $params]);
        if ($res->getStatusCode() != 200) {
            throw new \Exception('http unreachable');
        }

        return $res->getBody();
    }

    static function get_json($url, $params) {
        $client = new \GuzzleHttp\Client();

        $res = $client->get($url, ['query' => $params]);
        if ($res->getStatusCode() != 200) {
            throw new \Exception('http unreachable');
        }

        return json_decode($res->getBody(), true);
    }
}