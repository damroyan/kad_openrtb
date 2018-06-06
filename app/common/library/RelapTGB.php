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

    public $sessionUuid = "";

    public $cookie = "";

    public $referrer = "unknown";

    public $bidfloor = 0.1;

    public $partner_id = "undefined";

    function __construct($url, $debug = false) {

        $this->url = $url;
        $this->debug = $debug;
        $this->config = \Phalcon\DI\FactoryDefault::getDefault()->getShared('config');
    }

    public function setSession($string) {
        $this->sessionUuid = $string;
    }

    public function setCookie($string) {
        $this->cookie = $string;
    }

    public function setReferrer($url = "") {
        $this->referrer = $url;
    }

    public function setBidfloor($price) {
        $this->bidfloor = floatval($price);
    }

    public function setPartnerID($string) {
        $this->partner_id = $string;
    }


    /**
     * Запрос на получение блоков
     *
     * @return array
     */
    public function get($count = 1, $geo = 'RUS') {

        $this->getParams($count, $geo);

        if ($this->debug) {
            echo '<pre> Запрашиваем на урл: '.$this->url;
            print_r($this->params);
        }
//    echo json_encode($this->params);
//        exit;
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
     * Конвертация в упрощенный формат
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
                    'nurl'              => $b['nurl'],
                    'tracking_pixel'    => $this->trackUrl($b['nurl'],'imp', ['id' => $b['id']]),
                    'url'               => $this->trackUrl($data['native']['link']['url'], 'click', ['id' => $b['id']]),
                    'price_cpc'         => floatval($b['price_cpc']),
                    'ecpm'              => floatval($b['price']),
                    'id'                => $b['id'],
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
     * генерация трекинг ссылок
     *
     * @param string $url
     * @param string $action
     * @param array $additional_params
     * @return string
     */
    public function trackUrl($url = "", $action = "request", $additional_params = []) {

        $params = [
            'session'   => $this->sessionUuid,
            'client'    => $this->cookie,
            'url'       => $url,
            'action'    => $action,
            'partner'   => $this->partner_id,
        ];
        $params = array_merge($additional_params, $params);

        $params['crc']= md5( $this->config->crc_secret_key . ":{$params['url']}:{$params['session']}:{$params['client']}");

        return "//" . $this->config->domains->tracking . "/tgb/track?" . http_build_query($params);
    }


    /**
     * Получение параметров запроса к Relap
     *
     * @param int $count количество запрашиваемых блоков
     * @param string $geo трехсимвольный ГЕО
     * @return array - массив параметров для отправки
     */
    public function getParams($count = 1, $geo = 'RUS', $ip = null) {

        if ($ip === null) {
            $ip = self::getIp();
        }

        $url        = $this->referrer;


        //$ip = '92.100.230.106';
        //$url = "https://rueconomics.ru/307776-idem-na-sblizhenie-kak-siriiskii-vopros-pomozhet-otnosheniyam-rossii-i-turcii";

        $pieces     = parse_url($url);
        if($pieces['host']) {
            $domain     = $pieces['host'];
        } else {
            $domain = '';
        }


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
                "bidfloor"      => floatval($this->bidfloor),
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



    // получение IP
    public static function getIp() {

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
            $ip = '92.100.230.106';
        }

        return $ip;

    }


}