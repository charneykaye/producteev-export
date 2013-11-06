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

    /** @var string */
    protected $basePath = '';

    /** @var string */
    protected $filename = '';

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
     * @param string $basePath - NO trailing slash
     * @param string $filename
     * @param array $vars
     */
    public function __construct($token, $basePath, $filename, $vars = array())
    {
        $this->token = $token;
        $this->basePath = $basePath;
        $this->filename = $filename;
        if (count($vars))
            foreach ($vars as $key => $val)
                if (isset($this->defaultVars[$key]))
                    $this->defaultVars[$key] = $val;
    }

    /**
     * @var bool $verbose
     * @var int $page optional
     * @return bool;
     */
    public function scrape($verbose = false, $page = null)
    {
        // Set if verbose
        if ($verbose)
            $this->verbose = true;

        // Requires token
        if (!strlen($this->token))
            return false;

        // Scrape one page (if specified, else all pages)
        if (is_numeric($page))
            $this->scrapePage($page);
        else
            $this->scrapeAllPages();

        // If verbose, talk about it.
        if ($this->verbose)
            echo 'Scraped total ' . count($this->tasks) . ' tasks.<br/>';
        ob_flush();
        flush();

        // Write a CSV file
        $this->writeCsv();

        // If verbose, talk about it.
        if ($this->verbose)
            echo 'Wrote <a href="' .  $this->filename . '" target="_blank">' .  $this->filename . '</a>.<br/>';
        ob_flush();
        flush();

        // Success!
        return true;
    }

    /**
     * @param int $page
     * @param $vars
     * @return int
     */
    protected function scrapePage($page, $vars = array())
    {
        // If verbose, talk about it.
        if ($this->verbose)
            echo 'cURL page ' . $page . '...';
        ob_flush();
        flush();

        // Implement cURL to talk to Producteev API
        $ch = curl_init(self::buildQueryURI($page));
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

        // If verbose, talk about it.
        if ($this->verbose)
            echo 'scraped ' . count($data->tasks) . ' tasks.<br/>';
        ob_flush();
        flush();

        // Return count;
        return count($data->tasks);
    }

    /**
     *
     */
    protected function scrapeAllPages()
    {
        $page = 1;
        while (self::scrapePage($page++)) ;
    }

    /**
     *
     */
    public function writeCsv()
    {
        $fp = fopen($this->basePath . '/' . $this->filename, 'w');
        fputcsv($fp, ProducteevTask::getHeader());
        foreach ($this->tasks as $task)
            if ($task instanceof ProducteevTask)
                fputcsv($fp, $task->getData());
        fclose($fp);
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