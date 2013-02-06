<?php
/**
 * Created as StickConfig.php.
 * Developer: Hamza Waqas
 * Date:      2/6/13
 * Time:      12:59 PM
 */

class StickConfig {

    private static $_data = array();

    private static $_instance = null;

    static function getInstance() {
        if ( !static::$_instance instanceof StickConfig)
            static::$_instance = new StickConfig();

        return static::$_instance;
    }

    public  function __get($key) {
        return static::$_data[$key];
    }

    public function __set($key, $value) {
        static::$_data[$key] = $value;
    }

}