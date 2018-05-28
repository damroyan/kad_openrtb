<?php
/**
 * Created by PhpStorm.
 * User: dmitryamroyan
 * Date: 28.05.18
 * Time: 22:47
 */

namespace Tizer;

class RelapTGB {

    // адрес откуда забирать openRtb
    public $url;

    // массив параметров
    public $params = [];

    // режим отладки
    public $debug;

    function __construct($url, $debug = false) {
        $this->url = $url;

        $this->debug = $debug;
    }


    /**
     * Запрос на получение блоков
     *
     * @return array
     */
    public function get() {

        if ($this->debug) {
            echo '<pre> Запрашиваем на урл: '.$this->url;
            print_r($this->params);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->params));
        $body = curl_exec($ch);
        $ok = !curl_errno($ch) && 200 == curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($this->debug) {
            echo '<pre> Получаем ответ('.$ok.'): ' ;

            print_r(json_decode($body,true));
            print_r($info);
        }

        if ($ok == 1) {
            $tgb = json_decode($body,true);
            if (isset($tgb['seatbid'][0]['bid']))
                return $this->convertBlocks($tgb['seatbid'][0]['bid']);
            else
                return [];
        } else {
            return [];
        }
    }

    /**
     * Конывертация в упрощенный формат
     *
     * @param array $blocks
     * @return array
     */
    public function convertBlocks($blocks = []) {
        $return = [];
        if (count($blocks)) {
            foreach ($blocks as $b) {
                $data = json_decode($b['adm'], true);

                $return[] = [
                    'tracking_pixel'    => $b['nurl'],
                    'price_cpc'         => floatval($b['price_cpc']),
                    'ecpm'              => floatval($b['price']),
                    'url'               => $data['native']['link']['url'],
                ];

                $index = count($return)-1;
                $assets = $data['native']['assets'];
                foreach ($assets as $a) {
                    if (isset($a['title'])) {
                        $return[$index]['text'] = $a['title']['text'];
                    }

                    if (isset($a['img'])) {
                        $return[$index]['img'] = $a['img'];
                    }
                }


            }
        }
        return $return;
    }

    /**
     * @param $url урл страницы с которой теоритечески идет запрос
     * @param $ip IP пользователя для которого идет запрос
     * @param int $count количество запрашиваемых блоков
     * @param float $bidfloor минимальная ставка которую готовы забрать
     * @param string $geo трехсимвольный ГЕО
     * @return array - массив параметров для отправки
     */
    public function getParams($url, $count = 1, $ip = null, $bidfloor = 0.1, $geo = 'RUS') {

        if ($ip === null) {
            if ($_SERVER['HTTP_CLIENT_IP']) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
            else if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            }
            else if($_SERVER["HTTP_X_REAL_IP"]) {
                $ip = $_SERVER["HTTP_X_REAL_IP"];
            }
            else if($_SERVER["REMOTE_ADDR"]) {
                $ip = $_SERVER["REMOTE_ADDR"];
            }
            else {
                $ip = '127.0.0.1';
            }
        }

        $url        = $url;
        $pieces     = parse_url($url);
        $domain     = $pieces['host'];

        $request = [
            "native"    => [
                "adunit"    => 5,
                "ver"       => 1,
                "assets"    => [
                    [
                        "required"  => 0,
                        "id"        => 0,
                        "title"     => [
                            "len"       => 100,
                        ]
                    ],
                    [
                        "required"  => 1,
                        "id"        => 5,
                        "img"       => [
                            "type"      => 3,
                        ]
                    ]
                ],
                "seq"       => 0,
                "plcmtcnt"  => 1
            ]
        ];

        $imp = [];
        for ($i = 1; $i <= $count; $i++) {
            array_push($imp, [
                "id"            => $i,
                "tagid"         => "rtb",
                "bidfloorcur"   => "RUB",
                "bidfloor"      => floatval($bidfloor),
                "secure"        => 1,
                "native"        => [
                    "ver"           => "1.0.0.1",
                    "request"       => json_encode($request)
                ]
            ]);
        }

        $params = array(
            "device"    => [
                "ip"        => $ip,
                "ua"        => $_SERVER['HTTP_USER_AGENT']
            ],
            "geo"       => [
                "country"   => $geo,
            ],
            "imp"       => $imp,
            "id"        => rand(1000, 100000000),
            "site"      => [
                "id"        => md5($domain),
                "domain"    => $domain,
                "page"      => $url,
            ]
        );

        return $this->params = $params;
    }
}