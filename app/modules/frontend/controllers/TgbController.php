<?php
namespace Tizer\Modules\Frontend\Controllers;

use \Tizer\Uuid as Uuid;

class TgbController extends \Phalcon\Mvc\Controller
{

    public $partners = [
        '50bd8c21bfafa6e4e962f6a948b1ef92' => 'fan',
    ];

    public function initialize() {

        $partnerUUID = "";
        if ((new Uuid())->validate($this->request->get('partner_id'))) {
            $partnerUUID = $this->request->get('partner_id');
        }
        $this->partner_id = $partnerUUID;

        // Уникальная сессис
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
        if ($this->partner_id == "" || !isset($this->partners[$this->partner_id]) || !isset($_GET['callback'])) {
            return json_encode(['response'=>'error']);
        }

        $tgb = new \Tizer\RelapTGB('https://relap.io/openrtb/2_3/videocapcinema/bid_request'
            //,true
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

        $this->view->start();
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        $this->view->setVar('items' ,$blocks);
        $this->view->render('tgb','default');
        $this->view->finish();

        echo 'end of tgb/get';
        exit;

        $html = $this->view->getContent();

        return $_GET['callback'].'('.json_encode(['response'=>'ok', 'count'=>count($blocks),'html'=>$html]).')';
    }


    public function jsAction() {


        return $_GET['callback'].'('.json_encode(['ok'=>'ok']).')';
    }

    /**
     * Весь трекинг в одном экшене
     */
    public function trackAction() {

        $params  = $this->request->get();

        if (!isset($params['partner'])) {
            $params['partner'] = 'undef';
        }

        $logger = new \Tizer\Logger(__DIR__.'/../../../../logs/'.strtolower($params['partner']));

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
                $this->response->send();
                break;

            case 'imp':     // + impression
            case 'click':   // + click
                if ($params['url']) {
                    $this->response->setStatusCode(302);
                    $this->response->setHeader('X-State', 'true');
                    $this->response->setHeader('Location', $params['url']);
                    $this->response->send();
                } else {
                    $this->response->setStatusCode(200);
                    $this->response->setContentType('image/gif');
                    $this->response->send();
                }
                break;

            default:
                $this->response->setStatusCode('405');
                $this->response->send();
                return;
                break;
        }

        return;
    }


}

