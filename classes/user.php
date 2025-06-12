<?php
class GetBookingsWPUser
{
	var $sys_prefix = 'getbwp';
	
	function __construct() 
	{
				
		$this->ini_module();
		
		add_action( 'wp_ajax_getbwp_get_new_staff', array( &$this, 'getbwp_get_new_staff' ));
		add_action( 'wp_ajax_getbwp_get_staff_details_ajax', array( &$this, 'getbwp_get_staff_details_ajax' ));
		add_action( 'wp_ajax_getbwp_add_staff_confirm', array( &$this, 'getbwp_add_staff_confirm' ));
		add_action( 'wp_ajax_getbwp_add_client_confirm', array( &$this, 'getbwp_add_client_confirm' ));
		add_action( 'wp_ajax_getbwp_update_staff_services', array( &$this, 'getbwp_update_staff_services' ));		
		add_action( 'wp_ajax_getbwp_autocomple_clients_tesearch', array( &$this, 'get_users_auto_complete' ));
		add_action( 'wp_ajax_getbwp_get_staff_list_admin_ajax', array( &$this, 'get_staff_list_admin_ajax' ));
		add_action( 'wp_ajax_getbwp_get_staff_details_admin', array( &$this, 'get_staff_details_admin_ajax' ));
		add_action( 'wp_ajax_getbwp_update_staff_admin', array( &$this, 'getbwp_update_staff_admin' ));
		add_action( 'wp_ajax_getbwp_delete_staff_admin', array( &$this, 'getbwp_delete_staff_admin' ));
		add_action( 'wp_ajax_getbwp_ajax_upload_avatar', array( &$this, 'getbwp_ajax_upload_avatar' ));
		add_action( 'wp_ajax_getbwp_crop_avatar_user_profile_image', array( &$this, 'getbwp_crop_avatar_user_profile_image' ));
		add_action( 'wp_ajax_getbwp_delete_user_avatar', array( &$this, 'delete_user_avatar' ));
		add_action( 'wp_ajax_getbwp_disconnect_user_gcal', array( &$this, 'disconnect_user_gcal' ));
		add_action( 'wp_ajax_getbwp_disconnect_user_zoom', array( &$this, 'disconnect_user_zoom' ));
		
		add_action( 'wp_ajax_getbwp_update_user_account_settings', array( &$this, 'update_user_account_settings' ));
		
		add_action( 'wp_ajax_getbwp_set_default_gcal_staff', array( &$this, 'set_default_google_calendar' ));


	}
	
	public function ini_module()
	{
		global $wpdb;
		
		   
		
	}
	
	public function get_user_info()
	{
		$current_user = wp_get_current_user();
		return $current_user;
	}
	
	public function disconnect_user_gcal(){
		global $wpdb, $getbookingwp, $getbwpcomplement;	
		
		$staff_id = sanitize_text_field($_POST['user_id']);		
		delete_user_meta($staff_id, 'google_cal_access_token');	
		delete_user_meta($staff_id, 'google_calendar_default');	
		die();
	}

	public function disconnect_user_zoom(){
		global $wpdb, $getbookingwp, $getbwpcomplement;	
		
		$staff_id = sanitize_text_field($_POST['user_id']);		
		delete_user_meta($staff_id, 'getbwp_zoom_access_token');	
		delete_user_meta($staff_id, 'getbwp_zoom_refresh_access_token');	
		die();
	}
	
	
	
	public function getbwp_get_new_staff()
	{	
	
		global $wpdb, $getbookingwp, $getbwpcomplement;	
		
		
		$display = true;	
				
		if(!isset($getbwpcomplement))
		{
			//check for amount of staff members
			$total = $this->get_staff_members_total();				
			if($total!=0)
			{					
				$display = false;
			}			
		}
		
		
		$html = '';
		
		$html .= '<div class="getbwp-sect-adm-edit">';
		
		if($display)
		{
			$html .= '<p>'.__('Here you can add new staff members. Please fill in with the full name and email then click on the Add button.','get-bookings-wp').'</p>';
		
		}
		
		$html .= '<div class="getbwp-edit-service-block">';
		
		
		
		if($display){
			
				$html .= '<div class="getbwp-field-separator"><label for="getbwp-box-title">'.__('Full Name','get-bookings-wp').':</label><input type="text" name="staff_name" id="staff_name" class="getbwp-common-textfields" /></div>';				
				
				$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Email','get-bookings-wp').':</label><input type="text" name="staff_email" id="staff_email" class="getbwp-common-textfields" /></div>';					
				$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Username','get-bookings-wp').':</label><input type="text" name="staff_nick" id="staff_nick" class="getbwp-common-textfields" /></div>';			
			
				$html .= '<div class="getbwp-field-separator" id="getbwp-err-message"></div>';	
		}else{
			
			$html .= __( "If you need to add more than one staff member, please consider upgrading your plugin. The lite version allows you to have only one Staff Member. ", 'get-bookings-wp' ).'<a href="https://getbookingswp.com/pricing-compare-plans" target="_blank">Click here</a> to upgrade your plugin.';
			
		}
			
			
			$html .= '</div>';
		
		$html .= '</div>';		
		

		echo wp_kses($html, $getbookingwp->allowed_html);
		die();		
	
	}
	
	function get_staff_members_total()
	{
		global $getbookingwp;
		$uultra_combined_search = '';
		$relation = "AND";
		$args= array('keyword' => $uultra_combined_search ,  'relation' => $relation,  'sortby' => 'ID', 'order' => 'DESC');
		$users = $this->get_staff_filtered($args);
		
		$total = $users['total'];
		if(!isset($users['total'])){$total=0;}
		
		return $total;
	}
	
	public function getbwp_get_staff_details_ajax()
	{
		global $wpdb, $getbookingwp;

		session_start();
	
		$staff_id = sanitize_text_field($_POST['staff_id'])	;		
		$_SESSION["current_staff_id"] =$staff_id ;		

		echo wp_kses($this->getbwp_get_staff_details($staff_id), $getbookingwp->allowed_html);		
		die();
	
	}
	
	 public function getAppointmentsForFC( DateTime $start_date, DateTime $end_date, $staff_id = NULL )
    {
		
		global $wpdb, $getbookingwp;
				
		$appointments_re = array();
		$staff_service_details = array();
		
		$time_format =  $getbookingwp->service->get_time_format();
		
		$what_display_in_calendar =  $getbookingwp->get_option('what_display_in_admin_calendar');
       		
		$sql =  'SELECT appo.*, usu.*, serv.*	 FROM ' . $wpdb->prefix . 'getbwp_bookings appo  ' ;				
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";	
		$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_services serv ON (serv.service_id = appo.booking_service_id)";		
		$sql .= " WHERE (DATE(appo.booking_time_from) BETWEEN %s AND  %s ) AND appo.booking_staff_id = %s AND 
		serv.service_id = appo.booking_service_id AND  appo.booking_status= '1' ORDER BY appo.booking_id desc ";	
			
		$sql = $wpdb->prepare($sql,array(
										$start_date->format( 'Y-m-d' ), 
										$end_date->format( 'Y-m-d' ), 
										$staff_id) 
									);	

		$appointments = $wpdb->get_results($sql );
			
		
        foreach ( $appointments as  $appointment ) {
            $desc = '';
			
			$key = $appointment->booking_id;			
			$staff_service_details = $this->get_staff_service_rate( $staff_id, $appointment->service_id );			
			$appointment_capacity = $staff_service_details['capacity'];
			
			
			$availability_cap_groups = 0;
			
			$day_time_slot = $appointment->booking_time_from;
			$staff_time_slots = array();
			
			$booking_totals = array();
			$day=$appointment->booking_time_from;	
			$day_to=$appointment->booking_time_to;	
					
			$booking_totals = $getbookingwp->service->get_total_bookings($staff_id, $appointment->service_id, $day, $day_to);
			
			$availability_cap_groups = $booking_totals['total_groups'];
			
			
			if($what_display_in_calendar==1 || $what_display_in_calendar==''){ //staff data
			
				$display_name = sanitize_text_field( $appointment->display_name );
				$display_email = sanitize_text_field( $appointment->user_email );
				
			}else{ //client data
			
				
				$client = get_user_by( 'id', $appointment->booking_user_id );			
				$display_name = sanitize_text_field( $client->display_name );
				$display_email = sanitize_text_field( $client->user_email );
				
			}	
			
			//font styles
			$font_styles = '';

			if($appointment->service_font_color!=''){

				$font_styles = 'style="color:'.$appointment->service_font_color.'"';

			}
			
			
            if ( $appointment_capacity == 1 ) 
			{
                				
				 $desc .= '<div '.$font_styles.'> ' . $appointment->service_title . '</div>'; 
                 $desc .= '<div '.$font_styles.'>' . $display_name . '</div>';
				 $desc .= '<div '.$font_styles.'>' . $display_email . '</div>';     	
				
				
            } else {
				
                $desc .= sprintf( '<div '.$font_styles.'>%s %s</div>', __( 'Signed up', 'get-bookings-wp' ), $availability_cap_groups);
                $desc .= sprintf( '<div '.$font_styles.'>%s %s</div>', __( 'Capacity', 'get-bookings-wp' ), $appointment_capacity );
				
            }
            
            $extra_prop = array('desc'     => $desc,
                                'staffId'  => $staff_id);

            $appointments_re[ $key ] = array(
                'id'       => $appointment->booking_id,
                'start'    => $appointment->booking_time_from	,
                'end'      => $appointment->booking_time_to,
               // 'title'    => $appointment->service_title ? esc_html( $appointment->service_title ) : __( 'Untitled', 'get-bookings-wp' ),
                'title'    => ' ',
                
              //  'desc'     => $desc,
                'color'    =>   $appointment->service_color ?  $appointment->service_color : 'gray',
				'textColor'    =>   $appointment->service_font_color ?  $appointment->service_font_color : '#fff',				
                'staffId'  => $staff_id,
                'resourceId'  => $staff_id,
                'extendedProps'  => $extra_prop
            );
        }

        return $appointments_re;
    }
	
	public function get_working_hours($staff_id)
	{
		global $wpdb, $getbookingwp;
		$hourly = array();
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'getbwp_staff_availability  WHERE avail_staff_id = %s ' ;
		$sql = $wpdb->prepare($sql,array($staff_id));
		$rows = $wpdb->get_results($sql);
		
		if ( !empty( $rows ) )
		{
			foreach ( $rows as $row )
			{
				$hourly[$row->avail_day] = array(
                'day'       => $row->avail_day,
                'start_time'    => 	date('H:i', strtotime($row->avail_from)),
                'end_time'      =>  date('H:i', strtotime($row->avail_to))
            );
				
			}							
		
		}
		
		return $hourly;
	
	
	}
	
	
	
	public function getbwp_update_staff_services()
	{
		$staff_id = sanitize_text_field($_POST['staff_id'])	;

			
		$service_list = array();
		$modules = sanitize_text_field($_POST["service_list"]); 		
		
		//delete all services from this staff member
		if($staff_id!='')
		{
			$this->getbwp_delete_staff_services($staff_id);
		}
		
		if($modules!="" && $staff_id!='')
		{
			$modules =rtrim($modules,"|");
			$service_list = explode("|", $modules);
			
						
			foreach($service_list as  $service)
			{
				$details = explode("-", $service);				
			
				$service_id = $details[0];
				$service_price= $details[1];
				$service_qty= $details[2];				
							
				$this->getbwp_assign_staff_services($staff_id, $service_id, $service_price, $service_qty);
			
			}
		}
		
		die();
	}
	
	function get_me_wphtml_editor($meta, $content)
	{
		// Turn on the output buffer
		ob_start();
		
		$editor_id = $meta;				
		$editor_settings = array('media_buttons' => false , 'textarea_rows' => 15 , 'teeny' =>true); 
							
					
		wp_editor( $content, $editor_id , $editor_settings);
		
		// Store the contents of the buffer in a variable
		$editor_contents = ob_get_clean();
		
		// Return the content you want to the calling function
		return $editor_contents;	
	
	}
	
	
	
