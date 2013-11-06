<?php
/**
 * @author Nick Kaye <nick@outrightmental.com>
 * ©2013 Outright Mental Inc.
 * All Rights Reserved
 */
class ProducteevTask
{
    public $id; // "520144d8fa4634b608000000"
    public $created_at; // "2013-08-06T18:47:52+0000"
    public $updated_at; // "2013-08-06T18:47:53+0000"
    public $title; // "Submit the new iOS App"
    public $priority; // 0
    public $status; // 1
    public $deadline; // "2013-08-07T07:00:00+0000"
    public $deadline_timezone; // "PST"
    public $creator; // ProducteevUser
    public $responsibles = array(); // ProducteevUser[]
    public $followers = array(); // ProducteevUser[]
    public $project; // ProducteevProject
    public $labels = array(); // array()
    public $subtasks = array(); // array()
    public $notes_count; // 0
    public $allday; // true,
    public $reminder; // 0
    public $permissions; // 2047
    public $deadline_status; // "upcoming"

    /**
     * @param $data
     */
    function __construct($data = null)
    {
        if ($data)
            $this->setData($data);
    }

    /**
     * @param array|object $data
     */
    protected function setData($data)
    {
        if (is_object($data))
            $data = get_object_vars($data);
        if (is_array($data))
            foreach ($data as $key => $val)
                if (property_exists($this, $key))
                    $this->$key = $val;
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