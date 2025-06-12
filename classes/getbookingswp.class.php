<?php
class GetBookingsWP
{
	public $classes_array = array();
	public $registration_fields;
	public $login_fields;
	public $fields;
	public $allowed_inputs;
	public $allowed_html;
	public $use_captcha = "no";
	
		
	public function __construct(){		
		
		$this->logged_in_user = 0;
		$this->login_code_count = 0;
		$this->current_page = sanitize_url($_SERVER['REQUEST_URI']);
		$this->set_allowed_html();	
    }
	
	public function plugin_init() 	{	
		
		/*Load Amin Classes*/		
		if (is_admin()) 
		{
			$this->set_admin_classes();
			$this->load_classes();					
		
		}else{
			
			/*Load Main classes*/
			$this->set_main_classes();
			$this->load_classes();
			
		
		}
		
		//ini settings
		$this->intial_settings();		
		
	}

	
	

	public function set_allowed_html(){

		global $allowedposttags;
		

		$allowed_html = wp_kses_allowed_html( 'post' );

		$allowed_html['select'] = array(
			'name' => array(),
			'id' => array(),
			'class' => array(),
			'style' => array()
		);

		$allowed_html['option'] = array(
			'name' => array(),
			'id' => array(),
			'class' => array(),
			'value' => array(),
			'selected' => array(),
			'style' => array()
		);

		$allowed_html['input'] = array(
			'name' => true,
			'id' => true,
			'class' => true,
			'value' => true,
			'selected' => true,
			'style' =>true
		);

		$allowed_html['table'] = array(
			'name' => true,
			'id' => true,
			'class' => true,			
			'style' => true
		);

		$allowed_html['td'] = array(
			'name' =>true,
			'id' => true,
			'class' => true,
			'style' => true
		);

		$allowed_html['tr'] = array(
			'name' => array(),
			'id' => array(),
			'class' => array(),
			
		);

		$allowed_atts = array(
			'align'      => array(),
			'span'      => array(),
			'checked'      => array(),
			'class'      => array(),
			'selected'      => array(),
			'type'       => array(),
			'id'         => array(),
			'dir'        => array(),
			'lang'       => array(),
			'style'      => array(),
			'display'      => array(),
			'xml:lang'   => array(),
			'src'        => array(),
			'alt'        => array(),
			'href'       => array(),
			'rel'        => array(),
			'rev'        => array(),
			'target'     => array(),
			'novalidate' => array(),
			'type'       => array(),
			'value'      => array(),
			'name'       => array(),
			'tabindex'   => array(),
			'action'     => array(),
			'method'     => array(),
			'for'        => array(),
			'width'      => array(),
			'height'     => array(),
			'data'       => array(),
			'title'      => array(),
			'getbwp-data-date'      => array(),
			'getbwp-data-timeslot'      => array(),
			'getbwp-data-service-staff'      => array(),
			'getbwp-max-capacity'      => array(),
			'getbwp-max-available'      => array(),
			'data-nuve-rand-id'      => array(),
			'data-nuve-rand-key'      => array(),
			'data-location'      => array(),
			'data-cate-id'      => array(),
			'data-category-id'      => array(),
			'data-staff-id'      => array(),
			'data-staff_id'      => array(),
			'data-id'      => array(),
			'appointment-id'      => array(),
			'message-id'      => array(),
			
			'appointment-status'      => array(),
			'getbwp-staff-id'      => array(),				
			'service-id'      => array(),			
			'staff-id'      => array(),	
			'user-id'      => array(),	
			'staff_id'      => array(),		
			'widget-id'      => array(),
			'day-id'      => array(),
			'break-id'      => array(),	
			'category-id'      => array(),			
			'/option'      => array(),
			'label'      => array(),
			
			

			
		);



		$allowedposttags['button']     = $allowed_atts;
		$allowedposttags['form']     = $allowed_atts;
		$allowedposttags['label']    = $allowed_atts;
		$allowedposttags['input']    = $allowed_atts;
		$allowedposttags['textarea'] = $allowed_atts;
		$allowedposttags['iframe']   = $allowed_atts;
		$allowedposttags['script']   = $allowed_atts;
		$allowedposttags['style']    = $allowed_atts;
		$allowedposttags['display']    = $allowed_atts;
	
		$allowedposttags['select']    = $allowed_atts;
		$allowedposttags['option']    = $allowed_atts;
		$allowedposttags['optgroup']    = $allowed_atts;
		$allowedposttags['strong']   = $allowed_atts;
		$allowedposttags['small']    = $allowed_atts;
		$allowedposttags['table']    = $allowed_atts;
		$allowedposttags['span']     = $allowed_atts;
		$allowedposttags['abbr']     = $allowed_atts;
		$allowedposttags['code']     = $allowed_atts;
		$allowedposttags['pre']      = $allowed_atts;
		$allowedposttags['div']      = $allowed_atts;
		$allowedposttags['img']      = $allowed_atts;
		$allowedposttags['h1']       = $allowed_atts;
		$allowedposttags['h2']       = $allowed_atts;
		$allowedposttags['h3']       = $allowed_atts;
		$allowedposttags['h4']       = $allowed_atts;
		$allowedposttags['h5']       = $allowed_atts;
		$allowedposttags['h6']       = $allowed_atts;
		$allowedposttags['ol']       = $allowed_atts;
		$allowedposttags['ul']       = $allowed_atts;
		$allowedposttags['li']       = $allowed_atts;
		$allowedposttags['em']       = $allowed_atts;
		$allowedposttags['hr']       = $allowed_atts;
		$allowedposttags['br']       = $allowed_atts;
		$allowedposttags['tr']       = $allowed_atts;
		$allowedposttags['td']       = $allowed_atts;
		$allowedposttags['p']        = $allowed_atts;
		$allowedposttags['a']        = $allowed_atts;
		$allowedposttags['b']        = $allowed_atts;
		$allowedposttags['i']        = $allowed_atts;

		$this->allowed_html = $allowedposttags;

	}
	
 
	public function set_main_classes()
	{
		 $this->classes_array = array( "commmonmethods" =>"common",		 
		 "shortcode" =>"shorcodes",
		 "appointment" =>"appointment",
		 "breaks" =>"break",
		 "paypal" =>"paypal",
		 "register" =>"register",
		 "order" =>"order",
		 "service" =>"service",
		 "userpanel" =>"user",		 
		 "imagecrop" =>"cropimage",
		 "messaging" =>"messaging"	
		 
		   ); 	
	
	}
	
	public function set_admin_classes()
	{
				 
		 $this->classes_array = array( "commmonmethods" =>"common" , 
			
		 "shortcode" =>"shorcodes",
		 "appointment" =>"appointment",
		 "breaks" =>"break",
		 "paypal" =>"paypal",
		 "register" =>"register",
		 "order" =>"order",
		 "buupadmin" =>"admin"	,				
		 "service" =>"service",
		 "userpanel" =>"user",
		 "imagecrop" =>"cropimage",		
		 "adminshortcode" =>"adminshortcodes",
		 "messaging" =>"messaging"
		 
		  
		   ); 	
		 
		
	}
	
	
	public  function get_date_picker_format( ) {
		global  $getbookingwp;
		
		$date_format = $getbookingwp->get_option('getbwp_date_picker_format');
		
		if($date_format=='d/m/Y'){			
			
			$date_format = 'dd/mm/yy';
			
		}elseif($date_format=='m/d/Y'){
			
			$date_format = 'mm/dd/yy';			
			
		}else{
			
			$date_format = 'mm/dd/yy';
			
		}
        return $date_format;
		
	
	}
	
	public  function get_date_picker_date()  {
		global  $getbookingwp;
		
		$date_format = $getbookingwp->get_option('getbwp_date_picker_format');
		
		if($date_format==''){			
			
			$date_format = 'm/d/Y';					
		}
        return $date_format;
		
	
	}

	public function get_front_step_label($step){

		global  $getbookingwp;

		$label = '';

		if($step==1){
			$label = __('Service','get-bookings-wp');						
		}elseif($step==2){
			$label = __('Staff','get-bookings-wp');		
		}elseif($step==3){
			$label = __('Time','get-bookings-wp');
		}elseif($step==4){
			$label = __('Details','get-bookings-wp');
						
		}


		return $label;

	}


	
	public function get_my_account_page(){
		global $getbookingwp, $wp_rewrite, $blog_id ; 
		
		$wp_rewrite = new WP_Rewrite();
		
		$account_page_id = get_option('getbwp_my_account_page');				
		$my_account_url = get_page_link($account_page_id);				
				
		if($my_account_url==""){
			$url = sanitize_url($_SERVER['REQUEST_URI']);
						
		}else{
							
			$url = $my_account_url;				
						
		}
					
		 return $url ;		
	
	}
	
	public  function get_int_date_format(){
		global  $getbookingwp;
		
		$date_format = $getbookingwp->get_option('getbwp_date_admin_format');
		if($date_format==''){			
			$date_format = 'm/d/Y';					
		}
        return $date_format;	
	}
	
	
	
	
	
	
	public function intial_settings(){

		add_action( 'admin_notices', array(&$this, 'getbwp_display_custom_message'));	
		
			
		add_action( 'wp_ajax_create_default_pages_auto', array( $this, 'create_default_pages_auto' ));	
		add_action( 'wp_ajax_getbwp_hide_proversion_message', array( $this, 'hide_proversion_message' ));				
			 			 
		$this->include_for_validation = array('text','fileupload','textarea','select','radio','checkbox','password');
			
		add_action('wp_enqueue_scripts', array(&$this, 'add_front_end_styles'), 9); 
		add_action('admin_enqueue_scripts', array(&$this, 'add_styles_scripts'), 9);
		
		/*Create a generic profile page*/
		add_action( 'init', array(&$this, 'activate_profile_module'), 9);
		
		/* Remove bar except for admins */
		//add_action('init', array(&$this, 'getbwp_remove_admin_bar'), 9);	
		
		/* Create Standar Fields */		
		add_action('init', array(&$this, 'getbokingswp_create_standard_fields'));
		add_action('admin_init', array(&$this, 'getbokingswp_create_standard_fields'));	
		
		add_action('init', array(&$this, 'create_default_business_hours'));
		add_action('admin_init', array(&$this, 'create_default_business_hours'));
	}

	
	

		
	
