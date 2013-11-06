<?php
/**
 * @author Nick Kaye <nick@outrightmental.com>
 * ©2013 Outright Mental Inc.
 * All Rights Reserved
 */
class ProducteevSubtask extends ProducteevObject
{
    public $title; // "Submit the new iOS App"
    public $status; // 1
    public $position; // 1
    public $creator; // ProducteevUser

    /**
     * @param $data
     */
    function __construct($data = null)
    {
        $this->setData($data);
        $this->extendOne('creator','ProducteevUser');
    }

    /**
     * @return bool
     */
    public
    function isDone()
    {
        return !($this->isActive());
    }

    /**
     * @return bool
     */
    public
    function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    /** Possible Values for status */
    const STATUS_DONE = 0;
    const STATUS_ACTIVE = 1;
}