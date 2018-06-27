<?php
namespace Tizer\Modules\Frontend\Controllers;

use \Tizer\Uuid as Uuid;

class TgbController extends \Phalcon\Mvc\Controller
{

    public $partners = [
        '50bd8c21bfafa6e4e962f6a948b1ef92'  => 'fan',
        'f08dfd477b90159ac5cef98cebe1ee90'  => 'fan_3399_test',
        '31698b6796a85f7781e6ae8227856659'  => 'megapolisonline.ru',
        '3fbedfa6485396a0270f537c792fc525'  => 'advmaker',
        '2aa225f1f6acc0e3159456f98de2bcd1'  => 'adbless',
        '2f3a4fccca6406e35bcf33e92dd93135'  => 'magic',

        //frames
        '826056f90a060bfaafd60d98c55bb588'  => 'adulter_1',
        '99f6fc8e38a5e962da486c645ec4f65b'  => 'adulter_2',
        '7eccf1fed316da45eced1e1cd7900c80'  => 'main_1',
        'aad78e67f876a337c488f884bc941894'  => 'main_2',
    ];

    public $log_dir = __DIR__.'/../../../../logs/';

    public $openrtb_sources = [
        'rp_cinema'    => 'https://relap.io/openrtb/2_3/videocapcinema/bid_request',
        'rp_capvideo'     => 'https://relap.io/openrtb/2_3/videocapvideo/bid_request',
        'rp_cap'          => 'https://relap.io/openrtb/2_3/videocap/bid_request',
    ];

    public function initialize() {
        $partnerUUID = "";
        if ((new Uuid())->validate($this->request->get('partner_id'))) {
            $partnerUUID = $this->request->get('partner_id');
        }
        $this->partner_id = $partnerUUID;

        // Уникальная сессия
        if ((new Uuid())->validate($this->request->get('session'))) {
            $sessionUUID = $this->request->get('session');
        }
        else {
            $sessionUUID = (new Uuid())->get();
        }
        $this->session->set('tzr_sessionuuid', $sessionUUID);
        $this->sessionUUID = $sessionUUID;

        // ClientUUID из cookie
        if ((new Uuid())->validate($this->request->get('client'))) {
            $clientUuid = $this->request->get('client');
        }
        else if (!$this->cookies->has('tzr_uuid')) {
            $clientUuid = (new Uuid())->get();
        }
        else {
            $clientUuid = $this->cookies->has('tzr_uuid');
            if (!(new Uuid())->validate($clientUuid)) {
                $clientUuid = (new Uuid())->get();
            }
        }

        $this->cookies->set(
            'tzr_uuid',
            $clientUuid,
            time() + 15 * 86400
        );

        $this->clientUuid = $clientUuid;


    }

