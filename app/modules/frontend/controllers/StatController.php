<?php
namespace Tizer\Modules\Frontend\Controllers;


use Tizer\Common\Models\StatOpenrtb;

class StatController extends \Phalcon\Mvc\Controller
{

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
            '50bd8c21bfafa6e4e962f6a948b1ef92'  => 1,
            'f08dfd477b90159ac5cef98cebe1ee90'  => 1,
            '31698b6796a85f7781e6ae8227856659'  => 1,
            '3fbedfa6485396a0270f537c792fc525'  => 1,
            '2aa225f1f6acc0e3159456f98de2bcd1'  => 1,
            '2f3a4fccca6406e35bcf33e92dd93135'  => 1,
        ];

        $result = [];
        if (count($stat) > 0) {
            foreach ($stat as $values) {
                $date = date('Y-m-d',strtotime($values['stat_openrtb_date']));
                $money = $values['stat_openrtb_money']*$koeff[$partner_id];

                if (!isset($result[$date])) {
                    $result[$date] = [
                        'date'  => $date,
                        'init'  => $values['stat_openrtb_init'],
                        'money' => $money,
                    ];
                } else {
                    $result[$date]['init']  += $values['stat_openrtb_init'];
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

