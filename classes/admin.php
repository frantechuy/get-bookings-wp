<?php
class GetBookingsWPAdmin extends GetBookingsWPCommon {

	var $options;
	var $wp_all_pages = false;
	var $getbwp_default_options;
	var $valid_c;
	
	var $notifications_email = array();

	function __construct() {
	
		/* Plugin slug and version */
		$this->slug = 'getbookingswp';
		
		$this->set_default_email_messages();				
		$this->update_default_option_ini();		
		$this->set_font_awesome();		
		
		add_action('admin_menu', array(&$this, 'add_menu'), 9);	
		add_action('admin_enqueue_scripts', array(&$this, 'add_styles'), 9);
		add_action('admin_head', array(&$this, 'admin_head'), 9 );
		add_action('admin_init', array(&$this, 'admin_init'), 9);
		add_action('admin_init', array(&$this, 'do_valid_checks'), 9);				
		add_action( 'wp_ajax_save_fields_settings', array( &$this, 'save_fields_settings' ));				
		add_action( 'wp_ajax_add_new_custom_profile_field', array( &$this, 'add_new_custom_profile_field' ));
		add_action( 'wp_ajax_delete_profile_field', array( &$this, 'delete_profile_field' ));
		add_action( 'wp_ajax_sort_fileds_list', array( &$this, 'sort_fileds_list' ));
		add_action( 'wp_ajax_getbwp_reload_custom_fields_set', array( &$this, 'getbwp_reload_custom_fields_set' ));		
		add_action( 'wp_ajax_getbwp_reload_field_to_edit', array( &$this, 'getbwp_reload_field_to_edit' ));	
		add_action( 'wp_ajax_custom_fields_reset', array( &$this, 'custom_fields_reset' ));			
		add_action( 'wp_ajax_create_uploader_folder', array( &$this, 'create_uploader_folder' ));
		add_action( 'wp_ajax_reset_email_template', array( &$this, 'reset_email_template' ));
		add_action( 'wp_ajax_getbwp_vv_c_de_a', array( &$this, 'getbwp_vv_c_de_a' ));
	}
	
	function admin_init() {
		$this->tabs = array(
		    'main' => __('Dashboard','get-bookings-wp'),
			'calendar' => __('Calendar','get-bookings-wp'),
			'services' => __('Services','get-bookings-wp'),
			'users' => __('Staff','get-bookings-wp'),
			'appointments' => __('Appointments','get-bookings-wp'),
			'orders' => __('Payments','get-bookings-wp'),
			'fields' => __('Fields','get-bookings-wp'),
			'settings' => __('Settings','get-bookings-wp'),				
			'mail' => __('Notifications','get-bookings-wp'),			
			'gateway' => __('Gateways','get-bookings-wp'),
			'help' => __('Help','get-bookings-wp'),
			'pro' => __('PREMIUM FEATURES!','get-bookings-wp'),
		);
		
		$this->default_tab = 'main';	
		$this->default_tab_membership = 'main';
	}
	
	public function update_default_option_ini () {
		$this->options = get_option('getbwp_options');		
		$this->getbwp_set_default_options();
		
		if (!get_option('getbwp_options')){
			update_option('getbwp_options', $this->getbwp_default_options );
		}
		
		if (!get_option('getbwp_pro_active')){
			update_option('getbwp_pro_active', true);
		}	
	}
	
		
	public function custom_fields_reset () 	{
		if($_POST["p_confirm"]=="yes"){		
			$custom_form = sanitize_text_field($_POST["getbwp_custom_form"]);
			if($custom_form!=""){
				$custom_form = 'getbwp_profile_fields_'.$custom_form;		
				$fields_set_to_update =$custom_form;
			}else{				
				$fields_set_to_update ='getbwp_profile_fields';			
			}			
			update_option($fields_set_to_update, NULL);
		}
	}	
	
	function get_pending_verify_requests_count(){
		$count = 0;
		if ($count > 0){
			return '<span class="upadmin-bubble-new">'.$count.'</span>';
		}
	}
	
	function get_pending_verify_requests_count_only(){
		$count = 0;
		if ($count > 0){
			return $count;
		}
	}
	
	function admin_head(){
		$screen = get_current_screen();
		$slug = $this->slug;
	}

	function add_styles(){		
		 global $wp_locale, $getbookingwp, $pagenow;
		 
		if('customize.php' != $pagenow ){
			 
			wp_register_style('getbwp_admin', getbookingpro_url.'admin/css/admin.css');
			wp_enqueue_style('getbwp_admin');
			
			wp_register_style('getbwp_datepicker', getbookingpro_url.'admin/css/datepicker.css');
			wp_enqueue_style('getbwp_datepicker');
			
			wp_register_style('getbwp_admin_calendar', getbookingpro_url.'admin/css/getbwp-calendar.css');
			wp_enqueue_style('getbwp_admin_calendar');				
			
				/*google graph*/		
			wp_register_script('bupro_jsgooglapli', 'https://www.gstatic.com/charts/loader.js');
			wp_enqueue_script('bupro_jsgooglapli');						
				
				
			//color picker		
			wp_enqueue_style( 'wp-color-picker' );			 	 
			wp_register_script( 'getbwp_color_picker', getbookingpro_url.'admin/scripts/color-picker-js.js', array( 
					'wp-color-picker'
				) );
			wp_enqueue_script( 'getbwp_color_picker' );
			
			
			wp_register_script( 'getbwp_admin', getbookingpro_url.'admin/scripts/admin.js', array( 
				'jquery','jquery-ui-core','jquery-ui-draggable','jquery-ui-droppable',	'jquery-ui-sortable', 'jquery-ui-tabs', 'jquery-ui-autocomplete', 'jquery-ui-widget', 'jquery-ui-position'	), null );
			wp_enqueue_script( 'getbwp_admin' );	
            wp_register_style( 'getbwp_event_cal_css', getbookingpro_url.'admin/scripts/event-calendar.min.css');
			wp_enqueue_style('getbwp_event_cal_css');
			wp_register_script( 'getbwp_angular_calendar', getbookingpro_url.'admin/scripts/angular.min.js', array( 
				'jquery') );
			wp_enqueue_script( 'getbwp_angular_calendar' );
			wp_register_script( 'getbwp_angular_calendar_ui', getbookingpro_url.'admin/scripts/angular-ui-date-0.0.8.js', array( 
				'wp-color-picker') );
			wp_enqueue_script( 'getbwp_angular_calendar_ui' );	
            wp_register_script( 'getbwp_moment_calendar', getbookingpro_url.'admin/scripts/moment.min.js', array( 
				'wp-color-picker') );
			wp_enqueue_script( 'getbwp_moment_calendar' );
            wp_register_script( 'getbwp_date_range_picker', getbookingpro_url.'admin/scripts/daterangepicker.js' );
			wp_enqueue_script( 'getbwp_date_range_picker' );
			
			$current_tab = '';
			if(isset($_GET['tab'])){
				$current_tab = sanitize_text_field($_GET['tab']);
			}
			
           if($current_tab =='calendar' ){
			
				wp_register_script( 'getbwp_event_calendar', getbookingpro_url.'admin/scripts/event-calendar.min.js', array( 
					'wp-color-picker'),null );            
				wp_enqueue_script( 'getbwp_event_calendar' );				
				wp_register_script( 'getbwp_calendar_commons', getbookingpro_url.'admin/scripts/get-bookings-wp-calendar-common.js', array( 
					'wp-color-picker') );
				wp_enqueue_script( 'getbwp_calendar_commons' );
			    wp_register_script( 'getbwp_calendar_js', getbookingpro_url.'admin/scripts/get-bookings-wp-calendar.js', array( 
					'wp-color-picker') ,null );
				wp_enqueue_script( 'getbwp_calendar_js' );   
		    }

			wp_register_script( 'getbwp_calendar_funct_js', getbookingpro_url.'admin/scripts/getbwp-calendar.js', array( 
				'wp-color-picker') );
			wp_enqueue_script( 'getbwp_calendar_funct_js' );
            
			/* Font Awesome */
			wp_register_style( 'getbwp_font_awesome', getbookingpro_url.'css/css/font-awesome.min.css');
			wp_enqueue_style('getbwp_font_awesome');
			// Add the styles first, in the <head> (last parameter false, true = bottom of page!)
			wp_enqueue_style('qtip', getbookingpro_url.'js/qtip/jquery.qtip.min.css' , null, false, false);
			
			// Using imagesLoaded? Do this.
			wp_enqueue_script('getbwp_imagesloaded',  getbookingpro_url.'js/qtip/imagesloaded.pkgd.min.js' , null, false, true);
			wp_enqueue_script('getbwp_qtip_js',  getbookingpro_url.'js/qtip/jquery.qtip.min.js', null, false, true);		
		}
		
		$slot_length_minutes = $getbookingwp->get_option( 'getbwp_calendar_time_slot_length' );
		if($slot_length_minutes==''){$slot_length_minutes ='15';}
        $csrf_token = $this->get_csrf_token();		
        $slot = new DateInterval( 'PT' . $slot_length_minutes . 'M' );
		
		wp_localize_script( 'getbwp_calendar_js', 'BuproL10n', array(
            'slotDuration'     => $slot->format( '%H:%I:%S' ),
            'csrf_token'     => $csrf_token,
            'datePicker'      => $this->datePickerOptions(),
            'dateRange'       => $this->dateRangeOptions(),
            'locale'          => $this->getShortLocale(),
            'shortMonths'      => array_values( $wp_locale->month_abbrev ),
            'longMonths'       => array_values( $wp_locale->month ),
            'shortDays'        => array_values( $wp_locale->weekday_abbrev ),
            'longDays'         => array_values( $wp_locale->weekday ),
            'AM'               => $wp_locale->meridiem[ 'AM' ],
            'PM'               => $wp_locale->meridiem[ 'PM' ],
			'mjsDateFormat'    => $this->convertFormat('date', 'fc'),
            'mjsTimeFormat'    => $this->convertFormat('time' , 'fc'),            
            'today'            => __( 'Today', 'get-bookings-wp' ),
            'week'             => __( 'Week',  'get-bookings-wp' ),
            'day'              => __( 'Day',   'get-bookings-wp' ),
            'month'            => __( 'Month', 'get-bookings-wp' ),
            'list'            => __( 'List', 'get-bookings-wp' ),
            'allDay'           => __( 'All Day', 'get-bookings-wp' ),
            'noStaffSelected'  => __( 'No staff selected', 'get-bookings-wp' ),
            'newAppointment'   => __( 'New appointment',   'get-bookings-wp' ),
            'editAppointment'  => __( 'Edit appointment',  'get-bookings-wp' ),
            'are_you_sure'     => __( 'Are you sure?',     'get-bookings-wp' ),
            'startOfWeek'      => (int) get_option( 'start_of_week' ),
			'msg_quick_list_pending_appointments'  => __( 'Pending Appointments', 'get-bookings-wp' ),
			'msg_quick_list_cancelled_appointments'  => __( 'Cancelled Appointments', 'get-bookings-wp' ),
			'msg_quick_list_noshow_appointments'  => __( 'No-show Appointments', 'get-bookings-wp' ),
			'msg_quick_list_unpaid_appointments'  => __( 'Unpaid Appointments', 'get-bookings-wp' ),
            
        ) );
		
		$date_picker_format = $getbookingwp->get_date_picker_format();
		
		wp_localize_script( 'getbwp_admin', 'getbwp_admin_v98', array(
            'msg_cate_delete'  => __( 'Are you totally sure that you wan to delete this category?', 'bookingup' ),
			'msg_service_edit'  => __( 'Edit Service', 'get-bookings-wp' ),
			'msg_service_add'  => __( 'Add Service', 'get-bookings-wp' ),
			'msg_category_edit'  => __( 'Edit Category', 'get-bookings-wp' ),
			'msg_category_add'  => __( 'Add Category', 'get-bookings-wp' ),
			'msg_service_input_title'  => __( 'Please input a title', 'get-bookings-wp' ),
			'msg_service_input_price'  => __( 'Please input a price', 'get-bookings-wp' ),
			'msg_service_delete'  => __( 'Are you totally sure that you wan to delete this service?', 'bookingup' ),
			'msg_user_delete'  => __( 'Are you totally sure that you wan to delete this user?', 'get-bookings-wp' ),
			'message_wait_staff_box'     => __("Please wait ...","get-bookings-wp"),
			'msg_wait'  => __( '<img src="'.getbookingpro_url.'templates/img/loaderB16.gif" width="16" height="16" /> &nbsp; Please wait ... ', 'get-bookings-wp' ) ,
			'bb_date_picker_format'     => $date_picker_format,
			'msg_quick_list_pending_appointments'  => __( 'Pending Appointments', 'get-bookings-wp' ),
			'msg_quick_list_cancelled_appointments'  => __( 'Cancelled Appointments', 'get-bookings-wp' ),
			'msg_quick_list_noshow_appointments'  => __( 'No-show Appointments', 'get-bookings-wp' ),
			'msg_quick_list_unpaid_appointments'  => __( 'Unpaid Appointments', 'get-bookings-wp' )
           
            
        ) );
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
				
				
		wp_localize_script('getbwp_admin', 'GBPDatePicker', $date_picker_array);
		
	}
    
