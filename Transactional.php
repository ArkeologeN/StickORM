<?php
/**
 * Created as Transactional.php.
 * Developer: Hamza Waqas
 * Date:      2/4/13
 * Time:      4:39 PM
 */
interface Transactional
{

    public function beginTransaction();
    public function commit();
    public function rollback();
}
