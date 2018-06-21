<?php
namespace Tizer\Modules\Frontend\Controllers;


use Tizer\Common\Models\StatOpenrtb;

class StatController extends \Phalcon\Mvc\Controller
{

    public $partners = [
        '50bd8c21bfafa6e4e962f6a948b1ef92'  => 'fan',
        'f08dfd477b90159ac5cef98cebe1ee90'  => 'fan_3399_test',
        '31698b6796a85f7781e6ae8227856659'  => 'megapolisonline.ru',
        '3fbedfa6485396a0270f537c792fc525'  => 'advmaker',
        '2aa225f1f6acc0e3159456f98de2bcd1'  => 'adbless',
        '2f3a4fccca6406e35bcf33e92dd93135'  => 'magic',
    ];

    public function indexAction()
    {

    }

    public function showAction() {

        $partner_id = $this->request->getQuery('partner');

        $stat = StatOpenrtb::find([
            'columns'       => 'stat_openrtb_date,stat_openrtb_init,stat_openrtb_money',
            'conditions'    => 'partner_id = :partner_id:',
            'bind'          => [
                'partner_id'    => $partner_id,
            ],
            'order' => 'stat_openrtb_date',
        ])->toArray();

        $koeff = [
            '50bd8c21bfafa6e4e962f6a948b1ef92'  => [
                'init'  => 0.8,
                'money' => 0.65,
            ],
            'f08dfd477b90159ac5cef98cebe1ee90'  => [
                'init'  => 0.8,
                'money' => 0.65,
            ],
            '31698b6796a85f7781e6ae8227856659'  => [
                'init'  => 0.8,
                'money' => 0.65,
            ],
            '3fbedfa6485396a0270f537c792fc525'  => [
                'init'  => 0.8,
                'money' => 0.65,
            ],
            '2aa225f1f6acc0e3159456f98de2bcd1'  => [
                'init'  => 0.8,
                'money' => 0.65,
            ],
            '2f3a4fccca6406e35bcf33e92dd93135'  => [
                'init'  => 0.8,
                'money' => 0.65,
            ],
        ];

        $result = [];
        if (count($stat) > 0) {
            foreach ($stat as $values) {
                $date   = date('Y-m-d',strtotime($values['stat_openrtb_date']));
                $money  = $values['stat_openrtb_money']*$koeff[$partner_id]['money'];
                $init   = floor($values['stat_openrtb_init']*$koeff[$partner_id]['init']);

                if (!isset($result[$date])) {
                    $result[$date] = [
                        'date'  => $date,
                        'init'  => $init,
                        'money' => $money,
                    ];
                } else {
                    $result[$date]['init']  += $init;
                    $result[$date]['money'] += $money;
                }
            }

            foreach ($result as $r) {
                echo implode(';',$r);
                echo '<br />';
            }
        }



//        echo date('Y-m-d',strtotime($values['stat_openrtb_date']));
//        echo ';'.$values['stat_openrtb_init'];
//        echo ';'.($values['stat_openrtb_money']*$koeff[$partner_id]);
//
//        echo '<br>';
        exit;
    }
}