	public function getbwp_delete_staff_services($staff_id)
	{
		global $wpdb;		
		$sql = 'DELETE FROM ' . $wpdb->prefix . 'getbwp_service_rates  WHERE rate_staff_id="'.(int)$staff_id.'" ';
		$wpdb->query($sql);		
	}
	
	public function getbwp_assign_staff_services($staff_id, $service_id, $service_price, $service_qty)
	{
		global $wpdb;
		
		
		$new_record = array(
						'rate_id'        => NULL,
						'rate_staff_id' => $staff_id,
						'rate_service_id' => $service_id, 
						'rate_price' => $service_price,
						'rate_capacity'   => $service_qty						
						
						
						
					);

				
					
		$wpdb->insert( $wpdb->prefix . 'getbwp_service_rates', $new_record, array( '%d', '%s', '%s', '%s', '%s'));
						
	}
	
	
	
	
	
	public function getbwp_add_staff_confirm()
	{
		global $blog_id, $getbookingwp;

		$error = '';
		$staff_name = sanitize_text_field($_POST['staff_name'])	;
		$email = sanitize_text_field($_POST['staff_email']);
		$user_name = sanitize_text_field($_POST['staff_nick']);				
		$user_pass = wp_generate_password( 12, false);		
		
		/* Create account, update user meta */
		$sanitized_user_login = sanitize_user($user_name);
		
		if(email_exists($email))
		{			
			
			//$error .=__('<strong>ERROR:</strong> This email is already registered. Please choose another one.','get-bookings-wp');
		
		}elseif(username_exists($user_name)){
			
			$error .=__('<strong>ERROR:</strong> This username is already registered. Please choose another one.','get-bookings-wp');
		
		}elseif($staff_name=='' || $email=='' || $user_name==''){
			
			$error .=__('<strong>ERROR:</strong> All fields are mandatory.','get-bookings-wp');		
		
		}
		
		if($error=='')
		{
			
			if(email_exists($email))
			{
				
				/* We Update Already user */
				$user = get_user_by( 'email', $email );
				$user_id = $user->ID;
				update_user_meta ($user_id, 'getbwp_is_staff_member',1);
				
				//check multisite				
				if ( is_multisite() ) 
				{					
					if ($user_id && !is_user_member_of_blog($user_id, $blog_id)) 
					{
						//Exist's but is not user to the current blog id
						$result = add_user_to_blog( $blog_id, $user_id, 'subscriber');

   					 }	
				} 
			
			}else{
				
				/* We create the New user */
				$user_id = wp_create_user( $sanitized_user_login, $user_pass, $email);
				
				if($user_id)
				{
					update_user_meta ($user_id, 'getbwp_is_staff_member',1);
					wp_update_user( array('ID' => $user_id, 'display_name' => esc_attr($staff_name)) );
				
				}				
			
			}				
			
			
			echo esc_attr($user_id);		
		
		}else{
			
			echo wp_kses($error, $getbookingwp->allowed_html);		
		
		}
		
		die();
	
	}
	
	public function getbwp_add_client_confirm()
	{
		global $getbookingwp;

		$user_id = '';
		$client_name = sanitize_text_field($_POST['client_name'])	;
		$client_last_name = sanitize_text_field($_POST['client_last_name']);
		$email = sanitize_text_field($_POST['client_email']);
		
		$user_name = strtolower($client_name.$this->genRandomString());		
		
		$user_pass = wp_generate_password( 12, false);		
		
		/* Create account, update user meta */
		$sanitized_user_login = sanitize_user($user_name);
		
		if(email_exists($email))
		{			
			
			$error .=__('<strong>ERROR:</strong> This email is already registered. Please choose another one.','get-bookings-wp');
		
		}elseif(username_exists($user_name)){
			
			$error .=__('<strong>ERROR:</strong> This username is already registered. Please choose another one.','get-bookings-wp');
		
		}elseif($client_name=='' || $email=='' || $client_last_name==''){
			
			$error .=__('<strong>ERROR:</strong> All fields are mandatory.','get-bookings-wp');		
		
		}
		
		if($error=='')
		{			
			/* We create the New user */
			$user_id = wp_create_user( $sanitized_user_login, $user_pass, $email);
			
			if($user_id)
			{
				$display_name =$client_name.' '.$client_last_name ;
				$respon = $display_name.' ('.$email.')';
				wp_update_user( array('ID' => $user_id, 'display_name' => esc_attr($display_name)) );
			
			}

			$respon = wp_kses($respon, $getbookingwp->allowed_html);			
			$response = array('response' => 'OK', 'content' => $respon, 'user_id' => $user_id);	
		
		}else{

			$error = wp_kses($error, $getbookingwp->allowed_html);			
			$response = array('response' => 'ERROR', 'content' => $error, 'user_id' => $user_id);	
		
		}	
		
		
		echo json_encode($response) ;	
			
		
		die();
	
	}
	
	public function getbwp_update_staff_admin()
	{
		$staff_id = sanitize_text_field($_POST['staff_id'])	;
		$staff_name = sanitize_text_field($_POST['display_name'])	;
		$reg_telephone = sanitize_text_field($_POST['reg_telephone']);
        $u_profession = sanitize_text_field($_POST['u_profession']);
		
		$email = sanitize_text_field($_POST['reg_email']);
		$email2 = sanitize_text_field($_POST['reg_email2']);
		
		
		if($email=='')
		{
			$error .=__('<strong>ERROR:</strong> Please input an email address.','get-bookings-wp');			
		
				
		}elseif($staff_name==''){
			
			$error .=__('<strong>ERROR:</strong> Please input a Full Name.','get-bookings-wp');		
		
		}
		
		if($email!=$email2)
		{
			if(email_exists($email))
			{
				$error .=__('<strong>ERROR:</strong> This email is already registered. Please choose another one.','get-bookings-wp');
			
			}else{
				
				wp_update_user( array('ID' => $staff_id, 'user_email' => esc_attr($email)) );
				
			}
		
		}	
		
		if($error=='')
		{			
						
			if($staff_id)
			{
				update_user_meta ($staff_id, 'reg_telephone',$reg_telephone);
                update_user_meta ($staff_id, 'u_profession',$u_profession);
                
				update_user_meta ($staff_id, 'display_name',$staff_name);
				wp_update_user( array('ID' => $staff_id, 'display_name' => esc_attr($staff_name)) );
				delete_user_meta ($staff_id, 'getbwp_is_client'	);
				
				
			
			}
			
			echo __('<strong>Done!</strong>','get-bookings-wp');			;		
		
		}else{
			
			echo wp_kses($error, $getbookingwp->allowed_html)		;		
		
		}
		
			
		
		die();
	
	}
	
	public function getbwp_delete_staff_admin()
	{
		global $wpdb,  $getbookingwp;
		
		require_once(ABSPATH. 'wp-admin/includes/user.php' );
		
		$html = '';		
		
		//close
		$user_to_delete = sanitize_text_field($_POST["staff_id"]);
		
		if(!is_super_admin( $user_to_delete ))
		{
			if ( current_user_can( 'manage_options' ) ) 
			{
				//delete meta data		
				$sql = 'DELETE FROM ' . $wpdb->prefix . 'usermeta WHERE user_id = "'.$user_to_delete.'" ' ;			
				$wpdb->query( $sql );
				
				//delete availability
				$sql = 'DELETE FROM ' . $wpdb->prefix . 'getbwp_staff_availability WHERE avail_staff_id = "'.$user_to_delete.'" ' ;			
				$wpdb->query( $sql );
							
				//delete breaks
				$sql = 'DELETE FROM ' . $wpdb->prefix . 'getbwp_staff_availability_breaks WHERE break_staff_id = "'.$user_to_delete.'" ' ;			
				$wpdb->query( $sql );
				
				//delete rates
				$sql = 'DELETE FROM ' . $wpdb->prefix . 'getbwp_service_rates WHERE rate_staff_id = "'.$user_to_delete.'" ' ;			
				$wpdb->query( $sql );
				
				//delete user					
				wp_delete_user( $user_to_delete );	
				delete_user_meta ($user_to_delete, 'getbwp_is_staff_member'	);	
				delete_user_meta ($user_to_delete, 'getbwp_is_client'	);	
					
				$html  = $this->get_first_staff_on_list();
			
			}
			
			
		}else{
			
			delete_user_meta ($user_to_delete, 'getbwp_is_staff_member'	);	
			delete_user_meta ($user_to_delete, 'getbwp_is_client'	);
			$html  = $this->get_first_staff_on_list();			
				
			
		}
		echo wp_kses($html, $getbookingwp->allowed_html);
		die();		
			
	}
		
