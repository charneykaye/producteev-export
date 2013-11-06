<?php
/**
 * @author Nick Kaye <nick@outrightmental.com>
 * ©2013 Outright Mental Inc.
 * All Rights Reserved
 */
class ProducteevNetwork extends ProducteevObject
{
    /** @var string */
    public $title;
    /** @var string */
    public $domain_name;
    /** @var ProducteevUser */
    public $creator;
    /** @var ProducteevUser[] */
    public $admins;
    /** @var ProducteevUser[] */
    public $users;

    /**
     * @param $data
     */
    function __construct($data = null)
    {
        $this->setData($data);
        $this->extendOne('creator','ProducteevUser');
        $this->extendMany('admins','ProducteevUser');
        $this->extendMany('users','ProducteevUser');
    }

}