	public function activate_profile_module (){
		$this->create_initial_pages();		
	}
	
	public function create_initial_pages (){
		global $getbookingwp;
		
		$fresh_page_creation  = get_option( 'getbwp_auto_page_creation' );			
		$profile_page_id = $this->get_option('profile_page_id');	
		
		if($profile_page_id!='' ){
			$profile_page = get_post($profile_page_id);
			

			if(isset($profile_page->post_name)){

				$slug =  $profile_page->post_name;
				
				if($fresh_page_creation==1) {						
					//pages created
					update_option('getbwp_auto_page_creation',0);			 
					
					add_rewrite_rule("$slug/([^/]+)/?",'index.php?page_id='.$profile_page_id.'&getbwp_username=$matches[1]', 'top');		
					//this rules is for displaying the user's profiles
					add_rewrite_rule("([^/]+)/$slug/([^/]+)/?",'index.php?page_id='.$profile_page_id.'&getbwp_username=$matches[2]', 'top');
					
					flush_rewrite_rules(false);
				
				}else{			
						
					add_rewrite_rule("$slug/([^/]+)/?",'index.php?page_id='.$profile_page_id.'&getbwp_username=$matches[1]', 'top');		
					//this rules is for displaying the user's profiles
					add_rewrite_rule("([^/]+)/$slug/([^/]+)/?",'index.php?page_id='.$profile_page_id.'&getbwp_username=$matches[2]', 'top');
				
				}
			}		
		}
			
		/* Setup query variables */
		 add_filter( 'query_vars',   array(&$this, 'getbwp_uid_query_var') );				
			
	}
	
	public function getbwp_uid_query_var( $query_vars ){
		$query_vars[] = 'getbwp_username';
		//$query_vars[] = 'searchuser';
		return $query_vars;
	}
	
	public function create_rewrite_rules() {
		global  $getbookingwp;
		
		//$slug = $getbookingwp->get_option("getbwp_slug"); // Profile Slug
		$profile_page_id = $this->get_option('profile_page_id');
		$profile_page = get_post($profile_page_id);
		$slug =  $profile_page->post_name;
		
		add_rewrite_rule("$slug/([^/]+)/?",'index.php?page_id='.$profile_page_id.'&getbwp_username=$matches[1]', 'top');		
			//this rules is for displaying the user's profiles
		add_rewrite_rule("([^/]+)/$slug/([^/]+)/?",'index.php?page_id='.$profile_page_id.'&getbwp_username=$matches[2]', 'top');
		flush_rewrite_rules(false);
	}
	
	public function hide_proversion_message () {
		$message= sanitize_text_field($_POST['message_id']);		
		update_option('getbwp_pro_improvement_'.$message,1);
		die();
		
	}
	
	public function display_ultimate_validate_copy (){
		global  $getbookingwp;
			
		$res_message  = get_option( 'getbwp_pro_improvement_13' );		
		if($res_message=="" ){
		
			$message = '<div id="message" class="updated buppro-message wc-connect">
	<a class="buppro-message-close notice-dismiss" href="" message-id="13"> '.__('Dismiss','get-bookings-wp').'</a>

	<p><strong>Get Bookings WP Updates:</strong> – We highly recommend you creating a serial number for your domain which will allow you to update your plugin automatically.</p>
	
	<p class="submit">
		
		<a href="?page=getbookingswp&tab=licence" class="button-secondary" > '.__('Validate your Copy','get-bookings-wp').'</a>
	</p>
</div>';
			
			
				
		echo wp_kses($message, $getbookingwp->allowed_html);
		
		}
		
		
		
		
	}
	
	
	public function getbwp_display_custom_message () 
	{
		
		$getbwp_pro_message  = get_option( 'getbwp_pro_improvement_12' );
		
	}	

	public function getbwp_fresh_install_message ($message) 
	{
	
		global $getbookingwp;
		echo wp_kses($message, $getbookingwp->allowed_html);
	
	}	

	function user_exists( $user_id ) 
	{
		$aux = get_userdata( $user_id );
		if($aux==false){
			return false;
		}
		return true;
	}
	
	public function create_default_pages_auto () 
	{
		update_option('getbwp_auto_page_creation',1);
		
	}
	
	
	//display message
	public function uultra_fresh_install_message ($message) 
	{
		global $getbookingwp;

		$m = "<p><strong>$message</strong></p></div>";
	
		echo wp_kses($m, $getbookingwp->allowed_html);
	
	}
	

	function getbwp_remove_admin_bar() 
	{
		if (!current_user_can('manage_options') && !is_admin())
		{			

		}
	}
	
	function userultra_convert_date($date) 
	{
		
		$custom_date_format = $this->get_option('getbwp_date_format');
			
		if ($custom_date_format) 
		{
			$date = date($custom_date_format, strtotime($date));
		}		
		
		return $date;
	}
	
	public function get_currency_symbol() 
	{
		
		$currency_symbol = $this->get_option('currency_symbol');
			
		if ($currency_symbol=='') 
		{
			$currency_symbol = '$';
		}	
		
		return $currency_symbol;
	}
	
	
	
	public function get_logout_url ()
	{		

		$redirect_to = $this->current_page;			
		return wp_logout_url($redirect_to);
	}
	
	
	public function custom_logout_page ($atts)
	{
		global $xoouserultra, $wp_rewrite ;
		
		$wp_rewrite = new WP_Rewrite();		
		require_once(ABSPATH . 'wp-includes/link-template.php');		
		
		extract( shortcode_atts( array(	
			
			'redirect_to' => '', 		
							
			
		), $atts ) );		
		
		
		//check redir		
		$account_page_id = get_option('getbwp_my_account_page');
		$my_account_url = get_permalink($account_page_id);
		
		if($redirect_to=="")
		{
				$redirect_to =$my_account_url;
		
		}
		$logout_url = wp_logout_url($redirect_to);	

		
		$logout_url = str_replace("amp;","",$logout_url);
	
		wp_redirect($logout_url);
		exit;
		
	}
	
	public function get_redirection_link ($module)
	{
		$url ="";
		
		if($module=="profile")
		{
			$url = $this->get_option('profile_page_id');			
		}
		
		return $url;
	}		
		
	
	/*Setup redirection*/
	public function getbwp_pro_redirect() 
	{
		global $pagenow;

		/* Not admin */
		if (!current_user_can('administrator')) {
			
		    $option_name = '';
			// Check if current page is profile page
			if('profile.php' == $pagenow)
			{
				// If user have selected to redirect backend profile page            
				if($this->get_option('redirect_backend_profile') == '1')
				{
					$option_name = 'profile_page_id';
				}
			}  
            

        // Check if current page is login or not
        if('wp-login.php' == $pagenow && !isset($_REQUEST['action']))
        {
            if($this->get_option('redirect_backend_login') == '1')
            {
                $option_name = 'login_page_id';
            }
        }

        if('wp-login.php' == $pagenow && isset($_REQUEST['action']) && $_REQUEST['action'] == 'register')
        {
            if($this->get_option('redirect_backend_registration') == '1')
            {
                $option_name = 'registration_page_id';
            }
        }
		
        
        if($option_name != '')
        {
            if($this->get_option($option_name) > 0)
            {
                // Generating page url based on stored ID
                $page_url = get_permalink($this->get_option($option_name));
                
                // Redirect if page is not blank
                if($page_url != '')
                {
                    if($option_name == 'login_page_id' && isset($_GET['redirect_to']))
                    {
                        $url_data = parse_url($page_url);
                        $join_code = '/?';
                        if(isset($url_data['query']) && $url_data['query']!= '')
                        {
                            $join_code = '&';
                        }
                        
                        $page_url= $page_url.$join_code.'redirect_to='.sanitize_url($_GET['redirect_to']);
                    }			
					
                    
                    wp_redirect($page_url);
                    exit;
                }
            }    
        }
		}

	}
	
	
		
	
	/*Create Directory page */
	public function create_directory_page($parent) 
	{
		
	}
	
	/*Create login page */
	public function create_login_page() 
	{
		
	}
	
	/*Create register page */
	public function create_register_page() 
	{
		
	}
	
		
		
	public function getbwp_set_option($option, $newvalue)
	{
		$settings = get_option('getbwp_options');
		$settings[$option] = $newvalue;
		update_option('getbwp_options', $settings);
	}
	
	
	public function get_fname_by_userid($user_id) 
	{
		$f_name = get_user_meta($user_id, 'first_name', true);
		$l_name = get_user_meta($user_id, 'last_name', true);
		
		$f_name = str_replace(' ', '_', $f_name);
		$l_name = str_replace(' ', '_', $l_name);
		$name = $f_name . '-' . $l_name;
		return $name;
	}
	
	public function getbwp_get_user_meta($user_id, $meta) 
	{
		$data = get_user_meta($user_id, $meta, true);
		
		return $data;
	}
	
