<?php
/**
 * Created as DataSource.php.
 * Developer: Hamza Waqas
 * Date:      2/4/13
 * Time:      4:31 PM
 */

interface DataSource {

    public function get($object, array $criteria = array(), $order = null, $limit = null, $offset = 0);
    public function get_all($object, array $criteria = array(), $order = null, $limit = null, $offset = 0);
    public function count($object, array $criteria = array());
}