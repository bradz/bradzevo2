<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}

/**
 * Class containing the valid parse types.
 * 
 * Valid types are contained in the array $valid_types.
 * 
 * @author morrow
 *
 */
class parse_type {
	private $type;
	
	/**
	 * Constructor. Bitches if the parse type is not valid.
	 * 
	 * @param $type
	 * @return unknown_type
	 */
	public function __construct( $type ) {
		if( in_array( $type, parse_types::$types ) ) {
			$this->type = $type;
		} else {
			echo "blahrgh";
		}
	}
}
?>