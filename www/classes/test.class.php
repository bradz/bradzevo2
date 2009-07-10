<?php
class test {
	private $woot;
	
	public function __construct() {
		$this -> woot = 5;
	}
	
	public function get_woot() {
		return $this -> woot;
	}
}
?>