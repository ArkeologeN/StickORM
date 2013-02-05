<?php
/**
 * Created as Modifiable.php.
 * Developer: Hamza Waqas
 * Date:      2/5/13
 * Time:      2:11 PM
 */
interface Modifiable {

    public function add($object, array &$data);
    public function update($object, array &$data);
    public function remove($object, array $data);

}
