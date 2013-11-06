<?php
/**
 * @author Nick Kaye <nick@outrightmental.com>
 * ©2013 Outright Mental Inc.
 * All Rights Reserved
 */
class ProducteevTask extends ProducteevObject
{
    /** @var string */
    public $title;

    /** @var int */
    public $priority;

    /** @var int */
    public $status;

    /** @var string */
    public $deadline;

    /** @var string */
    public $deadline_timezone;

    /** @var ProducteevUser */
    public $creator;

    /** @var array ProducteevUser[] */
    public $responsibles = array();

    /** @var array ProducteevUser[] */
    public $followers = array();

    /** @var ProducteevProject */
    public $project; // ProducteevObject

    /** @var ProducteevLabel[] */
    public $labels = array(); // array()

    /** @var ProducteevSubtask[]  */
    public $subtasks = array(); // array()

    /** @var int */
    public $notes_count; // 0

    /** @var bool */
    public $allday; // true,

    /** @var ing */
    public $reminder; // 0

    /** @var ing */
    public $permissions; // 2047

    /** @var string */
    public $deadline_status; // "upcoming"

    /**
     * @param $data
     */
    function __construct($data = null)
    {
        $this->setData($data);
        $this->extendMany('subtasks','ProducteevSubtask');
        $this->extendMany('labels','ProducteevLabel');
        $this->extendOne('creator','ProducteevUser');
        $this->extendOne('project','ProducteevProject');
        $this->extendMany('responsibles','ProducteevUser');
        $this->extendMany('followers','ProducteevUser');
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