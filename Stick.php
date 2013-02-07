<?php
/**
 * Created as Stick.php.
 * Developer: Hamza Waqas
 * Date:      2/4/13
 * Time:      4:20 PM
 */

class Stick {

    private $_table = "Hamza";

    private $_data = array();

    private $_isNew;

    private $_dataSource;

    private $_fetchAs = PDO::FETCH_ASSOC;

    protected static $_classDataSource = null;

    protected static $_name;

    private $_config = null;

    private static $static_config = null;

    /*
    static function newStick($table_name = null, $id = null) {

        if ( is_null($table_name))
            throw new Exception("Cannot create null stick.");


        if ( !is_null($id)) {
            return self::_auto_get($id);
        }

        return new Stick($table_name);
    }
    */

    public function __construct($table_name,$data = array()) {
        $this->_table = $table_name;
        $this->_data = $data;
        $this->_isNew = true;
        $this->_config = StickConfig::getInstance();
        static::$static_config = $this->_config;
        $this->_dataSource = $this->_config->datasource;
        //echo "<pre>"; print_r(static::$_table); exit;
    }

    public function save() {
        $this->_validateModifiable();
        if ($this->_isNew) {
            $this->_dataSource->add($this->_getName(), $this->_data);
        } else {
            $this->_dataSource->update($this->_getName(), $this->_data);
        }
    }

    static  function _auto_get($id, $table_name = null) {
        if (!is_null($table_name))
            self::$_table = $table_name;

        $record = static::getDataSource()->scanAndGet(static::_getName(), $id);
        return $record;
    }

    public function delete() {
        $this->_dataSource->remove($this->_getName(), $this->_data);
    }

    public function _setNew($bool) {
        $this->_isNew = $bool;
    }

    private function _makeStick($data) {
        if ( !empty ($data)) {
            $stick = Stick::newStick($this->_getName());
            foreach ($data as $column => $value) {
                $stick->$column = $value;
            }

            return $stick;
        }
        return Stick::newStick($this->_getName());
    }

    public function get($criteria = array(), $order = null, $limit = null, $offset = 0) {
        try {
            $result = static::getDataSource()->get($this->_getName(), $criteria, $order, $limit, $offset);
            $object = new stdClass();
            if ( !empty($result)) {
                $object = Stick::newStick($this->_getName());
                $object->_setNew(false);
                foreach ($result as $column => $value) {
                    $object->$column = $value;
                }
            }

        } catch (Exception $ex) {
            echo "<pre>"; print_r($ex); exit;
        }
        return $object;
    }

    private function _validateModifiable()
    {
        if (!($this->getDataSource() instanceof Modifiable)) {
            throw new Exception("Object is read-only.");
        }
    }

    public function __unset($column)
    {
        $this->_validateModifiable();
        $this->_data[$column] = null;
    }

    public function get_all($object, array $criteria = array(), $order = null, $limit = null, $offset = 0) {
        try {
            $result = static::getDataSource()->get_all($this->_getName(), $criteria, $order, $limit, $offset);
            $objects = array();
            if ( !empty($result) && count($result) > 0) {
                foreach ($result as $data) {
                    $object = Stick::newStick($this->_getName());
                    $object->_setNew(false);
                    foreach ($data as $column => $value) {
                        $object->$column = $value;
                    }
                    $objects[] = $object;
                }
            }

        } catch (Exception $ex) {
            echo "<pre>"; print_r($ex); exit;
        }

        return $objects;
    }

    public function count($criteria = array()) {
        return static::getDataSource()->count($this->_getName(),$criteria);
    }

    public function __set($column, $value) {
        $this->_data[$column] = $value;
    }

    public function __get($column) {
        return $this->_data[$column];
    }

    public function fetchAs($type = PDO::FETCH_OBJ) {
        $this->_fetchAs = $type;
        static::getDataSource()->setFetchMode($this->_fetchAs);
        return $this;
    }

    public static function getDataSource() {
        return static::$static_config->datasource;
    }

    public static function setDataSource(DataSource $datasource) {
        static::$_classDataSource = $datasource;
    }

    public function _getName() {
        if ( !isset ($this->_table)) {
            throw new Exception("Table name not found in ".get_called_class());
        }

        return $this->_table;
    }

    public function hasProperty($key) {
        if ( array_key_exists($key, $this->_data))
            return true;

        return false;
    }

}