	public function getbokingswp_create_standard_fields ()	
	{
		
		/* Allowed input types */
		$this->allowed_inputs = array(
			'text' => __('Text','get-bookings-wp'),
			
			'textarea' => __('Textarea','get-bookings-wp'),
			'select' => __('Select Dropdown','get-bookings-wp'),
			'radio' => __('Radio','get-bookings-wp'),
			'checkbox' => __('Checkbox','get-bookings-wp'),			
		  'datetime' => __('Date Picker','get-bookings-wp')
		);
		
		/* Core registration fields */
		$set_pass = $this->get_option('set_password');
		if ($set_pass) 
		{
			$this->registration_fields = array( 
			50 => array( 
				'icon' => 'user', 
				'field' => 'text', 
				'type' => 'usermeta', 
				'meta' => 'display_name', 
				'name' => __('Your Name', 'get-bookings-wp'),
				'required' => 1
			),
			100 => array( 
				'icon' => 'envelope', 
				'field' => 'text', 
				'type' => 'usermeta', 
				'meta' => 'user_email', 
				'name' => __('E-mail','get-bookings-wp'),
				'required' => 1,
				'can_hide' => 1,
			),
			
			250 => array( 
				'icon' => 'phone', 
				'field' => 'text', 
				'type' => 'usermeta', 
				'meta' => 'telephone', 
				'name' => __('Phone Number','get-bookings-wp'),
				'required' => 1,
				'can_hide' => 1,
				'help' => __('Input your phone number','get-bookings-wp')
			)
		);
		} else {
			
		$this->registration_fields = array( 
			50 => array( 
				'icon' => 'user', 
				'field' => 'text', 
				'type' => 'usermeta', 
				'meta' => 'display_name', 
				'name' => __('Your Name','get-bookings-wp'),
				'required' => 1
			),
			100 => array( 
				'icon' => 'envelope', 
				'field' => 'text', 
				'type' => 'usermeta', 
				'meta' => 'user_email', 
				'name' => __('E-mail','get-bookings-wp'),
				'required' => 1,
				'can_hide' => 1,
				'help' => __('Information about your booking will be sent to you.','get-bookings-wp')
			),
			
			250 => array( 
				'icon' => 'phone', 
				'field' => 'text', 
				'type' => 'usermeta', 
				'meta' => 'telephone', 
				'name' => __('Phone Number','get-bookings-wp'),
				'required' => 1,
				'can_hide' => 1,
				'help' => __('Input your Phone Number','get-bookings-wp')
			)
		);
		}
		
		/* Core login fields */
		$this->login_fields = array( 
			50 => array( 
				'icon' => 'user', 
				'field' => 'text', 
				'type' => 'usermeta', 
				'meta' => 'user_login', 
				'name' => __('Username or Email','get-bookings-wp'),
				'required' => 1
			),
			100 => array( 
				'icon' => 'lock', 
				'field' => 'password', 
				'type' => 'usermeta', 
				'meta' => 'login_user_pass', 
				'name' => __('Password','get-bookings-wp'),
				'required' => 1
			)
		);
		
		/* These are the basic profile fields */
		$this->fields = array(
			80 => array( 
			  'position' => '50',
				'type' => 'separator', 
				'name' => __('Appointment Info','get-bookings-wp'),
				'private' => 0,
				'show_in_register' => 1,
				'deleted' => 0,
				'show_to_user_role' => 0
			),			
			
			170 => array( 
			  'position' => '200',
				'icon' => 'pencil',
				'field' => 'textarea',
				'type' => 'usermeta',
				'meta' => 'special_notes',
				'name' => __('Comments','get-bookings-wp'),
				'can_hide' => 0,
				'can_edit' => 1,
				'show_in_register' => 1,
				'private' => 0,
				'social' => 0,
				'deleted' => 0,
				'allow_html' => 1,				
				'help_text' => ''
			
			)
		);
		
		
		
		/* Store default profile fields for the first time */
		if (!get_option('getbwp_profile_fields'))
		{
			update_option('getbwp_profile_fields', $this->fields);
		}	
		
		
		
		
	}
	
	public function create_default_business_hours() 
	{
		$business_hours = array();
		
		$business_hours[1] = array('from' =>'08:00', 'to' =>'18:00');
		$business_hours[2] = array('from' =>'08:00', 'to' =>'18:00');
		$business_hours[3] = array('from' =>'08:00', 'to' =>'18:00');
		$business_hours[4] = array('from' =>'08:00', 'to' =>'18:00');
		$business_hours[5] = array('from' =>'08:00', 'to' =>'18:00');
		
		if (!get_option('getbwp_business_hours'))
		{
			update_option('getbwp_business_hours', $business_hours);
		}	
	
	}
	
	public function xoousers_update_field_value($option, $newvalue) 
	{
		$fields = get_option('getbwp_profile_fields');
		$fields[$option] = $newvalue;
		update_option('getbwp_profile_fields', $settings);
	
	}
	
	
	
	
		
	function get_the_guid( $id = 0 )
	{
		$post = get_post($id);
		return apply_filters('get_the_guid', $post->guid);
	}
	   	
	function load_classes() 
	{	
		
		foreach ($this->classes_array as $key => $class) 
		{
			if (file_exists(getbookingpro_path."classes/$class.php")) 
			{
				require_once(getbookingpro_path."classes/$class.php");
						
					
			}
				
		}	
	}
	
	
	
	
	function uultra_my_theme_add_editor_styles( $mce_css ) 
	{
	  if ( !empty( $mce_css ) )
		$mce_css .= ',';
		//$mce_css .=  getbwp_url.'templates/'.getbwp_template.'/css/editor-style.css';
		return $mce_css;
	  }
	  
	  
	  /* register admin scripts */
	public function add_styles_scripts()
	{	
		
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_style("wp-jquery-ui-dialog");
		wp_enqueue_script('jquery-ui-datepicker' );
		
		wp_enqueue_script('plupload-all');	
		wp_enqueue_script('jquery-ui-progressbar');	
		
				
		wp_register_script( 'form-validate-lang', getbookingpro_url.'js/languages/jquery.validationEngine-en.js',array('jquery'));
			
		wp_enqueue_script('form-validate-lang');			
		wp_register_script( 'form-validate', getbookingpro_url.'js/jquery.validationEngine.js',array('jquery'));
		wp_enqueue_script('form-validate');		
	}
	