	public function getbwp_get_staff_details($staff_id)	{

		global  $getbookingwp, $getbwpcomplement, $getbwpultimate;
		
		
		$html = '';
		
		$html .= '<div class="getbwp-sect-adm-edit">';
		$html .= '<input type="hidden" value="'.$staff_id.'" id="staff_id" name="staff_id">';
		
		$html .= '<ul class="getbwp-details-staff-sections">';
		
		$html .='<li class="left_widget_customizer_li">';
			
		$html .='<div class="getbwp-staff-details-header" widget-id="1"><h3> '.__('Details','get-bookings-wp').'<h3>';
				
		$html .='<span class="getbwp-widgets-icon-close-open" id="getbwp-widgets-icon-close-open-id-1"  widget-id="1" style="background-position: 0px 0px;"></span>';
		
		$html .= '</div>';
		
		$html .='<div id="getbwp-widget-adm-cont-id-1" class="getbwp-staff-details">';
		
		$html .='<span class="getbwp-action-staff-id">'.__('ID: ','get-bookings-wp').' '.$staff_id.' </span>';
		
		$html .='<span class="getbwp-action-staff"><a href="#" id="getbwp-staff-member-delete"  title="'.__('Delete','get-bookings-wp').'" staff-id="'.$staff_id.'" ><i class="fa fa-trash-o"></i></a> </span>';
		
		$html .= $this->get_staff_personal_details($staff_id);
		$html .= '</div>';
		$html .='</li>';
		
		//account and backend		
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="getbwp-staff-details-header"  widget-id="8"><h3> '.__('Account & Backend','get-bookings-wp').'<h3>';
		
		$html .='<span class="getbwp-widgets-icon-close-open" id="getbwp-widgets-icon-close-open-id-8"  widget-id="8" style="background-position: 0px 0px;"></span>';
		
		$html .= '</div>';
		
		$html .='<div id="getbwp-widget-adm-cont-id-8" class="getbwp-staff-details" style=" display:none">';
		$html .=  $this->get_staff_backend_settings($staff_id);
		$html .= '</div>';
		$html .='</li>';
		
		
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="getbwp-staff-details-header" widget-id="2" ><h3> '.__('Services','get-bookings-wp').'<h3>';
		
		$html .='<span class="getbwp-widgets-icon-close-open" id="getbwp-widgets-icon-close-open-id-2"  widget-id="2" style="background-position: 0px 0px;"></span>';
		
		$html .= '</div>';
				
		$html .='<div id="getbwp-widget-adm-cont-id-2" class="getbwp-tabs-sections-staff-services getbwp-services-list-adm" style=" display:none">';
		$html .= $this->get_staff_services_admin($staff_id);
		$html .= '</div>';
		$html .='</li>';
		
		
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="getbwp-staff-details-header"  widget-id="3"><h3> '.__('Schedule','get-bookings-wp').'<h3>';
		
		$html .='<span class="getbwp-widgets-icon-close-open" id="getbwp-widgets-icon-close-open-id-3"  widget-id="3" style="background-position: 0px 0px;"></span>';
		
		$html .= '</div>';
		
		$html .='<div id="getbwp-widget-adm-cont-id-3" class="getbwp-tabs-sections-staff-services" style=" display:none">';
		$html .=  $getbookingwp->service->get_business_staff_business_hours($staff_id);
		$html .= '</div>';
		$html .='</li>';
		
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="getbwp-staff-details-header" widget-id="7"><h3> '.__('Special Schedule','get-bookings-wp').'<h3>';
			
			$html .='<span class="getbwp-widgets-icon-close-open" id="getbwp-widgets-icon-close-open-id-7"  widget-id="7" style="background-position: 0px 0px;"></span>';
			
			$html .= '</div>';
			
			$html .='<div id="getbwp-widget-adm-cont-id-7" class="getbwp-tabs-sections-staff-services" style=" display:none">';
			
			if(isset($getbwpcomplement) && class_exists('getbwpcomplementDayOff'))
			{
				$html .= $getbwpcomplement->dayoff->get_staff_special_schedule($staff_id);
			
			}else{
				
				$html .= __('Please consider upgrading your plugin if you need to set special rules for your schedule. This feature allows you to set your availability on a particular day in advance.','get-bookings-wp');
				
			}
			
			$html .= '</div>';
			$html .='</li>';
		
		
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="getbwp-staff-details-header" widget-id="4"><h3> '.__('Breaks','get-bookings-wp').'<h3>';
		
		$html .='<span class="getbwp-widgets-icon-close-open" id="getbwp-widgets-icon-close-open-id-4"  widget-id="4" style="background-position: 0px 0px;"></span>';
		
		$html .= '</div>';
		
		$html .='<div id="getbwp-widget-adm-cont-id-4" class="getbwp-staff-break" style=" display:none">';
		
		$html .=  $getbookingwp->breaks->get_staff_breaks($staff_id);
		$html .= '</div>';
		$html .='</li>';
		
		if(isset($bupultimate))
		{
		
			$html .='<li class="left_widget_customizer_li">';
			$html .='<div class="getbwp-staff-details-header" widget-id="6"><h3> '.__('Locations','get-bookings-wp').'<h3>';
			
			$html .='<span class="getbwp-widgets-icon-close-open" id="getbwp-widgets-icon-close-open-id-6"  widget-id="6" style="background-position: 0px 0px;"></span>';
			
			$html .= '</div>';
			
			$html .='<div id="getbwp-widget-adm-cont-id-6" class="getbwp-tabs-sections-staff-services getbwp-services-list-adm" style=" display:none">';
			
			if(isset($getbwpcomplement))
			{
				$html .= $this->get_staff_locations_admin($staff_id);
			
			}else{
				
				$html .= __('Please consider upgrading your plugin if you need to manage multiple locations.','get-bookings-wp');
				
			}
			
			$html .= '</div>';
			$html .='</li>';
		
		}
		
		
		
			$html .='<li class="left_widget_customizer_li">';
			$html .='<div class="getbwp-staff-details-header" widget-id="5"><h3> '.__('Days off','get-bookings-wp').'<h3>';
			
			$html .='<span class="getbwp-widgets-icon-close-open" id="getbwp-widgets-icon-close-open-id-5"  widget-id="5" style="background-position: 0px 0px;"></span>';
			
			$html .= '</div>';
			
			$html .='<div id="getbwp-widget-adm-cont-id-5" class="getbwp-tabs-sections-staff-services" style=" display:none">';
			
			if(isset($getbwpcomplement) && class_exists('GetBookingsComplementDayOff'))
			{
				$html .= $getbwpcomplement->dayoff->get_staff_daysoff($staff_id);
			
			}else{
				
				$html .= __('Please consider upgrading your plugin if you need to add breaks.','get-bookings-wp');
				
			}
			
			$html .= '</div>';
			$html .='</li>';
		
		
		
		
		
		$html .= '</ul>';
		
		$html .= '</div>';
			
		return $html ;		
			
	
	}
	
	
	//this returns the staff permissions and settings
	function get_staff_backend_setting_dropdown($settings, $setting_id)
	{
		$html ='';
		
		if(isset($settings[$setting_id]) && $settings[$setting_id]=='NO')
		{
			$selected_yes = '';
			$selected_no = 'selected="selected"';
			
		}else{
			
			$selected_yes = 'selected="selected"';
			$selected_no = '';
			
		}
		
		
		
		$html .= '<select name="'.$setting_id.'" size="1" id="'.$setting_id.'">
  					<option '.$selected_yes.' value="YES">'.__('YES','get-bookings-wp').'</option>
					<option '.$selected_no.' value="NO">'.__('NO','get-bookings-wp').'</option>
				</select>';
		
		return $html;
		
	}
	
	
	
	//this returns the staff permissions and settings
	function get_staff_backend_settings( $staff_id)
	{
		global $wpdb, $getbookingwp;
		
		$settings = array();
		$settings = get_user_meta( $staff_id, 'getbwp_staff_acc_setting', true ); 
		
		if(!is_array($settings)){$settings== array();}
		
		$html ='';		
				
		$html .='<div class="getbwp-profile-field" >';		
		$html .='<label class="getbwp-field-type" for="display_name"><span>'.__('Backend Access?','get-bookings-wp').'</span></label>';
		$html .='<div class="getbwp-field-value" >'.$this->get_staff_backend_setting_dropdown($settings, "getbwp_per_backend_access").'</div>';		
		$html .= '</div>';
		
		$html .='<div class="getbwp-profile-field" >';		
		$html .='<label class="getbwp-field-type" for="display_name"><span>'.__('Can Update Picture?','get-bookings-wp').'</span></label>';
		$html .='<div class="getbwp-field-value" >'.$this->get_staff_backend_setting_dropdown($settings, "getbwp_upload_picture").'</div>';		
		$html .= '</div>';
		
		$html .='<div class="getbwp-profile-field" >';		
		$html .='<label class="getbwp-field-type" for="display_name"><span>'.__('Can Reschedule Appointments?','get-bookings-wp').'</span></label>';
		$html .='<div class="getbwp-field-value" >'.$this->get_staff_backend_setting_dropdown($settings, "getbwp_reschedule").'</div>';		
		$html .= '</div>';
		
		$html .='<div class="getbwp-profile-field" >';		
		$html .='<label class="getbwp-field-type" for="display_name"><span>'.__('Can Add Notes?','get-bookings-wp').'</span></label>';
		$html .='<div class="getbwp-field-value" >'.$this->get_staff_backend_setting_dropdown($settings, "getbwp_add_notes").'</div>';		
		$html .= '</div>';
		
		$html .='<div class="getbwp-profile-field" >';		
		$html .='<label class="getbwp-field-type" for="display_name"><span>'.__('Update Booking Details?','get-bookings-wp').'</span></label>';
		$html .='<div class="getbwp-field-value" >'.$this->get_staff_backend_setting_dropdown($settings, "getbwp_update_details").'</div>';		
		$html .= '</div>';
		
		$user_profile_bg_color = get_user_meta( $staff_id, 'getbwp_profile_bg_color', true ); 
		$user_profile_bg_font_color = get_user_meta( $staff_id, 'getbwp_profile_bg_font_color', true );
		
		$html .='<div class="getbwp-field "><span id="getbwp-edit-details-message">&nbsp;</span></div>';
		
		$html .='<h2>'.__("Profile Customization",'get-bookings-wp').'</h2>';
		
		$html .='<div class="getbwp-profile-field" >';		
		$html .='<label class="getbwp-field-type" for="display_name"><span>'.__("Header Background Color",'get-bookings-wp').'</span></label>';
		$html .='<div class="getbwp-field-value" ><input name="getbwp-profile-bg-color" type="text" id="getbwp-profile-bg-color" value="'.$user_profile_bg_color.'" class="color-picker" data-default-color=""/></div>';		
		$html .= '</div>';

		
		$html .='<div class="getbwp-profile-field" >';		
		$html .='<label class="getbwp-field-type" for="display_name"><span>'.__("Header Font Color",'get-bookings-wp').'</span></label>';
		$html .='<div class="getbwp-field-value" ><input name="getbwp-profile-bg-font-color" type="text" id="getbwp-profile-bg-font-color" value="'.$user_profile_bg_font_color.'" class="color-picker" data-default-color=""/></div>';		
		$html .= '</div>';
		
		$html .=' <p class="submit">
	<button name="getbwp-save-acc-settings-staff" id="getbwp-save-acc-settings-staff" class="getbwp-button-submit-changes" getbwp-staff-id= "'.$staff_id.'">'.__('Save Settings','get-bookings-wp').'	</button>&nbsp; <span id="getbwp-loading-animation-acc-setting-staff">  <img src="'.getbookingpro_url.'admin/images/loaderB16.gif" width="16" height="16" /> &nbsp; '.__('Please wait ...','get-bookings-wp').' </span>
	
	</p>';
	
		$html .= '<p><i class="fa fa-info-circle"></i> '.__('You can use the following button to send a password reset link to this staff member. The reset will allow the staff meber to login and manage their appointments','get-bookings-wp').'</p>';
			
		$html .=' <p class="submit">
		<button name="getbwp-save-acc-send-reset-link-staff" id="getbwp-save-acc-send-reset-link-staff" class="getbwp-button-submit-changes" getbwp-staff-id= "'.$staff_id.'"><i class="fa fa-refresh "></i> '.__('Send Password Reset Link','get-bookings-wp').'	</button>&nbsp; <span id="getbwp-loading-animation-acc-resetlink-staff">  <img src="'.getbookingpro_url.'admin/images/loaderB16.gif" width="16" height="16" /> &nbsp; '.__('Please wait ...','get-bookings-wp').' </span> <p id="getbwp-acc-resetlink-staff-message">   </p>
		
		</p>';
		
		$reset_link_page = $getbookingwp->get_option("getbwp_password_reset_page");
		
		if($reset_link_page=='')
		{	
			$html .= '<p class="getbwp-backend-info-tool">'.'<i class="fa fa-info-circle"></i> <strong>'.__("You haven't set a password reset page, this is very imporant. Click on Staff & Client Account link and set a page for the reset password shortcode. ",'get-bookings-wp').'</strong>'.'</p>';
		
		}
		
		
		
		
		
				
		return $html;
		
	}
	
	public function has_account_permision($staff_id, $setting_id)
	{
		global $wpdb, $getbookingwp;
		
		$settings = array();
		$settings = get_user_meta( $staff_id, 'getbwp_staff_acc_setting', true ); 
		
		if(!is_array($settings)){$settings== array();}
		
		if(isset($settings[$setting_id]) && $settings[$setting_id]=='NO')
		{
			return false;
			
		}else{
			
			return true;
			
		}		
		
	}
	
	public function update_user_account_settings()	{
		global $wpdb, $getbookingwp;
		
		
		$getbwp_per_backend_access = sanitize_text_field($_POST['getbwp_per_backend_access']);		
		$getbwp_upload_picture = sanitize_text_field($_POST['getbwp_upload_picture']);
		$getbwp_reschedule = sanitize_text_field($_POST['getbwp_reschedule']);
		$getbwp_add_notes = sanitize_text_field($_POST['getbwp_add_notes']);
		$getbwp_update_details = sanitize_text_field($_POST['getbwp_update_details']);		
		$getbwp_profile_bg_color = sanitize_text_field($_POST['getbwp_profile_bg_color']);
		$getbwp_profile_bg_font_color = sanitize_text_field($_POST['getbwp_profile_bg_font_color']);
		
		
		$staff_id = sanitize_text_field($_POST['staff_id']);
		
		$settings = array('getbwp_per_backend_access' =>$getbwp_per_backend_access, 
						  'getbwp_upload_picture' =>$getbwp_upload_picture,
						  'getbwp_reschedule' =>$getbwp_reschedule,
						  'getbwp_add_notes' =>$getbwp_add_notes,
						  'getbwp_update_details' =>$getbwp_update_details,
						  'getbwp_profile_bg_color' =>$getbwp_profile_bg_color,
						  'getbwp_profile_bg_font_color' =>$getbwp_profile_bg_font_color);		
		update_user_meta($staff_id, 'getbwp_staff_acc_setting', $settings);
		
		if(!isset($_POST['getbwp_profile_bg_color']) || $_POST['getbwp_profile_bg_color']==''){			
			$getbwp_profile_bg_color = '';			
		}

		if(!isset($_POST['getbwp_profile_bg_font_color']) || $_POST['getbwp_profile_bg_font_color']==''){			
			$getbwp_profile_bg_font_color = '';			
		}
		
		update_user_meta($staff_id, 'getbwp_profile_bg_color', $getbwp_profile_bg_color);
		update_user_meta($staff_id, 'getbwp_profile_bg_font_color', $getbwp_profile_bg_font_color);
		
		die();
	
	
	}
	
