<?php
namespace Tizer\Common\Models;

class StatOpenrtb extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $stat_openrtb_id;

    /**
     *
     * @var string
     */
    public $partner_id;

    /**
     *
     * @var integer
     */
    public $stat_openrtb_host;

    /**
     *
     * @var string
     */
    public $stat_openrtb_date;

    /**
     *
     * @var integer
     */
    public $stat_openrtb_init;

    /**
     *
     * @var integer
     */
    public $stat_openrtb_request;

    /**
     *
     * @var integer
     */
    public $stat_openrtb_empty_responds;

    /**
     *
     * @var integer
     */
    public $stat_openrtb_castrated_responds;

    /**
     *
     * @var integer
     */
    public $stat_openrtb_imp;

    /**
     *
     * @var integer
     */
    public $stat_openrtb_click;

    /**
     *
     * @var double
     */
    public $stat_openrtb_money;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("firsttex");
        $this->setSource("stat_openrtb");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'stat_openrtb';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return StatOpenrtb[]|StatOpenrtb|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return StatOpenrtb|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