    public  function getShortLocale(){
        $locale = $this->getLocale();
        if ( $second = strpos( $locale, '_', min( 3, strlen( $locale ) ) ) ) {
            $locale = substr( $locale, 0, $second );
        }
        return $locale;
    }
    
    public  function getLocale(){
        $locale = get_locale();
        if ( function_exists( 'get_user_locale' ) ) {
            $locale = get_user_locale();
        }
        return $locale;
    }
    
    /**
     * @param array $array
     * @return array
     */
    public  function dateRangeOptions( $array = array() ){
        return array_merge(
            array(
                'format'           => $this->convertFormat( 'date','fc' ),
                'applyLabel'       => __( 'Apply', 'get-bookings-wp' ),
                'cancelLabel'      => __( 'Cancel', 'get-bookings-wp' ),
                'fromLabel'        => __( 'From', 'get-bookings-wp' ),
                'toLabel'          => __( 'To', 'get-bookings-wp' ),
                'customRangeLabel' => __( 'Custom range', 'get-bookings-wp' ),
                'tomorrow'         => __( 'Tomorrow', 'get-bookings-wp' ),
                'today'            => __( 'Today', 'get-bookings-wp' ),
                'yesterday'        => __( 'Yesterday', 'get-bookings-wp' ),
                'last_7'           => __( 'Last 7 days', 'get-bookings-wp' ),
                'last_30'          => __( 'Last 30 days', 'get-bookings-wp' ),
                'thisMonth'        => __( 'This month', 'get-bookings-wp' ),
                'nextMonth'        => __( 'Next month', 'get-bookings-wp' ),
                'firstDay'         => (int) get_option( 'start_of_week' ),
            ),
            $array
        );
    }

    /**
     * @param array $array
     * @return array
     */
    public  function datePickerOptions( $array = array() )  {
        /** @var \WP_Locale $wp_locale */
        global $wp_locale;

        if ( is_rtl() ) {
            $array['direction'] = 'rtl';
        }

        return array_merge(
            array(
                'format'          => $this->convertFormat( 'date', 'fc' ),
                'monthNames'      => array_values( $wp_locale->month ),
                'daysOfWeek'      => array_values( $wp_locale->weekday_abbrev ),
                'firstDay'        => (int) get_option( 'start_of_week' ),
                'monthNamesShort' => array_values( $wp_locale->month_abbrev ),
                'dayNames'        => array_values( $wp_locale->weekday ),
                'dayNamesShort'   => array_values( $wp_locale->weekday_abbrev ),
                'meridiem'        => $wp_locale->meridiem
            ),
            $array
        );
    }
    
    public function get_csrf_token( ){
        
        session_start();
        if (empty($_SESSION['token'])) {
            if (function_exists('mcrypt_create_iv')) {
                $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
            } else {
                $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
            }
        }
        $token = sanitize_text_field($_SESSION['token']);
        return  $token;
    }	
	
	public  function convertFormat( $source_format, $to ) {
		global $getbookingwp ;
		
        switch ( $source_format ) {
            case 'date':
                $php_format = get_option( 'date_format', 'Y-m-d' );
                break;
            case 'time':
                $php_format = get_option( 'time_format', 'H:i' );
                break;
            default:
                $php_format = $source_format;
        }
		
		 switch ( $to ) {
            case 'fc' :			
                $replacements = array(
                    'd' => 'DD',   '\d' => '[d]',
                    'D' => 'ddd',  '\D' => '[D]',
                    'j' => 'D',    '\j' => 'j',
                    'l' => 'dddd', '\l' => 'l',
                    'N' => 'E',    '\N' => 'N',
                    'S' => 'o',    '\S' => '[S]',
                    'w' => 'e',    '\w' => '[w]',
                    'z' => 'DDD',  '\z' => '[z]',
                    'W' => 'W',    '\W' => '[W]',
                    'F' => 'MMMM', '\F' => 'F',
                    'm' => 'MM',   '\m' => '[m]',
                    'M' => 'MMM',  '\M' => '[M]',
                    'n' => 'M',    '\n' => 'n',
                    't' => '',     '\t' => 't',
                    'L' => '',     '\L' => 'L',
                    'o' => 'YYYY', '\o' => 'o',
                    'Y' => 'YYYY', '\Y' => 'Y',
                    'y' => 'YY',   '\y' => 'y',
                    'a' => 'a',    '\a' => '[a]',
                    'A' => 'A',    '\A' => '[A]',
                    'B' => '',     '\B' => 'B',
                    'g' => 'h',    '\g' => 'g',
                    'G' => 'H',    '\G' => 'G',
                    'h' => 'hh',   '\h' => '[h]',
                    'H' => 'HH',   '\H' => '[H]',
                    'i' => 'mm',   '\i' => 'i',
                    's' => 'ss',   '\s' => '[s]',
                    'u' => 'SSS',  '\u' => 'u',
                    'e' => 'zz',   '\e' => '[e]',
                    'I' => '',     '\I' => 'I',
                    'O' => '',     '\O' => 'O',
                    'P' => '',     '\P' => 'P',
                    'T' => '',     '\T' => 'T',
                    'Z' => '',     '\Z' => '[Z]',
                    'c' => '',     '\c' => 'c',
                    'r' => '',     '\r' => 'r',
                    'U' => 'X',    '\U' => 'U',
                    '\\' => '',
                );
                return strtr( $php_format, $replacements );
			}
	}
	
	function add_menu(){
		global $getbookingwp, $getbwpcomplement ;		
		$pending_count = $getbookingwp->appointment->get_appointments_total_by_status(0);
		$pending_title = esc_attr( sprintf(__( '%d  pending bookings','get-bookings-wp'), $pending_count ) );
		
		if ($pending_count > 0){
			$menu_label = sprintf( __( 'Get Bookings Wp %s','get-bookings-wp' ), "<span class='update-plugins count-$pending_count' title='$pending_title'><span class='update-count'>" . number_format_i18n($pending_count) . "</span></span>" );
		} else {
			$menu_label = __('Get Bookings Wp','get-bookings-wp');
		}
		
		add_menu_page( __('GetBookingsWp Pro','get-bookings-wp'), $menu_label, 'manage_options', $this->slug, array(&$this, 'admin_page'), getbookingpro_url .'admin/images/small_logo_16x16.png', '159.140');
		add_submenu_page( $this->slug, __('Services','get-bookings-wp'), __('Calendar','get-bookings-wp'), 'manage_options', 'getbookingswp&tab=calendar', array(&$this, 'admin_page') );
        add_submenu_page( $this->slug, __('Services','get-bookings-wp'), __('Services','get-bookings-wp'), 'manage_options', 'getbookingswp&tab=services', array(&$this, 'admin_page') );
        add_submenu_page( $this->slug, __('Staff','get-bookings-wp'), __('Staff','get-bookings-wp'), 'manage_options', 'getbookingswp&tab=users', array(&$this, 'admin_page') );
        add_submenu_page( $this->slug, __('Appointments','get-bookings-wp'), __('Appointments','get-bookings-wp'), 'manage_options', 'getbookingswp&tab=appointments', array(&$this, 'admin_page') );
        add_submenu_page( $this->slug, __('Payments','get-bookings-wp'), __('Payments','get-bookings-wp'), 'manage_options', 'getbookingswp&tab=orders', array(&$this, 'admin_page') );
        add_submenu_page( $this->slug, __('Custom Fields','get-bookings-wp'), __('Custom Fields','get-bookings-wp'), 'manage_options', 'getbookingswp&tab=fields', array(&$this, 'admin_page') );
        add_submenu_page( $this->slug, __('Settings','get-bookings-wp'), __('Settings','get-bookings-wp'), 'manage_options', 'getbookingswp&tab=settings', array(&$this, 'admin_page') );
        add_submenu_page( $this->slug, __('Notifications','get-bookings-wp'), __('Notifications','get-bookings-wp'), 'manage_options', 'getbookingswp&tab=mail', array(&$this, 'admin_page') );
        add_submenu_page( $this->slug, __('Payment Gateways','get-bookings-wp'), __('Payment Gateways','get-bookings-wp'), 'manage_options', 'getbookingswp&tab=gateway', array(&$this, 'admin_page') );
        add_submenu_page( $this->slug, __('Documentation','get-bookings-wp'), __('Documentation','get-bookings-wp'), 'manage_options', 'getbookingswp&tab=help', array(&$this, 'admin_page') );
		if(!isset($getbwpcomplement)){
			add_submenu_page( $this->slug, __('More Functionality!','get-bookings-wp'), __('More Functionality!','get-bookings-wp'), 'manage_options', 'getbookingswp&tab=pro', array(&$this, 'admin_page') );
		}
		add_submenu_page( $this->slug, __('Licensing','get-bookings-wp'), __('Licensing','get-bookings-wp'), 'manage_options', 'getbookingswp&tab=licence', array(&$this, 'admin_page') );
		do_action('getbwp_admin_menu_hook');
	}

