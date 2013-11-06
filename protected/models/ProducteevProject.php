<?php
/**
 * @author Nick Kaye <nick@outrightmental.com>
 * ©2013 Outright Mental Inc.
 * All Rights Reserved
 */
class ProducteevProject extends ProducteevObject
{
    /** @var string */
    public $title;
    /** @var string */
    public $description;
    /** @var bool */
    public $locked;
    /** @var bool */
    public $restricted;
    /** @var ProducteevUser */
    public $creator;
    /** @var ProducteevNetwork */
    public $network;

    /**
     * @param $data
     */
    function __construct($data = null)
    {
        $this->setData($data);
        $this->extendOne('creator','ProducteevUser');
        $this->extendOne('network','ProducteevNetwork');
    }

}