<?php
interface Model {
	public static function save($data);
	public static function find($id);
	public static function remove($id);
	public function __toString();
}
?>