	//this returns the service for a particular user, if it has not been set we will take the defaul.	
	function get_staff_personal_details( $staff_id )
	{
		global $wpdb, $getbookingwp, $getbwpcomplement, $getbwp_zoom;		
		
		$user = get_user_by( 'id', $staff_id );
		
		$html = '';
		
		
		$html .='<div class="getbwp-profile-field getbwp-avadiv" >';		
		$html .='<label class="getbwp-field-type" for="display_name"><span>'.$getbookingwp->userpanel->get_user_pic( $staff_id, 80, 'getbwp-avatar', null, null, false).' <div class="getbwp-div-for-avatar-upload"> <a href="?page=getbookingswp&tab=users&avatar='.$staff_id.'"><button name="getbwp-button-change-avatar" id="getbwp-button-change-avatar" class="getbwp-button-change-avatar" type="link"><span><i class="fa fa-camera"></i></span>'.__('Update Pic','get-bookings-wp').'	</button></a></div></span>
		
		</label>';
		
		$html .='<div class="getbwp-field-value" ></div>';		
		$html .= '</div>';
		
		$html .='<div class="getbwp-profile-field" >';		
		$html .='<label class="getbwp-field-type" for="display_name"><span>'.__('Full Name','get-bookings-wp').'</span></label>';
		$html .='<div class="getbwp-field-value" ><input type="text" class=" getbwp-input " name="display_name" id="reg_display_name" value="'.$user->display_name.'" title="'.__('Your Full Name','get-bookings-wp').'" ></div>';		
		$html .= '</div>';
        
        $html .='<div class="getbwp-profile-field" >';		
		$html .='<label class="getbwp-field-type" for="display_name"><span>'.__('Profession','get-bookings-wp').'</span></label>';
		$html .='<div class="getbwp-field-value" ><input type="text" class=" getbwp-input " name="u_profession" id="u_profession" value="'.$getbookingwp->getbwp_get_user_meta($staff_id, 'u_profession').'" title="'.__('Your Profession','get-bookings-wp').'" ></div>';		
		$html .= '</div>';
		
		$html .='<div class="getbwp-profile-field" >';		
		$html .='<label class="getbwp-field-type" for="display_name"><span>'.__('Phone','get-bookings-wp').'</span></label>';
		$html .='<div class="getbwp-field-value" ><input type="text" class=" getbwp-input " name="reg_telephone" id="reg_telephone" value="'.$getbookingwp->getbwp_get_user_meta($staff_id, 'reg_telephone').'" title="'.__('Your Phone Number','get-bookings-wp').'" ></div>';		
		$html .= '</div>';
		
		$html .='<div class="getbwp-profile-field" >';		
		$html .='<label class="getbwp-field-type" for="display_name"><span>'.__('E-mail','get-bookings-wp').'</span></label>';
		$html .='<div class="getbwp-field-value" > <input type="text" class=" getbwp-input " name="reg_email" id="reg_email" value="'.$user->user_email.'" title="'.__('Your Email','get-bookings-wp').'" > <input type="hidden" class=" getbwp-input " name="reg_email2" id="reg_email2" value="'.$user->user_email.'"  ></div>';		
		$html .= '</div>';
		
		
		$html .= '<div class="getbwp-field ">';
		$html .= '				<label class="getbwp-field-type "><button name="getbwp-btn-user-details-confirm" id="getbwp-btn-user-details-confirm" class="getbwp-button-submit-changes">'.__('Submit','get-bookings-wp').'	</button></label>';
		
	
		$html .= '<div class="getbwp-field-value">
						    <input type="hidden" name="getbwp-register-form" value="getbwp-register-form">								
							
							
				   </div>';
		$html .= '</div>';
		
		$html .= '<div class="getbwp-field "><span id="getbwp-edit-details-message">&nbsp;</span>';
		$html .= '</div>';
		
		
		$html .= '<h2>'.__('Google Calendar','get-bookings-wp').'</h2>';		
		$html .= '<div class="getbwp-field ">';
		
			if(isset($getbwpcomplement->googlecalendar))
			{
				//do we have a calendar list?				
				$html .= $this->get_user_auth_status_staff($staff_id);					
				
				$html .= $getbwpcomplement->googlecalendar->get_user_auth_status($staff_id);			
				
			
			}else{			
				
				$html .= '<p>'.__('Please consider upgrading your plugin if you wish to use Google Calendar features.','get-bookings-wp').'</p>';
				
			
			}		
		$html .= '</div>';

		$html .= '<h2>'.__('Zoom Meetings','get-bookings-wp').'</h2>';		
		$html .= '<div class="getbwp-field ">';
		
		if(isset($getbwp_zoom)){
						
			$html .= $getbwp_zoom->get_user_auth_status_staff($staff_id);				
			
		}else{			
				
			$html .= '<p>'.__('Please consider upgrading your plugin if you wish to use Zoom Meetings features.','get-bookings-wp').'</p>';
			
		}		
		$html .= '</div>';
		
		
		return $html;
	
	
	}
	
	//this is used on admin dashboard
	public function get_user_auth_status_staff($staff_id)
	{
		global $getbookingwp;
		
		$html = '';
		
		$client_id = $getbookingwp->get_option('google_calendar_client_id');
		$client_secret = $getbookingwp->get_option('google_calendar_client_secret');
		
		//get client access token		
		$accessToken = $getbookingwp->getbwp_get_user_meta($staff_id, 'google_cal_access_token');
		
		if($accessToken=='') //get auth url
		{
			if($client_id=='' || $client_secret=='')
			{				
				$html = "<p>".__('Please set client ID and client Secret!','get-bookings-wp')."</p>";
				
			
			}else{
				
				//$auth_url = $this->get_auth_url_staff();			
				//$html = "<p><a href='$auth_url'>".__('Connect Me!','get-bookings-wp')."</a></p>";
				
			}	
		
		}else{
			
			if($client_id=='' || $client_secret=='')
			{				
				$html = "<p>".__('Please set client ID and client Secret!','get-bookings-wp')."</p>";
				
			
			}else{	
								
				$html .= "<p>".__('Select Your Calendar:','get-bookings-wp')."</p>";
				$html .= "<p>".$this->get_calendar_list_drop($staff_id)."</p>";
					
				$html .= '<p> <button name="getbwp-backenedb-set-gacal-adm" id="getbwp-backenedb-set-gacal-adm" class="getbwp-button-submit-changes" staff-id="'.$staff_id.'">'.__('SET CALENDAR','get-bookings-wp').'	</button> </p>';				
				$html .= "<p id='getbwp-gcal-message3'>&nbsp;</p>";			
				
				$google_calendar_default = $getbookingwp->getbwp_get_user_meta($staff_id, 'google_calendar_default');
				
				if($google_calendar_default=='')
				{
					$html .= "<p id='getbwp-gcal-message1' ><strong class='getbwp-backend-info-tool-warning'>".__("IMPORTANT: You haven't set a calendar, yet.",'get-bookings-wp')."</strong></p>";
					
					$html .= "<p class='getbwp-backend-info-tool' id='getbwp-gcal-message2'><i class='fa fa-info-circle'></i><strong>&nbsp;".__("If you don't set a calendar the primary calendar will be used by default. ",'get-bookings-wp')."</strong></p>";
				
				}else{
					
					$html .= "<p class='getbwp-backend-info-tool' id='getbwp-gcal-message44'><i class='fa fa-info-circle'></i><strong>&nbsp;".__("If you don't see your new calendars, plase disconnect and connect again. ",'get-bookings-wp')."</strong></p>";
					
					
				
				}							
			
			}
			
			
		}		
		
		return $html;
		
			
	}
	
	public function set_default_google_calendar()
	{
		
		global $wpdb, $getbookingwp;
		
		$staff_id =sanitize_text_field($_POST['staff_id']);		
		$google_calendar = sanitize_text_field($_POST['google_calendar']);		
		update_user_meta ($staff_id, 'google_calendar_default', $google_calendar);		
		
		$html =__("Your calendar has been set! ", 'get-bookings-wp');
		
		echo wp_kses($html, $getbookingwp->allowed_html)		;
		
		die();
	
	}
	
	function get_calendar_list_drop($staff_id)	
	{
		global $getbookingwp;
		
		$html = '<select name="getbwp_staff_calendar_list" size="1" id="getbwp_staff_calendar_list">';
		
		//display calendars list				
		$google_calendar_list = $getbookingwp->getbwp_get_user_meta($staff_id, 'google_calendar_list');
		$google_calendar_default = $getbookingwp->getbwp_get_user_meta($staff_id, 'google_calendar_default');
		
		 foreach ($google_calendar_list as $calendar) 
		 {
			 $sel =  '';
			 if($calendar['id']==$google_calendar_default){$sel =  'selected="selected"';}
			 
 			$html .= '<option value="'.$calendar['id'].'" '.$sel.'>'.$calendar['summary'].'</option>'; 		 			   
			   
    	 }
		 
		 $html .= '</select>';
		 
		 if(empty($google_calendar_list))
		 {
		 
			 $html .= "<p class='getbwp-backend-info-tool' id='getbwp-gcal-message2'><i class='fa fa-info-circle'></i><strong>&nbsp;".__("If the calendars list is empty, please disconnect and connect again. ",'get-bookings-wp')."</strong></p>";
		 
		  }
				
				
		return $html;
	
	}
	
