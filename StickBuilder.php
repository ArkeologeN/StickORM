<?php
/**
 * Created as StickBuilder.php.
 * Developer: Hamza Waqas
 * Date:      2/6/13
 * Time:      4:03 PM
 */

class StickBuilder {

    private $_db = null;

    private static $_dataSource = null;


    public function __construct() {
        $this->_db = StickConfig::getInstance()->datasource;
        static::$_dataSource = $this->_db->getAbstract();
        //$this->_dataSource = $this->_db->getAbstract();
    }

    static function newBuilder() {
        return new StickBuilder();
    }

    public function newQuery() {
        return static::$_dataSource;
    }

    static function newExecutor(Zend_Db_Select $select) {
        if ( !$select instanceof Zend_Db_Select)
            throw new Exception("{$select} should be instance of Zend_Db_Select");
        return self::$_dataSource->query($select);
    }
}