    /**
     * Получение списка баннеров
     */
    public function getAction()
    {
        $openrtb_url = $this->openrtb_sources['rp_cap'];

        if ($this->partner_id == "" || !isset($this->partners[$this->partner_id]) || !isset($_GET['callback'])) {
            return json_encode(['response'=>'error']);
        }

        if ($source = $this->request->getQuery('source')) {
            if (isset($this->openrtb_sources[$source])) {
                $openrtb_url = $this->openrtb_sources[$source];
            }
        }

        $tgb = new \Tizer\RelapTGB( $openrtb_url
           // ,true
        );

        if ($url = $this->request->getQuery('url')) {
            $tgb->setReferrer($url);
        }

        $bid_floor = 0.0001;

        $tgb->setSession($this->sessionUUID);
        $tgb->setCookie($this->clientUuid);
        $tgb->setPartnerID($this->partner_id);
        $tgb->setBidfloor($bid_floor);

        $count = 2;
        if ($this->request->has('count')) {
            $count = intval($this->request->get('count'));
        }
        if ($count > 10 ) {
            $count = 10;
        }

        $blocks = $tgb->get($count);


        $logger = new \Tizer\Logger($this->log_dir.strtolower($this->partner_id)
            , \Tizer\Logger::DEBUG
            , ['prefix'=>'init_']);

        $message = [
            'action'            => 'init',
            'partner'           => mb_strtolower($this->partner_id),
            'session'           => mb_strtolower($this->sessionUUID),
            'client'            => mb_strtolower($this->clientUuid),
            'referer'           => $url,
            'ip'                => \Tizer\RelapTGB::getIp(),
            'source'            => $openrtb_url,
            'bid_floor'         => $bid_floor,
            'count_needed'      => $count,
            'count_received'    => count($blocks),
        ];
        $logger->log(
            json_encode($message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT)
        );
        $logger->complete();

        if (count($blocks) == 0) {

            return $_GET['callback'].'('.json_encode(['response'=>'ok', 'count'=>0,'html'=>'']).')';
            exit;
        }

        // дозаполнить в конец до нужного количества
        if ( count($blocks) < $count) {
           /* while( count($blocks) < $count) {
                $b = [
                    'nurl'          =>  '//yandex.ru/logo.png',
                    'url'           =>  '//yandex.ru',
                    'price_cpc'     =>  0.000001,
                    'price'         =>  0.000001,
                    'id'            =>  'spigot1',
                ];

                $blocks[] = [
                    'nurl'              => $b['nurl'],
                    'tracking_pixel'    => '',
                    'url'               => '',
                    'price_cpc'         => floatval($b['price_cpc']),
                    'ecpm'              => floatval($b['price']),
                    'id'                => $b['id'],
                    'text'              => 'Перейди на Яндекс!',
                    'img'               => [
                        'url'           => '//informnapalm.org/wp-content/uploads/2017/04/y.png'
                    ]
                ];
            }*/

            $logger->complete();

            return $_GET['callback'].'('.json_encode(['response'=>'ok', 'count'=>0,'html'=>'']).')';
            exit;
        }

        $logger = new \Tizer\Logger($this->log_dir.strtolower($this->partner_id));

        foreach ($blocks as $b) {

            $message = [
                'action'            => 'request',
                'partner'           => mb_strtolower($this->partner_id),
                'session'           => mb_strtolower($this->sessionUUID),
                'client'            => mb_strtolower($this->clientUuid),
                'ip'                => \Tizer\RelapTGB::getIp(),
                'price_cpc'         => $b['price_cpc'],
                'price_ecpm'        => $b['ecpm'],
                'id'                => $b['id'],
                'host'              => $b['host']
            ];

            try {
                $logger->log(
                    json_encode($message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT)
                );
            }
            catch (\Exception $e) {

            }
        }
        $logger->complete();


        $this->view->start();
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        $this->view->setVar('items' ,$blocks);
        $this->view->render('tgb','default');
        $this->view->finish();

        $html = $this->view->getContent();

        return $_GET['callback'].'('.json_encode(['response'=>'ok', 'count'=>count($blocks),'html'=>$html]).')';
        exit;
    }

