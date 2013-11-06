<?php

/**
 * @author Nick Kaye <nick@outrightmental.com>
 * ©2013 Outright Mental Inc.
 * All Rights Reserved
 */
class Util
{
    // Defaults
    const DEFAULT_TIMEZONE = 'America/New_York';
    const DEFAULT_DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @static
     * @param $attr
     * @param $aliases
     * @internal param $out
     * @internal param $this
     * @return array
     */
    static public function aliasedAttributes($attr, $aliases)
    {
        $out = array();
        foreach ($attr as $key => $val)
            if (isset($aliases[$key]))
                $out[$aliases[$key]] = $val;
            else
                $out[$key] = $val;
        return $out;
    }

    /**
     * @static
     * @param $umetaAr
     * @return array
     */
    static public function transformUmetasToAttributes($umetaAr)
    {
        $out = array();
        foreach ($umetaAr as $umeta) {
            if (isset($umeta['name_um']) && isset($umeta['value_um'])) {
                $out[strtolower($umeta['name_um'])] = $umeta['value_um'];
                continue;
            }
            if (isset($umeta['name']) && isset($umeta['value'])) {
                $out[strtolower($umeta['name'])] = $umeta['value'];
                continue;
            }
        }
        return $out;
    }

    /**
     *
     * double explode
     * @param $delimiter
     * @param $data
     */
    static public function doubleExplode($delimiter, $data)
    {
        $_OUT = array();
        foreach (explode($delimiter, $data) as $one) {
            $_OUT[$one] = $one;
        }
    }

    /**
     *
     * proper list, ala:  One, Two, Three, and Four.
     * @param array $items
     * @return string
     */
    static public function properList($items)
    {
        $out = "";
        for ($i = 0; $i < count($items); $i++) {
            if ($i == 0) {
                // first
                $out .= $items[$i];
            } elseif ($i == count($items) - 1) {
                // last
                $out .= " and " . $items[$i];
            } else {
                // in between
                $out .= ", " . $items[$i];
            }
        }
        return $out;
    }

    /**
     *
     * complex merge
     * @param array $A
     * @param array $B
     * @return array
     */
    static public function complexMerge($A, $B)
    {
        return array_merge_recursive($A, $B);

        /*
          if ($A==null)
          if ($B!=null) return $B;
          else return null;

          if (is_array($A) && is_array($B)) {
          if (!isAssoc($A) && !isAssoc($B))
          $B = array_merge($A,$B);
          else
          foreach ($A as $key=>$val) {
          if (isset($B[$key])) {
          $B[$key] = self::complexMerge($B[$key],$A[$key]);
          } else {
          $B[$key] = $A[$key];
          }
          }
          return $B;

          } elseif ( is_array($A) && (!is_array($B))) {
          array_push($A,$B);
          return $A;

          } elseif ( (!is_array($A)) && is_array($B)) {
          array_push($B,$A);
          return $B;

          } elseif ( (!is_array($B)) && ((!is_array($A))) ) {
          return array($A,$B);

          } else {
          return $A;
          }
         */
    }

    /**
     * @param $fp
     * @param $string
     * @return int
     */
    static public function fwriteStream($fp, $string)
    {
        for ($written = 0; $written < strlen($string); $written += $fwrite) {
            $fwrite = fwrite($fp, substr($string, $written));
            if ($fwrite === false) {
                return $written;
            }
        }
        return $written;
    }

    /**
     *   isAssoc
     *
     * @param array $array
     * @return bool
     */
    static public function isAssoc($array)
    {
        return (is_array($array) && (0 !== count(array_diff_key($array, array_keys(array_keys($array)))) || count($array) == 0));
    }

    /**
     *
     * add spaces to beginning until length met
     * @param $src
     * @param $len
     * @return string
     */
    static public function prespaceToLength($src, $len)
    {
        $out = $src;
        $add = max(0, ($len - strlen($src)));
        for ($i = 0; $i < $add; $i++) {
            $out = " " . $out;
        }
        return $out;
    }

