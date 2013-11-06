<?php
/**
 * @author Nick Kaye <nick@outrightmental.com>
 * ©2013 Outright Mental Inc.
 * All Rights Reserved
 */
require(__DIR__ . '/components/Util.php');
require(__DIR__ . '/models/ProducteevTaskScraper.php');
require(__DIR__ . '/models/ProducteevTask.php');

class App {
    /** @var string */
    public $access_token = '';

    /** @var ProducteevTaskScraper */
    public $taskScraper;

    /**
     * Application Entry Point
     */
    public function __construct()
    {
        $this->access_token = Util::requestVar('access_token');
        $this->taskScraper = new ProducteevTaskScraper($this->access_token);
    }

    // Constants
    const DATETIME_FORMAT = 'Y-m-d H:i:s';
    const DATETIME_FORMAT_PRETTY = 'Y/m/d - g:i a';
    const DATETIME_FORMAT_PRETTY_DAY = 'F d, Y';
}