<?php
namespace Tizer\Modules\Frontend\Controllers;

use \Tizer\Uuid as Uuid;

class TgbController extends \Phalcon\Mvc\Controller
{

    public $partners = [
        '50bd8c21bfafa6e4e962f6a948b1ef92' => 'fan',
    ];

    public $log_dir = __DIR__.'/../../../../logs/';

    public $openrtb_sources = [
        'relap'   => [
            'videocapcinema'    => 'https://relap.io/openrtb/2_3/videocapcinema/bid_request',
            'videocapvideo'     => 'https://relap.io/openrtb/2_3/videocapvideo/bid_request',
            'videocap'          => 'https://relap.io/openrtb/2_3/videocap/bid_request',
        ]
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
        $openrtb_url = $this->openrtb_sources['relap']['videocap'];

        if ($this->partner_id == "" || !isset($this->partners[$this->partner_id]) || !isset($_GET['callback'])) {
            return json_encode(['response'=>'error']);
        }

        $tgb = new \Tizer\RelapTGB( $openrtb_url
           // ,true
        );

        if ($url = $this->request->getQuery('url')) {
            $tgb->setReferrer($url);
        }

        $tgb->setSession($this->sessionUUID);
        $tgb->setCookie($this->clientUuid);
        $tgb->setPartnerID($this->partner_id);
        $tgb->setBidfloor(0.0001);

        $count = 2;
        if ($this->request->has('count')) {
            $count = intval($this->request->get('count'));
        }
        if ($count > 10 ) {
            $count = 10;
        }

        $blocks = $tgb->get(4);

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

            return $_GET['callback'].'('.json_encode(['response'=>'ok', 'count'=>0,'html'=>'']).')';
            exit;
        }


        foreach ($blocks as $b) {
            $logger = new \Tizer\Logger($this->log_dir.strtolower($this->partner_id));

            $message = [
                'action'            => 'request',
                'partner'           => mb_strtolower($this->partner_id),
                'session'           => mb_strtolower($this->sessionUUID),
                'client'            => mb_strtolower($this->clientUuid),
                'ip'                => \Tizer\RelapTGB::getIp(),
                'price_cpc'         => $b['price_cpc'],
                'price_ecpm'        => $b['ecpm'],
                'id'                => $b['id']
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

        $logger = new \Tizer\Logger($this->log_dir.strtolower($params['partner']));

        switch ($params['action']) {
            case 'request': // + php
            case 'error':   // + php
            case 'ready':   // + php
            case 'show':    // + js
            case 'no_ads':  // + php
            case 'close':   // js
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

        $message = [
            'action'            => $params['action'],
            'partner'           => mb_strtolower($params['partner']),
            'session'           => mb_strtolower($params['session']),
            'client'            => mb_strtolower($params['client']),
            'ip'                => \Tizer\RelapTGB::getIp(),
            'ua'                => mb_substr(mb_strtolower($_SERVER['HTTP_USER_AGENT']), 0, 512),
            'url'               => isset($params['url']) ? $params['url'] : null,
        ];

        try {
            $logger->log(
                json_encode($message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT)
            );
        }
        catch (\Exception $e) {
            $this->response->setStatusCode('500');
            $this->response->setContent($e->getMessage());
            $this->response->send();

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
                $this->response->setStatusCode(200);
                $this->response->setContentType('image/gif');
               // $this->response->send();
                break;

            case 'imp':     // + impression
                $this->response->setStatusCode(200);
                $this->response->setContentType('image/gif');
                $this->request->get($params['url']);
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