    /**
     *
     * safe $_POST[$var] else $_GET[$var] else $default
     * @param $var
     * @param $default
     * @return null
     */
    static public function requestVar($var, $default = null)
    {
        // search for key B and return it if found
        return self::POST($var, self::GET($var, $default));
    }

    /**
     *
     * safe $_POST[$var] else $_GET[$var] else false
     * @param $var
     * @return bool
     */
    static public function requestBool($var)
    {
        // grab value and implement boolean interpretation
        return self::interpretBoolean(self::requestVar($var, false));
    }

    /**
     *
     * safe $_POST[$var] else $_GET[$var] else $default
     * @return integer
     */
    static public function requestIntFromEndOfRequestUrl()
    {
        if (!isset($_GET['r'])) return null;
        $subject = $_GET['r'];
        $pattern = '/([0-9]+)$/';
        preg_match($pattern, substr($subject, 3), $matches, PREG_OFFSET_CAPTURE);
        if (!isset($matches[0])) return null;
        if (!isset($matches[0][0])) return null;
        return (int)$matches[0][0];
    }

    /**
     *
     * safe $_POST[$var] else $default
     * @param $var
     * @param $default
     * @return null
     */
    static public function POST($var, $default = null)
    {
        return isset($_POST[$var]) ? $_POST[$var] : $default;
    }

    /**
     *
     * safe $_GET[$var] else $default
     * @param $var
     * @param $default
     * @return null
     */
    static public function GET($var, $default = null)
    {
        return isset($_GET[$var]) ? $_GET[$var] : $default;
    }

    /**
     *
     * safe $_FILES[]
     * @param $var
     * @param $default
     * @return null
     */
    static public function FILES($var, $default = null)
    {
        return isset($_FILES[$var]) ? $_FILES[$var] : $default;
    }

    /**
     * @static
     * @param $arr
     * @return mixed
     */
    static public function randomIndexOfArray($arr)
    {
        return mt_rand(0, max(count($arr) - 1, 0));
//        return array_rand($arr);
    }