	function admin_tabs( $current = null ) {
		global $getbwpultimate, $getbwpcomplement;
		$tabs = $this->tabs;
		$links = array();
		if ( isset ( $_GET['tab'] ) ) {
				$current = sanitize_text_field($_GET['tab']);
		} else {
				$current = $this->default_tab;
		}
		
		foreach( $tabs as $tab => $name ) :
			
			$custom_badge = "";				
			if($tab=="pro"){
				$custom_badge = 'getbwp-pro-tab-bubble ';
			}
				
			if(isset($getbwpcomplement) && $tab=="pro"){continue;}
				
			if ( $tab == $current ) :
					$links[] = "<a class='nav-tab nav-tab-active ".$custom_badge."' href='?page=".$this->slug."&tab=$tab'>$name</a>";
				else :
					$links[] = "<a class='nav-tab ".$custom_badge."' href='?page=".$this->slug."&tab=$tab'>$name</a>";
				endif;
				
			endforeach;
			foreach ( $links as $link )
				echo esc_url($link);
	}
	
	function do_action(){
		global $bup;		
	}
	
	/* set a global option */
	function getbwp_set_option($option, $newvalue)	{
		$settings = get_option('getbwp_options');		
		$settings[$option] = $newvalue;
		update_option('getbwp_options', $settings);
	}
	
	/* default options */
	function getbwp_set_default_options(){
	
		$this->getbwp_default_options = array(									
						
						'messaging_send_from_name' => __('Appointments Plugin','get-bookings-wp'),
						'getbwp_time_slot_length' => 15,
						'getbwp_calendar_time_slot_length' => 15,
						'getbwp_calendar_days_to_display' => 7,
						'gateway_free_default_status' => 0,
						'gateway_bank_default_status' => 0,
						'google_map_profile_active' => 1,
						'notifications_sms_reminder_at' => 18,				
						
						'getbwp_noti_admin' => 'yes',
						'getbwp_noti_staff' => 'yes',
						'getbwp_noti_client' => 'yes',						
						'google_calendar_template' => 'service_name',						
						'currency_symbol' => '$',						
						'email_new_booking_admin' => $this->get_email_template('email_new_booking_admin'),
						'email_new_booking_subject_admin' => __('New Appointment Request has been received','get-bookings-wp'),
						
						'email_new_booking_staff' => $this->get_email_template('email_new_booking_staff'),
						'email_new_booking_subject_staff' => __('You have a new appointment','get-bookings-wp'),						
						'email_new_booking_client' => $this->get_email_template('email_new_booking_client'),
						'email_new_booking_subject_client' => __('Thank you for your appointment','get-bookings-wp'),
						'email_reschedule' => $this->get_email_template('email_reschedule'),
						'email_reschedule_staff' => $this->get_email_template('email_reschedule_staff'),
						'email_reschedule_admin' => $this->get_email_template('email_reschedule_admin'),
						'email_reschedule_subject' => __('Appointment Reschedule','get-bookings-wp'),
						'email_reschedule_subject_staff' => __('Appointment Reschedule','get-bookings-wp'),
						'email_reschedule_subject_admin' => __('Appointment Reschedule','get-bookings-wp'),
						'email_bank_payment' => $this->get_email_template('email_bank_payment'),
						'email_bank_payment_subject' => __('Appointment Details','get-bookings-wp'),
						'email_bank_payment_admin' => $this->get_email_template('email_bank_payment_admin'),
						'email_bank_payment_admin_subject' => __('New Appointment','get-bookings-wp'),
						'email_bank_payment_staff' => $this->get_email_template('email_bank_payment_staff'),
						'email_bank_payment_staff_subject' => __('You have a new Appointment','get-bookings-wp'),
						
						'email_appo_status_changed_admin' => $this->get_email_template('email_appo_status_changed_admin'),
						'email_appo_status_changed_admin_subject' => __('Appointment Status Changed','get-bookings-wp'),
						'email_appo_status_changed_staff' => $this->get_email_template('email_appo_status_changed_staff'),
						'email_appo_status_changed_staff_subject' => __('Appointment Status Changed','get-bookings-wp'),
						'email_appo_status_changed_client' => $this->get_email_template('email_appo_status_changed_client'),
						'email_appo_status_changed_client_subject' => __('Appointment Status Changed','get-bookings-wp'),
						
						'email_password_change_staff' => $this->get_email_template('email_password_change_staff'),
						'email_password_change_staff_subject' => __('Password Changed','get-bookings-wp'),
						
						'email_reset_link_message_body' => $this->get_email_template('email_reset_link_message_body'),
						'email_reset_link_message_subject' => __('Password Reset','get-bookings-wp'),
						
						'email_welcome_staff_link_message_body' => $this->get_email_template('email_welcome_staff_link_message_body'),
						'email_welcome_staff_link_message_subject' => __('Your Account Details','get-bookings-wp'),
						
						'email_sms_body_reminder_customer_1' => $this->get_email_template('email_sms_body_reminder_customer_1'),
						
				);
		
	}
	
	public function set_default_email_messages(){
		$line_break = "\r\n";	
						
		//notify admin 		
		$email_body = __('Hello Admin ' ,"get-bookings-wp") .$line_break.$line_break;
		$email_body .= __("A new booking has been received. Below are the details of the appointment.","get-bookings-wp") .  $line_break.$line_break;
		
		$email_body .= "<strong>".__("Appointment Details:","get-bookings-wp")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{getbwp_booking_service}}','get-bookings-wp') . $line_break;	
		$email_body .= __('Client: {{getbwp_client_name}}','get-bookings-wp') . $line_break;
		$email_body .= __('Phone: {{getbwp_client_phone}}','get-bookings-wp') . $line_break;
		$email_body .= __('Client Email: {{getbwp_client_email}}','get-bookings-wp') . $line_break;
		$email_body .= __('Date: {{getbwp_booking_date}}','get-bookings-wp') . $line_break;
		$email_body .= __('Time: {{getbwp_booking_time}}','get-bookings-wp') . $line_break;
		$email_body .= __('With: {{getbwp_booking_staff}}','get-bookings-wp') . $line_break;
		$email_body .= __('Cost: {{getbwp_booking_cost}}','get-bookings-wp'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','get-bookings-wp'). $line_break;
		$email_body .= '{{getbwp_company_name}}'. $line_break;
		$email_body .= '{{getbwp_company_phone}}'. $line_break;
		$email_body .= '{{getbwp_company_url}}'. $line_break;
		$email_body .= __("Please, use the following link in case you'd like to approve this appointment.",'get-bookings-wp'). $line_break;
		$email_body .='{{getbwp_booking_approval_url}}';		
	    $this->notifications_email['email_new_booking_admin'] = $email_body;
		
		//notify staff 		
		$email_body =  '{{getbwp_staff_name}},'.$line_break.$line_break;
		$email_body .= __("You have a new appointment. ","get-bookings-wp") .  $line_break.$line_break;
		
		$email_body .= "<strong>".__("Appointment Details:","get-bookings-wp")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{getbwp_booking_service}}','get-bookings-wp') . $line_break;	
		$email_body .= __('Client: {{getbwp_client_name}}','get-bookings-wp') . $line_break;
		$email_body .= __('Phone: {{getbwp_client_phone}}','get-bookings-wp') . $line_break;
		$email_body .= __('Client Email: {{getbwp_client_email}}','get-bookings-wp') . $line_break;
		$email_body .= __('Date: {{getbwp_booking_date}}','get-bookings-wp') . $line_break;
		$email_body .= __('Time: {{getbwp_booking_time}}','get-bookings-wp') . $line_break;
		$email_body .= __('With: {{getbwp_booking_staff}}','get-bookings-wp') . $line_break;
		$email_body .= __('Cost: {{getbwp_booking_cost}}','get-bookings-wp'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','get-bookings-wp'). $line_break;
		$email_body .= '{{getbwp_company_name}}'. $line_break;
		$email_body .= '{{getbwp_company_phone}}'. $line_break;
		$email_body .= '{{getbwp_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_new_booking_staff'] = $email_body;
		
		//notify client 		
		$email_body =  '{{getbwp_client_name}},'.$line_break.$line_break;
		$email_body .= __("Thank you for booking {{getbwp_booking_service}}. Below are the details of your appointment.","get-bookings-wp") .  $line_break.$line_break;
		
