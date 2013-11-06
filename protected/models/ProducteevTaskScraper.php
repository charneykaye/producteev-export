<?php
/**
 * Class to scrape all tasks from a Producteev account via the API
 *
 * @author Nick Kaye <nick@outrightmental.com>
 * ©2013 Outright Mental Inc.
 * All Rights Reserved
 *
 */
class ProducteevTaskScraper
{

    /** @var string */
    protected $token = '';

    /** @var array */
    protected $defaultVars = array(
        'sort' => 'deadline_time',
        'order' => 'desc',
        'include_deleted' => 1,
    );

    /** @var ProducteevTask[] */
    public $tasks = array();

    /** @var string */
    protected $url = 'http://www.producteev.com/api/tasks/search';

    /** @var bool $verbose */
    protected $verbose = false;

    /**
     * @param string $token Required OAuth access_token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * @var bool $verbose
     * @var int $page optional
     * @return bool;
     */
    public function scrape($verbose = false, $page = 1)
    {
        if ($verbose)
            $this->verbose = true;

        if (!strlen($this->token))
            return false;

//        while (self::scrapeUrl(self::buildQueryURI($page++))) ;
        self::scrapeUrl(self::buildQueryURI($page));

        return true;
    }


    /**
     * @param int $page
     * @return string
     */
    protected function buildQueryURI($page)
    {
        $pairs = array();
        foreach (array_merge(
                     $this->defaultVars,
                     array('page' => $page)
                 ) as $key => $val)
            $pairs[] = urlencode($key) . '=' . urlencode($val);
        if (count($pairs))
            return $this->url . '?' . implode('&', $pairs);
        else return $this->url;
    }

    /**
     * @param string $url
     * @param $vars
     * @return int
     */
    protected function scrapeUrl($url, $vars = array())
    {
        // Implement cURL to talk to Producteev API
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($vars));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'Authorization:Bearer ' . $this->token
        ));
        $result = curl_exec($ch);
        curl_close($ch);

        // Result must be non-null string, else return 0
        if (!$result) return 0;
        if (!is_string($result)) return 0;

        // Decoded JSON data root must contain a "tasks" array, else return 0
        $data = json_decode($result);
        if (!is_object($data)) return $this->error($data);
        if (!property_exists($data, 'tasks')) return $this->error($data);
        if (!is_array($data->tasks)) return 0;

        // Add all tasks and return count
        foreach ($data->tasks as $toAdd)
            $this->tasks[] = new ProducteevTask($toAdd);
        return count($data->tasks);
    }

    /**
     * @param $content
     * @param int $return
     * @return int
     */
    protected function error($content, $return = 0)
    {
        if ($this->verbose)
            var_dump($content);
        return $return;
    }

}