    /**
     * Весь трекинг в одном экшене
     */
    public function trackAction() {
        $params  = $this->request->get();

        if (!isset($params['partner'])) {
            $params['partner'] = 'undef';
        }

        switch ($params['action']) {
            case 'request': // + php
            case 'error':   // + php
            case 'ready':   // + php
            case 'show':    // + js
            case 'no_ads':  // + php
            case 'close':   // js
            case 'nojquery':
            case 'track':
                break;

            case 'imp':     // + impression
            case 'click':   // + click
                if ($params['crc'] !== $params['crc']= md5( $this->config->crc_secret_key . ":{$params['url']}:{$params['session']}:{$params['client']}")) {
                    $this->response->setStatusCode('403');
                    $this->response->send();
                    return;
                }

                break;

            default:
                $this->response->setStatusCode('405');
                $this->response->send();
                return;
                break;
        }

        if ($params['action']!= 'nojquery' && $params['action']!='track') {
            if (!(new Uuid())->validate($params['session'])) {
                $this->response->setStatusCode('400');
                $this->response->send();
                return;
            }

            if (!(new Uuid())->validate($params['client'])) {
                $this->response->setStatusCode('401');
                $this->response->send();
                return;
            }
        }

        if (!$params['host'] || $params['host'] == '') {
            $params['host'] = 'undef';
        }

        $message = [
            'action'            => $params['action'],
            'banner_id'         => $params['id'],
            'partner'           => mb_strtolower($params['partner']),
            'session'           => mb_strtolower($params['session']),
            'client'            => mb_strtolower($params['client']),
            'ip'                => \Tizer\RelapTGB::getIp(),
            'ua'                => mb_substr(mb_strtolower($_SERVER['HTTP_USER_AGENT']), 0, 512),
            'url'               => isset($params['url']) ? $params['url'] : null,
            'host'              => $params['host'],

        ];

        $logger = new \Tizer\Logger($this->log_dir.strtolower($params['partner']));
        try {
            $logger->log(
                json_encode($message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT)
            );
        }
        catch (\Exception $e) {
            $this->response->setStatusCode('500');
            $this->response->setContent($e->getMessage());
            $this->response->send();

            $logger->complete();
            return;
        }

        $logger->complete();
        switch ($params['action']) {
            case 'request': // + php
            case 'error':   // + php
            case 'ready':   // + php
            case 'show':    // + js
            case 'no_ads':  // + php
            case 'close':   // js
            case 'nojquery':
            case 'track':
                $this->response->setStatusCode(200);
                $this->response->setContentType('image/gif');
               // $this->response->send();
                break;

            case 'imp':     // + impression
                $this->response->setStatusCode(200);
                $this->response->setContentType('image/gif');
                $this->response->send();
                break;

            case 'click':   // + click
                if ($params['url']) {
                    $this->response->setStatusCode(302);
                    $this->response->setHeader('Location', $params['url']);
                    $this->response->sendHeaders();
                } else {
                    $this->response->setStatusCode(200);
                    $this->response->setContentType('image/gif');
                }

                break;

            default:
                $this->response->setStatusCode('405');
                //$this->response->send();
                break;
        }

        exit;
    }
}



/*
194.105.107.145 - - [26/Jun/2018:18:04:22 +0200] "GET /tgb/track?
id=1530027671%3A21696%3APKndHw&
session=afb93078-b48e-42a0-b578-37306ad9053d&
client=15b3ad43-a88e-4eaf-a4ba-b32049d0537b&
url=https%3A%2F%2Frelap.io%2Fr%3Fr%3DsX_BPGQBhOVqxgSDHXI%253APKndHw%253AZsuNJQ%253AkCCEOg%253AFAKE0UID%253AWzJelw%253AaHR0cHM6Ly9maXRlcmlhLnJ1L3N0YXRqaS8xMi1mYWt0b3Ytby1wb3BlLWtvdG9yeWtoLW1ub2dpZS1uZS16bmF5dXQuaHRtbD91dG1fbWVkaXVtPXJlbGFwLWFkcm9vbSZ1dG1fY29udGVudD12OTU5NjkmdXRtX2NhbXBhaWduPWZpdGVyaWEtdWtyLWthejEyMTA5JnV0bV9zb3VyY2U9Zml0ZXJpYS11a3Ita2F6%253AwmlrkQ%253AeyJwciI6MC4wMDUsImFjIjoyMTY5NiwiYXBpIjoib3BlbnJ0YiIsInJyIjowLjAwNSwiYTIiOjEsImdzIjoiS1oiLCJpbSI6MCwiaXIiOjAsInVnIjoiS1oiLCJhbGciOjc0LCJwb3MiOjR9%253A2%253AhitiHQ%26_s%3DRW2Gtw&
action=click&
partner=50bd8c21bfafa6e4e962f6a948b1ef92&host=slovodel.com&crc=d09a5a18b46f6ece791214b41303a486 HTTP/1.0" 302 815 "https://slovodel.com/511214-ne-bylo-nikakogo-podarka-kravchuk-rasskazal-pochemu-khrushev-peredal-krym-ukraine?utm_source=24smi&utm_medium=referral&utm_term=605&utm_content=1704315&utm_campaign=2616" "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:60.0) Gecko/20100101 Firefox/60.0"
178.67.10.6 - - [26/Jun/2018:18:07:04 +0200] "GET /tgb/track?partner_id=2f3a4fccca6406e35bcf33e92dd93135&action=track&host=tophotels.ru&id=0.20319651987924958&session=test&client=test&url=https://tophotels.ru/hotel/al5014/media/gallery?photo_type=7 HTTP/1.0" 200 276 "https://tophotels.ru/hotel/al5014/media/gallery?photo_type=7" "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.87 Safari/537.36"
*/