		$email_body .= "<strong>".__("Appointment Details:","get-bookings-wp")."</strong>".  $line_break.$line_break;
		$email_body .= __('Service: {{getbwp_booking_service}}','get-bookings-wp') . $line_break;		
		$email_body .= __('Date: {{getbwp_booking_date}}','get-bookings-wp') . $line_break;
		$email_body .= __('Time: {{getbwp_booking_time}}','get-bookings-wp') . $line_break;
		$email_body .= __('With: {{getbwp_booking_staff}}','get-bookings-wp') . $line_break;
		$email_body .= __('Cost: {{getbwp_booking_cost}}','get-bookings-wp'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','get-bookings-wp'). $line_break;
		$email_body .= '{{getbwp_company_name}}'. $line_break;
		$email_body .= '{{getbwp_company_phone}}'. $line_break;
		$email_body .= '{{getbwp_company_url}}'. $line_break. $line_break;
		
		$email_body .= __("Please, use the following link in case you'd like to cancel your appointment.",'get-bookings-wp'). $line_break;
		$email_body .='{{getbwp_booking_cancelation_url}}';
		
	    $this->notifications_email['email_new_booking_client'] = $email_body;
		
		//notify reschedule client		
		$email_body =  '{{getbwp_client_name}},'.$line_break.$line_break;
		$email_body .= __("Your appointment has been rescheduled . ","get-bookings-wp") .  $line_break.$line_break;
		
		$email_body .= "<strong>".__("Appointment Details:","get-bookings-wp")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{getbwp_booking_service}}','get-bookings-wp') . $line_break;	
		$email_body .= __('Date: {{getbwp_booking_date}}','get-bookings-wp') . $line_break;
		$email_body .= __('Time: {{getbwp_booking_time}}','get-bookings-wp') . $line_break;
		$email_body .= __('With: {{getbwp_booking_staff}}','get-bookings-wp') . $line_break;
		$email_body .= __('Cost: {{getbwp_booking_cost}}','get-bookings-wp'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','get-bookings-wp'). $line_break;
		$email_body .= '{{getbwp_company_name}}'. $line_break;
		$email_body .= '{{getbwp_company_phone}}'. $line_break;
		$email_body .= '{{getbwp_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_reschedule'] = $email_body;
		
		//notify reschedule staff		
		$email_body =  '{{getbwp_staff_name}},'.$line_break.$line_break;
		$email_body .= __("One of your appointments has been rescheduled . ","get-bookings-wp") .  $line_break.$line_break;
		
