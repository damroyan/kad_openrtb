<?php
namespace Tizer\Modules\Frontend\Controllers;

class TgbController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }

    public function getAction()
    {

        $tgb = new \Tizer\RelapTGB('https://relap.io/openrtb/2_3/videocapcinema/bid_request', true);

        $ip = null;
        if ($ip_tmp = $this->request->getQuery('ip')) {
            $ip = $ip_tmp; // ????????????????????????????????????? как правильно определять
        }

        //// ????????????????????????????
        $url = 'https://inforeactor.ru/region/spb/155068-stalo-izvestno-kogda-izmenitsya-tarif-po-zsd-dlya-vladelcev-transponderov';


        $tgb->getParams(
            $url,
            4,
            $ip);

        echo '<pre>';
        echo json_encode($tgb->get());


        exit;
    }

    public function trackAction()
    {
        $partner = 'fan';

        $logger = new \Tizer\Logger(__DIR__.'/../../../../logs/'.$partner);

        $logger->log('test');
        $logger->complete();

        echo 'track action';
        exit;
    }


}