    /**
     * @param string|array $path
     * @return mixed
     */
    static public function rawPostJSON($path = null)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (is_array($path))
            return self::getIfSetDeep($data, $path);
        elseif (is_string($path))
            return self::getIfSet($data, $path);
        else
            return $data;
    }

    /** removeIllegalChars
     *
     * @param $_IN
     * @return mixed
     */
    static public function removeIllegalChars($_IN)
    {
        return preg_replace("/([\x80-\xFF])/", "", $_IN);
    }

    /** removeSpaces
     *
     * @param $_IN
     * @return mixed
     */
    static public function removeSpaces($_IN)
    {
        return preg_replace('/([\s])/', '', $_IN);
    }

    /** removeNonAlphaNumeric
     *
     * @param $_IN
     * @return mixed
     */
    static public function removeNonAlphaNumeric($_IN)
    {
        return preg_replace('/([^A-Za-z0-9])/', '', $_IN);
    }

    /** removeNonDNS
     *
     * @param $_IN
     * @return mixed
     */
    static public function removeNonDNS($_IN)
    {
        return preg_replace('/([^A-Za-z0-9\-\.])/', '', $_IN);
    }

    /** convertFancyQuotes
     *
     * @param $_IN
     * @return mixed
     */
    static public function convertFancyQuotes($_IN)
    {
        return str_replace(array(chr(145), chr(146), chr(147), chr(148), chr(151), chr(130), chr(132), chr(133)), array("'", "'", '"', '"', '-', ",", ",,", "..."), $_IN);
    }

    /**
     * removeNull
     * @param array
     * @return array without null values or their keys
     */
    static public function removeNull($_IN)
    {
        $_OUT = array();
        foreach ($_IN as $key => $value)
            if ($value !== null)
                $_OUT[$key] = $value;
        return $_OUT;
    }

    /**
     *
     * keys to strings
     * @param object $_IN associative array
     * @return array $_OUT straight array of the keys
     */
    static public function keysToStrings($_IN)
    {
        $_OUT = array();
        foreach ($_IN as $key => $value) {
            array_push($_OUT, $key);
        }
        return $_OUT;
    }

    /**
     *
     * add a value to a csv
     * @param $csv
     * @param $val
     */
    static public function csv_push(&$csv, $val)
    {
        if ($csv == null)
            $csv = $val;
        else if (strlen(ltrim(rtrim($csv))) == 0)
            $csv = $val;
        else
            $csv .= "," . $val;
    }


    /**
     * @static
     * @param Array $ar
     * @return string
     */
    static public function quotedCSVfromArray($ar)
    {
        $quoted_ar = array();
        foreach ($ar as $one)
            $quoted_ar[] = '"' . str_replace('"', '\"', $one) . '"';
        return implode(',', $quoted_ar);
    }

    /**
     *
     * @param string $csv
     * @return array
     */
    static public function idArrayFromCsv($csv = null)
    {
        if ($csv == null)
            return array();
        return explode(',', preg_replace('/([^0-9,]+)/', '', $csv));
    }

    /**
     *
     * get key within arr if set, else return def
     * @param $pre_arr
     * @param $key
     * @param null $def
     * @return null
     */
    static public function getIfSet($pre_arr, $key, $def = null)
    {
        if (!(is_string($key) || is_numeric($key)))
            return $def;

        $arr = (array)$pre_arr;

        return isset($arr[$key]) ? $arr[$key] : $def;
    }

    /**
     *   getIfSetDeep
     *
     * Used in stacks to test for the deep existence of a variable in an array without throwing errors
     *
     * @param array $arr
     * @param string|array $key
     * @param null $def
     * @return mixed
     */
    static public function getIfSetDeep($arr, $key, $def = null)
    {
        // null values = return default
        if ($arr == null || $key == null || $arr == $def || $key == $def)
            return $def;

        // single-element array key ought to be converted to straight key
        if (is_array($key) && count($key) < 2)
            $key = $key[0];

        // if the key is an array, use the first element as the key for a getIfSet, then re-iterate on the shortened key array with the found sub-array
        if (is_array($key))
            return self::getIfSetDeep(self::getIfSet($arr, array_shift($key), $def), $key, $def);

        // if the key is not an array, then just use getIfSet
        return self::getIfSet($arr, $key, $def);
    }

    /**
     * returns the input variable print_r expanded with line breaks and spaces converted for html
     * @param type $var
     * @return mixed
     */
    static public function debugHtml($var)
    {
        return str_replace(array("\n", "\r", " "), array("<br/>", "<br/>", "&nbsp;"), print_r($var, true));
    }

    /**
     *
     */
    static public function nameColumnsFromString($name)
    {
        if ($name == null)
            return array();
        $pieces = explode(' ', $name);
        return array(
            "name_first_u" => array_shift($pieces),
            "name_last_u" => implode(' ', $pieces)
        );
    }

    /**
     *
     * @param string $name_first_u
     * @param string $name_last_u
     * @return string
     */
    static public function nameFromFirstLast($name_first_u = "", $name_last_u = null)
    {
        return $name_first_u . (($name_last_u != null) ? (' ' . $name_last_u) : '');
    }

    /**
     * @param int $timestring
     * @param string $format
     * @param string $tz
     * @return null|string
     */
    static public function datetime($timestring = 0, $format = self::DEFAULT_DATETIME_FORMAT, $tz = self::DEFAULT_TIMEZONE)
    {
        // timestring
        if ($timestring === 0)
            $timestring = time();

        // target timezone
        if ($tz == null)
            $tz = new DateTimeZone(self::DEFAULT_TIMEZONE);
        if (is_string($tz))
            $tz = new DateTimeZone($tz);

        // instantiate and set time in system timezone
        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone(self::DEFAULT_TIMEZONE))
            ->setTimestamp(strtotime($timestring));

        // return timezone shifted to target timezone
        return $dt
            ->setTimezone($tz)
            ->format($format);
    }

    /**
     * @static
     * @param int $unixtimestamp
     * @param string $format
     * @param DateTimeZone $tz
     * @return null|string
     */
    static public function datetimeFromEpoch($unixtimestamp = 0, $format = self::DEFAULT_DATETIME_FORMAT, $tz = null)
    {
        if (!$unixtimestamp)
            return null;
        if ($tz == null)
            $tz = new DateTimeZone(self::DEFAULT_TIMEZONE);
        $dt = new DateTime('', new DateTimeZone(self::DEFAULT_TIMEZONE));
        $dt->setTimestamp($unixtimestamp);
        $dt->setTimezone($tz);
        return $dt->format($format); // Prints "1970-01-01 00:00:00"
    }

    /**
     * Caching this statically speeds up tests
     * @return string
     */
    static public function timestampOneMonthAgo()
    {
        if (!self::$_timestampOneMonthAgo)
            self::$_timestampOneMonthAgo = Util::datetime('now - ' . (30 * 24 * 60 * 60) . ' seconds');
        return self::$_timestampOneMonthAgo;
    }

    /** @var string */
    private static $_timestampOneMonthAgo;

    /**
     * Caching this statically speeds up tests
     * @return string
     */
    static public function timestampTwoMonthsAgo()
    {
        if (!self::$_timestampTwoMonthsAgo)
            self::$_timestampTwoMonthsAgo = Util::datetime('now - ' . (2 * 30 * 24 * 60 * 60) . ' seconds');
        return self::$_timestampTwoMonthsAgo;
    }

    /** @var string */
    private static $_timestampTwoMonthsAgo;

    /**
     * Caching this statically speeds up tests
     * @return string
     */
    static public function timestampOneSecondAgo()
    {
        if (!self::$_timestampOneSecondFromNow)
            self::$_timestampOneSecondFromNow = Util::datetime('now - 1 second');
        return self::$_timestampOneSecondFromNow;
    }

    /** @var string */
    private static $_timestampOneSecondFromNow;

    /**
     *
     * @param type $email
     * @param array $domainList
     * @return boolean
     */
    static public function emailInDomainList($email, $domainList)
    {
        $emailDomainPos = (strpos($email, '@') + 1);
        if ($emailDomainPos == null)
            return false;
        $emailDomain = substr($email, $emailDomainPos);
        if ($domainList == null)
            return false;
        if (!(count($domainList) > 0))
            return false;
        foreach ($domainList as $domain)
            if ($domain == $emailDomain)
                return true;
        return false;
    }

    /*     * * @param string $L
     * @return int 
     */

    static public function numCode($L, $codes)
    {
        $num = array_search($L, $codes);
        // type number found for string
        if ($num != null)
            return $num;
        // number found
        elseif (array_key_exists($L, $codes))
            return $L; // invalid type
        else
            return null;
    }

    /*
    * * @param int $L
     * @return string 
     */
    static public function codeNum($L, $codes)
    {
        if (isset($codes[$L]))
            return $codes[$L];
        if (in_array($L, $codes))
            return $L;
        return null;
    }

    /**
     * @static
     * @param $arr
     * @param $L
     * @param null $found
     * @return int|null|string
     */
    static public function arraySearchInsensitive($arr, $L, $found = null)
    {
        foreach ($arr as $key => $val)
            if (strtolower($val) == strtolower($L))
                $found = $key;
        return $found;
    }

    /**
     *
     * @param array $arr
     * @return mixed
     */
    static public function getRandomFrom($arr)
    {
        if ($arr == null || (!count($arr)))
            return null;
        $key = (self::randomIndexOfArray($arr));
        if (isset($arr[$key]))
            return $arr[$key];
        else
            return null;
    }


    /**
     * @param $a
     * @param $b
     * @return boolean
     */
    static public function monotononicIsGreaterThanOrEqualTo($a, $b)
    {
        if ($a == $b) return true;
        return self::monotononicCompare($a, $b, function ($a, $b) {
            return $a > $b;
        });
    }

    /**
     * @param $a
     * @param $b
     * @return boolean
     */
    static public function monotononicIsLessThan($a, $b)
    {
        return self::monotononicCompare($a, $b, function ($a, $b) {
            return $a < $b;
        });
    }

    /**
     * @param $a
     * @param $b
     * @param $cmp
     * @return boolean
     */
    static public function monotononicCompare($a, $b, $cmp)
    {
        $a_ar = explode('.', $a);
        $b_ar = explode('.', $b);
        for ($i = 0; $i < max(count($a_ar), count($b_ar)); $i++)
            if ($cmp(self::getIfSet($a_ar, $i, 0), self::getIfSet($b_ar, $i, 0))) return true;
        return false;
    }

    /**
     * @static
     * @param $arr
     * @param $key
     * @return array
     */
    static public function arrayFromSubkeyOfObjectsInArray($arr, $key)
    {
        // create array for output
        $new_arr = array();

        // for each sub-array in the input, search for the target key in that array, and if it exists, push it into the output array
        foreach ($arr as $sub_arr)
            if (($val = self::getIfSet($sub_arr, $key)) != null)
                $new_arr[] = $val;

        // return output
        return $new_arr;
    }

    /**
     * @static
     * @param $arr
     * @param $source_key
     * @param $source_val
     * @param $target_key
     * @param null $default
     * @internal param $key
     * @return array
     */
    static public function valueOfSiblingKeyOfMatchingKeyOfObjectInArray($arr, $source_key, $source_val, $target_key, $default = null)
    {
        // not array, return default
        if (!is_array($arr)) return $default;

        // for each sub-object in the input array, search for a matching source key/value in that array, and if it matches, return the value of target_key within that sub-object
        foreach ($arr as $sub_arr)
            if ($source_val == self::getIfSet($sub_arr, $source_key))
                return self::getIfSet($sub_arr, $target_key, $default);

        // else return default value
        return $default;
    }

    /**
     * @static
     * @param $arr
     * @param $key
     * @param $val
     * @return bool
     */
    static public function setKeyIfNotAlready(&$arr, $key, $val = true)
    {
        if (self::getIfSet($arr, $key) == $val) return false;
        $arr[$key] = $val;
        return true;
    }

    /**
     * @static
     * @param $arr
     * @param $arrAdd
     * @param $min
     * @return array
     */
    static public function arraySupplementToMinimum($arr, $arrAdd, $min)
    {
        // if necessary, make up minimum
        while (count($arrAdd) > 0 && count($arr) < $min)
            array_push($arr, array_shift($arrAdd));

        // return array
        return $arr;
    }

    /**
     * Interprets any input value and returns boolean
     * @static
     * @param $b
     * @return bool
     */
    static public function interpretBoolean($b)
    {
        return in_array(trim(strtolower($b)), array(
            'yeah',
            'yes',
            'true',
            '1',
            1,
            true
        ), true) ? true : false;
    }


    /**
     * Thanks to Matteo Spinelli
     * http://cubiq.org/the-perfect-php-clean-url-generator
     *
     * @param $str
     * @param array $replace
     * @param string $delimiter
     * @return mixed
     */
    static public function slugify($str, $replace = array(), $delimiter = '-')
    {
        if (!empty($replace)) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    /**
     * @param $url
     * @return mixed
     */
    static public function parseVideoIdFromYouTubeUrl($url)
    {
        if (strstr(strtolower($url), 'youtube.com') == false) return $url;
        parse_str(parse_url($url, PHP_URL_QUERY), $vars);
        return isset($vars['v']) ? $vars['v'] : $url;
    }

    /**
     * @param $url
     * @return mixed
     */
    static public function parseVideoIdFromVimeoUrl($url)
    {
        if (strstr(strtolower($url), 'vimeo.com') == false) return $url;
        $parsed = parse_url($url, PHP_URL_PATH);
        if (!$parsed) return $url;
        if (!strlen($parsed) > 1) return $url;
        return substr($parsed, 1);
    }

    /**
     * Sanitizes array of elements to string for Filename:
     * lowercasefolder/UppercasefirstFolder/UppercasefirstFilename.php
     *
     * @param array $el_ar
     * @return string
     */
    static public function createTestLoaderFilename($el_ar)
    {
        /** @var <string> $lowercaseFirstElement from array */
        $lowercaseElement = strtolower(array_shift($el_ar));
        /** @var <string> $OUT string to output */
        $OUT = $lowercaseElement;
        /** @var <string> $el element to add on to filename */
        foreach ($el_ar as $el)
            $OUT .= '/' . ucfirst($el);
        // add extension and return
        $OUT .= '.php';
        return $OUT;
    }

    /**
     * Sanitizes array of elements slug for Classname:
     * Uppercasefirst_Uppercasefirst_Uppercasefirst
     *
     * @param array $el_ar
     * @return string
     */
    static public function createTestLoaderClassname($el_ar)
    {
        // all elements get ucfirst
        foreach ($el_ar as $key => $val)
            $el_ar[$key] = ucfirst($val);

        // return class file via implode
        return implode('_', $el_ar);
    }

    /**
     * @param string $phone_number
     * @return string
     */
    static public function parseUnitedStatesPhoneNumber($phone_number)
    {
        // Global filtering
        $phone_number = preg_replace(
            array(
                '/([ABC])/i', // ABC to 2
                '/([DEF])/i', // DEF to 3
                '/([GHI])/i', // GHI to 4
                '/([JKL])/i', // JKL to 5
                '/([MNO])/i', // MNO to 6
                '/([PQRS])/i', // PQRS to 7
                '/([TUV])/i', // TUV to 8
                '/([WXYZ])/i', // WXYZ to 9
                '/(\+)/i', // erase +
                '/([^0-9]+)/', // erase non-numeric
            ),
            array(
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8',
                '9',
                '',
                '',
            ),
            $phone_number);

        // U.S.-specific filtering
        if (preg_match(
            '/^([0]+)/', // leading zeros equals fail
            $phone_number)
        )
            return false;

        // if the number is 12 digits and the first two digits are 01, cut off the first two digits
        if (strlen($phone_number) == 12 && substr($phone_number, 0, 2) == '01')
            $phone_number = substr($phone_number, 2);

        // if the number is 11 digits and the first digit is a 1, cut off the first digit
        if (strlen($phone_number) == 11 && substr($phone_number, 0, 1) == '1')
            $phone_number = substr($phone_number, 1);

        // must be exactly 10 digits; then add the U.S. country code to the top of the string
        if (strlen($phone_number) == 10)
            $phone_number = '1' . $phone_number;

        // return value
        return $phone_number;
    }

    /**
     * @param string $phone_number
     * @return string
     */
    static public function formatPhoneNumber($phone_number)
    {
        // so we're cool; reformat to e.g. +1(212)555-1212
        return preg_replace(
            '/^(\d+)(\d{3})(\d{3})(\d{4})$/',
            '+\1 (\2) \3-\4',
            $phone_number
        );
    }

    /**
     * @param $number
     * @return bool
     */
    static public function isUnitedStatesPhoneNumber($number)
    {
        // Parse attribute and replace with United States phone number version
        $number = Util::parseUnitedStatesPhoneNumber($number);

        // must be exactly 11 digits
        if (strlen($number) != 11)
            return false;

        // must begin with a 1
        if (substr($number, 0, 1) != '1')
            return false;

        return true;
    }

    /**
     * @param $arr
     * @return mixed
     */
    public static function getRandomKeyFrom($arr)
    {
        return array_rand($arr);
    }

    /**
     * @param array $originalOptions
     * @param array $defaultOptions
     * @return array
     */
    public static function addDropdownDefaultBeforeOptions($originalOptions, $defaultOptions)
    {
        $OUT = array();
        foreach ($defaultOptions as $key => $val)
            $OUT[$key] = $val;
        foreach ($originalOptions as $key => $val)
            $OUT[$key] = $val;
        return $OUT;
    }

    /**
     * @param string $dir
     */
    public static function requireAllPhpFilesInDir($dir)
    {
        // If no trailing slash, add one.
        if (!preg_match('/\//', $dir))
            $dir .= '/';
        foreach (scandir($dir) as $filename)
            if (preg_match('/\.php$/', $filename))
                /** @noinspection PhpIncludeInspection */
                require($dir . $filename);
    }


}