		$email_body .= "<strong>".__("Appointment Details:","get-bookings-wp")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{getbwp_booking_service}}','get-bookings-wp') . $line_break;	
		$email_body .= __('Date: {{getbwp_booking_date}}','get-bookings-wp') . $line_break;
		$email_body .= __('Time: {{getbwp_booking_time}}','get-bookings-wp') . $line_break;
		$email_body .= __('With: {{getbwp_booking_staff}}','get-bookings-wp') . $line_break;
		$email_body .= __('Cost: {{getbwp_booking_cost}}','get-bookings-wp'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','get-bookings-wp'). $line_break;
		$email_body .= '{{getbwp_company_name}}'. $line_break;
		$email_body .= '{{getbwp_company_phone}}'. $line_break;
		$email_body .= '{{getbwp_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_reschedule_staff'] = $email_body;
		
		//notify reschedule admin		
		$email_body =  'Dear Admin,'.$line_break.$line_break;
		$email_body .= __("This is a confirmation that an appointment has been rescheduled . ","get-bookings-wp") .  $line_break.$line_break;
		
		$email_body .= "<strong>".__("Appointment Details:","get-bookings-wp")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{getbwp_booking_service}}','get-bookings-wp') . $line_break;	
		$email_body .= __('Date: {{getbwp_booking_date}}','get-bookings-wp') . $line_break;
		$email_body .= __('Time: {{getbwp_booking_time}}','get-bookings-wp') . $line_break;
		$email_body .= __('With: {{getbwp_booking_staff}}','get-bookings-wp') . $line_break;
		$email_body .= __('Cost: {{getbwp_booking_cost}}','get-bookings-wp'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','get-bookings-wp'). $line_break;
		$email_body .= '{{getbwp_company_name}}'. $line_break;
		$email_body .= '{{getbwp_company_phone}}'. $line_break;
		$email_body .= '{{getbwp_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_reschedule_admin'] = $email_body;		
		
		//notify bank 		
		$email_body =  '{{getbwp_client_name}},'.$line_break.$line_break;
		$email_body .= __("Please deposit the payment in the following bank account: ","get-bookings-wp") .  $line_break.$line_break;
		
		$email_body .= "<strong>Bank Name</strong>: ".  $line_break;
		$email_body .= "<strong>Account Number</strong>: ".  $line_break;
		$email_body .=   $line_break;
		
		$email_body .= "<strong>".__("Appointment Details:","get-bookings-wp")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{getbwp_booking_service}}','get-bookings-wp') . $line_break;	
		
		$email_body .= __('Date: {{getbwp_booking_date}}','get-bookings-wp') . $line_break;
		$email_body .= __('Time: {{getbwp_booking_time}}','get-bookings-wp') . $line_break;
		$email_body .= __('With: {{getbwp_booking_staff}}','get-bookings-wp') . $line_break;
		$email_body .= __('Cost: {{getbwp_booking_cost}}','get-bookings-wp'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','get-bookings-wp'). $line_break;
		$email_body .= '{{getbwp_company_name}}'. $line_break;
		$email_body .= '{{getbwp_company_phone}}'. $line_break;
		$email_body .= '{{getbwp_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_bank_payment'] = $email_body;
		
		//notify bank to admin	
		$email_body = __('Hello Admin ' ,"get-bookings-wp") .$line_break.$line_break;
		$email_body .= __("A new appointment with local payment has been submitted. ","get-bookings-wp") .  $line_break.$line_break;			
		$email_body .= "<strong>".__("Appointment Details:","get-bookings-wp")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{getbwp_booking_service}}','get-bookings-wp') . $line_break;
		$email_body .= __('Client: {{getbwp_client_name}}','get-bookings-wp') . $line_break;
		$email_body .= __('Phone: {{getbwp_client_phone}}','get-bookings-wp') . $line_break;
		$email_body .= __('Client Email: {{getbwp_client_email}}','get-bookings-wp') . $line_break;	
		$email_body .= __('Date: {{getbwp_booking_date}}','get-bookings-wp') . $line_break;
		$email_body .= __('Time: {{getbwp_booking_time}}','get-bookings-wp') . $line_break;
		$email_body .= __('With: {{getbwp_booking_staff}}','get-bookings-wp') . $line_break;
		$email_body .= __('Cost: {{getbwp_booking_cost}}','get-bookings-wp'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','get-bookings-wp'). $line_break;
		$email_body .= '{{getbwp_company_name}}'. $line_break;
		$email_body .= '{{getbwp_company_phone}}'. $line_break;
		$email_body .= '{{getbwp_company_url}}'. $line_break. $line_break;	
		
		$email_body .= __("Please, use the following link in case you'd like to approve this appointment.",'get-bookings-wp'). $line_break;
		$email_body .='{{getbwp_booking_approval_url}}';		
		
	    $this->notifications_email['email_bank_payment_admin'] = $email_body;
		
		//notify bank to staff	
		$email_body = '{{getbwp_staff_name}},' .$line_break.$line_break;
		$email_body .= __("Dear staff member, new appointment with local payment has been submitted. ","get-bookings-wp") .  $line_break.$line_break;			
		$email_body .= "<strong>".__("Appointment Details:","get-bookings-wp")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{getbwp_booking_service}}','get-bookings-wp') . $line_break;
		$email_body .= __('Client: {{getbwp_client_name}}','get-bookings-wp') . $line_break;
		$email_body .= __('Phone: {{getbwp_client_phone}}','get-bookings-wp') . $line_break;
		$email_body .= __('Client Email: {{getbwp_client_email}}','get-bookings-wp') . $line_break;	
		$email_body .= __('Date: {{getbwp_booking_date}}','get-bookings-wp') . $line_break;
		$email_body .= __('Time: {{getbwp_booking_time}}','get-bookings-wp') . $line_break;
		$email_body .= __('With: {{getbwp_booking_staff}}','get-bookings-wp') . $line_break;
		$email_body .= __('Cost: {{getbwp_booking_cost}}','get-bookings-wp'). $line_break.$line_break;
		
		$email_body .= __('Best Regards!','get-bookings-wp'). $line_break;
		$email_body .= '{{getbwp_company_name}}'. $line_break;
		$email_body .= '{{getbwp_company_phone}}'. $line_break;
		$email_body .= '{{getbwp_company_url}}'. $line_break. $line_break;	
		
		$email_body .= __("Please, use the following link in case you'd like to approve this appointment.",'get-bookings-wp'). $line_break;
		$email_body .='{{getbwp_booking_approval_url}}';		
		
	    $this->notifications_email['email_bank_payment_staff'] = $email_body;
		
		//Appointment Status Changed Admin	
		$email_body = __('Hello Admin ' ,"get-bookings-wp") .$line_break.$line_break;
		$email_body .= __("The status of the following appointment has changed. ","get-bookings-wp") .  $line_break.$line_break;
		
		$email_body .= __('New Status: {{getbwp_booking_status}}','get-bookings-wp') . $line_break.$line_break;		
				
		$email_body .= "<strong>".__("Appointment Details:","get-bookings-wp")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{getbwp_booking_service}}','get-bookings-wp') . $line_break;	
		$email_body .= __('Date: {{getbwp_booking_date}}','get-bookings-wp') . $line_break;
		$email_body .= __('Time: {{getbwp_booking_time}}','get-bookings-wp') . $line_break;
		$email_body .= __('With: {{getbwp_booking_staff}}','get-bookings-wp') . $line_break;
		
		$email_body .= __('Best Regards!','get-bookings-wp'). $line_break;
		$email_body .= '{{getbwp_company_name}}'. $line_break;
		$email_body .= '{{getbwp_company_phone}}'. $line_break;
		$email_body .= '{{getbwp_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_appo_status_changed_admin'] = $email_body;
		
		//Appointment Status Changed Staff	
		$email_body =  '{{getbwp_staff_name}},'.$line_break.$line_break;
		$email_body .= __("The status of the following appointment has changed. ","get-bookings-wp") .  $line_break.$line_break;
		$email_body .= __('New Status: {{getbwp_booking_status}}','get-bookings-wp') . $line_break.$line_break;
		$email_body .= "<strong>".__("Appointment Details:","get-bookings-wp")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{getbwp_booking_service}}','get-bookings-wp') . $line_break;	
		$email_body .= __('Date: {{getbwp_booking_date}}','get-bookings-wp') . $line_break;
		$email_body .= __('Time: {{getbwp_booking_time}}','get-bookings-wp') . $line_break;
		$email_body .= __('With: {{getbwp_booking_staff}}','get-bookings-wp') . $line_break;
		
		$email_body .= __('Best Regards!','get-bookings-wp'). $line_break;
		$email_body .= '{{getbwp_company_name}}'. $line_break;
		$email_body .= '{{getbwp_company_phone}}'. $line_break;
		$email_body .= '{{getbwp_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_appo_status_changed_staff'] = $email_body;
		
		//Appointment Status Changed Client	
		$email_body =  '{{getbwp_client_name}},'.$line_break.$line_break;
		$email_body .= __("The status of your appointment has changed. ","get-bookings-wp") .  $line_break.$line_break;
		
		$email_body .= __('New Status: {{getbwp_booking_status}}','get-bookings-wp') . $line_break.$line_break;		
				
		$email_body .= "<strong>".__("Appointment Details:","get-bookings-wp")."</strong>".  $line_break.$line_break;	
		$email_body .= __('Service: {{getbwp_booking_service}}','get-bookings-wp') . $line_break;	
		$email_body .= __('Date: {{getbwp_booking_date}}','get-bookings-wp') . $line_break;
		$email_body .= __('Time: {{getbwp_booking_time}}','get-bookings-wp') . $line_break;
		$email_body .= __('With: {{getbwp_booking_staff}}','get-bookings-wp') . $line_break;
		
		$email_body .= __('Best Regards!','get-bookings-wp'). $line_break;
		$email_body .= '{{getbwp_company_name}}'. $line_break;
		$email_body .= '{{getbwp_company_phone}}'. $line_break;
		$email_body .= '{{getbwp_company_url}}'. $line_break. $line_break;		
		
	    $this->notifications_email['email_appo_status_changed_client'] = $email_body;
		
		//Staff Password Change	
		$email_body =  '{{getbwp_staff_name}},'.$line_break.$line_break;
		$email_body .= __("This is a notification that your password has been changed. ","get-bookings-wp") .  $line_break.$line_break;
				
		$email_body .= __('Best Regards!','get-bookings-wp'). $line_break;
		$email_body .= '{{getbwp_company_name}}'. $line_break;
		$email_body .= '{{getbwp_company_phone}}'. $line_break;
		$email_body .= '{{getbwp_company_url}}'. $line_break. $line_break;
		
	    $this->notifications_email['email_password_change_staff'] = $email_body;
		
		//Staff Password Reset	
		$email_body =  '{{getbwp_staff_name}},'.$line_break.$line_break;
		$email_body .= __("Please use the following link to reset your password.","get-bookings-wp") . $line_break.$line_break;			
		$email_body .= "{{getbwp_reset_link}}".$line_break.$line_break;
		$email_body .= __('If you did not request a new password delete this email.','get-bookings-wp'). $line_break.$line_break;	
			
		$email_body .= __('Best Regards!','get-bookings-wp'). $line_break;
		$email_body .= '{{getbwp_company_name}}'. $line_break;
		$email_body .= '{{getbwp_company_phone}}'. $line_break;
		$email_body .= '{{getbwp_company_url}}'. $line_break. $line_break;
		
	    $this->notifications_email['email_reset_link_message_body'] = $email_body;
		
		//Staff Welcome Account Reset Link	
		$email_body =  '{{getbwp_staff_name}},'.$line_break.$line_break;
		$email_body .= __("Your login details for your account are as follows:","get-bookings-wp") . $line_break.$line_break;
		$email_body .= __('Username: {{getbwp_user_name}}','get-bookings-wp') . $line_break;
		$email_body .= __("Please use the following link to reset your password.","get-bookings-wp") . $line_break.$line_break;			
		$email_body .= "{{getbwp_reset_link}}".$line_break.$line_break;
			
		$email_body .= __('Best Regards!','get-bookings-wp'). $line_break;
		$email_body .= '{{getbwp_company_name}}'. $line_break;
		$email_body .= '{{getbwp_company_phone}}'. $line_break;
		$email_body .= '{{getbwp_company_url}}'. $line_break. $line_break;
		
	    $this->notifications_email['email_welcome_staff_link_message_body'] = $email_body;	
		
		//SMS Reminder to Customer
		$email_body =  __('Dear ','get-bookings-wp').'{{getbwp_client_name}}. ';
		$email_body .= __("You have an appointment with our company tomorrow at {{getbwp_booking_time}}.","get-bookings-wp");
		$email_body .= __(" We are waiting you at {{getbwp_company_address}}. ","get-bookings-wp") ;
		$email_body .= '{{getbwp_company_name}}';	
		
	    $this->notifications_email['email_sms_body_reminder_customer_1'] = $email_body;	
	}
	
	public function get_email_template($key){
		return $this->notifications_email[$key];	
	}
	
	public function set_font_awesome(){
		        /* Store icons in array */
        $this->fontawesome = array(
                'cloud-download','cloud-upload','lightbulb','exchange','bell-alt','file-alt','beer','coffee','food','fighter-jet',
                'user-md','stethoscope','suitcase','building','hospital','ambulance','medkit','h-sign','plus-sign-alt','spinner',
                'angle-left','angle-right','angle-up','angle-down','double-angle-left','double-angle-right','double-angle-up','double-angle-down','circle-blank','circle',
                'desktop','laptop','tablet','mobile-phone','quote-left','quote-right','reply','github-alt','folder-close-alt','folder-open-alt',
                'adjust','asterisk','ban-circle','bar-chart','barcode','beaker','beer','bell','bolt','book','bookmark','bookmark-empty','briefcase','bullhorn',
                'calendar','camera','camera-retro','certificate','check','check-empty','cloud','cog','cogs','comment','comment-alt','comments','comments-alt',
                'credit-card','dashboard','download','download-alt','edit','envelope','envelope-alt','exclamation-sign','external-link','eye-close','eye-open',
                'facetime-video','film','filter','fire','flag','folder-close','folder-open','gift','glass','globe','group','hdd','headphones','heart','heart-empty',
                'home','inbox','info-sign','key','leaf','legal','lemon','lock','unlock','magic','magnet','map-marker','minus','minus-sign','money','move','music',
                'off','ok','ok-circle','ok-sign','pencil','picture','plane','plus','plus-sign','print','pushpin','qrcode','question-sign','random','refresh','remove',
                'remove-circle','remove-sign','reorder','resize-horizontal','resize-vertical','retweet','road','rss','screenshot','search','share','share-alt',
                'shopping-cart','signal','signin','signout','sitemap','sort','sort-down','sort-up','spinner','star','star-empty','star-half','tag','tags','tasks',
                'thumbs-down','thumbs-up','time','tint','trash','trophy','truck','umbrella','upload','upload-alt','user','volume-off','volume-down','volume-up',
                'warning-sign','wrench','zoom-in','zoom-out','file','cut','copy','paste','save','undo','repeat','text-height','text-width','align-left','align-right',
                'align-center','align-justify','indent-left','indent-right','font','bold','italic','strikethrough','underline','link','paper-clip','columns',
                'table','th-large','th','th-list','list','list-ol','list-ul','list-alt','arrow-down','arrow-left','arrow-right','arrow-up','caret-down',
                'caret-left','caret-right','caret-up','chevron-down','chevron-left','chevron-right','chevron-up','circle-arrow-down','circle-arrow-left',
                'circle-arrow-right','circle-arrow-up','hand-down','hand-left','hand-right','hand-up','play-circle','play','pause','stop','step-backward',
                'fast-backward','backward','forward','step-forward','fast-forward','eject','fullscreen','resize-full','resize-small','phone','phone-sign',
                'facebook','facebook-sign','twitter','twitter-sign','github','github-sign','linkedin','linkedin-sign','pinterest','pinterest-sign',
                'google-plus','google-plus-sign','sign-blank'
        );
        asort($this->fontawesome);
	}
	
	/*This Function Change the Profile Fields Order when drag/drop */	
	public function sort_fileds_list()	{
		global $wpdb;
	
		$order = explode(',', sanitize_text_field($_POST['order']));
		$counter = 0;
		$new_pos = 10;		
		//multi fields		
		$custom_form = sanitize_text_field($_POST["getbwp_custom_form"]);
		
		if($custom_form!=""){
			$custom_form = 'getbwp_profile_fields_'.$custom_form;		
			$fields = get_option($custom_form);			
			$fields_set_to_update =$custom_form;
		}else{			
			$fields = get_option('getbwp_profile_fields');
			$fields_set_to_update ='getbwp_profile_fields';
		}
		
		$new_fields = array();		
		$fields_temp = $fields;
		ksort($fields);
		
		foreach ($fields as $field){
			
			$fields_temp[$order[$counter]]["position"] = $new_pos;			
			$new_fields[$new_pos] = $fields_temp[$order[$counter]];				
			$counter++;
			$new_pos=$new_pos+10;
		}
		ksort($new_fields);		
		update_option($fields_set_to_update, $new_fields);		
		die();
    }
	/*  delete profile field */
    public function delete_profile_field(){						
		
		if($_POST['_item']!= ""){		
			$custom_form = sanitize_text_field($_POST["getbwp_custom_form"]);
			if($custom_form!=""){
				$custom_form = 'getbwp_profile_fields_'.$custom_form;		
				$fields = get_option($custom_form);			
				$fields_set_to_update =$custom_form;
				
			}else{
				
				$fields = get_option('getbwp_profile_fields');
				$fields_set_to_update ='getbwp_profile_fields';
			}
			
			$pos = sanitize_text_field($_POST['_item']);
			unset($fields[$pos]);
			ksort($fields);
			print_r($fields);
			update_option($fields_set_to_update, $fields);
		}
	
	}
	
	
	 /* create new custom profile field */
    public function add_new_custom_profile_field()	{				
		
		if($_POST['_meta']!= ""){
			$meta = sanitize_text_field($_POST['_meta']);
		}else{			
			$meta = sanitize_text_field($_POST['_meta_custom']);
		}	
	
		$custom_form = sanitize_text_field($_POST["getbwp_custom_form"]);
		
		if($custom_form!=""){
			$custom_form = 'getbwp_profile_fields_'.$custom_form;		
			$fields = get_option($custom_form);			
			$fields_set_to_update =$custom_form;
		}else{
			$fields = get_option('getbwp_profile_fields');
			$fields_set_to_update ='getbwp_profile_fields';
		}

		$min = min(array_keys($fields)); 
		$pos = $min-1;
		$fields[$pos] =array(
			  'position' => $pos,
				'icon' => sanitize_text_field($_POST['_icon']),
				'type' => sanitize_text_field($_POST['_type']),
				'field' => sanitize_text_field($_POST['_field']),
				'meta' => sanitize_text_field($meta),
				'name' => sanitize_text_field($_POST['_name']),				
				'tooltip' => sanitize_text_field($_POST['_tooltip']),
				'help_text' => sanitize_text_field($_POST['_help_text']),							
				'can_edit' => sanitize_text_field($_POST['_can_edit']),
				'allow_html' => sanitize_text_field($_POST['_allow_html']),
				'can_hide' => sanitize_text_field($_POST['_can_hide']),				
				'private' => sanitize_text_field($_POST['_private']),
				'required' => sanitize_text_field($_POST['_required']),
				'show_in_register' => sanitize_text_field($_POST['_show_in_register']),
				'predefined_options' => sanitize_text_field($_POST['_predefined_options']),				
				'choices' => sanitize_text_field($_POST['_choices']),												
				'deleted' => 0
				

			);
		ksort($fields);
		print_r($fields);
		update_option($fields_set_to_update, $fields);         
    }
	
    public function save_fields_settings() {		
		
		$pos = sanitize_text_field($_POST['pos']);		
		if($_POST['_meta']!= "")		{
			$meta = sanitize_text_field($_POST['_meta']);
		}else{
			$meta = sanitize_text_field($_POST['_meta_custom']);
		}		
		//multi fields		
		$custom_form = sanitize_text_field($_POST["getbwp_custom_form"]);		
		if($custom_form!=""){
			$custom_form = 'getbwp_profile_fields_'.$custom_form;		
			$fields = get_option($custom_form);			
			$fields_set_to_update =$custom_form;
		}else{			
			$fields = get_option('getbwp_profile_fields');
			$fields_set_to_update ='getbwp_profile_fields';
		}
		
		$fields[$pos] =array(
			  'position' => $pos,
				'icon' => sanitize_text_field($_POST['_icon']),
				'type' => sanitize_text_field($_POST['_type']),
				'field' => sanitize_text_field($_POST['_field']),
				'meta' => sanitize_text_field($meta),
				'name' => sanitize_text_field($_POST['_name']),
				'ccap' => sanitize_text_field($_POST['_ccap']),
				'tooltip' => sanitize_text_field($_POST['_tooltip']),
				'help_text' => sanitize_text_field($_POST['_help_text']),
				'social' =>  sanitize_text_field($_POST['_social']),
				'is_a_link' =>  sanitize_text_field($_POST['_is_a_link']),
				'can_edit' => sanitize_text_field($_POST['_can_edit']),
				'allow_html' => sanitize_text_field($_POST['_allow_html']),				
				'required' => sanitize_text_field($_POST['_required']),
				'show_in_register' => sanitize_text_field($_POST['_show_in_register']),				
				'predefined_options' => sanitize_text_field($_POST['_predefined_options']),				
				'choices' => sanitize_text_field($_POST['_choices']),																
				'deleted' => 0,
				'show_to_user_role' => sanitize_text_field($_POST['_show_to_user_role']),
                'edit_by_user_role' => sanitize_text_field($_POST['_edit_by_user_role'])
			);
			print_r($fields);
		    update_option($fields_set_to_update , $fields);
    }	
		

	function getbwp_reload_field_to_edit(){
		global $getbookingwp;		

		$pos = sanitize_text_field($_POST["pos"]);
		$i = 0;			

		//multi fields		
		$custom_form =sanitize_text_field( $_POST["getbwp_custom_form"]);
		
		if($custom_form!=""){
			$custom_form = 'getbwp_profile_fields_'.$custom_form;		
			$fields = get_option($custom_form);			
			$fields_set_to_update =$custom_form;
			
		}else{			
			$fields = get_option('getbwp_profile_fields');
			$fields_set_to_update ='getbwp_profile_fields';		
		}
		
		$array = $fields[$pos];
		extract($array); $i++;

		$input = sanitize_text_field($input);
		$name = sanitize_text_field($name);	

		if(!isset($required))
		       $required = 0;

		    if(!isset($fonticon))
		        $fonticon = '';	
				
			if(!isset($tooltip)){
				$tooltip = '';	
			}	
				
			if ($type == 'seperator' || $type == 'separator') {
				$class = "separator";
				$class_title = "";
			} else {			  
				$class = "profile-field";
				$class_title = "profile-field";
			}
		?>
		
		

				<p>
					<label for="uultra_<?php echo esc_attr($pos); ?>_position"><?php _e('Position','get-bookings-wp'); ?>
					</label> <input name="uultra_<?php echo esc_attr($pos); ?>_position"
						type="text" id="uultra_<?php echo esc_attr($pos); ?>_position"
						value="<?php echo esc_attr($pos); ?>" class="small-text" /> <i
						class="uultra_icon-question-sign uultra-tooltip2"
						title="<?php _e('Please use a unique position. Position lets you place the new field in the place you want exactly in Profile view.','get-bookings-wp'); ?>"></i>
				</p>

				<p>
					<label for="uultra_<?php echo esc_attr($pos); ?>_type"><?php _e('Field Type','get-bookings-wp'); ?>
					</label> <select name="uultra_<?php echo esc_attr($pos); ?>_type"
						id="uultra_<?php echo esc_attr($pos); ?>_type">
						<option value="usermeta" <?php selected('usermeta', $type); ?>>
							<?php _e('Booking Field','get-bookings-wp'); ?>
						</option>
						<option value="separator" <?php selected('separator', $type); ?>>
							<?php _e('Separator','get-bookings-wp'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('You can create a separator or a usermeta (profile field)','get-bookings-wp'); ?>"></i>
				</p> 
				
				<?php if ($type != 'separator') { ?>

				<p class="uultra-inputtype">
					<label for="uultra_<?php echo esc_attr($pos); ?>_field"><?php _e('Field Input','get-bookings-wp'); ?>
					</label> <select name="uultra_<?php echo esc_attr($pos); ?>_field"
						id="uultra_<?php echo esc_attr($pos); ?>_field">
						<?php
						
						 foreach($getbookingwp->allowed_inputs as $input=>$label) { ?>
						<option value="<?php echo esc_attr($input); ?>"
						<?php selected($input, $field); ?>>
							<?php echo esc_attr($label) ?>
						</option>
						<?php } ?>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('When user edit profile, this field can be an input (text, textarea, image upload, etc.)','get-bookings-wp'); ?>"></i>
				</p>

				
				<p>
					<label for="uultra_<?php echo esc_attr($pos); ?>_meta_custom"><?php _e('Custom Meta Field','get-bookings-wp'); ?>
					</label> <input name="uultra_<?php echo esc_attr($pos); ?>C"
						type="text" id="uultra_<?php echo esc_attr($pos); ?>_meta_custom"
						value="<?php if (!isset($all_meta_for_user[$meta])) echo esc_attr($meta); ?>" />
					<i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Enter a custom meta key for this profile field if do not want to use a predefined meta field above. It is recommended to only use alphanumeric characters and underscores, for example my_custom_meta is a proper meta key.','get-bookings-wp'); ?>"></i>
				</p> <?php } ?>

				
                
                
                <p>
					<label for="uultra_<?php echo esc_attr($pos); ?>_name"><?php _e('Label / Name','get-bookings-wp'); ?>
					</label> <input name="uultra_<?php echo esc_attr($pos); ?>_name" type="text"
						id="uultra_<?php echo esc_attr($pos); ?>_name" value="<?php echo esc_attr($name) ?>" />
					<i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Enter the label / name of this field as you want it to appear in front-end (Profile edit/view)','get-bookings-wp'); ?>"></i>
				</p>
                
                

			<?php if ($type != 'separator' ) { ?>

				
				<p>
					<label for="uultra_<?php echo esc_attr($pos); ?>_tooltip"><?php _e('Tooltip Text','get-bookings-wp'); ?>
					</label> <input name="uultra_<?php echo esc_attr($pos); ?>_tooltip" type="text"
						id="uultra_<?php echo esc_attr($pos); ?>_tooltip"
						value="<?php echo esc_attr($tooltip) ?>" /> <i
						class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('A tooltip text can be useful for social buttons on profile header.','get-bookings-wp'); ?>"></i>
				</p> 
                
               <p>
               
               <label for="uultra_<?php echo esc_attr($pos); ?>_help_text"><?php _e('Help Text','get-bookings-wp'); ?>
                </label><br />
                    <textarea class="uultra-help-text" id="uultra_<?php echo esc_attr($pos); ?>_help_text" name="uultra_<?php echo esc_attr($pos); ?>_help_text" title="<?php _e('A help text can be useful for provide information about the field.','get-bookings-wp'); ?>" ><?php echo esc_attr($help_text); ?></textarea>
                    <i class="uultra-icon-question-sign uultra-tooltip2"
                                title="<?php _e('Show this help text under the profile field.','get-bookings-wp'); ?>"></i>
                              
               </p> 
				
				
				
                
               				
				<?php 
				if(!isset($can_edit))
				    $can_edit = '1';
				?>
				<p>
					<label for="uultra_<?php echo esc_attr($pos); ?>_can_edit"><?php _e('User can edit','get-bookings-wp'); ?>
					</label> <select name="uultra_<?php echo esc_attr($pos); ?>_can_edit"
						id="uultra_<?php echo esc_attr($pos); ?>_can_edit">
						<option value="1" <?php selected(1, $can_edit); ?>>
							<?php _e('Yes','get-bookings-wp'); ?>
						</option>
						<option value="0" <?php selected(0, $can_edit); ?>>
							<?php _e('No','get-bookings-wp'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Users can edit this profile field or not.','get-bookings-wp'); ?>"></i>
				</p> 
				
				<?php if (!isset($array['allow_html'])) { 
				    $allow_html = 0;
				} ?>
				<p>
					<label for="uultra_<?php echo esc_attr($pos); ?>_allow_html"><?php _e('Allow HTML','get-bookings-wp'); ?>
					</label> <select name="uultra_<?php echo esc_attr($pos); ?>_allow_html"
						id="uultra_<?php echo esc_attr($pos); ?>_allow_html">
						<option value="0" <?php selected(0, $allow_html); ?>>
							<?php _e('No','get-bookings-wp'); ?>
						</option>
						<option value="1" <?php selected(1, $allow_html); ?>>
							<?php _e('Yes','get-bookings-wp'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('If yes, users will be able to write HTML code in this field.','get-bookings-wp'); ?>"></i>
				</p> 
				
				
				
				<?php 
				if(!isset($required))
				    $required = '0';
				?>
				<p>
					<label for="uultra_<?php echo esc_attr($pos); ?>_required"><?php _e('This field is Required','get-bookings-wp'); ?>
					</label> <select name="uultra_<?php echo esc_attr($pos); ?>_required"
						id="uultra_<?php echo esc_attr($pos); ?>_required">
						<option value="0" <?php selected(0, $required); ?>>
							<?php _e('No','get-bookings-wp'); ?>
						</option>
						<option value="1" <?php selected(1, $required); ?>>
							<?php _e('Yes','get-bookings-wp'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Selecting yes will force user to provide a value for this field at registration and edit profile. Registration or profile edits will not be accepted if this field is left empty.','get-bookings-wp'); ?>"></i>
				</p> <?php } ?> <?php

				/* Show Registration field only when below condition fullfill
				1) Field is not private
				2) meta is not for email field
				3) field is not fileupload */
				if(!isset($private))
				    $private = 0;

				if(!isset($meta))
				    $meta = '';

				if(!isset($field))
				    $field = '';


				//if($type == 'separator' ||  ($private != 1 && $meta != 'user_email' ))
				if($type == 'separator' ||  ($private != 1 && $meta != 'user_email' ))
				{
				    if(!isset($show_in_register))
				        $show_in_register= 0;
						
					 if(!isset($show_in_widget))
				        $show_in_widget= 0;
				    ?>
				<p>
					<label for="uultra_<?php echo esc_attr($pos); ?>_show_in_register"><?php _e('Show on Registration Form','get-bookings-wp'); ?>
					</label> <select name="uultra_<?php echo esc_attr($pos); ?>_show_in_register"
						id="uultra_<?php echo esc_attr($pos); ?>_show_in_register">
						<option value="0" <?php selected(0, $show_in_register); ?>>
							<?php _e('No','get-bookings-wp'); ?>
						</option>
						<option value="1" <?php selected(1, $show_in_register); ?>>
							<?php _e('Yes','get-bookings-wp'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Show this profile field on the registration form','get-bookings-wp'); ?>"></i>
				</p>    
               
                
                 <?php } ?>
                 
			<?php if ($type != 'seperator' || $type != 'separator') { ?>

		  <?php if (in_array($field, array('select','radio','checkbox')))
				 {
				    $show_choices = null;
				} else { $show_choices = 'uultra-hide';
				
				
				} ?>

				<p class="uultra-choices <?php echo esc_attr($show_choices); ?>">
					<label for="uultra_<?php echo esc_attr($pos); ?>_choices"
						style="display: block"><?php _e('Available Choices','get-bookings-wp'); ?> </label>
					<textarea name="uultra_<?php echo esc_attr($pos); ?>_choices" type="text" id="uultra_<?php echo esc_attr($pos); ?>_choices" class="large-text"><?php if (isset($array['choices'])) echo trim(esc_attr($choices)); ?></textarea>
                    
                    <?php
                    
					if($getbookingwp->uultra_if_windows_server())
					{
						_e('<strong>PLEASE NOTE: </strong>Enter values separated by commas, example: 1,2,3. The choices will be available for front end user to choose from.');					
					}else{
						
						_e('<strong>PLEASE NOTE:</strong> Enter one choice per line please. The choices will be available for front end user to choose from.');
					
					
					}
					
					?>
                    <p>
                    
                    
                    </p>
					<i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Enter one choice per line please. The choices will be available for front end user to choose from.','get-bookings-wp'); ?>"></i>
				</p> <?php //if (!isset($array['predefined_loop'])) $predefined_loop = 0;
				
				if (!isset($predefined_options)) $predefined_options = 0;
				
				 ?>

				<p class="uultra_choices <?php echo esc_attr($show_choices); ?>">
					<label for="uultra_<?php echo esc_attr($pos); ?>_predefined_options" style="display: block"><?php _e('Enable Predefined Choices','get-bookings-wp'); ?>
					</label> 
                    <select name="uultra_<?php echo esc_attr($pos); ?>_predefined_options"id="uultra_<?php echo esc_attr($pos); ?>_predefined_options">
						<option value="0" <?php selected(0, $predefined_options); ?>>
							<?php _e('None','get-bookings-wp'); ?>
						</option>
						<option value="countries" <?php selected('countries', $predefined_options); ?>>
							<?php _e('List of Countries','get-bookings-wp'); ?>
						</option>
                        
                        <option value="age" <?php selected('age', $predefined_options); ?>>
							<?php _e('Age','get-bookings-wp'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('You can enable a predefined filter for choices. e.g. List of countries It enables country selection in profiles and saves you time to do it on your own.','get-bookings-wp'); ?>"></i>
				</p>

				
				<div class="clear"></div> 
				
				<?php } ?>


  <div class="getbwp-ultra-success getbwp-notification" id="getbwp-sucess-fields-<?php echo esc_attr($pos); ?>"><?php _e('Success ','get-bookings-wp'); ?></div>
				<p>
                
               
                 
				<input type="button" name="submit"	value="<?php _e('Update','get-bookings-wp'); ?>"						class="button button-primary getbwp-btn-submit-field"  data-edition="<?php echo esc_attr($pos); ?>" /> 
                   <input type="button" value="<?php _e('Cancel','get-bookings-wp'); ?>"
						class="button button-secondary getbwp-btn-close-edition-field" data-edition="<?php echo esc_attr($pos); ?>" />
				</p>
                
      <?php
	  
	  die();
		
	}
	
	public function getbwp_create_standard_form_fields ($form_name ){		
	
		/* These are the basic profile fields */
		$fields_array = array(
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
		if (!get_option($form_name)){
			if($form_name!=""){
				update_option($form_name,$fields_array);			
			}			
		}			
	}
	
	/*Loads all field list */	
	function getbwp_reload_custom_fields_set (){
		
		global $getbookingwp;
		
		$custom_form = sanitize_text_field($_POST["getbwp_custom_form"]);		
		
		if($custom_form!=""){
			//check if fields have been added			
			$custom_form = 'getbwp_profile_fields_'.$custom_form;
			
			if (!get_option($custom_form)){
				$this->getbwp_create_standard_form_fields($custom_form);									
				$fields = get_option($custom_form);
				
			}else{
				$fields = get_option($custom_form);
			}
		
		}else{ //use the default registration from
			$fields = get_option('getbwp_profile_fields');
		}
		
		ksort($fields);		
		
		$i = 0;
		foreach($fields as $pos => $array){
		    extract($array); $i++;

		    if(!isset($required))
		        $required = 0;

		    if(!isset($fonticon))
		        $fonticon = '';
				
				
			if ($type == 'seperator' || $type == 'separator') {
			   
				$class = "separator";
				$class_title = "";
				$class_h3 = "";
			} else {
			  
				$class = "profile-field";
				$class_title = "profile-field";
				$class_h3 = "profile-h3";
			}
		    ?>
            
          <li class="getbwp-profile-fields-row <?php echo esc_attr($class_title)?>" id="<?php echo esc_attr($pos); ?>">
            
            
            <div class="heading_title  <?php echo esc_attr($class)?>">
            
            <h3 class="<?php echo esc_attr($class_h3)?>">
            <?php

			if ($type == 'separator'){

				_e('<span class="getbwp-field-separator"><i class="fa fa-list"></i> </span>');
			}
			
			if (isset($array['name']) && $array['name']){	

			    echo  esc_attr($array['name']);
			}
			?>
            
            <?php
			if ($type == 'separator') {
				
			     _e(' - Separator','get-bookings-wp');
				
			} else {				
			   
				
			}
			?>
            
            </h3>
            
            
              <div class="options-bar">
             
			  		<p>             
                    
					<a class="button button-secondary getbwp-delete-profile-field-btn" data-field="<?php echo esc_attr($pos); ?>"><i class="fa fa-trash-o"></i> </a> 
					<a class="button getbwp-btn-edit-field button-primary" data-edition="<?php echo esc_attr($pos); ?>"><i class="fa fa-edit fa-lg"></i> </a>
                    </p>
            
             </div>          
            
          

            </div>
            
             
             <div class="getbwp-ultra-success getbwp-notification" id="getbwp-sucess-delete-fields-<?php echo esc_attr($pos); ?>"><?php _e('Success! This field has been deleted ','get-bookings-wp'); ?></div>
            
           
        
          <!-- edit field -->
          
          <div class="user-ultra-sect-second uultra-fields-edition user-ultra-rounded"  id="getbwp-edit-fields-bock-<?php echo esc_attr($pos); ?>">
        
          </div>
          
          
          <!-- edit field end -->

       </li>







	<?php
	
	}
		
		die();
		
	
	}
		
	// update settings
    function update_settings() 	{
		global $getbookingwp;
		foreach($_POST as $key => $value){

            if ($key != 'submit'){
				if (strpos($key, 'html_') !== false) {
                      
                }else{
					
					 
                 }
					
				$this->getbwp_set_option($key, $value) ; 
					
				//special setting for page
				if($key=="getbwp_my_account_page"){					
					update_option('getbwp_my_account_page',$value);	
				} 
            }
        }	

		  
		 if ( isset ( $_GET['tab'] ) ) {			 
			  $current = sanitize_text_field($_GET['tab']);				
          } else {
               $current = sanitize_text_field($_GET['page']);				
          }	
            
		$special_with_check = $this->get_special_checks($current);
         
        foreach($special_with_check as $key){         
            
            if(!isset($_POST[$key])){	

                $value= '0';
					
			} else {
				 
				$value= sanitize_text_field($_POST[$key]);
			}	 	
			
			$this->getbwp_set_option($key, $value) ;  
        }
         
      $this->options = get_option('getbwp_options');

	  	$text_sett = __('Settings saved.','get-bookings-wp');
		echo  wp_kses( '<div class="updated"><p><strong>'.$text_sett.'</strong></p></div>',  $getbookingwp->allowed_html);
    }
	
	public function get_special_checks($tab) 
	{
		$special_with_check = array();
		
		if($tab=="settings")
		{				
		
		 $special_with_check = array('uultra_loggedin_activated', 'private_message_system','redirect_backend_profile','redirect_backend_registration', 'redirect_registration_when_social','redirect_backend_login', 'social_media_fb_active', 'social_media_linked_active', 'social_media_yahoo', 'social_media_google', 'twitter_connect', 'instagram_connect', 'gateway_free_success_active', 'mailchimp_active', 'mailchimp_auto_checked',  'aweber_active', 'aweber_auto_checked');
		 
		}elseif($tab=="gateway"){
			
			 $special_with_check = array('gateway_paypal_active', 'gateway_bank_active', 'gateway_authorize_active', 
			 'gateway_authorize_success_active' ,'gateway_stripe_active', 'gateway_stripe_success_active' ,
			 'gateway_bank_success_active', 'gateway_free_success_active',  
			 'gateway_paypal_success_active' ,  
			 'appointment_cancellation_active', 			
			 'gateway_paypal_cancel_active');
		
		}elseif($tab=="mail"){
			
			 $special_with_check = array('getbwp_smtp_mailing_return_path', 'getbwp_smtp_mailing_html_txt');
		 
		}
		
		
		if($tab=="getbwp-reminders")
		{				
		
			 $special_with_check = array('notifications_sms_reminder_1');		
		 
		}

		if($tab=="getbwp-zooom")
		{				
		
			 $special_with_check = array( 'zoom_active');		
		 
		}
	
	return  $special_with_check ;
	
	}	
	
	public function do_valid_checks()
	{
		
		global $getbookingwp, $getbwpcomplement, $getbwpultimate ;
		
		$va = get_option('getbwp_c_key');
		
		if(isset($getbwpcomplement))		
		{		
			if($va=="")
			{
				if(isset($getbwpultimate)) //no need to validate
				{
					$this->valid_c = "";						
				
				}else{
					
					$this->valid_c = "no";				
				
				}				
				//
					
			}
		
		}
	
	
	}
	
	public function getbwp_vv_c_de_a () 
	{		
		global $getbookingwp, $wpdb ;		
		 	
		$p = sanitize_text_field($_POST["p_s_le"]);			
		$domain = sanitize_text_field($_SERVER['SERVER_NAME']);		
		$server_add = sanitize_text_field($_SERVER['SERVER_ADDR']);		
		
		$url = getbwp_pro_url."check_l_p.php";			
		
		$response = wp_remote_post(
            $url,
            array(
                'body' => array(
                    'd'   => $domain,
                    'server_ip'     => $server_add,
                    'sial_key' => $p,
					'action' => 'validate',
					
                )
            )
        );

		//print_r($response);

		
		
		$response = json_decode($response["body"]);
		
		$message =$response->{'message'}; 
		$result =$response->{'result'}; 
		$expiration =$response->{'expiration'};
		$serial =$response->{'serial'};
		
		//validate
		
		if ( is_multisite() ) // See if being activated on the entire network or one blog
		{		
			
	 
			// Get this so we can switch back to it later
			$current_blog = $wpdb->blogid;
			// For storing the list of activated blogs
			$activated = array();
			
			// Get all blogs in the network and activate plugin on each one
			
			$args = array(
				'network_id' => $wpdb->siteid,
				'public'     => null,
				'archived'   => null,
				'mature'     => null,
				'spam'       => null,
				'deleted'    => null,
				'limit'      => 100,
				'offset'     => 0,
			);
			$blog_ids = wp_get_sites( $args ); 
			
		
			foreach ($blog_ids as $key => $blog)
			{
				$blog_id = $blog["blog_id"];

				switch_to_blog($blog_id);				
				
				if($result =="OK")
				{
					update_option('getbwp_c_key',$serial );
					update_option('getbwp_c_expiration',$expiration );
					
					$html = '<div class="getbwp-ultra-success">'. __("Congratulations!. Your copy has been validated", 'get-bookings-wp').'</div>';
				
				}elseif($result =="EXP"){
					
					update_option('getbwp_c_key',"" );
					update_option('getbwp_c_expiration',$expiration );
					
					$html = '<div class="getbwp-ultra-error">'. __("We are sorry the serial key you have used has expired", 'get-bookings-wp').'</div>';
				
				}elseif($result =="NO"){
					
					update_option('getbwp_c_key',"" );
					update_option('getbwp_c_expiration',$expiration );
					
					$html = '<div class="getbwp-ultra-error">'. __("We are sorry your serial key is not valid", 'get-bookings-wp').'</div>';
				
				}
				
				
			} //end for each
			

			switch_to_blog($current_blog); 
			
			
		}else{
			
			//this is not a multisite
			
			if($result =="OK")
			{
				update_option('getbwp_c_key',$serial );
				update_option('getbwp_c_expiration',$expiration );				
				$html = '<div class="getbwp-ultra-success">'. __("Congratulations!. Your copy has been validated", 'get-bookings-wp').'</div>';
			
			}elseif($result =="EXP"){
				
				update_option('getbwp_c_key',"" );
				update_option('getbwp_c_expiration',$expiration );
				
				$html = '<div class="getbwp-ultra-error">'. __("We are sorry the serial key you have used has expired", 'get-bookings-wp').'</div>';
			
			}elseif($result =="NO"){
				
				update_option('getbwp_c_key',"" );
				update_option('getbwp_c_expiration',$expiration );
				
				$html = '<div class="getbwp-ultra-error">'. __("We are sorry your serial key is not valid", 'get-bookings-wp').'</div>';
			
			}		
		
		}
		
		//
		echo wp_kses($html, $getbookingwp->allowed_html);	 
		
		die();
		
	}
	
	function include_tab_content() {
		
		global $getbookingwp, $wpdb, $getbwpcomplement ;
		
		$screen = get_current_screen();
		
		if( strstr($screen->id, $this->slug ) ) 
		{
			if ( isset ( $_GET['tab'] ) ) 
			{
				$tab = sanitize_text_field($_GET['tab']);
				
			} else {
				
				$tab = $this->default_tab;
			}

			$this->current_tab = $tab;
			
			if($this->valid_c=="" )
			{
				require_once (getbookingpro_path.'admin/tabs/'.$tab.'.php');			
			
			}else{ //no validated
				
				$tab = "licence";				
				require_once (getbookingpro_path.'admin/tabs/'.$tab.'.php');
				
			}

			
			
			
		}
	}
	
	function reset_email_template()	{
		
		$template = sanitize_text_field($_POST['email_template']);
		$new_template = $this->get_email_template($template);
		$this->getbwp_set_option($template, $new_template);
		die();
		
	}
	
	public function display_ultimate_validate_copy () 
	{
		global $getbookingwp;
			
		$res_message  = get_option( 'getbwp_pro_improvement_13' );		
		if($res_message=="" )
		{
		
			$message = '<div id="message" class="updated buppro-message wc-connect">
	<a class="buppro-message-close notice-dismiss" href="#" message-id="13"> '.__('Dismiss','get-bookings-wp').'</a>

	<p><strong>Get Bookings Wp Updates:</strong> – We highly recommend you creating a serial number for your domain which will allow you to update your plugin automatically.</p>
	
	<p class="submit">
		
		<a href="?page=getbookingswp&tab=licence" class="button-secondary" > '.__('Validate your Copy','get-bookings-wp').'</a>
	</p>
</div>';

		echo wp_kses($message, $getbookingwp->allowed_html);			

		
		}
		
		
		
		
	}
	
	function admin_page() {

		global $getbookingwp; $getbwpcomplement;

		$va = get_option('getbwp_c_key');

		if ( isset ( $_GET['tab'] ) ) {
			$tab = sanitize_text_field($_GET['tab']);				
		} else {				
			$tab = $this->default_tab;
		}
		
		
		if (isset($_POST['update_settings']) ) {
            $this->update_settings();
        }
		
		if (isset($_POST['update_settings']) && isset($_POST['reset_email_template']) && $_POST['reset_email_template']=='yes' && $_POST['email_template']!='') {
			$txt = '<div class="updated"><p><strong>'.__('Email Template has been restored.','get-bookings-wp').'</strong></p></div>';
			echo wp_kses($txt, $getbookingwp->allowed_html);
		}
		
		
		if (isset($_POST['update_getbwp_slugs']) && $_POST['update_getbwp_slugs']=='getbwp_slugs'){
           $getbookingwp->create_rewrite_rules();    
		   $txt =       __('Rewrite Rules were Saved.','get-bookings-wp');
		   $txt =  '<div class="updated"><p><strong>'.$txt.'</strong></p></div>';
		   echo wp_kses($txt, $getbookingwp->allowed_html);
        }	
		
			
	?>
	
		<div class="wrap <?php echo esc_attr($this->slug); ?>-admin"> 

		<?php if($tab !='welcome' && $tab !='pro'){ ?>            
            
            <div class="wrap getbwp-top-main-bar">
				<div class="getbwp-top-main-texts">
				<div class="getbwp-top-main-plugin-name">

					<?php
					$urlText ='<b>G</b>et<b>B</b>ookings<b>W</b>p';
					_e('<a href="?page=getbookingswp">'.$urlText.'</a>');?>
				</div>			

					<ul>
						<li>
							<a href="?page=getbookingswp"><i class="fa fa-home fa-2x"></i><p><?php _e('DASHBOARD','get-bookings-wp');?></p></a>
						</li>
					    <li>   
							<a href="?page=getbookingswp&tab=calendar"><i class="fa fa-calendar fa-2x"></i><p><?php _e('CALENDAR','get-bookings-wp');?></p></a>
						</li>

						<li>   
							<a href="?page=getbookingswp&tab=appointments"><i class="fa fa-list fa-2x"></i><p><?php _e('BOOKINGS','get-bookings-wp');?></p></a>
						</li>

						<li>   
							<a href="?page=getbookingswp&tab=users"><i class="fa fa-users fa-2x"></i><p><?php _e('STAFF','get-bookings-wp');?></p></a>
						</li>

						<li>   
							<a href="?page=getbookingswp&tab=orders"><i class="fa fa-dollar fa-2x"></i><p><?php _e('ORDERS','get-bookings-wp');?></p></a>
						</li>

						<li>   
							<a href="?page=getbookingswp&tab=help"><i class="fa fa-question fa-2x"></i><p><?php _e('GET HELP','get-bookings-wp');?></p></a>
						</li>

						<?php if($va==''){?>

						<li class="pro">   
							<a href="https://getbookingswp.com/pricing"><i class="fa fa-thumbs-up fa-2x pro"></i><p><?php _e('GO PRO','get-bookings-wp');?></p></a>
						</li>

						<?php    }?>

					
							
						
					</ul>
					
				</div>
				                
            </div>

			<?php  }?>
            

			<div class="<?php echo esc_attr($this->slug); ?>-admin-contain">        
            
			
				<?php $this->include_tab_content(); ?>				
				<div class="clear"></div>
				
			</div>
			
		</div>

	<?php }

}

$key = "buupadmin";
$this->{$key} = new GetBookingsWPAdmin();