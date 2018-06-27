<?php

namespace Tizer\Common\Models;

class Source extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $source_id;

    /**
     *
     * @var string
     */
    public $source_date;

    /**
     *
     * @var string
     */
    public $source_partner;

    /**
     *
     * @var string
     */
    public $source_host;

    /**
     *
     * @var integer
     */
    public $source_traffic;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("firsttex");
        $this->setSource("source");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'source';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Source[]|Source|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Source|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
