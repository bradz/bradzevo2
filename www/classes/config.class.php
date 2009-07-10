<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}

/**
 * 
 * The config class manages the site configuration and makes it accessible to the scripts
 *  
 * @author morrow
 * @version Alpha
 * 
 */
class config {
	private $config_file;
	private $config_ini;
	
	/**
	 * Constructor, sets configuration file and parses it
	 * @param $config_file (relative path of configuration file)
	 * @return unknown_type
	 */
	public function __construct( $config_file ) {
		$this->config_file=$config_file;
		$this->config_ini=parse_ini_file( $this->config_file );
	}
	
	/**
	 * Read out the configuration file and set the corresponding constants 
	 * @return unknown_type
	 */
	public function get_config() {
		// database connection
		define('MYSQL_HOST', $this->config_ini['server']);
		define('MYSQL_USER', $this->config_ini['username']);
		define('MYSQL_PASS', $this->config_ini['password']);
		define('MYSQL_DB', $this->config_ini['database']);
		
		// cookie
		define('COOKIE_NAME', $this->config_ini['cookiename']);
		
		// prefixed
		define('PHPBB_PREFIX', $this->config_ini['phpbb_prefix']);
		define('EVO_PREFIX', $this->config_ini['evo_prefix']);
		
		// what user group has access to the tools?
		define('TOOLS_GROUP', $this->config_ini['tools_group']);
		
		// path to phpBB
		define('PHPBB_RELATIVE_PATH', $this->config_ini['phpbb_relative_path']);
	}
}
?>