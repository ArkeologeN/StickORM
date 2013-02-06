<?php
/**
 * Created as Database.php.
 * Developer: Hamza Waqas
 * Date:      2/4/13
 * Time:      4:40 PM
 */

require 'Zend/Db.php';

class Database implements Transactional, DataSource, Fetcher, Modifiable {

    private $_db = null;

    public function __construct(Zend_Db_Adapter_Abstract $db) {
        $this->_db = $db;
    }

    private  function _getDb() {
        return $this->_db;
    }

    private function _getWhere($criteria) {
        $result = array();

        foreach ($criteria as $column => $value) {
            if ( strpos($value,'*') !== FALSE) {
                $result["{$column} LIKE ?"] = str_replace('*','%', $value);
            } else {
                $result["{$column} = ?"] = $value;
            }
        }

        return $result;
    }

    private function _getPrimaryKeyColumn($object) {
        $columns = $this->_getDb()->describeTable($object);
        foreach ($columns as $column) {
            if ( $column['PRIMARY'])
                return $column['COLUMN_NAME'];
        }

        return null;
    }

    public function beginTransaction()
    {
        // TODO: Implement beginTransaction() method.
        $this->_getDb()->beginTransaction();
    }

    public function commit()
    {
        // TODO: Implement commit() method.
        $this->_getDb()->commit();
    }

    public function rollback()
    {
        // TODO: Implement rollback() method.
        $this->_getDb()->rollBack();
    }

    public function get($object, array $criteria = array(), $order = null, $limit = null, $offset = 0)
    {
        // TODO: Implement get() method.
        $select = $this->_getDb()->select()->from($object);
        foreach ($this->_getWhere($criteria) as $where => $value) {
            $select->where($where, $value);
        }

        if ($order != null) {
            $select->order($order);
        }

        if ($limit != null) {
            $select->limit($limit);
        }

        $stmt = $this->_getDb()->query($select);
        $stmt->execute();
        $result = $stmt->fetch($this->_getDb()->getFetchMode());
        return $result;
    }

    public function get_all($object, array $criteria = array(), $order = null, $limit = null, $offset = 0)
    {
        // TODO: Implement get_all() method.
        $select = $this->_getDb()->select()->from($object);
        foreach ($this->_getWhere($criteria) as $where => $value) {
            $select->where($where, $value);
        }

        if ($order != null) {
            $select->order($order);
        }

        if ($limit != null) {
            $select->limit($limit);
        }

        $stmt = $this->_getDb()->query($select);
        $stmt->execute();
        $result = $stmt->fetchAll($this->_getDb()->getFetchMode());
        return $result;
    }

    public function count($object, array $criteria = array())
    {
        // TODO: Implement count() method.
        $select = $this->_getDb()->select()->from($object, array('value' => 'COUNT(*)'));

        foreach ($this->_getWhere($criteria) as $where => $value) {
            $select->where($where, $value);
        }

        $stmt = $this->_getDb()->query($select);
        $stmt->execute();
        $result = $stmt->fetch();

        return (int)$result['value'];
    }

    public function setFetchMode($fetch_mode)
    {
        // TODO: Implement setFetchMode() method.
        //echo 'setting'; exit;
        $this->_getDb()->setFetchMode($fetch_mode);
    }

    public function getFetchMode()
    {
        // TODO: Implement getFetchMode() method.
        return $this->_getDb()->getFetchMode();
    }

    public function add($object, array &$data)
    {
        // TODO: Implement add() method.
        $primaryKeyColumn = $this->_getPrimaryKeyColumn($object);
        echo "<pre>"; print_r($object); exit;
        $this->_getDb()->insert($object, $data);
        $data[$primaryKeyColumn] = $this->_getDb()->lastInsertId();
    }

    public function update($object, array &$data)
    {
        // TODO: Implement update() method.
        $primaryKeyColumn = $this->_getPrimaryKeyColumn($object);
        $updateData = $data;
        unset($updateData[$primaryKeyColumn]);
        $criteria = array($primaryKeyColumn => $data[$primaryKeyColumn]);
        $where = $this->_getWhere($criteria);
        $this->_getDb()->update($object, $updateData, $where);
        $data = array_merge($data, $updateData);
    }

    public function remove($object, array $data)
    {
        $primaryKeycolumn = $this->_getPrimaryKeyColumn($object);
        $criteria = array($primaryKeycolumn => $data[$primaryKeycolumn]);
        $where = $this->_getWhere($criteria);

        $this->_getDb()->delete($object, $where);
    }

    public function scanAndGet($object,$id) {
        $primaryKeyColumn = $this->_getPrimaryKeyColumn($object);
        return static::get($object, array("{$primaryKeyColumn} = ?" => $id));
    }
}