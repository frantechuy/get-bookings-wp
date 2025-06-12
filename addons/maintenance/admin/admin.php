<?php
class GetBookingsWPMaintenance {

	var $options;

	function __construct() {
		
		
		$this->ini_module();
	
		/* Plugin slug and version */
		$this->slug = 'getbookingswp';
		$this->subslug = 'getbwp-maintenance';
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$this->plugin_data = get_plugin_data( getbwp_maintenance_path . 'index.php', false, false);
		$this->version = $this->plugin_data['Version'];
		
		/* Priority actions */
		add_action('admin_menu', array(&$this, 'add_menu'), 11);
		add_action('admin_enqueue_scripts', array(&$this, 'add_styles'), 13);
		add_action('admin_head', array(&$this, 'admin_head'), 12 );
		add_action('admin_init', array(&$this, 'admin_init'), 12);
		
		add_action( 'wp_ajax_getbwp_clean_appo_without_service', array( &$this, 'getbwp_clean_appo_without_service' ));
		add_action( 'wp_ajax_getbwp_clean_appo_without_staff', array( &$this, 'getbwp_clean_appo_without_staff' ));
		

	}
	
	
	
	public function getbwp_set_option($option, $newvalue)
	{
		$settings = get_option('getbwp_options');
		$settings[$option] = $newvalue;
		update_option('getbwp_options', $settings);
	}
	
	function get_without_service(){
		
		global $wpdb, $getbookingwp;		
		
		$sql =  'SELECT  appo.* FROM ' . $wpdb->prefix . 'getbwp_bookings appo  ' ;				
				$sql .= " WHERE NOT EXISTS (".'SELECT  serv.* FROM ' . $wpdb->prefix . 'getbwp_services serv'." 
				            WHERE appo.booking_service_id  = serv.service_id) ";							
						 
		$res = $wpdb->get_results($sql );		
		return $res ;	
	
	
	}
	
	function get_without_user(){
		
		global $wpdb, $getbookingwp;		
		
		$sql =  'SELECT  appo.* FROM ' . $wpdb->prefix . 'getbwp_bookings appo  ' ;				
				$sql .= " WHERE NOT EXISTS (".'SELECT  usu.* FROM ' . $wpdb->users . ' usu'." 
				            WHERE appo.booking_staff_id  = usu.ID) ";					
			
				 
		$res = $wpdb->get_results($sql );		
		return $res ;	
	
	
	}
	
	
	function getbwp_clean_appo_without_staff(){
		
		global $wpdb, $getbookingwp;		
		
		$sql =  'DELETE FROM ' . $wpdb->prefix . 'getbwp_bookings   ' ;				
				$sql .= " WHERE NOT EXISTS (".'SELECT  NULL FROM ' .  $wpdb->users. ' usu'." 
				            WHERE booking_staff_id  = usu.ID) ";
				 
		$res = $wpdb->get_results($sql );		
		return $res ;	
	
	
	}
	
	function getbwp_clean_appo_without_service(){
		
		global $wpdb, $getbookingwp;		
		
		$sql =  'DELETE FROM ' . $wpdb->prefix . 'getbwp_bookings   ' ;				
				$sql .= " WHERE NOT EXISTS (".'SELECT  NULL FROM ' . $wpdb->prefix . 'getbwp_services serv'." 
				            WHERE booking_service_id  = serv.service_id) ";
							
				 
		$res = $wpdb->get_results($sql );		
		return $res ;	
	
	
	}
	
	
	
	
	
	public function ini_module()
	{
		global $wpdb;		   		  		   
		
	}
	
	function admin_init() 
	{
	
		$this->tabs = array(
			'manage' => __('Maintenance','get-bookings-wp')
			
		);
		$this->default_tab = 'manage';		
		
	}		
	
	function admin_head(){

	}

	function add_styles(){
	
		wp_register_script( 'getbwp_maintenance_js', getbwp_maintenance_url . 'admin/scripts/admin.js', array( 
			'jquery'
		) );
		wp_enqueue_script( 'getbwp_maintenance_js' );
	
		wp_register_style('getbwp_maintenance_css', getbwp_maintenance_url . 'admin/css/admin.css');
		wp_enqueue_style('getbwp_maintenance_css');
		
	}
	
	function add_menu()
	{
		
		$appointments = $this->get_without_service();
		
		$pending_count = count($appointments);
		
		if ($pending_count > 0)
		{
			$menu_label = sprintf( __( 'Maintenance %s','get-bookings-wp' ), "<span class='update-plugins count-$pending_count' title='$pending_count'><span class='update-count'>" . number_format_i18n($pending_count) . "</span></span>" );
			
		} else {
			
			$menu_label = __('Maintenance','get-bookings-wp');
		}
		
		
	
		$pending_title =  sprintf(__( '%d  pending bookings','get-bookings-wp'), $pending_count ) ;
		
		add_submenu_page( 'getbookingswp', __('Maintenance','get-bookings-wp'), $menu_label, 'manage_options', 'getbwp-maintenance', array(&$this, 'admin_page') );
		
	
		
	}

	function admin_tabs( $current = null ) {

		global $getbookingwp, $bupcomplement;	
			$tabs = $this->tabs;
			$links = array();
			if ( isset ( $_GET['tab'] ) ) {

				$current = sanitize_key($_GET['tab']);

			} else {
				$current = $this->default_tab;
			}
			foreach( $tabs as $tab => $name ) :
				if ( $tab == $current ) :
					$links[] = "<a class='nav-tab nav-tab-active' href='?page=".$this->subslug."&tab=$tab'>$name</a>";
				else :
					$links[] = "<a class='nav-tab' href='?page=".$this->subslug."&tab=$tab'>$name</a>";
				endif;
			endforeach;
			foreach ( $links as $link )
			echo wp_kses($link, $getbookingwp->allowed_html);
				
	}

	function get_tab_content() {
		$screen = get_current_screen();
		if( strstr($screen->id, $this->subslug ) ) {
			if ( isset ( $_GET['tab'] ) ) {
				$tab = sanitize_key($_GET['tab']);
			} else {
				$tab = $this->default_tab;
			}
			require_once getbwp_maintenance_path.'admin/panels/'.$tab.'.php';
		}
	}
	
	
	
	function admin_page() {
		
		
		global $getbookingwp, $bupcomplement;		
		
		if (isset($_POST['update_settings']) &&  $_POST['reset_email_template']=='' && !isset($_POST['update_getbwp_slugs'])) {
            $getbookingwp->buupadmin->update_settings();
        }
		
		
		
				
	?>
	
		<div class="wrap <?php echo esc_attr($this->slug); ?>-admin">
        
           <h2>GET BOOKINGS WP - <?php _e('Maintenance','get-bookings-wp'); ?></h2>
           
           <div id="icon-users" class="icon32"></div>
			
						
			<h2 class="nav-tab-wrapper"><?php $this->admin_tabs(); ?></h2>

			<div class="<?php echo esc_attr($this->slug); ?>-admin-contain">
				
				<?php $this->get_tab_content(); ?>
				
				<div class="clear"></div>
				
			</div>
			
		</div>

	<?php }

}
global $getbwp_maintenance;
$getbwp_maintenance = new GetBookingsWPMaintenance();