<?php
/**
 * @author Nick Kaye <nick@outrightmental.com>
 * ©2013 Outright Mental Inc.
 * All Rights Reserved
 */
class ProducteevUser extends ProducteevObject
{
    /** @var string */
    public $email;

    /** @var string */
    public $firstname;

    /** @var string */
    public $lastname;

    /** @var string */
    public $timezone;

    /** @var string */
    public $timezone_utc_offset;

    /** @var array() */
    public $unread_notifications;

    /** @var string */
    public $job_title;

    /** @var string */
    public $verified;

    /** @var string */
    public $avatar_path;

    /** @var array() */
    public $pending_invitations;

    /** @var array() */
    public $pending_admin_requests;

    /**
     *
     */
    public function name()
    {
            return $this->firstname . ' ' . $this->lastname;
    }
}