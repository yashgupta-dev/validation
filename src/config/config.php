<?php
namespace CodeCorner\Validation\config;

use Exception;

/**
 * configuration class for validation
 */
class config {
    
    public static $defaultConfiguration = [
        'DEFAULT_PHONE_CODE' => 'IN'
        // 'DATABASE_OBJECT'    => DB::get()->get
        
    ];
    
    /**
     * get
     *
     * @param  mixed $key
     * @return mixed
     */
    public static function get($key)
    {
        if (isset(self::$defaultConfiguration[$key])) {
            return self::$defaultConfiguration[$key];
        } else {
            throw new Exception('Call to undefined configuation ' . $key);
        }
    }

}