	//this returns the service for a particular user, if it has not been set we will take the defaul.	
	function get_staff_service_rate( $staff_id, $service_id )
	{
		global $wpdb, $getbookingwp;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'getbwp_service_rates WHERE rate_service_id =  %s AND	
		rate_staff_id= %s ' ;			

		$sql = $wpdb->prepare($sql,array($service_id, $staff_id));
		$res = $wpdb->get_results($sql);
		
				
		$ret = array();
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				$ret = array('price'=>$row->rate_price, 'capacity'=>$row->rate_capacity);			
			
			}
			
		}else{
			
			//we need to get the default values for this service
			$serv = $getbookingwp->service->get_one_service($service_id);			
			$ret = array('price'=>$serv->service_price, 'capacity'=>$serv->service_capacity);
		}
		
		return $ret;
	
		
	}
	
	//returns true or false if the service is offered by the staff member	
	function staff_offer_service( $staff_id, $service_id )
	{
		global $wpdb, $getbookingwp;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'getbwp_service_rates WHERE rate_service_id = %s  AND
			rate_staff_id= %s ' ;	
			
			
		$sql = $wpdb->prepare($sql,array($service_id, $staff_id));	
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				
				return true			;
			
			}
			
		}else{
			
			return false;
		}
	
	}
	
	//returns true or false if the offers some of the services within this category	
	function staff_offer_this_category( $staff_id, $category_id )
	{
		global $wpdb, $getbookingwp;
		
			
		$sql = ' SELECT cate.*,sercvate.*, servrate.*  FROM ' . $wpdb->prefix.$this->sys_prefix.'_categories cate ' ;		
		$sql .= "RIGHT JOIN ".$wpdb->prefix .$this->sys_prefix."_services sercvate ON (sercvate.service_category_id = %s)";
		$sql .= "RIGHT JOIN ".$wpdb->prefix ."getbwp_service_rates servrate ON (servrate.rate_service_id = sercvate.service_id )";
		
		
		$sql .= ' WHERE servrate.rate_service_id = sercvate.service_id AND servrate.rate_staff_id = %s AND sercvate.service_category_id = %s ' ;	
					
		$sql = $wpdb->prepare($sql,array($category_id, $staff_id, $category_id));		
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				return true			;
			
			}
			
		}else{
			
			return false;
		}
	
	}
	
	//returns true if the staff works on this location
	function staff_works_in_location( $staff_id, $location_id )
	{
		global $wpdb, $getbookingwp;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'getbwp_filter_staff WHERE fstaff_location_id =  %s AND	
		fstaff_staff_id= %s ' ;	
		
		$sql = $wpdb->prepare($sql,array($location_id, $staff_id));				
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				return true			;
			
			}
			
		}else{
			
			return false;
		}
	
	}
	
	function get_staff_locations_admin( $staff_id )
	{
		global $wpdb, $getbookingwp, $getbwp_filter;

		$html = '';
		
		//get locations
		$locations_list = $getbwp_filter->get_all(); 
				
		if ( !empty( $locations_list ) )
		{
			$html .='<ul>';
					
			foreach ( $locations_list as $location ){
						
				$checked_service = 'checked="checked"';
				$disable_service = '';

				if(!$this->staff_works_in_location($staff_id, $location->filter_id)){

					$checked_service = '';
					$disable_service = 'disabled="disabled"'; 
				}		
						
				$html .='<li>';			
				$html .='<input type="checkbox" class="getbwp-location-checked getbwp-service-cate" value="'.$location->filter_id.'" name="getbwp-locations[]" data-location-id="'.$location->filter_id.'" id="getbwp-location-'.$location->filter_id.'" '. $checked_service.'><label for="getbwp-location-'.$location->filter_id.'"><span></span>'.$location->filter_name.'</label>';									
				$html .='</li>';
			}			
					
			$html .='</ul>'; //end categories
			$html .=' <p> <button name="getbwp-admin-edit-staff-service-save" id="getbwp-admin-edit-staff-location-save" class="getbwp-button-submit-changes" getbwp-staff-id= "'.$staff_id.'">'.__('Save Changes','get-bookings-wp').'</button>&nbsp; <span id="getbwp-loading-animation-services">  </span></p>';
		}	
		
		return $html;		
	}
	
	function get_staff_services_admin( $staff_id ){
		global $wpdb, $getbookingwp;

		
		$html = '';
		
		$cate_list = $getbookingwp->service->get_all_categories(); 
		
		if ( !empty( $cate_list ) )
		{		
		
			foreach ( $cate_list as $cate )
			{
				$html .='<div class="getbwp-serv-category-title">';
				
				
				    $html .='<div class="getbwp-col1">';					
						$html .='<input type="checkbox" class="getbwp-cate-service-checked getbwp-service-cate-parent" value="'.$cate->cate_id.'" name="getbwp-cate[]" data-category-id="'.$cate->cate_id.'" id="getbwp-cate-'.$cate->cate_id.'"><label for="getbwp-cate-'.$cate->cate_id.'"><span></span>'.$cate->cate_name.'</label>';					
					$html .='</div>';	
					
					
					$html .='<div class="getbwp-col2">'.__('Price','get-bookings-wp').''.'</div>';
					$html .='<div class="getbwp-col3">'.__('Capacity','get-bookings-wp').''.'</div>';
					
					
				$html .='</div>';
				
				//get services
				
				$service_list = $getbookingwp->service->get_all_services($cate->cate_id); 
				
				if ( !empty( $service_list ) )
				{
				
					$html .='<ul>';
					
					foreach ( $service_list as $service )
					{
						//get service data						
						$serv_data = $this->get_staff_service_rate($staff_id, $service->service_id);		

						
						$checked_service = 'checked="checked"';
						$disable_service = '';
						if(!$this->staff_offer_service($staff_id, $service->service_id)){						

							 $checked_service = '';
							 $disable_service = 'disabled="disabled"'; 
						}
						
						$html .='<li>';						
						$html .='<div class="getbwp-services-left">';				
						$html .='<input type="checkbox" class="getbwp-cate-service-checked getbwp-service-cate getbwp-service-cate-'.$cate->cate_id.'" value="'.$service->service_id.'" name="getbwp-service[]" data-category-id="'.$cate->cate_id.'" id="getbwp-service-'.$service->service_id.'" '. $checked_service.'><label for="getbwp-service-'.$service->service_id.'"><span></span>'.$service->service_title.'</label>';	
						$html .='</div>';
						
										
						$html .='<div class="getbwp-services-right">';
						$html .='<input type="text" value="'.$serv_data['price'].'" name="price['.$service->service_id.']" class="getbwp-price-box" id="getbwp-price-'.$service->service_id.'" '.$disable_service.'>';
						$html .='</div>';
						
						$html .='<div class="getbwp-services-right">';
						$html .='<input type="number" value="'.$serv_data['capacity'].'" name="capacity['.$service->service_id.']" min="1" class="getbwp-price-box" id="getbwp-qty-'.$service->service_id.'" '.$disable_service.'>';
						$html .='</div>';						
						
						$html .'<div style="border-bottom: 1px dotted black; overflow: hidden; padding-top: 15px;"></div>';
						
						$html .='</li>';
					
					}			
					
					
					$html .='</ul>'; //end categories
					
				
				}
						
			
			}		
			
			
			$html .=' <p> <button name="getbwp-admin-edit-staff-service-save" id="getbwp-admin-edit-staff-service-save" class="getbwp-button-submit-changes" getbwp-staff-id= "'.$staff_id.'">'.__('Save Changes','get-bookings-wp').'</button>&nbsp; <span id="getbwp-loading-animation-services">  </span></p>';
			
			
		}	
		
		
		return $html;		
	
	}
	
	function get_staff_member($staff_id){
		 global $wpdb,$blog_id, $wp_query;	
		 
		$args = array( 
						
			'meta_key' => 'getbwp_is_staff_member',                    
			'meta_value' => 1,                  
			'meta_compare' => '=',  
			'count_total' => true,   


		);		

		$user_query = new WP_User_Query( $args );
		$users= $user_query->get_results();
		
		
		if (!empty($users))
		{
			
			foreach($users as $user) 
			{
				if($user->ID==$staff_id)
				{
				
					return $user;
				}			
				
				
			}				
		
		}
		
		return $users;
	
	}
	
	//get all staf for FULL Calendar		
	function get_staff_list_fc($location_id = NULL)
	{
		 global $wpdb,$blog_id, $wp_query;	
		 
		 
		if($location_id=='' || $location_id=='undefined' )
		{
		 
			$args = array( 	
							
				'meta_key' => 'getbwp_is_staff_member',                    
				'meta_value' => 1,                  
				'meta_compare' => '=',  
				'count_total' => true,   
	
	
				);			
	
			 // Create the WP_User_Query object
			$user_query = new WP_User_Query( $args );
			$users= $user_query->get_results();			
		
		}else{			
			
			$sql =  "SELECT  usu.*, staff_location.* 	" ;		
			$sql .= " FROM " . $wpdb->users . " usu ";				
			$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_filter_staff staff_location ON (staff_location.	fstaff_staff_id = usu.ID)";		
					
			$sql .= " WHERE staff_location.	fstaff_staff_id = usu.ID AND  
			staff_location.fstaff_location_id  =  %s  ";

			$sql = $wpdb->prepare($sql,array($location_id));				
			$users = $wpdb->get_results($sql);		
		
		}
		
		
		return $users;
	
	}
    
    function get_staff_list_calendar_bar( $service_id=null )
	{
		 global $wpdb, $wp_query;	
		 
		$args = array( 	
						
			'meta_key' => 'getbwp_is_staff_member',                    
			'meta_value' => 1,                  
			'meta_compare' => '=',  
			'count_total' => true,   


			);
			
		$getbwp_staff_calendar = '';		
		if(isset($_GET["getbwp-staff-calendar"]))
		{
			$getbwp_staff_calendar = sanitize_text_field($_GET["getbwp-staff-calendar"]);		
		}		

		$user_query = new WP_User_Query( $args );
		$users= $user_query->get_results();
		
		$selected ='';		
		$count = 0;

		$all_staff_img = '<img src="'.getbookingpro_url.'/admin/images/group.png'.'" class="getbwp-avatar" style="width:75px;height:75px"     />';

		$htm = '<ul> ';		
        $htm .= '<li uuid="" class="getbwp-staff-cal-list bupro-staff-noselect buu-all" >';
                $htm .= '<a href="#" uuid="">';

                $htm .= '<div class="getbwp-st-photo" >'.$all_staff_img.'</div>';    
                $htm .= '<div class="bupro-st-name">'.__('All', 'get-bookings-wp').'</div>';
                $htm .= '</a>';                
       $htm .= '</li>';
        
        
		if (!empty($users))	{

			foreach($users as $user) {

                $staff_id = $user->ID;
				
				$selected = '';				
				if($getbwp_staff_calendar==$user->ID){$selected = 'selected="selected"';}
                
                 $htm .= '<li data-li-staff_id="'.$user->ID.'" class="getbwp-staff-cal-list bupro-staff-noselect" >';
                    $htm .= '<a href="#" data-staff_id="'.$user->ID.'" staff_id="'.$user->ID.'">';

                    $htm .= '<div class="getbwp-st-photo" >'.$this->get_user_pic( $staff_id, 75, 'avatar', null, null, false).'</div>';    
                    $htm .= '<div class="bupro-st-name">'.$user->display_name.'</div>';
                    $htm .= '</a>';
                
                $htm .= '</li>';
			}
		}
		
		$htm .= '</ul>';		
		return $htm;
	
	}
	
	function get_staff_list_calendar_filter( $service_id=null )
	{
		 global $wpdb, $wp_query;	
		 
		$args = array( 	
						
			'meta_key' => 'getbwp_is_staff_member',                    
			'meta_value' => 1,                  
			'meta_compare' => '=',  
			'count_total' => true,   


			);
			
		$getbwp_staff_calendar = ''	;
		if(isset($_GET["getbwp-staff-calendar"]))
		{
			$getbwp_staff_calendar = sanitize_text_field($_GET["getbwp-staff-calendar"]);		
		}
		

		 // Create the WP_User_Query object
		$user_query = new WP_User_Query( $args );
		$users= $user_query->get_results();
		
		$selected ='';

		
		$count = 0;
		
		//$html = '';
		
		$htm = '<select id="getbwp-staff-calendar" name="getbwp-staff-calendar"> ';		
		$htm .= '<option value="" selected="selected" >'.__('All Staff Members', 'get-bookings-wp').'</option>';
				
		if (!empty($users))
		{
			
			foreach($users as $user) 
			{
				
				$selected = '';				
				if($getbwp_staff_calendar==$user->ID){$selected = 'selected="selected"';}
				
				$htm .= '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.'</option>';
				
				
				
			}
			
		
		}
		
		$htm .= '</select>';
		
		return $htm;
	
	}
	
	function get_not_staff_users_to_convert()
	{
		 global $wpdb,$blog_id, $wp_query;	
		 
		
		$args = array( 	
						
			'meta_key' => 'getbwp_is_staff_member',                    
			'meta_value' => '1',                  
			'meta_compare' => '!=',  
			'count_total' => true,   


			);
		

		// Create the WP_User_Query object
		$user_query = new WP_User_Query( $args );
		$users= $user_query->get_results();	
		
		
		
		
		$selected ='';

		
		$count = 0;
		
		$html = '';
		
		$html .= '<select name="getbwp-staff" id="getbwp-staff">';
		$html .= '<option value="" selected="selected" >'.__('Select User', 'get-bookings-wp').'</option>';
		
		if (!empty($users))
		{
			
			foreach($users as $user) 
			{
				
		
				$html .= '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.'</option>';
				
				
				
			}
			$html .= '</select>';
		
		
		
					
		
		}
		
		return $html;
	
	}
	
	function get_staff_list_front( $location_id=null )
	{
		 global $wpdb,$blog_id, $wp_query;	
		 
		
		if($location_id=='')
		{
			$args = array( 	
						
			'meta_key' => 'getbwp_is_staff_member',                    
			'meta_value' => 1,                  
			'meta_compare' => '=',  
			'count_total' => true,   


			);
		

			 // Create the WP_User_Query object
			$user_query = new WP_User_Query( $args );
			$users= $user_query->get_results();
		
		}else{
		
		
			$sql = ' SELECT  user.*, staff_location.*  FROM ' . $wpdb->users . '  user ' ;		
			
			$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_filter_staff staff_location ON (staff_location.fstaff_staff_id = user.ID)";
			
			$sql .= " WHERE staff_location.fstaff_staff_id = user.ID AND  staff_location.fstaff_location_id = %s " ;					
			$sql .= ' ORDER BY user.display_name ASC  ' ;

			$sql = $wpdb->prepare($sql,array($location_id));	
			$users = $wpdb->get_results($sql);
			
		}
		
		
		
		
		
		$selected ='';

		
		$count = 0;
		
		$html = '';
		
		$html .= '<select name="getbwp-staff" id="getbwp-staff">';
		$html .= '<option value="" selected="selected" >'.__('Any', 'get-bookings-wp').'</option>';
		
		if (!empty($users))
		{
			
			foreach($users as $user) 
			{
				
		
				$html .= '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.'</option>';
				
				
				
			}
			$html .= '</select>';
		
		
		
					
		
		}
		
		return $html;
	
	}
	
	
	function get_staff_filtered( $args )
	{

        global $wpdb,$blog_id, $wp_query;
		
		$getbwp_meta = '';	
		$per_page = '';		
		
		extract($args);		
		$memberlist_verified = 1;		
		$blog_id = get_current_blog_id();

		$paged = (!empty($_GET['paged'])) ? $_GET['paged'] : 1;	
		
		if($per_page!=''){
			
			$offset = ( ($paged -1) * $per_page);			
		}
		
		
		$query['search_columns']= array('display_name', 'user_email');					
		$query['meta_query'] = array('relation' => strtoupper($relation) );	
		
		$total_pages = '';
	  	
				
		if ($getbwp_meta)
		{
			
			$query['meta_query'][] = array(
					'key' => $uultra_meta,
					'value' => $keyword,
					'compare' => 'LIKE'
				);				
		}
		
		$query['meta_query'][] = array(
					'key' => 'getbwp_is_staff_member',
					'value' => 1,
					'compare' => '='
		);			
		
				
				
    	if ($sortby) $query['orderby'] = $sortby;			
	    if ($order) $query['order'] = strtoupper($order); // asc to ASC
			
		/** QUERY ARGS END **/
			
		$query['number'] = $per_page;
		//$query['offset'] = $offset;
			
		/* Search mode */
		if ( ( isset($_GET['getbwp_search']) && !empty($_GET['getbwp_search']) ) || count($query['meta_query']) > 1 )
		{
			$count_args = array_merge($query, array('number'=>10000));
			unset($count_args['offset']);
			$user_count_query = new WP_User_Query($count_args);
						
		}

		if ($per_page) 
		{			
		
			/* Get Total Users */
			if ( ( isset($_GET['getbwp_search']) && !empty($_GET['getbwp_search']) ) || count($query['meta_query']) > 1 )
			{
				$user_count = $user_count_query->get_results();								
				$total_users = $user_count ? count($user_count) : 1;
				
			} else {
				
			
				$result = count_users();
				$total_users = $result['total_users'];
				
			}
			
			$total_pages = ceil($total_users / $per_page);
		
		}
		
		$user_count = $user_count_query->get_results();								
		$total_users = $user_count ? count($user_count) : 1;
		
		$wp_user_query = new WP_User_Query($query);
		
	
		if (! empty( $wp_user_query->results )) 
		{
			$arr['total'] = $total_users;
			$arr['paginate'] = paginate_links( array(
					'base'         => @add_query_arg('paged','%#%'),
					'total'        => $total_pages,
					'current'      => $paged,
					'show_all'     => false,
					'end_size'     => 1,
					'mid_size'     => 2,
					'prev_next'    => true,
					'prev_text'    => __('« Previous','get-bookings-wp'),
					'next_text'    => __('Next »','get-bookings-wp'),
					'type'         => 'plain',
				));
			$arr['users'] = $wp_user_query->results;
		}
		
				
		return $arr;
		
		
	}
	
	function get_staff_details_admin_ajax()
	{
		global $wpdb, $getbookingwp;
		
		$html='';
		
		$staff_id = sanitize_text_field($_POST['staff_id']);		
		$html .= $this->getbwp_get_staff_details($staff_id);					
		
		echo wp_kses($html, $getbookingwp->allowed_html);
		die();
		
	}
	
	function get_first_staff_on_list()
	{
		global $wpdb, $getbookingwp;
		
		$relation = "AND";
		$howmany = '1';
		$uultra_combined_search = '';
		$uultra_meta = '';
		$args= array('per_page' => $howmany, 'keyword' => $uultra_combined_search , 'getbwp_meta' => $uultra_meta,  'relation' => $relation,  'sortby' => 'ID', 'order' => 'DESC');
		$users = $getbookingwp->userpanel->get_staff_filtered($args);
		
		$c_c =0;
		$user_id = '';
		
		if(!empty($users['users']))
		{
			foreach($users['users'] as $user) 
			{
					
					$user_id = $user->ID;				
					$c_c++;				
					if($c_c==1){return $user_id;}
			}
		}
	}
	
	function get_staff_list_admin_ajax()
	{
		global $wpdb, $getbookingwp;
		
		$html='';
		$uultra_combined_search = '';
		
		$relation = "AND";
		$args= array('keyword' => $uultra_combined_search ,  'relation' => $relation,  'sortby' => 'ID', 'order' => 'DESC');
		$users = $getbookingwp->userpanel->get_staff_filtered($args);
		
		$total = $users['total'];
		
		if (empty($users['users']))
		{
			$total = 0;		
		
		}		
			
		$html .='<div class="getbwp-staff-list-act">';		
		$html .='<span class="getbwp-add-staff"><a href="#" id="getbwp-add-staff-btn" title="'.__('Add New Staff Member','get-bookings-wp').'" ><i class="fa fa-plus"></i></a></span>';
		$html .='</div>';
		
		if (!empty($users['users']))
		{
			$html .='<ul>';
			$c_c =0;
			
			foreach($users['users'] as $user) {
				
				$user_id = $user->ID;
				
				$c_c++;
				
				if($c_c==1){$html .='<input type="hidden" id="getbwp-first-staff-id" value="'.$user_id.'">';}
			
				$html .='<li>';
				$html .='<a href="#" id="getbwp-staff-load" class="getbwp-staff-load" staff-id="'.$user_id.'"> ';				
				$html .= $getbookingwp->userpanel->get_user_pic( $user_id, 75, 'avatar', null, null, false);
				$html .='<div class="getbwp-staff-name">';
				$html .='<h3>'.$user->display_name.'</h3>';
				$html .='</div>';
				$html .='</a>';
				$html .='</li>';
				
			}
			
			$html .='</ul>';
		
		}else{
			
			$html .=__('There are no staff members.','get-bookings-wp');
			
		
		}	
		
		echo wp_kses($html, $getbookingwp->allowed_html)	;
		die();
		
	}
	
		/* Get picture by ID */
	function get_user_pic( $id, $size, $pic_type=NULL, $pic_boder_type= NULL, $size_type=NULL, $with_url=true ) 
	{
		
		 global  $getbookingwp;
		 
		 require_once(ABSPATH . 'wp-includes/link-template.php');
	 
		
		$site_url = site_url()."/";
		
		//rand_val_cache		
		$cache_rand = time();
			 
		$avatar = "";
		$pic_size = "";
		$dimension_2 = '';
		$user_url = '';
		
				
		$upload_dir = wp_upload_dir(); 
		$path =   $upload_dir['baseurl']."/".$id."/";
				
		$author_pic = get_the_author_meta('user_pic', $id);
		
		//get user url
		//$user_url=$this->get_user_profile_permalink($id);
		
		if($pic_boder_type=='none'){$pic_boder_type='uultra-none';}
		
		
		if($size_type=="fixed" || $size_type=="")
		{
			$dimension = "width:";
			$dimension_2 = "height:";
		}
		
		if($size_type=="dynamic" )
		{
			$dimension = "max-width:";
		
		}
		
		if($size!="")
		{
			$pic_size = $dimension.$size."px".";".$dimension_2.$size."px";
		
		}
		
		if($getbookingwp->get_option('getbwp_force_cache_issue')=='yes')
		{
			$cache_by_pass = '?rand_cache='.$cache_rand;
		
		}
		
		$user = get_user_by( 'id', $id );
		
			
		
		if ($author_pic  != '') 
			{
				$avatar_pic = $path.$author_pic;
				
				
				if($with_url)
				{
		 
					$avatar= '<a href="'.$user_url.'">'. '<img src="'.$avatar_pic.'" class="getbwp-avatar '.$pic_boder_type.'" style="'.$pic_size.' "   id="getbwp-avatar-img-'.$id.'" title="'.$user->display_name.'" /></a>';
				
				}else{
					
					$avatar=  '<img src="'.$avatar_pic.'" class="getbwp-avatar '.$pic_boder_type.'" style="'.$pic_size.' "   id="getbwp-avatar-img-'.$id.'" title="'.$user->display_name.'" />';
				
				}
				
				
				
			} else {
				
				$user = get_user_by( 'id', $id );		
				$avatar = get_avatar( $user->user_email, $size );
		
	    	}
		
		return $avatar;
	}
	
	
	
	
	/* delete avatar */
	function delete_user_avatar() 
	{			
		$user_id =   sanitize_text_field($_POST['user_id']);			
		update_user_meta($user_id, 'user_pic', '');
		die();
	}
	
	public function avatar_uploader($staff_id=NULL) 
	{

		//$avatar_is_called = 
		
	   // Uploading functionality trigger:
	  // (Most of the code comes from media.php and handlers.js)
	      $template_dir = get_template_directory_uri();
?>
		
		<div id="uploadContainer" style="margin-top: 10px;">
			
			
			<!-- Uploader section -->
			<div id="uploaderSection" style="position: relative;">
				<div id="plupload-upload-ui-avatar" class="hide-if-no-js">
                
					<div id="drag-drop-area-avatar">
						<div class="drag-drop-inside">
							<p class="drag-drop-info"><?php	_e('Drop Image here', 'get-bookings-wp') ; ?></p>
							<p><?php _ex('or', 'Uploader: Drop files here - or - Select Files'); ?></p>
							                            
                            
							<p>
                                                      
                            <button name="plupload-browse-button-avatar" id="plupload-browse-button-avatar" class="getbwp-button-upload-avatar" ><span><i class="fa fa-camera"></i></span> <?php	_e('Select Image', 'get-bookings-wp') ; ?>	</button>
                            </p>
                            
                            <p>
                                                      
                            <button name="plupload-browse-button-avatar" id="btn-delete-user-avatar" class="getbwp-button-delete-avatar" user-id="<?php echo esc_attr($staff_id)?>" redirect-avatar="yes"><span><i class="fa fa-times"></i></span> <?php	_e('Remove', 'get-bookings-wp') ; ?>	</button>
                            </p>
                            
                            <p>
                            <a href="?page=getbookingswp&tab=users&ui=<?php echo esc_attr($staff_id)?>" class="uultra-remove-cancel-avatar-btn"><?php	_e('Cancel', 'get-bookings-wp') ; ?></a>
                            </p>
                                                        
                           
														
						</div>
                        
                        <div id="progressbar-avatar"></div>                 
                         <div id="getbwp_filelist_avatar" class="cb"></div>
					</div>
				</div>
                
                 
			
			</div>
            
           
		</div>

		<?php
		$site_redir = '?page=getbookingswp&tab=users&ui='.$staff_id;
		
		?>
        
         <form id="getbwp_frm_img_cropper" name="getbwp_frm_img_cropper" method="post">                
                
                	<input type="hidden" name="image_to_crop" value="" id="image_to_crop" />
                    <input type="hidden" name="crop_image" value="crop_image" id="crop_image" />
                    
                    <input type="hidden" name="site_redir" value="<?php echo  esc_html($site_redir)?>" id="site_redir" />                   
                
                </form>

		<?php
			
			$plupload_init = array(
				'runtimes'            => 'html5,silverlight,flash,html4',
				'browse_button'       => 'plupload-browse-button-avatar',
				'container'           => 'plupload-upload-ui-avatar',
				'drop_element'        => 'getbwp-drag-avatar-section',
				'file_data_name'      => 'async-upload',
				'multiple_queues'     => true,
				'multi_selection'	  => false,
				'max_file_size'       => wp_max_upload_size().'b',
				//'max_file_size'       => get_option('drag-drop-filesize').'b',
				'url'                 => admin_url('admin-ajax.php'),
				'flash_swf_url'       => includes_url('js/plupload/plupload.flash.swf'),
				'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
				//'filters'             => array(array('title' => __('Allowed Files', $this->text_domain), 'extensions' => "jpg,png,gif,bmp,mp4,avi")),
				'filters'             => array(array('title' => __('Allowed Files', "xoousers"), 'extensions' => "jpg,png,gif,jpeg")),
				'multipart'           => true,
				'urlstream_upload'    => true,

				// Additional parameters:
				'multipart_params'    => array(
					'_ajax_nonce' => wp_create_nonce('photo-upload'),
					'staff_id' => $staff_id,
					'action'      => 'getbwp_ajax_upload_avatar' // The AJAX action name
					
				),
			);
			
			//print_r($plupload_init);

			// Apply filters to initiate plupload:
			$plupload_init = apply_filters('plupload_init', $plupload_init); ?>

			<script type="text/javascript">
			
				jQuery(document).ready(function($){
					
					// Create uploader and pass configuration:
					var uploader_avatar = new plupload.Uploader(<?php echo json_encode($plupload_init); ?>);

					// Check for drag'n'drop functionality:
					uploader_avatar.bind('Init', function(up){
						
						var uploaddiv_avatar = $('#plupload-upload-ui-avatar');
						
						// Add classes and bind actions:
						if(up.features.dragdrop){
							uploaddiv_avatar.addClass('drag-drop');
							
							$('#drag-drop-area-avatar')
								.bind('dragover.wp-uploader', function(){ uploaddiv_avatar.addClass('drag-over'); })
								.bind('dragleave.wp-uploader, drop.wp-uploader', function(){ uploaddiv_avatar.removeClass('drag-over'); });

						} else{
							uploaddiv_avatar.removeClass('drag-drop');
							$('#drag-drop-area').unbind('.wp-uploader');
						}

					});

					
					// Init ////////////////////////////////////////////////////
					uploader_avatar.init(); 
					
					// Selected Files //////////////////////////////////////////
					uploader_avatar.bind('FilesAdded', function(up, files) {
						
						
						var hundredmb = 100 * 1024 * 1024, max = parseInt(up.settings.max_file_size, 10);
						
						// Limit to one limit:
						if (files.length > 1){
							alert("<?php _e('You may only upload one image at a time!', 'get-bookings-wp'); ?>");
							return false;
						}
						
						// Remove extra files:
						if (up.files.length > 1){
							up.removeFile(uploader_avatar.files[0]);
							up.refresh();
						}
						
						// Loop through files:
						plupload.each(files, function(file){
							
							// Handle maximum size limit:
							if (max > hundredmb && file.size > hundredmb && up.runtime != 'html5'){
								alert("<?php _e('The file you selected exceeds the maximum filesize limit.', 'get-bookings-wp'); ?>");
								return false;
							}
						
						});
						
						jQuery.each(files, function(i, file) {
							jQuery('#getbwp_filelist_avatar').append('<div class="addedFile" id="' + file.id + '">' + file.name + '</div>');
						});
						
						up.refresh(); 
						uploader_avatar.start();
						
					});
					
					// A new file was uploaded:
					uploader_avatar.bind('FileUploaded', function(up, file, response){					
						
						
						
						var obj = jQuery.parseJSON(response.response);												
						var img_name = obj.image;							
						
						$("#image_to_crop").val(img_name);
						$("#getbwp_frm_img_cropper").submit();

						
						
						
						jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {"action": "refresh_avatar"},
							
							success: function(data){
								
								//$( "#uu-upload-avatar-box" ).slideUp("slow");								
								$("#uu-backend-avatar-section").html(data);
								
								//jQuery("#uu-message-noti-id").slideDown();
								//setTimeout("hidde_noti('uu-message-noti-id')", 3000)	;
								
								
								}
						});
						
						
					
					});
					
					// Error Alert /////////////////////////////////////////////
					uploader_avatar.bind('Error', function(up, err) {
						alert("Error: " + err.code + ", Message: " + err.message + (err.file ? ", File: " + err.file.name : "") + "");
						up.refresh(); 
					});
					
					// Progress bar ////////////////////////////////////////////
					uploader_avatar.bind('UploadProgress', function(up, file) {
						
						var progressBarValue = up.total.percent;
						
						jQuery('#progressbar-avatar').fadeIn().progressbar({
							value: progressBarValue
						});
						
						jQuery('#progressbar-avatar').html('<span class="progressTooltip">' + up.total.percent + '%</span>');
					});
					
					// Close window after upload ///////////////////////////////
					uploader_avatar.bind('UploadComplete', function() {
						
						//jQuery('.uploader').fadeOut('slow');						
						jQuery('#progressbar-avatar').fadeIn().progressbar({
							value: 0
						});
						
						
					});
					
					
					
				});
				
					
			</script>
			
		<?php
	
	
	}
	
	//crop avatar image
	function getbwp_crop_avatar_user_profile_image()
	{
		global $getbookingwp;
		global $wpdb;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		$site_url = site_url()."/";		
	
		/// Upload file using Wordpress functions:
		$x1 = sanitize_text_field($_POST['x1']);
		$y1 = sanitize_text_field($_POST['y1']);
		
		$x2 = sanitize_text_field($_POST['x2']);
		$y2= sanitize_text_field($_POST['y2']);
		$w = sanitize_text_field($_POST['w']);
		$h = sanitize_text_field($_POST['h']);	
		
		$image_id =   sanitize_text_field($_POST['image_id']);
		$user_id =   sanitize_text_field($_POST['user_id']);		
		
		if($user_id==''){echo esc_attr('error');exit();}
				
		
		$getbookingwp->imagecrop->setDimensions($x1, $y1, $w, $h)	;
		
		$upload_dir = wp_upload_dir(); 
		$path_pics =   $upload_dir['basedir'];		
		$src = $path_pics.'/'.$user_id.'/'.$image_id;
		
		//new random image and crop procedure				
		$getbookingwp->imagecrop->setImage($src);
		$getbookingwp->imagecrop->createThumb();		
		$info = pathinfo($src);
        $ext = $info['extension'];
		$ext=strtolower($ext);		
		$new_i = time().".". $ext;		
		$new_name =  $path_pics.'/'.$user_id.'/'.$new_i;				
		$getbookingwp->imagecrop->renderImage($new_name);
		//end cropping
		
		//check if there is another avatar						
		$user_pic = get_user_meta($user_id, 'user_pic', true);	
		
		//resize
		//check max width		
		$original_max_width = $getbookingwp->get_option('media_avatar_width'); 
        $original_max_height =$getbookingwp->get_option('media_avatar_height'); 
		
		if($original_max_width=="" || $original_max_height=="")
		{			
			$original_max_width = 150;			
			$original_max_height = 150;			
		}
														
		list( $source_width, $source_height, $source_type ) = getimagesize($new_name);
		
		if($source_width > $original_max_width) 
		{
			if ($this->image_resize($new_name, $new_name, $original_max_width, $original_max_height,0)) 
			{
				$old = umask(0);
				chmod($new_name, 0755);
				umask($old);										
			}		
		}					
						
		if ( $user_pic!="" )
		{
				
			 //there is a pending avatar - delete avatar																					
			 	
			 $path_avatar = $path_pics['baseurl']."/".$user_id."/".$image_id;					
										  
			 //delete								
			 //update meta
			  update_user_meta($user_id, 'user_pic', $new_i);		  
			  
		  }else{
			  
			  //update meta
			  update_user_meta($user_id, 'user_pic', $new_i);
								  
		  
		  }
		  
		  
		  if(file_exists($src))
		  {
			  unlink($src);
		  }
			 
	
		// Create response array:
		$uploadResponse = array('image' => esc_attr($new_name));
		
		// Return response and exit:
		echo json_encode($uploadResponse);
		
		die();
		
	}
	
	function image_resize($src, $dst, $width, $height, $crop=0)
	{
		
		  if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";
		
		  $type = strtolower(substr(strrchr($src,"."),1));
		  if($type == 'jpeg') $type = 'jpg';
		  switch($type){
			case 'bmp': $img = imagecreatefromwbmp($src); break;
			case 'gif': $img = imagecreatefromgif($src); break;
			case 'jpg': $img = imagecreatefromjpeg($src); break;
			case 'png': $img = imagecreatefrompng($src); break;
			default : return "Unsupported picture type!";
		  }
		
		  // resize
		  if($crop){
			if($w < $width or $h < $height) return "Picture is too small!";
			$ratio = max($width/$w, $height/$h);
			$h = $height / $ratio;
			$x = ($w - $width / $ratio) / 2;
			$w = $width / $ratio;
		  }
		  else{
			if($w < $width and $h < $height) return "Picture is too small!";
			$ratio = min($width/$w, $height/$h);
			$width = $w * $ratio;
			$height = $h * $ratio;
			$x = 0;
		  }
		
		  $new = imagecreatetruecolor($width, $height);
		
		  // preserve transparency
		  if($type == "gif" or $type == "png"){
			imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
			imagealphablending($new, false);
			imagesavealpha($new, true);
		  }
		
		  imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);
		
		  switch($type){
			case 'bmp': imagewbmp($new, $dst); break;
			case 'gif': imagegif($new, $dst); break;
			case 'jpg': imagejpeg($new, $dst,100); break;
			case 'jpeg': imagejpeg($new, $dst,100); break;
			case 'png': imagepng($new, $dst,9); break;
		  }
		  return true;
	}
	
	function display_avatar_image_to_crop($image, $user_id=NULL)	
	{
		 global $getbookingwp;
		
		/* Custom style */		
		wp_register_style( 'getbwp_image_cropper_style', getbookingpro_url.'js/cropper/cropper.min.css');
		wp_enqueue_style('getbwp_image_cropper_style');	
					
		wp_enqueue_script('simple_cropper',  getbookingpro_url.'js/cropper/cropper.min.js' , array('jquery'), false, false);
		
	  
	    $template_dir = get_template_directory_uri();		  
				
		$site_url = site_url()."/";
		
		$html = "";
		
		$upload_dir = wp_upload_dir(); 
		$upload_folder =   $upload_dir['basedir'];		
				
		$user_pic = get_user_meta($user_id, 'user_profile_bg', true);		
		
		if($image!="")
		{
			$url_image_to_crop = $upload_dir['baseurl'].'/'.$user_id.'/'.$image;			
			$html_image = '<img src="'.$url_image_to_crop.'" id="uultra-profile-cover-horizontal" />';					
			
		}
		
		$my_account_url = '';
		
		
		
		?>
        
        
      	<div id="uultra-dialog-user-bg-cropper-div" class="getbwp-dialog-user-bg-cropper"  >	
				<?php

					echo wp_kses($html_image, $getbookingwp->allowed_html)	;
				
				?>                   
		</div>
            
            
             
             
             <p>
                                                      
                            <button name="plupload-browse-button-avatar" id="uultra-confirm-avatar-cropping" class="getbwp-button-upload-avatar" type="link"><span><i class="fa fa-crop"></i></span> <?php	_e('Crop & Save', 'get-bookings-wp') ; ?>	</button>
                            
                            
                            <div class="getbwp-please-wait-croppingmessage" id="getbwp-cropping-avatar-wait-message">&nbsp;</div>
                            </p>
                            
                            
                            <div class="uultra-uploader-buttons-delete-cancel" id="btn-cancel-avatar-cropping" >
                            <a href="?page=getbookingswp&tab=users&ui=<?php echo esc_attr($user_id)?>" class="uultra-remove-cancel-avatar-btn"><?php	_e('Cancel', 'get-bookings-wp') ; ?></a>
                            </div>
            
     			<input type="hidden" name="x1" value="0" id="x1" />
				<input type="hidden" name="y1" value="0" id="y1" />				
				<input type="hidden" name="w" value="<?php echo esc_attr($w)?>" id="w" />
				<input type="hidden" name="h" value="<?php echo esc_attr($h)?>" id="h" />
                <input type="hidden" name="image_id" value="<?php echo esc_attr($image)?>" id="image_id" />
                <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id)?>" id="user_id" />
                <input type="hidden" name="site_redir" value="<?php echo esc_attr($my_account_url)."?page=getbookingswp&tab=users&ui=".esc_attr($user_id).""?>" id="site_redir" />
                
		
		<script type="text/javascript">
		
		
				jQuery(document).ready(function($){
					
				
					<?php
					
					
					
					$source_img = $upload_folder.'/'.$user_id.'/'.$image;	
									 
					 $r_width = $this->getWidth($source_img);
					 $r_height= $this->getHeight($source_img);
					 
					$original_max_width = $getbookingwp->get_option('media_avatar_width'); 
					$original_max_height =$getbookingwp->get_option('media_avatar_height'); 
					
					if($original_max_width=="" || $original_max_height=="")
					{			
						$original_max_width = 150;			
						$original_max_height = 150;
						
					}
					
					$aspectRatio = $original_max_width/$original_max_height;
					
					
					 
						 ?>
						var $image = jQuery(".getbwp-dialog-user-bg-cropper img"),
						$x1 = jQuery("#x1"),
						$y1 = jQuery("#y1"),
						$h = jQuery("#h"),
						$w = jQuery("#w");
					
					$image.cropper({
								  aspectRatio: <?php echo esc_attr($aspectRatio)?>,
								  autoCropArea: 0.6, // Center 60%
								  zoomable: false,
								  preview: ".img-preview",
								  done: function(data) {
									$x1.val(Math.round(data.x));
									$y1.val(Math.round(data.y));
									$h.val(Math.round(data.height));
									$w.val(Math.round(data.width));
								  }
								});
			
			})	
				
									
			</script>
		
		
	<?php	
		
	}
	
	//You do not need to alter these functions
	function getHeight($image) {
		$size = getimagesize($image);
		$height = $size[1];
		return $height;
	}

	//You do not need to alter these functions
	function getWidth($image) {
		$size = getimagesize($image);
		$width = $size[0];
		return $width;
	}
	
	
	// File upload handler:
	function getbwp_ajax_upload_avatar()
	{
		global $getbookingwp;
		global $wpdb;
		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		$site_url = site_url()."/";
		
		// Check referer, die if no ajax:
		check_ajax_referer('photo-upload');
		
		/// Upload file using Wordpress functions:
		$file = $_FILES['async-upload'];
		
		
		$original_max_width = $getbookingwp->get_option('media_avatar_width'); 
        $original_max_height =$getbookingwp->get_option('media_avatar_height'); 
		
		if($original_max_width=="" || $original_max_height=="")
		{			
			$original_max_width = 150;			
			$original_max_height = 150;
			
		}
		
			
				
		$o_id = sanitize_text_field($_POST['staff_id']);
		
				
		$info = pathinfo($file['name']);
		$real_name = $file['name'];
        $ext = $info['extension'];
		$ext=strtolower($ext);
		
		$rand = $this->genRandomString();
		
		$rand_name = "avatar_".$rand."_".session_id()."_".time(); 
		
	
		$upload_dir = wp_upload_dir(); 
		$path_pics =   $upload_dir['basedir'];
			
			
		if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif') 
		{
			if($o_id != '')
			{
				
				   if(!is_dir($path_pics."/".$o_id."")) 
				   {
						 wp_mkdir_p( $path_pics."/".$o_id );							   
					}					
										
					$pathBig = $path_pics."/".$o_id."/".$rand_name.".".$ext;						
					
					
					if (copy($file['tmp_name'], $pathBig)) 
					{
						//check auto-rotation						
						if($getbookingwp->get_option('avatar_rotation_fixer')=='yes')
						{
							$this->orient_image($pathBig);
						
						}
						
						$upload_folder = $getbookingwp->get_option('media_uploading_folder');				
						$path = $site_url.$upload_folder."/".$o_id."/";
						
						//check max width												
						list( $source_width, $source_height, $source_type ) = getimagesize($pathBig);			
						
						
						
						$new_avatar = $rand_name.".".$ext;						
						$new_avatar_url = $path.$rand_name.".".$ext;				
						
						
						//check if there is another avatar						
						$user_pic = get_user_meta($o_id, 'user_pic', true);						
						
						if ( $user_pic!="" )
			            {
							//there is a pending avatar - delete avatar																					
							$path_avatar = $path_pics."/".$o_id."/".$user_pic;					
														
							//delete								
							if(file_exists($path_avatar))
							{
								unlink($path_avatar);
							}					
												
							
						}else{						
																	
						
						}
						
						//update user meta
						
					}
									
					
			     }  		
			
        } // image type
		
		// Create response array:
		$uploadResponse = array('image' => $new_avatar);
		
		// Return response and exit:
		echo json_encode($uploadResponse);		

		die();
		
	}
	
	public function genRandomString() {
		$length = 5;
		$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZ";
		
		$real_string_legnth = strlen($characters) ;
		//$real_string_legnth = $real_string_legnth– 1;
		$string="ID";
		
		for ($p = 0; $p < $length; $p++)
		{
			$string .= $characters[mt_rand(0, $real_string_legnth-1)];
		}
		
		return strtolower($string);
	}
	
	public function orient_image($file_path) 	{
        if (!function_exists('exif_read_data')) {
            return false;
        }
        $exif = @exif_read_data($file_path);
        if ($exif === false) {
            return false;
        }
        $orientation = intval(@$exif['Orientation']);
        if (!in_array($orientation, array(3, 6, 8))) {
            return false;
        }
        $image = @imagecreatefromjpeg($file_path);
        switch ($orientation) {
            case 3:
                $image = @imagerotate($image, 180, 0);
                break;
            case 6:
                $image = @imagerotate($image, 270, 0);
                break;
            case 8:
                $image = @imagerotate($image, 90, 0);
                break;
            default:
                return false;
        }
        $success = imagejpeg($image, $file_path);
        // Free up memory (imagedestroy does not delete files):
        @imagedestroy($image);
        return $success;
    }
	
	function validate_if_user_has_gravatar($user_id){
		
		$has_gravatar = get_user_meta( $user_id, 'getbwp_has_gravatar', true);
		
		if($has_gravatar=='' || $has_gravatar=='0')
		{			
			//check if user has a valid gravatar
			if($this->getbwp_validate_gravatar($user_id))
			{
				//has a valid gravatar				
				update_user_meta($user_id, 'getbwp_has_gravatar', 1);			
			
			}else{
				
				delete_user_meta($user_id, 'getbwp_has_gravatar')	;		
				
			}
		
		
		}
	
	}
	
	
	/**
	 * Utility function to check if a gravatar exists for a given email or id
	 * @param int|string|object $id_or_email A user ID,  email address, or comment object
	 * @return bool if the gravatar exists or not
	 */
	
	function getbwp_validate_gravatar($id_or_email) {
	  //id or email code borrowed from wp-includes/pluggable.php
		$email = '';
		if ( is_numeric($id_or_email) ) {
			$id = (int) $id_or_email;
			$user = get_userdata($id);
			if ( $user )
				$email = $user->user_email;
		} elseif ( is_object($id_or_email) ) {
			// No avatar for pingbacks or trackbacks
			$allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
			if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
				return false;
	
			if ( !empty($id_or_email->user_id) ) {
				$id = (int) $id_or_email->user_id;
				$user = get_userdata($id);
				if ( $user)
					$email = $user->user_email;
			} elseif ( !empty($id_or_email->comment_author_email) ) {
				$email = $id_or_email->comment_author_email;
			}
		} else {
			$email = $id_or_email;
		}
	
		$hashkey = md5(strtolower(trim($email)));
		$uri = 'http://www.gravatar.com/avatar/' . $hashkey . '?d=404';
	
		$data = wp_cache_get($hashkey);
		if (false === $data) {
			$response = wp_remote_head($uri);
			if( is_wp_error($response) ) {
				$data = 'not200';
			} else {
				$data = $response['response']['code'];
			}
			wp_cache_set($hashkey, $data, $group = '', $expire = 60*5);
	
		}		
		if ($data == '200'){
			return true;
		} else {
			return false;
		}
	}
	
	function validate_gravatar($email) 	{
		// Craft a potential url and test its headers
		/*$hash = md5(strtolower(trim($email)));
		$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
		$headers = @get_headers($uri);
		if (!preg_match("|200|", $headers[0])) {
			$has_valid_avatar = FALSE;
		} else {
			$has_valid_avatar = TRUE;
		}*/
		$has_valid_avatar = TRUE;
		return $has_valid_avatar;
	}

	function get_avatar_url( $avatar) {

		preg_match( '#src=["|\'](.+)["|\']#Uuis', $avatar, $matches );
	
		return ( isset( $matches[1] ) && ! empty( $matches[1]) ) ?
			(string) $matches[1] : '';  
	
	}
	
		
	function get_users_auto_complete()
	{
		global $wpdb, $getbookingwp;
		
		$term     = sanitize_text_field( $_GET['term'] );
		
		// Initialise suggestions array
    	$suggestions=array();		
		$sql = ' SELECT * FROM ' . $wpdb->users . ' WHERE display_name LIKE  
		"%'.$term.'%" OR user_email LIKE "%'.$term.'%" LIMIT 12' ;			
				
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) ){
		
			foreach ( $res as $row ){


				//client details_admin
				$html = '<h4>'.__('Client Data','get-bookings-wp').':</h4>';
				$html .='<span class="nuva-tab-clear-info" id="nuva-tab-clear-info"><i class="fas fa-times"></i></span>';
				$html .='<p><strong>'.__('Name','get-bookings-wp').':</strong> '.$row->display_name.' 
				'.$row->client_last_name.' '.'<strong>'.__('Email','get-bookings-wp').': </strong>'.$row->user_email.'</p>';

				$html = wp_kses($html, $getbookingwp->allowed_html);
												
				$options['results'][] = array(
						'id' => $row->ID,
						'value'    => $row->display_name,
						'label'    => $row->display_name.' '. '('.$row->user_email.')',
						'clinfo'    =>  $html
					); 
					 
							
			}
			
			
			
		
		}else{
			
			$options['results'][] = array(
						'id' => '0',
						'value'    => '0',
						'label'    => __('No results found','get-bookings-wp'),
					);
			
		}			
		
    	wp_send_json( $options);
    	exit();
	
	}
	
	function get_one($id){
		global $wpdb, $getbookingwp;		
		$user = get_user_by( 'id', $id );		
		return $user;
		
	}
	
}
$key = "userpanel";
$this->{$key} = new GetBookingsWPUser();
?>