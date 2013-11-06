<?php
/**
 * @author Nick Kaye <nick@outrightmental.com>
 * ©2013 Outright Mental Inc.
 * All Rights Reserved
 */
class ProducteevLabel extends ProducteevObject
{
    /** @var string */
    public $title;

    /** @var string */
    public $foreground_color;

    /** @var string */
    public $background_color;

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