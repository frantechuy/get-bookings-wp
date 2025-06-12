<?php
global $getbookingwp;

define('getbwp_maintenance_url',plugin_dir_url(__FILE__ ));
define('getbwp_maintenance_path',plugin_dir_path(__FILE__ ));



	/* functions */
	foreach (glob(getbwp_maintenance_path . 'functions/*.php') as $filename) { require_once $filename; }
	
	/* administration */
	if (is_admin()){
		foreach (glob(getbwp_maintenance_path . 'admin/*.php') as $filename) { include $filename; }
	}
	
