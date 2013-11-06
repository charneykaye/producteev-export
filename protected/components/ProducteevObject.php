<?php
/**
 * @author Nick Kaye <nick@outrightmental.com>
 * ©2013 Outright Mental Inc.
 * All Rights Reserved
 */
class ProducteevObject
{
    public $id; // "520144d8fa4634b608000000"
    public $created_at; // "2013-08-06T18:47:52+0000"
    public $updated_at; // "2013-08-06T18:47:53+0000"

    /**
     * @param $data
     */
    function __construct($data = null)
    {
        $this->setData($data);
    }

    /**
     * @param array|object $data
     */
    protected function setData($data = null)
    {
        if (!$data)
            return;
        if (is_object($data))
            $data = get_object_vars($data);
        if (is_array($data))
            foreach ($data as $key => $val)
                if (property_exists($this, $key))
                    $this->$key = $val;
    }

    /**
     * @param mixed $class
     * @return bool
     */
    private static function isProducteevObject($class)
    {
        if (is_string($class) && !class_exists($class))
            return false;
        if (!is_subclass_of($class, 'ProducteevObject'))
            return false;
        return true;
    }

    /**
     * @param $attr
     * @param $class
     */
    protected function extendMany($attr, $class)
    {
        // Attribute must exist and be array
        if (!property_exists($this, $attr))
            return;
        if (!is_array($this->$attr))
            return;

        // Class must be a ProducteevObject
        if (!self::isProducteevObject($class))
            return;

        // Replace attribute with collection of objects
        $newObjects = array();
        foreach ($this->$attr as $data)
            $newObjects[] = new $class($data);
        $this->$attr = $newObjects;
    }


    /**
     * @param $attr
     * @param $class
     */
    protected function extendOne($attr, $class)
    {
        // Attribute must exist and be array
        if (!property_exists($this, $attr))
            return;
        if (!is_array($this->$attr))
            return;

        // Class must be a ProducteevObject
        if (!self::isProducteevObject($class))
            return;

        // Replace attribute with an objects
        $this->$attr = new $class($this->$attr);
    }
}