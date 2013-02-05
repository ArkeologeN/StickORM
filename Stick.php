<?php
/**
 * Created as Stick.php.
 * Developer: Hamza Waqas
 * Date:      2/4/13
 * Time:      4:20 PM
 */

class Stick {

    private static $_table = null;

    public $_properties = array();

    private $_data = array();

    private $_isNew;

    private $_dataSource;

    private $_fetchAs = PDO::FETCH_OBJ;

    protected static $_classDataSource = null;

    protected static $_name;

    static function newStick($table_name = null) {
        if ( is_null($table_name))
            throw new Exception("Cannot create null stick.");

        self::$_table = $table_name;
        return new self;
    }

    public function __construct($data = array()) {
        $this->_data = $data;
        $this->_isNew = true;
        $this->_dataSource = static::getDataSource();
    }

    public function save() {
        $this->_validateModifiable();
        if ($this->_isNew) {
            $this->_dataSource->add(static::_getName(), $this->_data);
        } else {
            $this->_dataSource->update(static::_getName(), $this->_data);
        }
    }

    public function delete() {

    }

    public function _setNew($bool) {
        $this->_isNew = $bool;
    }

    public function get($criteria = array(), $order = null, $limit = null, $offset = 0) {
        try {
            $result = static::getDataSource()->get(static::_getName(), $criteria, $order, $limit, $offset);

            $object = new stdClass();
            if ( !empty($result)) {
                $object = Stick::newStick(static::_getName());
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
            $result = static::getDataSource()->get_all(static::_getName(), $criteria, $order, $limit, $offset);
            $objects = array();
            if ( !empty($result) && count($result) > 0) {
                foreach ($result as $data) {
                    $object = Stick::newStick(static::_getName());
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
        return static::getDataSource()->count(static::_getName(),$criteria);
    }

    public function __set($column, $value) {
        $this->_data[$column] = $value;
    }

    public function __get($column) {
        return $this->_data[$column];
    }

    public function setPk($column_name = null) {

    }

    public function fetchAs($type = PDO::FETCH_OBJ) {
        $this->_fetchAs = $type;
        static::getDataSource()->setFetchMode($this->_fetchAs);
        return $this;
    }

    public static function getDataSource() {
        return static::$_classDataSource;
    }

    public static function setDataSource(DataSource $datasource) {
        static::$_classDataSource = $datasource;
    }

    protected static function _getName() {
        if ( !isset (static::$_table)) {
            throw new Exception("Table name found in ".get_called_class());
        }

        return static::$_table;
    }


}