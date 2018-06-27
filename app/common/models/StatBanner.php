<?php

namespace Tizer\Common\Models;

class StatBanner extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $stat_banner_id;

    /**
     *
     * @var string
     */
    public $banner_id;

    /**
     *
     * @var integer
     */
    public $stat_banner_date;

    /**
     *
     * @var integer
     */
    public $stat_banner_imp;

    /**
     *
     * @var integer
     */
    public $stat_banner_click;

    /**
     *
     * @var integer
     */
    public $stat_banner_money;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("firsttex");
        $this->setSource("stat_banner");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'stat_banner';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return StatBanner[]|StatBanner|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return StatBanner|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
