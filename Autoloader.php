<?php
/**
 * Created as Autoloader.php.
 * Developer: Hamza Waqas
 * Date:      2/4/13
 * Time:      4:09 PM
 */

namespace StickORM;

class Autoloader {

    static function registerLoader() {
        spl_autoload_register(__NAMESPACE__."\\Autoloader::_autoload");
    }

    static function _autoload($class) {
        if (strpos($class,'_') === FALSE) {
            $class_path = dirname(__FILE__).DS.$class.'.php';
            if (file_exists($class_path))
                require_once $class_path;
                //throw new \Exception("Unable to find Entity class at ".basename($class_path));


        }

    }
}