	/* register styles */
	public function add_front_end_styles()
	{
		global $wp_locale, $getbwpcomplement;
	
		
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_style("wp-jquery-ui-dialog");
		wp_enqueue_script('jquery-ui-datepicker' );				

		/* Font Awesome */
		wp_register_style('getbwp_font_awesome', getbookingpro_url.'css/css/font-awesome.min.css');
		wp_enqueue_style('getbwp_font_awesome');
		
		//----MAIN STYLES		
				
		/* Custom style */		
		wp_register_style('getbwp_style', getbookingpro_url.'templates/css/styles.css');
		wp_enqueue_style('getbwp_style');		
	
		
		/*country detection css*/
		wp_register_style('getbwp_phone_code_detect_css', getbookingpro_url.'js/int-phone-code/css/intlTelInput.css');
		wp_enqueue_style('getbwp_phone_code_detect_css');
		
		/*country detection js*/		
		wp_register_script( 'getbwp_phone_code_detect_js', getbookingpro_url.'js/int-phone-code/js/intlTelInput.min.js',array('jquery'),  null);
		wp_enqueue_script('getbwp_phone_code_detect_js');
		
		
		/*Users JS*/		
		wp_register_script( 'getbwp-front_js', getbookingpro_url.'js/getbwp-front.js',array('jquery'),  null);
		wp_enqueue_script('getbwp-front_js');

		wp_add_inline_script( 'getbwp-front_js', 'const GETBWPFRONTV = ' . json_encode( array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),			
		) ), 'before' );
			
		
		/*uploader*/					
		wp_enqueue_script('jquery-ui');			
		wp_enqueue_script('plupload-all');	
		wp_enqueue_script('jquery-ui-progressbar');		
				
		
		/*Validation Engibne JS*/		
			
		wp_register_script( 'form-validate-lang', getbookingpro_url.'js/languages/jquery.validationEngine-en.js',array('jquery'));
			
		wp_enqueue_script('form-validate-lang');			
		wp_register_script('form-validate', getbookingpro_url.'js/jquery.validationEngine.js',array('jquery'));
		wp_enqueue_script('form-validate');
		
		$message_wait_submit ='<img src="'.getbookingpro_url.'admin/images/loaderB16.gif" width="16" height="16" /></span>&nbsp; '.__("Please wait ...","get-bookings-wp").'';	
		
		
		$date_picker_format = $this->get_date_picker_format();	
		
		$var_util_countries = getbookingpro_url.'js/int-phone-code/js/utils.js';
		
		if($this->get_option('country_detection') == '0'){
			
			$country_detection = 'no';			
		}else{
			$country_detection = 'yes';		
		}

		
		if($this->get_option('gateway_stripe_active') == '1' && isset($getbwpcomplement)){
			
			$stripe_is_active = 1;	

		}else{

			$stripe_is_active = 0;		
		}	


		
		
		wp_localize_script( 'getbwp-front_js', 'getbwp_pro_front', array(
            'wait_submit'     => $message_wait_submit,
			'country_detection'     => $country_detection,
			'button_legend_step2'     =>  __('Search Again',"get-bookings-wp"),
			'button_legend_step3'     => __("<< Back","get-bookings-wp"),
			'button_legend_step3_cart'     => __("Book More","get-bookings-wp"),
			'message_wait_staff_box'     => __("Please wait ...","get-bookings-wp"),
			'bb_date_picker_format'     => $date_picker_format,
			'stripe_is_active'     => $stripe_is_active,
			'country_util_url'     => $var_util_countries,
			'message_wait_availability'     => '<p><img src="'.getbookingpro_url.'admin/images/loaderB16.gif" width="16" height="16" /></span>&nbsp; '.__("Please wait ...","get-bookings-wp").'</p>'
            
            
        ) );
		
		
		
		$date_picker_array = array(
		            'closeText' =>  __('Done',"get-bookings-wp"),
		            'prevText' =>  __('Prev',"get-bookings-wp"),
		            'nextText' => __('Next',"get-bookings-wp"),
		            'currentText' => __('Today',"get-bookings-wp"),
		            'monthNames' => array(
		                        'Jan' =>  __('January',"get-bookings-wp"),
    		                    'Feb' =>  __('February',"get-bookings-wp"),
    		                    'Mar' =>  __('March',"get-bookings-wp"),
    		                    'Apr' =>  __('April',"get-bookings-wp"),
    		                    'May' =>  __('May',"get-bookings-wp"),
    		                    'Jun' =>  __('June',"get-bookings-wp"),
    		                    'Jul' =>  __('July',"get-bookings-wp"),
    		                    'Aug' =>  __('August',"get-bookings-wp"),
    		                    'Sep' =>  __('September',"get-bookings-wp"),
    		                    'Oct' => __('October' ,"get-bookings-wp"),
    		                    'Nov' =>  __('November' ,"get-bookings-wp"),
    		                    'Dec' =>  __('December' ,"get-bookings-wp")
		                    ),
		            'monthNamesShort' => array(
		                        'Jan' => __('Jan' ,"get-bookings-wp") ,
    		                    'Feb' => __('Feb' ,"get-bookings-wp"),
    		                    'Mar' => __('Mar' ,"get-bookings-wp"),
    		                    'Apr' => __('Apr' ,"get-bookings-wp"),
    		                    'May' => __('May' ,"get-bookings-wp"),
    		                    'Jun' => __('Jun' ,"get-bookings-wp"),
    		                    'Jul' => __('Jul' ,"get-bookings-wp"),
    		                    'Aug' => __('Aug' ,"get-bookings-wp"),
    		                    'Sep' => __('Sep' ,"get-bookings-wp"),
    		                    'Oct' =>__('Oct' ,"get-bookings-wp"),
    		                    'Nov' => __('Nov' ,"get-bookings-wp"),
    		                    'Dec' => __('Dec' ,"get-bookings-wp")
		                    ),
		            'dayNames' => array(
		                        'Sun' => __('Sunday'  ,"get-bookings-wp"),
    		                    'Mon' =>  __('Monday'  ,"get-bookings-wp"),
    		                    'Tue' => __( 'Tuesday'  ,"get-bookings-wp"),
    		                    'Wed' =>  __( 'Wednesday'  ,"get-bookings-wp"),
    		                    'Thu' =>  __(  'Thursday'  ,"get-bookings-wp"),
    		                    'Fri' =>   __('Friday'  ,"get-bookings-wp"),
    		                    'Sat' =>  __('Saturday'  ,"get-bookings-wp")
		                    ),
		            'dayNamesShort' => array(
		                        'Sun' => __('Sun'  ,"get-bookings-wp") ,
    		                    'Mon' => __('Mon'  ,"get-bookings-wp"),
    		                    'Tue' => __('Tue'  ,"get-bookings-wp"),
    		                    'Wed' => __('Wed'  ,"get-bookings-wp"),
    		                    'Thu' => __('Thu'  ,"get-bookings-wp"),
    		                    'Fri' =>__('Fri'  ,"get-bookings-wp"),
    		                    'Sat' =>__('Sat'  ,"get-bookings-wp")
		                    ),
		            'dayNamesMin' => array(
		                        'Sun' => __('Su'  ,"get-bookings-wp"),
    		                    'Mon' => __('Mo'  ,"get-bookings-wp"),
    		                    'Tue' => __('Tu'  ,"get-bookings-wp"),
    		                    'Wed' => __('We'  ,"get-bookings-wp"),
    		                    'Thu' => __('Th'  ,"get-bookings-wp"),
    		                    'Fri' => __('Fr'  ,"get-bookings-wp"),
    		                    'Sat' => __('Sa'  ,"get-bookings-wp")
		                    ),
		            'weekHeader' => 'Wk'
		        );
				
				//localize our js
				$date_picker_array = array(
					'closeText'         => __( 'Done', "get-bookings-wp" ),
					'currentText'       => __( 'Today', "get-bookings-wp" ),
					'prevText' =>  __('Prev',"get-bookings-wp"),
		            'nextText' => __('Next',"get-bookings-wp"),				
					'monthNames'        => array_values( $wp_locale->month ),
					'monthNamesShort'   => array_values( $wp_locale->month_abbrev ),
					'monthStatus'       => __( 'Show a different month', "get-bookings-wp" ),
					'dayNames'          => array_values( $wp_locale->weekday ),
					'dayNamesShort'     => array_values( $wp_locale->weekday_abbrev ),
					'dayNamesMin'       => array_values( $wp_locale->weekday_initial ),					
					// get the start of week from WP general setting
					'firstDay'          => get_option( 'start_of_week' ),
					// is Right to left language? default is false
					'isRTL'             => $wp_locale->is_rtl(),
				);
				
				
				wp_localize_script('getbwp-front_js', 'GETBWPDatePicker', $date_picker_array);
		
		
	}
	
	/* Custom WP Query*/
	public function get_results( $query ) 
	{
		$wp_user_query = new WP_User_Query($query);						
		return $wp_user_query;		
	
	}
	

	
	/* Show registration form on booking steps */
	function get_registration_form( $args=array() ){

		global $post;		
		
		// Loading scripts and styles only when required
		 /* Tipsy script */
        if (!wp_script_is('getbwp_tipsy')) {
            wp_register_script('getbwp_tipsy', getbookingpro_url . 'js/jquery.tipsy.js', array('jquery'));
            wp_enqueue_script('getbwp_tipsy');
        }

        /* Tipsy css */
        if (!wp_style_is('getbwp_tipsy')) {
            wp_register_style('getbwp_tipsy', getbookingpro_url . 'css/tipsy.css');
            wp_enqueue_style('getbwp_tipsy');
        }	


		//stripe payments
		
		
		/* Arguments */
		$defaults = array(       
			'redirect_to' => null,
			'form_header_text' => __('Sign Up','get-bookings-wp'),
			'getbwp_date' => '',
			'service_id' => '',
			'form_id' => '',
			'location_id' => '',
			'field_legends' => 'yes',
			'placeholders' => 'yes',
			'staff_id' => '',
			'book_from' => '',	
			'getbwp_service_cost' => '',					
			'book_to' => '',
			'template_id' => NULL,
			'max_capacity' => 1,
			'max_available' => 1
			
        		    
		);
		$args = wp_parse_args( $args, $defaults );
		$args_2 = $args;
		extract( $args, EXTR_SKIP );
						
		// Default set to blank
		$this->captcha = '';		
		
		$display = null;	
		
		
		$display .= '<div class="getbwp-user-data-registration-form">';	
		   
													
						/*Display errors*/
						if (isset($_POST['getbwp-register-form'])) 
						{
							$display .= $this->register->get_errors();
						}
						
						$display .= $this->display_registration_form_booking( $redirect_to, $args_2);

				$display .= '';
		
		
		return $display;
		
	}
	
	/* This is the Registration Form on booking */
	function display_registration_form_booking( $redirect_to=null , $args)	{

		global $getbwp_register,  $getbwp_captcha_loader, $getbwpcomplement;
		$display = null;

		session_start();
		extract( $args, EXTR_SKIP );		
		$require_phone = $this->get_option('phone_number_mandatory');
		$require_last_name = $this->get_option('last_name_mandatory');
				
		// Optimized condition and added strict conditions
		if (!isset($getbwp_register->registered) || $getbwp_register->registered != 1){
		
		$display .= '<form action="" method="post" id="getbwp-registration-form" name="getbwp-registration-form" enctype="multipart/form-data">';
		$_SESSION["amount_to_charge"]=$getbwp_service_cost;
		$_SESSION["rand_client_id"]=rand();		
		
		$display .= '<input type="hidden" name="getbwp_date" id="getbwp_date" value="'.$getbwp_date.'">';
		$display .= '<input type="hidden" name="getbwp_service_cost" id="getbwp_service_cost" value="'.$getbwp_service_cost.'">';
		$display .= '<input type="hidden" name="service_id" id="service_id" value="'.$service_id.'">';
		$display .= '<input type="hidden" name="staff_id" id="staff_id" value="'.$staff_id.'">';
		$display .= '<input type="hidden" name="book_from" id="book_from" value="'.$book_from.'">';
		$display .= '<input type="hidden" name="book_to" id="book_to" value="'.$book_to.'">';
		$display .= '<input type="hidden" name="getbwp-custom-form-id" id="getbwp-custom-form-id" value="'.$form_id.'">';
		$display .= '<input type="hidden" name="getbwp-filter-id" id="getbwp-filter-id" value="'.$location_id.'">';
		$display .= '<input type="hidden" name="template_id" id="template_id" value="'.$template_id.'">';
		
		$display .= '<div class="getbwp-field getbwp-align-left">'.__('Fields with (*) are required','get-bookings-wp').'</div>';		
		$display .= '<div class="getbwp-profile-separator">'.__('Account Info','get-bookings-wp').'</div>';
			
		/* These are the basic registrations fields */
		
		foreach($this->registration_fields as $key=>$field){
			extract($field);			
			
			$include_username =  true;
			
			if($this->get_option('allow_registering_only_with_email')=='yes'){
				if($meta=='user_login')	{
					$include_username =  false;				
				}		
			}			
			
			if ( $type == 'usermeta' && $include_username) {
				
				$display .= '<div class="getbwp-profile-field">';
				
				if(!isset($required))
				    $required = 0;
				
				$required_class = '';				
				$required_text = '';
				
				if($required == 1 && in_array($field, $this->include_for_validation)){
					$required_class = ' validate[required]';
					$required_text = '(*)';
				}
				
				//condition for telephone				
				if($meta=='telephone' && $require_phone=='no' ){
					$required_class = ' ';
					$required_text = '';				
				}
				
				$field_legend = 'no';				
				if($field_legends=='yes'){
					//$placeholder = 'placeholder="'.$name.'"';
					
				}
				
				/* Show the label */
				if (isset($this->registration_fields[$key]['name']) && $name ){
					
					if ( $field_legends!='no'){
						$display .= '<label class="getbwp-field-type" for="'.$meta.'">';
						
						if (isset($this->registration_fields[$key]['icon']) && $icon){
							//$display .= '<i class="fa fa-'.$icon.'"></i>';
						} else {
							//$display .= '<i class="fa fa-none"></i>';
						}
						
						$display .= '<span>'.$name.' '.$required_text.'</span></label>';
					}			
					
				} else {
					//$display .= '<label class="getbwp-field-type">&nbsp;</label>';
				}
				
				$placeholder = '';				
				if($placeholders=='yes'){
					$placeholder = 'placeholder="'.$name.'"';
					
				}
				
				$display .= '<div class="getbwp-field-value">';			
					switch($field) {				
						
						case 'textarea':
							$display .= '<textarea class="'.$required_class.' getbwp-input getbwp-input-text-area" name="'.$meta.'" id="reg_'.$meta.'" title="'.$name.'"  '.$placeholder.' data-errormessage-value-missing="'.__(' * This input is required!','get-bookings-wp').'">'.$this->get_post_value($meta).'</textarea>';
							break;
						
						case 'text':
							$display .= '<input type="text" class="'.$required_class.' getbwp-input " name="'.$meta.'" id="reg_'.$meta.'" value="'.$this->get_post_value($meta).'" title="'.$name.'" '.$placeholder.' data-errormessage-value-missing="'.__(' * This input is required!','get-bookings-wp').'"/>';
							
							if (isset($this->registration_fields[$key]['help']) && $help != '') {
								$display .= '<div class="getbwp-help">'.$help.'</div>';
							}
							
							break;
							
							case 'datetime':							    
							    $display .= '<input type="text" class="'.$required_class.' getbwp-input getbwp-input-datepicker" name="'.$meta.'" id="reg_'.$meta.'" value="'.$this->get_post_value($meta).'" title="'.$name.'" data-errormessage-value-missing="'.__(' * This input is required!','get-bookings-wp').'"/>';
							    
							    if (isset($this->registration_fields[$key]['help']) && $help != '') {
							        $display .= '<div class="getbwp-help">'.$help.'</div><div class="xoouserultra-clear"></div>';
							    }
							    break;							
						case 'password':
							$display .= '<input type="password" class="'.$required_class.' getbwp-input password" name="'.$meta.'" id="reg_'.$meta.'" value="" autocomplete="off" title="'.$name.'"  '.$placeholder.' data-errormessage-value-missing="'.__(' * This input is required!','get-bookings-wp').'" />';
							
							if (isset($this->registration_fields[$key]['help']) && $help != '') {
								$display .= '<div class="getbwp-help">'.$help.'</div><div class="xoouserultra-clear"></div>';
							}
							break;											
						case 'password_indicator':
							$display .= '<div class="password-meter"><div class="password-meter-message" id="password-meter-message">&nbsp;</div></div>';
							break;
					}			
					
				$display .= '</div>';				
				$display .= '</div>';				
				
				//last name			
				if($meta=='display_name'){
					if($require_last_name!='no'){
						
						$required_class = ' validate[required]';
						$required_text = '(*)';						
						$display .= '<div class="getbwp-profile-field">';					
						if ( $field_legends!='no'){				
							$display .= '<label class="getbwp-field-type" for="user_last_name">';
							//$display .= '<i class="fa fa-user"></i>';	
							$display .= '<span>'.__('Your Last Name', 'get-bookings-wp').' '.$required_text.'</span></label>';					
						}
						$display .= '<div class="getbwp-field-value">';					
						$display .= '<input type="text" class="'.$required_class.' getbwp-input " name="last_name" id="last_name" value="'.$this->get_post_value('last_name').'" title="'.__('Type your last name','get-bookings-wp').'"  placeholder="'.__('Type your last name','get-bookings-wp').'" data-errormessage-value-missing="'.__(' * This input is required!','get-bookings-wp').'"/>';		
						$display .= '</div>';
					}
				}
								
				
				//re-type password				
				if($meta=='user_email'){
					$required_class = ' validate[required]';
					$required_text = '(*)';					
					$display .= '<div class="getbwp-profile-field">';				
					
					if ( $field_legends!='no'){				
						$display .= '<label class="getbwp-field-type" for="user_email_2">';			
						$display .= '<span>'.__('Re-type your email', 'get-bookings-wp').' '.$required_text.'</span></label>';
					}					
					$display .= '<div class="getbwp-field-value">';				
					$display .= '<input type="text" class="'.$required_class.' getbwp-input " name="user_email_2" id="reg_user_email_2" value="'.$this->get_post_value('user_email_2').'" title="'.__('Re-type your email','get-bookings-wp').'"  placeholder="'.__('Re-type your email','get-bookings-wp').'" data-errormessage-value-missing="'.__(' * This input is required!','get-bookings-wp').'"/>';
					$display .= '</div>';					
				}
			}
		}
		
		$custom_form = '';
		if(isset($_GET["getbwp-custom-form-id"])){ 
			$custom_form=sanitize_text_field($_GET["getbwp-custom-form-id"]);
		}
		
		/* Get end of array */			
		if($form_id!="" || $custom_form !=""){
			//do we have a pre-set value in the get?			
			if($custom_form !=""){
				$form_id =$custom_form;			
			}
			
			$custom_form = 'getbwp_profile_fields_'.$form_id;		
			$array = get_option($custom_form);			
			$fields_set_to_update =$custom_form;
		}else{
			
			$array = get_option('getbwp_profile_fields');
			$fields_set_to_update ='getbwp_profile_fields';
		}
		
		if(!is_array($array)){
			$array = array();
		
		}	

		foreach($array as $key=>$field) {		     
		    $exclude_array = array('user_pass', 'user_pass_confirm', 'user_email');
		    if(isset($field['meta']) && in_array($field['meta'], $exclude_array)) {
		        unset($array[$key]);
		    }
		}
		
		$i_array_end = end($array);		
		if(isset($i_array_end['position'])){
		    $array_end = $i_array_end['position'];		    
			if (isset($array[$array_end]['type']) && $array[$array_end]['type'] == 'seperator'){
				if(isset($array[$array_end])){
					unset($array[$array_end]);
				}
			}
		}
		
		
		/*Display custom profile fields added by the user*/		
		foreach($array as $key => $field) {

			extract($field);
			
			// WP 3.6 Fix
			if(!isset($deleted))
			    $deleted = 0;
			
			if(!isset($private))
			    $private = 0;
			
			if(!isset($required))
			    $required = 0;
			
			$required_class = '';
			$required_text = '';
			if($required == 1 && in_array($field, $this->include_for_validation)){				
			    $required_class = 'validate[required] ';
				$required_text = '(*)';				
			}
						
			/* separator */
            if ($type == 'separator' && $deleted == 0 && $private == 0 && isset($array[$key]['show_in_register']) && $array[$key]['show_in_register'] == 1) 
			{
                   $display .= '<div class="getbwp-profile-separator">'.$name.'</div>';
				   
            }
			
					
			//check if display emtpy				
				
			if ($type == 'usermeta' && $deleted == 0 && $private == 0 && isset($array[$key]['show_in_register']) && $array[$key]['show_in_register'] == 1) 
			{
								
				$display .= '<div class="getbwp-profile-field">';
				
				/* Show the label */
				if (isset($array[$key]['name']) && $name) {
					 
					if ( $field_legends!='no') 	{
						
						$display .= '<label class="getbwp-field-type" for="'.$meta.'">';	
						
						if (isset($array[$key]['icon']) && $icon) 
						{
							
								//$display .= '<i class="fa fa-' . $icon . '"></i>';
						} else {
							//	$display .= '<i class="fa fa-icon-none"></i>';
						}

						$tooltipip_class = '';					
						if (isset($array[$key]['tooltip']) && $tooltip)	{
							$qtip_classes = 'qtip-light ';	
							$qtip_style = '';					
						
							 $tooltipip_class = '<a class="'.$qtip_classes.' uultra-tooltip" title="' . $tooltip . '" '.$qtip_style.'><i class="fa fa-info-circle reg_tooltip"></i></a>';
						} 						
												
						$display .= '<span>'.$name. ' '.$required_text.' '.$tooltipip_class.'</span></label>';
						
					}
					
					
				} else {
					
					$display .= '<label class="">&nbsp;</label>';
				}
				
				$display .= '<div class="getbwp-field-value">';
				
				$placeholder = '';				
				if($placeholders=='yes'){
					$placeholder = 'placeholder="'.$name.'"';
				}
					
				switch($field) {
					
					case 'textarea':
							$display .= '<textarea class="'.$required_class.' getbwp-input getbwp-input-text-area" rows="10" name="'.$meta.'" id="'.$meta.'" title="'.$name.'" '.$placeholder.' data-errormessage-value-missing="'.__(' * This input is required!','get-bookings-wp').'">'.$this->get_post_value($meta).'</textarea>';
							break;
							
					case 'text':
							$display .= '<input type="text" class="'.$required_class.' getbwp-input"  name="'.$meta.'" id="'.$meta.'" value="'.$this->get_post_value($meta).'"  title="'.$name.'"  '.$placeholder.' data-errormessage-value-missing="'.__(' * This input is required!','get-bookings-wp').'"/>';
							break;							
							
					case 'datetime':						
						    $display .= '<input type="text" class="'.$required_class.' getbwp-input bupro-datepicker" name="'.$meta.'" id="'.$meta.'" value="'.$this->get_post_value($meta).'"  title="'.$name.'" />';
						    break;
							
					case 'select':						
							
						if (isset($array[$key]['predefined_options']) && $array[$key]['predefined_options']!= '' && $array[$key]['predefined_options']!= '0' ){
								$loop = $this->commmonmethods->get_predifined( $array[$key]['predefined_options'] );
								
							}elseif (isset($array[$key]['choices']) && $array[$key]['choices'] != '') {
								
															
								$loop = $this->uultra_one_line_checkbox_on_window_fix($choices);
								 	
								
							}
							
						if (isset($loop)){
								$display .= '<select class="'.$required_class.' getbwp-input" name="'.$meta.'" id="'.$meta.'" title="'.$name.'" data-errormessage-value-missing="'.__(' * This input is required!','get-bookings-wp').'">';
								
								foreach($loop as $option){									
									$option = trim(stripslashes($option));						
									$display .= '<option value="'.$option.'" '.selected( $this->get_post_value($meta), $option, 0 ).'>'.$option.'</option>';
								}
								$display .= '</select>';
						}
							
							break;
							
					case 'radio':						
						
							if($required == 1 && in_array($field, $this->include_for_validation)){
								$required_class = "validate[required] radio ";
							}
						
							if (isset($array[$key]['choices']))	{		
								 $loop = $this->uultra_one_line_checkbox_on_window_fix($choices);
							}
							if (isset($loop) && $loop[0] != '') {
							  $counter =0;
							  
								foreach($loop as $option){
								    if($counter >0)
								        $required_class = '';
								    
								    $option = trim(stripslashes($option));
									$display .= '<input type="radio" class="'.$required_class.'" title="'.$name.'" name="'.$meta.'" id="uultra_multi_radio_'.$meta.'_'.$counter.'" value="'.$option.'" '.checked( $this->get_post_value($meta), $option, 0 );
									$display .= '/> <label for="uultra_multi_radio_'.$meta.'_'.$counter.'"><span></span>'.$option.'</label>';
									
									$counter++;									
								}
							}
							
							break;
							
						case 'checkbox':
						
						
							if($required == 1 && in_array($field, $this->include_for_validation)){
								$required_class = "validate[required] checkbox ";
							}						
						
							if (isset($array[$key]['choices']))	{
																
								 $loop = $this->uultra_one_line_checkbox_on_window_fix($choices);
							}
							
							if (isset($loop) && $loop[0] != '') {
							  $counter =0;
							  
								foreach($loop as $option){
								   
								   if($counter >0)
								        $required_class = '';
								  
								  $option = trim(stripslashes($option));
								  
								  $display .= '<div class="bupro-checkbox"><input type="checkbox" class="'.$required_class.'" title="'.$name.'" name="'.$meta.'[]" id="getbwp_multi_box_'.$meta.'_'.$counter.'" value="'.$option.'" ';
									if (is_array($this->get_post_value($meta)) && in_array($option, $this->get_post_value($meta) )) {
									$display .= 'checked="checked"';
									}
									$display .= '/> <label for="getbwp_multi_box_'.$meta.'_'.$counter.'"> '.$option.'</label> </div>';
									
									
									$counter++;
								}
							}
							
							break;
							
						
													
						case 'password':						
							$display .= '<input type="password" class="getbwp-input'.$required_class.'" title="'.$name.'" name="'.$meta.'" id="'.$meta.'" value="'.$this->get_post_value($meta).'" />';
							
							if ($meta == 'user_pass') {
								
							$display .= '<div class="getbwp-help">'.__('If you would like to change the password type a new one. Otherwise leave this blank.','get-bookings-wp').'</div>';
							
							} elseif ($meta == 'user_pass_confirm') {
								
							$display .= '<div class="getbwp-help">'.__('Type your new password again.','get-bookings-wp').'</div>';
							
							}
							break;
							
					}					
					
					if (isset($array[$key]['help_text']) && $help_text != '') {
						$display .= '<div class="getbwp-help">'.$help_text.'</div>';
					}						
				$display .= '</div>';
				$display .= '</div>';
			}
		}
		
		
		$show_cart = 0;
		
		/*If we are using Paid Registration*/	
		$registration_rule = 	$this->get_option('registration_rules');
		if($this->get_option('registration_rules')!=1 ){

			
			$service = $this->service->get_one_service($service_id);						
			//Payment Details
			$currency_symbol =  $this->get_option('paid_membership_symbol');			
			$service_details = $this->userpanel->get_staff_service_rate( $staff_id, $service_id );			
			$display .= '<div class="getbwp-profile-separator">'.__('Payment Details', 'get-bookings-wp').'</div>';
			
			if($show_cart==1){				
				$display .= '<div class="getbwp-profile-field-ppd" id="getbwp-strip-payment-details">';				
				//display cart totals				
				$display .= $this->service->getbwp_get_shopping_cart_summary($template_id);			
				$display .= '</div>';				
				
			}else{
			
				$display .= '<div class="getbwp-profile-field-ppd" id="getbwp-strip-payment-details">';
				$display .= '<div class="getbwp-total-qty" >';
				$display .= '<h4>'.__('Persons:', 'get-bookings-wp').'</h4>';				
					
				if($service->service_allow_multiple==1){
						
					$display .= '<select name="getbwp-purchased-qty" id="getbwp-purchased-qty" style="height:30px">';
					$i_q = 1;
					while ($i_q <= $max_available){
							$sel='';
							if($i_q==1){$sel='selected="selected"';}							
							$display .= ' <option value="'.$i_q.'" '.$sel.'>'.$i_q.'</option>';
							$i_q++;							
					}						
						$display .= '</select>';						
				}else{
						$display .= '<p id="getbwp-total-booking-amount">1</p>';
						$display .= '<input type="hidden" name="getbwp-purchased-qty" id="getbwp-purchased-qty" value="1">';
				}
					
												
				$display .= '</div>';
				$display .= '<div class="getbwp-total-qty" >';
				$display .= '<h4>'.__('Available:', 'get-bookings-wp').'</h4>';				
				$display .= '<p >'.$max_available.'</p>';								
				$display .= '</div>';
				$display .= '<div class="getbwp-total-detail" >';
				$display .= '<h4>'.__('Total:', 'get-bookings-wp').'</h4>';
				$display .= '<p id="getbwp-total-booking-amount">'.$currency_symbol.$service_details['price'].'</p>';
				$display .= '</div>';		
				$display .= '</div>';
			}
					
			
			$required_class = ' validate[required]';			
			//payment methods			
			$display .= '<div class="getbwp-profile-separator">'.__('Select Payment Method', 'get-bookings-wp').'</div>';	
			
			
			 		 
			 
			 /*Bank*/		
			if($this->get_option('gateway_bank_active')=='1'){
				//custom label
				
				$custmom_label = $this->get_option('gateway_bank_label');
				if($custmom_label==''){
					$custmom_label = __('I will pay locally','get-bookings-wp');				
				}
				
				$display_payment_method = '<input type="radio" class="'.$required_class.' getbwp_payment_options" title="" name="getbwp_payment_method" id="getbwp_payment_method_bank" value="bank" data-method="bank" /> <label for="getbwp_payment_method_bank"><span></span>'.$custmom_label.'</label>';
													 
				$display .= '<div class="getbwp-profile-field">';
				$display .= '<label class="getbwp-field-type" for="getbwp_payment_method_bank">';			
				$display .= '<span>'.$display_payment_method.' </span></label>';
				$display .= '<div class="getbwp-field-value">';
				$display .= '</div>';				
				$display .= '</div>';				
			}
			
			
			/*Paypal*/		
			if($this->get_option('gateway_paypal_active')=='1' && isset($getbwpcomplement))	{
				$paypal_logo = getbookingpro_url.'templates/img/paypal-logo.jpg';
				$display_payment_method = '<input type="radio" class="'.$required_class.' getbwp_payment_options" title="" name="getbwp_payment_method" id="getbwp_payment_method_paypal" value="paypal" data-method="paypal"/> <label for="getbwp_payment_method_paypal"><span></span>'.__('Pay with PayPal','get-bookings-wp').'<br><img align="absmiddle"  src="'.$paypal_logo.'" style="top:5px;"></label>';	
				$display .= '<div class="getbwp-profile-field">';
				$display .= '<label class="getbwp-field-type" for="getbwp_payment_method_paypal">';			
				$display .= '<span>'.$display_payment_method.' </span></label>';
				$display .= '<div class="getbwp-field-value">';
				$display .= '</div>';				
				$display .= '</div>';		
			
			}
			
			/*Stripe*/		

			$display_card_button = false;	
			if($this->get_option('gateway_stripe_active')=='1' && isset($getbwpcomplement))	{
				$cc_logo = getbookingpro_url.'templates/img/creditcard-icon.png';
				$display_payment_method = '<input type="radio" class="'.$required_class.' getbwp_payment_options" title="" name="getbwp_payment_method" id="getbwp_payment_method_stripe" value="stripe"  data-method="stripe" checked /> <label for="getbwp_payment_method_stripe"><span></span>'.__('Pay with Credit Card','get-bookings-wp').'<br><img align="absmiddle"  src="'.$cc_logo.'" style="top:5px; "></label>';	
				
				$display .= '<input type="hidden"  name="getbwp_payment_method_stripe_hidden" id="getbwp_payment_method_stripe_hidden" value="stripe" >';
										 
				$display .= '<div class="getbwp-profile-field">';
				$display .= '<label class="getbwp-field-type" for="getbwp_payment_method_stripe">';			
				$display .= '<span>'.$display_payment_method.' </span></label>';
				
				$display .= '<div class="getbwp-field-value">';
				$display .= '</div>';				
				$display .= '</div>'; 
				$display .= '<div class="getbwp-profile-field-cc" id="getbwp-strip-cc-form">';
				$display_card_button = true;							
				$display .= '<label><input type="radio" name="nuv_payment_method" class="nuv_payment_method" value="cc" id="RadioGroup1_0" checked />
											'.__('Credit Card','get-bookings-wp').'';

				$display .= '</label>';

				$display .='<div id="nuva-creditcard-option" class="nuva-p-options-div"> ';							
				$display .='<div id="card-field" class="getbwp-cc-fieldform"></div>
											<span id="card-errors" class="card-errors"></span> ';	
				$display .='</div>';					
				$display .= '</div>'; //field
				
						
			
			}			
			
			
		
		}
			
				
		/*If mailchimp*/		
		if($this->get_option('newsletter_active')=='mailchimp' && $this->get_option('mailchimp_api')!="" && isset($getbcoplement))
		{
			
			//new mailchimp field			
			$mailchimp_text = stripslashes($this->get_option('mailchimp_text'));
			$mailchimp_header_text = stripslashes($this->get_option('mailchimp_header_text'));
			
			if($mailchimp_header_text==''){
				
				$mailchimp_header_text = __('Receive Daily Updates ', 'get-bookings-wp');				
			}	

			
			$mailchimp_autchecked = $this->get_option('mailchimp_auto_checked');
			
			$mailchimp_auto = '';
			if($mailchimp_autchecked==1){
				
				$mailchimp_auto = 'checked="checked"';				
			}
			
			 $display .= '<div class="getbwp-profile-separator">'.$mailchimp_header_text.'</div>';			 
			 $display .= '<div class="getbwp-profile-field " style="text-align:left">';
			

			//$display .= '<div class="getbwp-field-value">';
			 $display .= '<input type="checkbox"  title="'.$mailchimp_header_text.'" name="getbwp-mailchimp-confirmation"  id="getbwp-mailchimp-confirmation" value="1"  '.$mailchimp_auto.' > <label for="getbwp-mailchimp-confirmation"><span></span>'.$mailchimp_text.'</label>' ;

			 $display .= '<div class="getbwp-field-value "></div>';
									
			 $display .= '</div>';
			
		
		}
		
		/*If aweber*/		
		if($this->get_option('newsletter_active')=='aweber' && $this->get_option('aweber_consumer_key')!="" && isset($getbcoplement))
		{
			
			//new aweber field			
			$aweber_text = stripslashes($this->get_option('aweber_text'));
			$aweber_header_text = stripslashes($this->get_option('aweber_header_text'));
			
			if($aweber_header_text==''){
				
				$aweber_header_text = __('Receive Daily Updates ', 'get-bookings-wp');				
			}	
			
			if($aweber_text==''){
				
				$aweber_text = __('Yes, I want to receive daily updates. ', 'get-bookings-wp');				
			}			
			
			$aweber_autchecked = $this->get_option('aweber_auto_checked');
			
			$aweber_auto = '';
			if($aweber_autchecked==1){
				
				$aweber_auto = 'checked="checked"';				
			}
			
			 $display .= '<div class="getbwp-profile-separator">'.$aweber_header_text.'</div>';			 
			 $display .= '<div class="getbwp-profile-field " style="text-align:left">';			
						
			 $display .= '<input type="checkbox"  title="'.$aweber_header_text.'" name="getbwp-aweber-confirmation"  id="getbwp-aweber-confirmation" value="1"  '.$aweber_auto.' > <label for="getbwp-aweber-confirmation"><span></span>'.$aweber_text.'</label>' ;
									
			 $display .= '</div>';
			
		
		}		
		
		$captcha_control = $this->get_option("captcha_plugin");
		
		if($captcha_control!='none' && $captcha_control!='')
		{
					
		
		}
		
		$other_payment_button_class = '';
		if($display_card_button){

			$other_payment_button_visible = 'style="display:none"';
			$card_button_visible = 'style="display:"';
			$other_payment_button_class = 'getbwp-other-payments-button-class';

		}else{

			$other_payment_button_visible = 'style="display:"';
			$card_button_visible = 'style="display:none"';

			
		}
				
		$display .= '<p>&nbsp;</p>';
		$display .= '<div class="getbwp-field " >
						<label class="getbwp-field-type "><button name="getbwp-btn-book-app-confirm" 
						id="getbwp-btn-book-app-confirm" '.$other_payment_button_visible.' class="getbwp-button-submit-changes '.$other_payment_button_class.'">'.__('Submit','get-bookings-wp').'	</button><span id="getbwp-message-submit-booking-conf"></span></label>
						
					</div>';

		if($this->get_option('gateway_stripe_active')=='1' && isset($getbwpcomplement) && $registration_rule!=1)	{
			

			$display .= '<div class="getbwp-field " >
			<label class="getbwp-field-type ">
			<button name="getbwp-btn-book-app-confirm" '.$card_button_visible.' id="card-button" class="getbwp-button-submit-changes">'.__('Submit','get-bookings-wp').'	</button><span id="getbwp-message-submit-booking-conf"></span></label>
			
			</div>';

		}

		
					
		$display .= '<div class="getbwp-profile-field-cc" id="getbwp-stripe-payment-errors"></div>';

		$display .= '  <input type="hidden" name="getbwp-register-form" value="getbwp-register-form" />
		<input type="hidden" name="full_number" id="full_number" value="" />
		<input type="hidden" name="full_number_prefix" id="full_number_prefix" value="" />
		<input type="hidden" name="full_number_iso" id="full_number_iso" value="" />
								   ';
					
		if ($redirect_to != '' )
		{
			$display .= '<input type="hidden" name="redirect_to" value="'.$redirect_to.'" />';
		}
		
		$display .= '</form>';
		
		} 		
		
		return $display;
	}


	public function get_template_label($value, $template_id, $parse_tags = false) 
	{
		$template = get_option('getbwp_template_'.$template_id);
		$ret_val = '';
		
		if(isset($template[$value]) && $template[$value]!='' && $template_id!='') 
		{
			$ret_val = $template[$value];
		
		}else{
			
			$ret_val = $this->get_template_default_label($value);
		}
		
		return stripslashes($ret_val);
	}
	
	public function get_template_default_label($label) 
	{
		$def_label = '';
		
		if($label=='step1_label')
		{
			$def_label = __('Service','get-bookings-wp');
			
		}elseif($label=='step2_label'){
			
			$def_label = __('Time','get-bookings-wp');
			
		}elseif($label=='step3_label'){
			
			$def_label = __('Details & Payment','get-bookings-wp'); 
		
		}elseif($label=='step3cart_label'){
			
			$def_label = __('Cart','get-bookings-wp');
		
		}elseif($label=='step4_label'){
			
			$def_label = __('Thank you','get-bookings-wp');
		
		}elseif($label=='bup_cus_bg_color'){
			
			$def_label = '#E55237';
		
		}elseif($label=='select_location_label'){
			
			$def_label =  __('Select Location','get-bookings-wp');
		
		}elseif($label=='select_service_label'){
			
			$def_label =  __('Select Service','get-bookings-wp');
			
		}elseif($label=='select_service_label_drop'){
			
			$def_label =  __('Select Service','get-bookings-wp');
		
		}elseif($label=='select_date_label'){
			
			$def_label =  __('On or After','get-bookings-wp');
		
		}elseif($label=='select_date_to_label'){
			
			$def_label =  __('Check-out date','get-bookings-wp');
			
		}elseif($label=='select_provider_label'){
			
			$def_label =  __('With','get-bookings-wp');
		
		}elseif($label=='step1_texts'){
			
			$def_label = __('Please select service, date and provider then click on the Find Appointments button.','get-bookings-wp');
			
		}elseif($label=='step2_texts'){
			
			$def_label =  __('Below you can find a list of available time slots for <strong>[GETBWP_SERVICE]</strong> by <strong>[GETBWP_PROVIDER]</strong>.','get-bookings-wp');
			
		}elseif($label=='step3_texts'){
			
			$def_label =  __("You're booking <strong>[GETBWP_SERVICE]</strong> by <strong>[GETBWP_PROVIDER]</strong> at <strong>[GETBWP_AT]</strong> on <strong>[GETBWP_DAY]</strong>",'get-bookings-wp');
			
		}elseif($label=='step3_cart_texts'){
			
			$def_label =  __("Please fill out the following form to confirm your purchase ",'get-bookings-wp');			
		
		}elseif($label=='layout_selected'){
			
			$def_label = 1;	
			
		}elseif($label=='cart_header_1_texts'){
			
			$def_label =  __("Service",'get-bookings-wp');
		
		}elseif($label=='cart_header_2_texts'){
			
			$def_label =  __("Date",'get-bookings-wp');
		
		}elseif($label=='cart_header_3_texts'){
			
			$def_label =  __("Time",'get-bookings-wp');
		
		}elseif($label=='cart_header_4_texts'){
			
			$def_label =  __("Staff",'get-bookings-wp');
		
		}elseif($label=='cart_header_5_texts'){
			
			$def_label =  __("Qty",'get-bookings-wp');
		
		}elseif($label=='cart_header_6_texts'){
			
			$def_label =  __("Price",'get-bookings-wp');
		
		}elseif($label=='cart_header_7_texts'){
			
			$def_label =  __("Action",'get-bookings-wp');
			
		}elseif($label=='btn_check_availability_button_text'){
			
			$def_label =  __("Check Availability",'get-bookings-wp');			
		
		}
		
				
		return $def_label;
		
	}
	
	
	/**
	 * This has been added to avoid the window server issues
	 */
	public function uultra_one_line_checkbox_on_window_fix($choices)
	{		
		
		if($this->uultra_if_windows_server()) //is window
		{
			$loop = array();		
			$loop = explode(",", $choices);
		
		}else{ //not window
		
			$loop = array();		
			$loop = explode(PHP_EOL, $choices);	
			
		}	
		
		
		return $loop;
	
	}
	
	public function uultra_if_windows_server()
	{
		$os = PHP_OS;
		$os = strtolower($os);			
		$pos = strpos($os, "win");	
		
		if ($pos === false) {			
			
			return false;
		} else {
			
			return true;
		}			
	
	}
	
		
	
	public function get_price_format($price)
	{
		$new_price='';
		
		$currency_symbol =  $this->get_option('paid_membership_symbol');
		$currency_position =  $this->get_option('currenciy_position');
		
		//without milliar separator
		$price = number_format($price, 2, '.', '');
		
		if($currency_position=='before')
		{
			
			$new_price=$price.$currency_symbol;
			
		}else{
			
			$new_price=$currency_symbol.$price;
			
		}
		
		
		
		return $new_price;		
			
	}
	
		
	
	/**
	 * Public Profile
	 */
	public function show_pulic_profile($atts)
	{
		 return $this->userpanel->show_public_profile($atts);		
			
	}
	
	
	
	public function get_social_buttons_short_code ($atts)
	{
		require_once(xoousers_path."libs/fbapi/src/facebook.php");
		
		$display ="";
		
		extract( shortcode_atts( array(
			'provider' => '',
			
		), $atts ) );
		
		$socials = explode(',', $provider); ;	
		
		
		$FACEBOOK_APPID = $this->get_option('social_media_facebook_app_id');  
		$FACEBOOK_SECRET = $this->get_option('social_media_facebook_secret');
							
			$config = array();
			$config['appId'] = $FACEBOOK_APPID;
			$config['secret'] = $FACEBOOK_SECRET;
			
			$web_url = site_url()."/"; 
			
			$action_text = __('Connect with ','get-bookings-wp');		
			
			$atleast_one = false;			
			
			if(in_array('facebook', $socials)) 	{
				$atleast_one = true;
				$facebook = new Facebook($config);					
				
				
				$params = array(
						  'scope' => 'read_stream, email',						  				  
						  'redirect_uri' => $web_url
						);
						
				$loginUrl = $facebook->getLoginUrl($params);
			
				//Facebook
				$display .='<div class="txt-center FacebookSignIn">
				
				       	               	
						<a href="'.$loginUrl.'" class="btnuultra-facebook" >
							<span class="uultra-icon-facebook"> <img src="'.xoousers_url.'templates/'.xoousers_template.'/img/socialicons/facebook.png" ></span>'.$action_text.' Facebook </a>
					
					</div>';
					
			}
			
			if(in_array('yahoo', $socials)) 
			{
			
				$auth_url_yahoo = $web_url."?uultrasocialsignup=yahoo";			
				
				$atleast_one = true;
			
				//Yahoo
				$display .='<div class="txt-center YahooSignIn">	               	
							<a href="'.$auth_url_yahoo.'" class="btnuultra-yahoo" >
							<span class="uultra-icon-yahoo"><img src="'.xoousers_url.'templates/'.xoousers_template.'/img/socialicons/yahoo.png" ></span>'.$action_text.' Yahoo </a>
					
					</div>';
		     }
			 
			if(in_array('google', $socials)) 
			{
				//google
			
				$auth_url_google = $web_url."?uultrasocialsignup=google";
			
				$atleast_one = true;
			
				//Google
				$display .='<div class="txt-center GoogleSignIn">	               	
						<a href="'.$auth_url_google.'" class="btnuultra-google" >
							<span class="uultra-icon-google"><img src="'.xoousers_url.'templates/'.xoousers_template.'/img/socialicons/googleplus.png" ></span>'.$action_text.' Google </a>
					
					</div>';
			}
			
			if(in_array('twitter', $socials)) 
			{
				//google
			
				$auth_url_google = $web_url."?uultrasocialsignup=twitter";
			
				$atleast_one = true;
			
				//Google
				$display .='<div class="txt-center TwitterSignIn">	               	
						<a href="'.$auth_url_google.'" class="btnuultra-twitter" >
							<span class="uultra-icon-twitter"><img src="'.xoousers_url.'templates/'.xoousers_template.'/img/socialicons/twitter.png" ></span>'.$action_text.' Twitter </a>
					
					</div>';
			}
			
			if(in_array('yammer', $socials)) 
			{
				//google
			
				$auth_url_google = $web_url."?uultrasocialsignup=yammer";
			
				$atleast_one = true;
			
				//Google
				$display .='<div class="txt-center YammerSignIn">	               	
						<a href="'.$auth_url_google.'" class="btnuultra-yammer" >
							<span class="uultra-icon-yammer"><img src="'.xoousers_url.'templates/'.xoousers_template.'/img/socialicons/yammer.png" ></span>'.$action_text.' Yammer </a>
					
					</div>';
			}
			
			if(in_array('linkedin', $socials)) 
			{
				$atleast_one = true;
				
							
				$requestlink = $web_url."?uultrasocialsignup=linkedin";
				
				
				//LinkedIn
				$display .='<div class="txt-center LinkedSignIn">	               	
							<a href="'.$requestlink.'" class="btnuultra-linkedin" >
								<span class="uultra-icon-linkedin"><img src="'.xoousers_url.'templates/'.xoousers_template.'/img/socialicons/linkedin.png" ></span>'.$action_text.' LinkedIn </a>
					
					</div>';
			}
			
		
	return $display;
		
	}
	
	public function get_social_buttons ($action_text, $atts)
	{
		
		
		$display ="";
		
		extract( shortcode_atts( array(
			'social_conect' => '',
			'display_style' => 'default', //default, minified
			'rounded_border' => 'no', //no, yes
			
		), $atts ) );
		
		return $display;
		
	}
	

	
	/*---->> Set Account Status  ****/  
 	public function user_account_status($user_id) {
	 // global $xoouserultra;
	  
	  //check if login automatically
	  $activation_type= $this->get_option('registration_rules');
	  
	  if($activation_type==1)
	  {
		  //automatic activation
		  update_user_meta ($user_id, 'getbwp_account_status', 'active');							
	  
	  }elseif($activation_type==2){
		  
		  //email activation link
		  update_user_meta ($user_id, 'getbwp_account_status', 'pending');	
	  
	  }elseif($activation_type==3){
		  
		  //manually approved
		  update_user_meta ($user_id, 'getbwp_account_status', 'pending_admin');
	  
	  
	  }
	
  } 
	
		
	public function get_current_url()
	{
		$result = 'http';
		$script_name = "";

		if(isset($_SERVER['REQUEST_URI'])) 
		{
			$script_name = sanitize_url($_SERVER['REQUEST_URI']);
		} 
		else 
		{
			$script_name = sanitize_text_field($_SERVER['PHP_SELF']);

			if($_SERVER['QUERY_STRING']>' ') 
			{
				$script_name .=  '?'.sanitize_url($_SERVER['QUERY_STRING']);
			}
		}
		
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') 
		{
			$result .=  's';
		}

		$result .=  '://';
		
		if($_SERVER['SERVER_PORT']!='80')  
		{
			$result .= sanitize_text_field($_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$script_name);
		} 
		else 
		{
			$result .=  sanitize_text_field($_SERVER['HTTP_HOST'].$script_name);
		}
	
		return sanitize_url($result);
	}
	
	/* get setting */
	function get_option($option) {
		$settings = get_option('getbwp_options');
		if (isset($settings[$option])) {
			if(is_array($settings[$option])){
				return $settings[$option];			
			}else{				
				return stripslashes($settings[$option]);
			}
			
		}else{
			
		    return '';
		}
		    
	}
	
	/* Get post value */
	function uultra_admin_post_value($key, $value, $post){
		if (isset($_POST[$key])){
			if ($_POST[$key] == $value)
				echo esc_attr('selected="selected"');
		}
	}
	
	/*Post value*/
	function get_post_value($meta) {
				
		if (isset($_POST['getbwp-register-form'])) {
			if (isset($_POST[$meta]) ) {

				return sanitize_text_field($_POST[$meta]);
			}
		} else {
			if (strstr($meta, 'country')) {
			return 'United States';
			}
		}
	}	
}
?>