<?php
/**
 * @author Nick Kaye <nick@outrightmental.com>
 * ©2013 Outright Mental Inc.
 * All Rights Reserved
 */
require(__DIR__ . '/components/Util.php');
require(__DIR__ . '/components/ProducteevObject.php');

/**
 * Require all models
 */
Util::requireAllPhpFilesInDir(__DIR__ . '/models/');

class App
{
    /**
     * Constants
     */
    const PROJECT_NAME = 'Scrape All Producteev Tasks';
    const PROJECT_DESCRIPTION = "Fueled purely by a desire to export my tasks from Producteev and never look back, I've been forced to build my own API consumer in lieu of a proper Export feature.";
    const PROJECT_INSTRUCTIONS = "Paste your <a target=\"_blank\" href=\"https://www.producteev.com/api/doc/#AuthenticationOAuth20Flows\">access_token</a> above.";
    const PROJECT_URL = "https://github.com/outrightmental/producteev-task-scraper-web";
    const DATETIME_FORMAT = 'Y-m-d H:i:s';
    const DATETIME_FORMAT_PRETTY = 'Y/m/d - g:i a';
    const DATETIME_FORMAT_PRETTY_DAY = 'F d, Y';

    /** @var string */
    public $access_token = '';

    /** @var ProducteevTaskScraper */
    public $taskScraper;

    /**
     * Application Entry Point
     */
    public function __construct()
    {
        ob_start();
        $this->access_token = Util::requestVar('access_token');
        $this->taskScraper = new ProducteevTaskScraper($this->access_token);
    }

}