<?php
class GetBookingsWPAppointment{
	
	function __construct() 	{
	
		add_action( 'wp_ajax_get_all_staff_appointments', array( &$this, 'get_all_staff_appointments' ));
		add_action( 'init', array($this, 'getbwp_handle_post') );		
		add_action( 'wp_ajax_getbwp_admin_new_appointment', array( &$this, 'getbwp_admin_new_appointment' ));
		add_action( 'wp_ajax_getbwp_admin_new_appointment_confirm', array( &$this, 'create_new_appointment' ));
		add_action( 'wp_ajax_appointment_get_selected_time', array( &$this, 'appointment_get_selected_time' ));
		add_action( 'wp_ajax_getbwp_admin_edit_appointment', array( &$this, 'edit_appointment' ));
		add_action( 'wp_ajax_getbwp_get_payments_list', array( &$this, 'appointment_get_payments_list' ));
		add_action( 'wp_ajax_getbwp_get_payment_form', array( &$this, 'getbwp_get_payment_form' ));
		add_action( 'wp_ajax_getbwp_admin_payment_confirm', array( &$this, 'getbwp_admin_payment_confirm' ));
		add_action( 'wp_ajax_getbwp_appointment_confirm_reschedule', array( &$this, 're_schedule_confirm' ));
		add_action( 'wp_ajax_getbwp_update_booking_info', array( &$this, 'getbwp_update_booking_info' ));
		add_action( 'wp_ajax_getbwp_delete_payment', array( &$this, 'getbwp_delete_payment' ));
		add_action( 'wp_ajax_getbwp_get_appointments_quick', array( &$this, 'get_appointments_quick' ));
		add_action( 'wp_ajax_getbwp_update_appointment_status', array( &$this, 'update_appointment_status_inline' ));
		add_action( 'wp_ajax_getbwp_update_payment_status_inline', array( &$this, 'update_payment_status_inline' ));
		
		add_action( 'wp_ajax_getbwp_appointment_status_options', array( &$this, 'get_appointment_status_options' ));
		add_action( 'wp_ajax_getbwp_update_appo_status_ed', array( &$this, 'update_appointment_status_ed' ));
		add_action( 'wp_ajax_getbwp_delete_appointment', array( &$this, 'delete_appointment_ajax' ));
		
		add_action( 'wp_head', array(&$this, 'getbwp_add_template_css_style'),114,1);	
	
	}
	
	public function getbwp_add_template_css_style ($template_id) 
	{
		global $getbookingwp;
		$html = "";
		$custom_css = '';		
		
		if($custom_css!=""  )	{
			$html .= ' <style type="text/css">';
			$html .= $custom_css;
			$html .= ' </style>';			
		}
		
		echo wp_kses($html, $getbookingwp->allowed_html);		
	}

	
	
	function getbwp_handle_post () {		
		
		/*Form is fired*/	    
		if (isset($_GET['getbwpcancelappointment'])) {
			
			/* cancel appointment */
			$this->cancel_appointment_by_staff_client();
		}
		
		/*Form is fired*/	    
		if (isset($_GET['getbwpapprovalappointment'])) {
			
			/* cancel appointment */
			$this->approval_appointment_by_admin();
		}
		
	}
	
	
	public function is_my_appointment($booking_id, $staff_id){
		global  $getbookingwp , $wpdb;
		
		$sql =  'SELECT appo.*, usu.*	 FROM ' . $wpdb->prefix . 'getbwp_bookings appo  ' ;				
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";	
		$sql .= " WHERE  appo.booking_id =%s AND appo.booking_staff_id =%s  ";

		$sql= $wpdb->prepare($sql, array($booking_id, $staff_id));				
		$appointments = $wpdb->get_results($sql );	
		
		if ( !empty( $appointments ) )	{
			
			foreach ( $appointments as  $appointment ) 	{
				return true;
			
			}
		
		}else{
			
			return false;			
			
		}
		
	}
	
	function approval_appointment_by_admin(){
		
		global $wpdb, $getbookingwp;	
		$appointment_key= sanitize_text_field($_GET['getbwpapprovalappointment']);
		$appointment_id= sanitize_text_field($_GET['bupid']);
		
		//change appointment status		
		$appointment = $this->get_appointment_with_key_approval($appointment_key);
		
		if($appointment->booking_id=='' || $appointment_id!= $appointment->booking_id){
		
			_e('Error!','get-bookings-wp');
			die();
			
		}	
		
		$staff_id = $appointment->booking_staff_id;	
		$client_id = $appointment->booking_user_id;	
		$service_id = $appointment->booking_service_id;
		
		/*Update Appointment*/						
		$getbookingwp->appointment->update_appointment_status($appointment->booking_id,1);
		
		/*Get Service*/			
		$service = $getbookingwp->service->get_one_service($service_id);		
		$new_status = $this->get_status_legend(1);							
											 
		//get user				
		$staff_member = get_user_by( 'id', $staff_id );
		$client = get_user_by( 'id', $client_id );					
											
		$getbookingwp->messaging->send_appointment_status_changed($staff_member, $client, $service, $appointment, $new_status);	
		
		//send notifications						
		$this->handle_redir_for_approval($appointment_key);		
	
	}
	
	//the cancellation needs a redirection page
	public function handle_redir_for_approval($key){
		global $getbookingwp, $wp_rewrite ;
		
		$wp_rewrite = new WP_Rewrite();		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$url = '';
		$my_success_url = '';		
					
		$sucess_page_id = $getbookingwp->get_option('appointment_admin_approval_page');
		$my_success_url = get_permalink($sucess_page_id);		
		
		
		if($my_success_url=="")
		{
			$url = sanitize_url($_SERVER['REQUEST_URI'].'&order_status=cancelled&getbwp_order_key='.$key);
		}else{
					
			$url = $my_success_url;				
		}	
		 		  
		wp_redirect( $url );
		exit;	  
		 
	}	
	
	function cancel_appointment_by_staff_client(){
		
		global $wpdb, $getbookingwp;	
		$appointment_key= sanitize_text_field($_GET['getbwpcancelappointment']);
		
		if(isset($_GET['bupid'])){
			
			$appointment_id= sanitize_text_field($_GET['bupid']);
		}		
		
		//change appointment status		
		$appointment = $this->get_appointment_with_key_cancellation($appointment_key);
		
		if($appointment->booking_id==''){
			_e('Error!','get-bookings-wp');
			die();
			
		}
		
		$staff_id = $appointment->booking_staff_id;	
		$client_id = $appointment->booking_user_id;	
		$service_id = $appointment->booking_service_id;
		
		/*Update Appointment*/						
		$getbookingwp->appointment->update_appointment_status($appointment->booking_id,2);		
		
		/*Get Service*/			
		$service = $getbookingwp->service->get_one_service($service_id);		
		$new_status = $this->get_status_legend(2);						
											 
		//get user				
		$staff_member = get_user_by( 'id', $staff_id );
		$client = get_user_by( 'id', $client_id );					
											
		$getbookingwp->messaging->send_appointment_status_changed($staff_member, $client, $service, $appointment, $new_status);	
		
		//send notifications						
		$this->handle_redir_for_cancelation($appointment_key);		
	
	}
	
	
	//the cancellation needs a redirection page
	public function handle_redir_for_cancelation($key){
		global $getbookingwp, $wp_rewrite ;
		
		$wp_rewrite = new WP_Rewrite();		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$url = '';
		$my_success_url = '';		
		
		if($getbookingwp->get_option('appointment_cancellation_active')=='1'){			
			$sucess_page_id = $getbookingwp->get_option('appointment_cancellation_redir_page');
			$my_success_url = get_permalink($sucess_page_id);		
		}
		
		if($my_success_url==""){
			$url = sanitize_url($_SERVER['REQUEST_URI'].'&order_status=cancelled&getbwp_order_key='.$key);
				
		}else{
					
			$url = $my_success_url;				
				
		}		
		 		  
		wp_redirect( $url );
		exit;
		  
		 
	}	
	
	function getbwp_admin_new_appointment () 
	{		
		//turn on output buffering to capture script output
        ob_start();		
		include(getbookingpro_path."admin/templates/new_appointment.php");
        $content = ob_get_clean();		
		echo wp_kses($content, $getbookingwp->allowed_html) ;		
		die();
	}
	
	function edit_appointment () 
	{
		global  $getbookingwp;

		$appointment_id = sanitize_text_field($_POST['appointment_id']);
		
		//turn on output buffering to capture script output
        ob_start();		
		include(getbookingpro_path."admin/templates/edit_appointment.php");
        $content = ob_get_clean();		
		echo wp_kses($content, $getbookingwp->allowed_html) ;			
		die();
	}
	
	function delete_appointment_ajax () 
	{
		global $wpdb, $getbookingwp, $getbwpcomplement;	
		$appointment_id = sanitize_text_field($_POST['appointment_id']);
		
		
		//delete appointment on Google Calendar		
		if(isset($getbwpcomplement))
		{
			$event_id = $this->get_booking_meta($appointment_id, 'google_event_id');
			$calendar_id = $this->get_booking_meta($appointment_id, 'google_calendar_id');
			$appointment = 		$this->get_one($appointment_id);
			$staff_id = $appointment->booking_staff_id;
			
			//get appointment meta gcal event id and google calendar id			
			$getbwpcomplement->googlecalendar->delete_event($event_id, $calendar_id, $staff_id);
		
		}
		
		//delete meta data
		$sql = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'getbwp_bookings_meta  WHERE meta_booking_id=%d  ;',array($appointment_id));		
		$wpdb->query($sql);
		
		//delete payments
		$sql = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'getbwp_orders  WHERE order_booking_id=%d  ;',array($appointment_id));		
		$wpdb->query($sql);
		
		//delete notes
		$sql = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'getbwp_appointment_notes  WHERE note_appointment_id=%d  ;',array($appointment_id));		
		$wpdb->query($sql);
		
		//delete appointment
		$sql = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'getbwp_bookings  WHERE booking_id=%d  ;',array($appointment_id));		
		$wpdb->query($sql);
				
		die();
	}
	
	
	
	function get_appointment_edition_form_fields ($booking_id) 
	{
		
		global $wpdb, $getbookingwp;
		
		$custom_form = $this->get_booking_meta($booking_id, 'custom_form');
		
		
		$form_id = '';
		$display ='';
		if(isset($_POST["form_id"])){
			
			$form_id = sanitize_text_field($_POST["form_id"]);
		
		}
		
		/* Get end of array */			
		if($custom_form!="" || $form_id !="")
		{
			//do we have a pre-set value in the get?			
			if($form_id !="")
			{
				$custom_form =$form_id;			
			}
			
			$custom_form = 'getbwp_profile_fields_'.$custom_form;		
			$array = get_option($custom_form);			
			$fields_set_to_update =$custom_form;
			
		}else{
			
			$array = get_option('getbwp_profile_fields');
			$fields_set_to_update ='getbwp_profile_fields';
		
		}
		

		foreach($array as $key=>$field) 
		{		     
		    $exclude_array = array('user_pass', 'user_pass_confirm', 'user_email');
		    if(isset($field['meta']) && in_array($field['meta'], $exclude_array))
		    {
		        unset($array[$key]);
		    }
		}
		
		$i_array_end = end($array);
		
		if(isset($i_array_end['position']))
		{
		    $array_end = $i_array_end['position'];
		    
			if (isset($array[$array_end]['type']) && $array[$array_end]['type'] == 'seperator') 
			{
				if(isset($array[$array_end]))
				{
					unset($array[$array_end]);
				}
			}
		}
		
		
		/*Display custom profile fields added by the user*/		
		foreach($array as $key => $field) 
		{

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
			
			if($required == 1 )
			{				
			    $required_class = 'validate[required] ';
				$required_text = '(*)';				
			}
			
			
			/* This is a Fieldset seperator */
						
			/* separator */
            if ($type == 'separator' && isset($array[$key]['show_in_register']) && $array[$key]['show_in_register'] == 1) 
			{
                   $display .= '<div class="getbwp-profile-separator">'.$name.'</div>';
				   
            }
			
					
			//check if display emtpy				
				
			if ($type == 'usermeta' &&  isset($array[$key]['show_in_register']) && $array[$key]['show_in_register'] == 1) 
			{
								
				$display .= '<div class="getbwp-profile-field">';
				
				/* Show the label */
				if (isset($array[$key]['name']) && $name)
				 {
					$display .= '<label class="getbwp-field-type" for="'.$meta.'">';	
					
					if (isset($array[$key]['icon']) && $icon) 
					{
						
                            $display .= '<i class="fa fa-' . $icon . '"></i>';
							
                    } else {
						
                            $display .= '<i class="fa fa-icon-none"></i>';
                    }
					
					
											
					$tooltipip_class = '';					
					if (isset($array[$key]['tooltip']) && $tooltip)
					{
						$qtip_classes = 'qtip-light ';	
						$qtip_style = '';					
					
						 //$tooltipip_class = '<a class="'.$qtip_classes.' uultra-tooltip" title="' . $tooltip . '" '.$qtip_style.'><i class="fa fa-info-circle reg_tooltip"></i></a>';
					} 
					
											
					$display .= '<span>'.$name. ' '.$required_text.' '.$tooltipip_class.'</span></label>';
					
					
				} else {
					
					$display .= '<label class="">&nbsp;</label>';
				}
				
				$display .= '<div class="getbwp-field-value">';
					
					switch($field) {
					
						case 'textarea':
							$display .= '<textarea class="'.$required_class.' getbwp-custom-field getbwp-input getbwp-input-text-area" rows="10" name="'.$meta.'" id="'.$meta.'" title="'.$name.'" data-errormessage-value-missing="'.__(' * This input is required!','get-bookings-wp').'">'.$getbookingwp->appointment->get_booking_meta($booking_id, $meta).'</textarea>';
							break;
							
						case 'text':
							$display .= '<input type="text" class="'.$required_class.' getbwp-custom-field getbwp-input"  name="'.$meta.'" id="'.$meta.'" value="'.$getbookingwp->appointment->get_booking_meta($booking_id, $meta).'"  title="'.$name.'" data-errormessage-value-missing="'.__(' * This input is required!','get-bookings-wp').'"/>';
							break;							
							
						case 'datetime':						
						    $display .= '<input type="text" class="'.$required_class.' getbwp-custom-field getbwp-input getbwp-datepicker" name="'.$meta.'" id="'.$meta.'" value="'.$getbookingwp->appointment->get_booking_meta($booking_id, $meta).'"  title="'.$name.'" />';
						    break;
							
						case 'select':
												
							if (isset($array[$key]['predefined_options']) && $array[$key]['predefined_options']!= '' && $array[$key]['predefined_options']!= '0' )
							
							{
								$loop = $getbookingwp->commmonmethods->get_predifined( $array[$key]['predefined_options'] );
								
							}elseif (isset($array[$key]['choices']) && $array[$key]['choices'] != '') {
								
															
								$loop = $getbookingwp->uultra_one_line_checkbox_on_window_fix($choices);
								 	
								
							}
							
							if (isset($loop)) 
							{
								$display .= '<select class="'.$required_class.' getbwp-custom-field getbwp-input" name="'.$meta.'" id="'.$meta.'" title="'.$name.'" data-errormessage-value-missing="'.__(' * This input is required!','get-bookings-wp').'">';
								
								foreach($loop as $option)
								{
									
									$option = trim(stripslashes($option));							
								    
									$display .= '<option value="'.$option.'" '.selected( $getbookingwp->appointment->get_booking_meta($booking_id, $meta), $option, 0 ).'>'.$option.'</option>';
									
								}
								$display .= '</select>';
							}
							
							break;
							
						case 'radio':						
						
							if($required == 1 && in_array($field, $this->include_for_validation))
							{
								$required_class = "validate[required] radio ";
							}
						
							if (isset($array[$key]['choices']))
							{				
													
								
								 $loop = $getbookingwp->uultra_one_line_checkbox_on_window_fix($choices);
								
							}
							if (isset($loop) && $loop[0] != '') 
							{
							  $counter =0;
							  
								foreach($loop as $option)
								{
								    if($counter >0)
								        $required_class = '';
								    
								    $option = trim(stripslashes($option));
									$display .= '<input type="radio" class="'.$required_class.' getbwp-custom-field" title="'.$name.'" name="'.$meta.'" id="uultra_multi_radio_'.$meta.'_'.$counter.'" value="'.$option.'" '.checked( $getbookingwp->appointment->get_booking_meta($booking_id, $meta), $option, 0 );
									$display .= '/> <label for="uultra_multi_radio_'.$meta.'_'.$counter.'"><span></span>'.$option.'</label>';
									
									$counter++;
									
								}
							}
							
							break;
							
						case 'checkbox':
						
						
							if($required == 1 && in_array($field, $this->include_for_validation))
							{
								$required_class = "validate[required] checkbox ";
							}						
						
							if (isset($array[$key]['choices'])) 
							{
																
								 $loop = $getbookingwp->uultra_one_line_checkbox_on_window_fix($choices);
								
								
							}
							
							$saved_choices = $getbookingwp->appointment->get_booking_meta($booking_id, $meta);
							$saved_choices = explode(',',$saved_choices);
							$saved_choices=array_map('trim',$saved_choices);
							
							if (isset($loop) && $loop[0] != '') 
							{
							  $counter =0;
							  
								foreach($loop as $option)
								{
								   
								   if($counter >0)
								        $required_class = '';
								  
								  $option = trim(stripslashes($option));
								  
								  $display .= '<div class="getbwp-checkbox getbwp-custom-field">
								  <input type="checkbox" class="'.$required_class.'" title="'.$name.'" name="'.$meta.'[]" id="getbwp_multi_box_'.$meta.'_'.$counter.'" value="'.$option.'" ';
									if (in_array($option, $saved_choices ))
									{
										$display .= 'checked="checked"';
									}
									$display .= '/> <label for="getbwp_multi_box_'.$meta.'_'.$counter.'"> '.$option.'</label> </div>';
									
									
									$counter++;
								}
							}
							
							break;	
						
													
						
							
					}
					
					
					if (isset($array[$key]['help_text']) && $help_text != '') 
					{
						$display .= '<div class="getbwp-help">'.$help_text.'</div>';
					}							
					
				$display .= '</div>';
				$display .= '</div>';
				
			}
		} //end for each
		
		return $display;
	}
	
	public function ini_module()
	{
		global $wpdb;	
		
		
	}
	
	public function update_booking_meta($booking_id, $key, $value)
	{
		
		global $wpdb, $getbookingwp;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'getbwp_bookings_meta  WHERE 
		meta_booking_id = %s  AND 
		meta_booking_name= %s ' ;
		$sql = $wpdb->prepare($sql,array($booking_id, $key));				
		$rows = $wpdb->get_results($sql);	
		
		
		
		if ( !empty( $rows ))
		{
			$query = "UPDATE " . $wpdb->prefix ."getbwp_bookings_meta SET meta_booking_value =%s WHERE 
			meta_booking_name = %s AND meta_booking_id =%s ";
			$query = $wpdb->prepare($query,array($value, $key, $booking_id));	
			$wpdb->query( $query );		
		
		}else{
			
			$query = "INSERT INTO " . $wpdb->prefix ."getbwp_bookings_meta ( meta_booking_value, meta_booking_name ,meta_booking_id ) 
			VALUES(%s , %s, %s) ";
			$query = $wpdb->prepare($query,array($value, $key, $booking_id));	
			$wpdb->query( $query );
		
		}
		
	
	}
	
	public function getbwp_delete_payment()
	{
		
		global $wpdb, $getbookingwp;
		
		$payment_id = sanitize_text_field($_POST['payment_id']);	
		$appointment_id = sanitize_text_field($_POST['appointment_id']);			
		
		$query = "DELETE FROM " . $wpdb->prefix ."getbwp_orders WHERE order_booking_id = '".$appointment_id."' AND order_id = '".$payment_id."' ";
		$wpdb->query( $query );		
		
		die();
	
	}
	
	
	
	public function get_booking_meta($booking_id, $key)
	{
		
		global $wpdb, $getbookingwp;
		
		$html='';
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'getbwp_bookings_meta  WHERE
		 meta_booking_id = %s  AND meta_booking_name= %s ' ;
		 
		 $sql = $wpdb->prepare($sql,array(trim($booking_id), trim($key)));
		$rows = $wpdb->get_results($sql);
		
			
		if ( !empty( $rows ))
		{
			foreach ( $rows as $row )
			{				
				$html =stripslashes($row->meta_booking_value);		
			
			}	
		
		}
		
		return $html;		
	
	}
	
	public function getbwp_update_booking_info()
	{
		
		global $wpdb, $getbookingwp;	
		
		$html='';	
			
		$getbwp_custom_fields = sanitize_text_field($_POST['custom_fields']);
		$booking_id = sanitize_text_field($_POST['booking_id']);	
		
		$exploded = array();
		parse_str($getbwp_custom_fields, $exploded);
		

		
		foreach($exploded as $field => $value)
		{
			if (is_array($value))   // checkboxes
			{
				$value = implode(',', $value);
			}	
						
			$this->update_booking_meta($booking_id, $field, $value);
		
		}	
		
		
		echo wp_kses($html, $getbookingwp->allowed_html);;
		die();
		
				
	
	}
	
	public function getbwp_admin_payment_confirm()
	{
		
		global $wpdb, $getbookingwp;	
		
		$html='';	
		
		$getbwp_payment_amount = sanitize_text_field($_POST['getbwp_payment_amount']);
		$getbwp_payment_transaction = sanitize_text_field($_POST['getbwp_payment_transaction']);		
		$getbwp_payment_date = sanitize_text_field($_POST['getbwp_payment_date']);
		$getbwp_booking_id = sanitize_text_field($_POST['getbwp_booking_id']);	
		$getbwp_payment_id = sanitize_text_field($_POST['getbwp_payment_id']);
		$getbwp_payment_status = sanitize_text_field($_POST['getbwp_payment_status']);	
		
		if($getbwp_booking_id!='' && $getbwp_payment_amount!='' && $getbwp_payment_date!='' && $getbwp_payment_id=='')		
		{
					
			$query = "INSERT INTO " . $wpdb->prefix ."getbwp_orders (`order_booking_id`,`order_txt_id`, `order_method_name`, `order_status` ,`order_amount` , `order_date`) VALUES ('$getbwp_booking_id','".$getbwp_payment_transaction."','local','".$getbwp_payment_status."', '$getbwp_payment_amount',  
			'".date('Y-m-d',strtotime($getbwp_payment_date)) ."')";
			
			$wpdb->query( $query );
			$html ='OK';
		
	    }else{
			
			$query = "UPDATE " . $wpdb->prefix ."getbwp_orders  SET `order_txt_id` = '".$getbwp_payment_transaction."',  `order_status` = '".$getbwp_payment_status."' ,`order_amount` = '$getbwp_payment_amount' , `order_date` = '".date('Y-m-d',strtotime($getbwp_payment_date)) ."' WHERE  `order_booking_id` = '$getbwp_booking_id' AND `order_ID` = '$getbwp_payment_id' ";
			
			$wpdb->query( $query );
			$html ='OK';
			
			
		}
		
		echo wp_kses($html, $getbookingwp->allowed_html);
		die();
		
				
	
	}
	
	
	public function getbwp_get_payment_form () 
	{
		global $wpdb, $getbookingwp;
		
		$html='';	
		
		$order_amount='';
		$order_txt_id='';
		
		$payment_id = '';		
		if(isset($_POST['payment_id'])){
			
			$payment_id = sanitize_text_field($_POST['payment_id']);
		}
		
		$appointment_id = '';		
		if(isset($_POST['appointment_id'])){
			
			$appointment_id = sanitize_text_field($_POST['appointment_id']);
		
		}
		
	
		$order_date =	date('m/d/Y');
		
		$status_pending ='';
		$status_confirmed ='';
		
		if($payment_id!='' && $appointment_id!='')		
		{
			//get payments			
			$order = $getbookingwp->order->get_order_edit( $payment_id , $appointment_id);
			$order_date =	date('m/d/Y', strtotime($order->order_date));
			
			$order_amount =	$order->order_amount;
			$order_txt_id =	$order->order_txt_id;			
			
		}			
			
		$html .= '<p>'.__('Amount:','get-bookings-wp').'</p>' ;	
		$html .= '<p><input type="text" id="getbwp_payment_amount" value="'.$order_amount.'"></p>' ;
		$html .= '<p>'.__('Transaction ID:','get-bookings-wp').'</p>' ;	
		$html .= '<p><input type="text" id="getbwp_payment_transaction" value="'.$order_txt_id.'"></p>' ;		
		$html .= '<p>'.__('Date:','get-bookings-wp').'</p>' ;	
		
		$html .= '<p>'.'<input type="text" class="bupro-datepicker" id="getbwp_payment_date" value="'.$order_date .'" /></p>' ;
		
		
		$html .= '<input type="hidden" id="getbwp_payment_id" value="'.$payment_id .'" />' ;
		
		$html .= '<p>'.__('Status:','get-bookings-wp').'</p>' ;
		$html .= '<p><select name="getbwp_payment_status" id="getbwp_payment_status">
				  <option value="pending" >'.__('Pending','get-bookings-wp').'</option>
				  <option value="confirmed" selected>'.__('Confirmed','get-bookings-wp').'</option>
				</select>' ;
		
				
		echo wp_kses($html, $getbookingwp->allowed_html);		
		die();
	
	}
	
	
	public function re_schedule_confirm () 
	{
		global $wpdb, $getbookingwp;
		
		$html='';		
				
		//create reservation in reservation table	
		$booking_id = sanitize_text_field($_POST['booking_id']);			
		$day_id = sanitize_text_field($_POST['getbwp_booking_date']);
		$service_and_staff_id = sanitize_text_field($_POST['getbwp_service_staff']);
		$time_slot = sanitize_text_field($_POST['getbwp_time_slot']);		
		$getbwp_notify_client_reschedule = sanitize_text_field($_POST['notify_client']);			
		
		$arr_ser = explode("-", $service_and_staff_id);			
		$service_id = $arr_ser[0]; 
		$staff_id = $arr_ser[1];
		
		$arr_time_slot = explode("-", $time_slot);			
		$book_from = $arr_time_slot[0]; 
		$book_to = $arr_time_slot[1];	
		
		$service_details = $getbookingwp->userpanel->get_staff_service_rate( $staff_id, $service_id ); 
		$amount= $service_details['price'];
		
		$booking_time_from = $day_id .' '.$book_from.':00';
		
		//appointment		
		$appointment = $getbookingwp->appointment->get_one($booking_id);
		
		$client_id = $appointment->booking_user_id;
		
				
		//service			
		$service = $getbookingwp->service->get_one_service($service_id);		
		
		$currency = $getbookingwp->get_option('currency_symbol');		
		$time_format = $getbookingwp->service->get_time_format();		
		$booking_time = date($time_format, strtotime($booking_time_from ))	;		
		$booking_day = date('l, j F, Y', strtotime($booking_time_from));		
				
						
		$staff_member = get_user_by( 'id', $staff_id );	
		$client = get_user_by( 'id', $client_id );					
										
		
		
		
		$order_data = array(
				
						'booking_id' => $booking_id,					 			 
						 'amount' => $amount,
						 'service_id' => $service_id ,
						 'staff_id' => $staff_id ,						 					 
						 'day' => $day_id,
						 'time_from' => $book_from,
						 'time_to' => $book_to
						 
						 ); 
						 
		$getbookingwp->order->update_appointment($order_data);
		
		//appointment		
		$appointment = $getbookingwp->appointment->get_one($booking_id);
		
		$getbookingwp->messaging->send_reschedule_notification_on_admin($staff_member, $client, $service, $appointment,  $getbwp_notify_client_reschedule );
		
											
		$html .= '<p><strong>'.__('Done!. The appointment has been rescheduled. Below are the new details.','get-bookings-wp').'</strong></p>';		
		$html .= '<p>'.__('Appointment Details.','get-bookings-wp').'</p>';
		
		$html .= '<p>'.__('Service: '.$service->service_title.'','get-bookings-wp').'</p>' ;	
		$html .= '<p>'.__('Date: '.$booking_day.'','get-bookings-wp').'</p>' ;
		$html .= '<p>'.__('Time: '.$booking_time.'','get-bookings-wp').'</p>' ;
		$html .= '<p>'.__('With: '.$staff_member->display_name.'','get-bookings-wp').'</p>' ;
		$html .= '<p>'.__('Cost: '.$currency.$amount.'','get-bookings-wp').'</p>';
				
		echo wp_kses($html, $getbookingwp->allowed_html);		
		die();
	
	}
	
	public function appointment_get_selected_time () 
	{
		global $wpdb, $getbookingwp;
		
		$html='';		
				
		//create reservation in reservation table				
		$day_id = sanitize_text_field($_POST['getbwp_booking_date']);
		$service_and_staff_id = sanitize_text_field($_POST['getbwp_service_staff']);
		$time_slot = sanitize_text_field($_POST['getbwp_time_slot']);	

		$getbwp_notify_client = '';			
		
		if(isset($_POST['getbwp_notify_client'])){

			$getbwp_notify_client = sanitize_text_field($_POST['getbwp_notify_client']);		

		}		
		
		$arr_ser = explode("-", $service_and_staff_id);			
		$service_id = $arr_ser[0]; 
		$staff_id = $arr_ser[1];
		
		$arr_time_slot = explode("-", $time_slot);			
		$book_from = $arr_time_slot[0]; 
		$book_to = $arr_time_slot[1];	
		
		$service_details = $getbookingwp->userpanel->get_staff_service_rate( $staff_id, $service_id ); 
		$amount= $service_details['price'];
		
		$booking_time_from = $day_id .' '.$book_from.':00';
		
				
		//service			
		$service = $getbookingwp->service->get_one_service($service_id);		
		
		$currency = $getbookingwp->get_option('currency_symbol');		
		$time_format = $getbookingwp->service->get_time_format();		
		$booking_time = date($time_format, strtotime($booking_time_from ))	;		
		$booking_day = date('l, j F, Y', strtotime($booking_time_from));		
				
						
		$staff_member = get_user_by( 'id', $staff_id );						
				
		$html .= '<p><strong>'.__('Appointment Details.','get-bookings-wp').'</strong></p>';
		
		$html .= '<p>'.__('Service: ','get-bookings-wp').$service->service_title.'</p>' ;	
		$html .= '<p>'.__('Date: ','get-bookings-wp').$booking_day.'</p>' ;
		$html .= '<p>'.__('Time: ','get-bookings-wp').$booking_time.'</p>' ;
		$html .= '<p>'.__('With: ','get-bookings-wp').$staff_member->display_name.'</p>' ;
		$html .= '<p>'.__('Cost: ','get-bookings-wp').$currency.$amount.'</p>';
				
		echo wp_kses($html, $getbookingwp->allowed_html);		
		die();
	
	}
	
	public function appointment_get_payments_list () 
	{
		global $wpdb, $getbookingwp;
		
		$html='';		
				
		//create reservation in reservation table				
		$appointment_id = sanitize_text_field($_POST['appointment_id']);	
		
		$totals = array();	
		
		$orders = $getbookingwp->order->get_booking_payments($appointment_id ); 		
		$currency = $getbookingwp->get_option('currency_symbol');		
		$time_format = $getbookingwp->service->get_time_format();
		
		$totals = $getbookingwp->order->get_booking_payments_balance($appointment_id );
		$paid = $currency.$totals['confirmed']; 
		$pending = $currency.$totals['pending'];	
		$balance = $currency.$totals['balance'];
		$cost = $currency.$totals['cost'];
		
		if($totals['pending']==0){$class_pending = 'bupendingok'; }else{$class_pending = 'bupending';} 
		
				
		if (!empty($orders)){
			
			
			$html .= '<div class="getbwp-financial-list"> ';
           
		    $html .= ' <ul>';
            $html .= '<li class="bupaid"><h3>'.__('Service Cost','get-bookings-wp').'</h3><p class="bupaid">'.$cost.'</p></li>
			<li class="bupaid"><h3>'.__('Paid','get-bookings-wp').'</h3><p class="bupaid">'.$paid.'</p></li>
                      <li class="bupending"><h3>'.__('Pending','get-bookings-wp').'</h3><p class="'.$class_pending.'">'.$pending.'</p></li>
                     ';
                
          $html .= '  </ul> ';
        
          $html .= ' </div>';		
				
				       
          $html .= '<table width="100%" class="wp-list-table widefat fixed posts table-generic"> ';
           $html .= ' <thead>
                <tr>
                    <th width="3%">'.__('#', 'get-bookings-wp').'</th>
                    <th width="11%">'.__('Date', 'get-bookings-wp').'</th>                     
                    <th width="16%">'.__('Transaction ID', 'get-bookings-wp').'</th>
                    <th width="9%">'.__('Method', 'get-bookings-wp').'</th>
                     <th width="9%">'.__('Status', 'get-bookings-wp').'</th>
                    <th width="9%">'.__('Amount', 'get-bookings-wp').'</th>
					<th width="9%">'.__('Actions', 'get-bookings-wp').'</th>
                </tr>
            </thead>';
            
           $html .= ' <tbody>';
            
           
				foreach($orders as $order) {
					
					$order_tr = $order->order_txt_id;
					if($order->order_txt_id==''){$order_tr = 'N/A';}
					
					if($order->order_status=='pending'){$class_pending = 'bupending'; }else{$class_pending = 'buconfirmed';}
						
							  
	
				  $html .= '   <tr>
						<td>'.$order->order_id.'</td>
						<td>'. date("m/d/Y", strtotime($order->order_date)).'</td>
						 
						<td>'. $order_tr.'</td>
						 <td>'. $order->order_method_name.'</td>
						  <td class="'.$class_pending.'">'. $order->order_status.'</td>
					   <td> '. $currency.$order->order_amount.'</td>
					   <td> <a href="#" title="'.__('Delete', 'get-bookings-wp').'" class="getbwp-payment-deletion" getbwp-payment-id="'.$order->order_id.'" getbwp-appointment-id="'.$appointment_id.'"> <i class="fa fa-remove"> </i> </a>
					<a href="#" title="'.__('Edit', 'get-bookings-wp').'" class="getbwp-payment-edit" getbwp-payment-id="'.$order->order_id.'" getbwp-appointment-id="'.$appointment_id.'"> <i class="fa fa-pencil"> </i> </a>   
					   </td>
					</tr>';
									
					
				   
				}
					
			} else {
			
			$html .='<p>'.__('There are no transactions yet.','get-bookings-wp').'</p>';
			} 

       $html .='     </tbody>
        </table>';
        
				
		echo wp_kses($html, $getbookingwp->allowed_html);		
		die();
	
	}
	
	public function create_new_appointment () 
	{
		global $wpdb, $getbookingwp, $getbwpcomplement;
		session_start();
		
		//create transaction
		$transaction_key = session_id()."_".time();	
		
		$html='';		
				
		//create reservation in reservation table				
		$day_id = sanitize_text_field($_POST['getbwp_booking_date']);
		$service_and_staff_id = sanitize_text_field($_POST['getbwp_service_staff']);
		$time_slot = sanitize_text_field($_POST['getbwp_time_slot']);
		$client_id = sanitize_text_field($_POST['getbwp_client_id']);
		$getbwp_notify_client = sanitize_text_field($_POST['getbwp_notify_client']);			
		
		$arr_ser = explode("-", $service_and_staff_id);			
		$service_id = $arr_ser[0]; 
		$staff_id = $arr_ser[1];
		
		$arr_time_slot = explode("-", $time_slot);			
		$book_from = $arr_time_slot[0]; 
		$book_to = $arr_time_slot[1];	
		
		$service_details = $getbookingwp->userpanel->get_staff_service_rate( $staff_id, $service_id ); 
		$amount= $service_details['price'];	
		
		$order_data = array(
		
				'user_id' => $client_id,	
				 'transaction_key' => $transaction_key,					 
				 'amount' => $amount,
				 'service_id' => $service_id ,
				 'staff_id' => $staff_id ,
				 'product_name' => $p_name ,						 
				 'day' => $day_id,
				 'time_from' => $book_from,
				 'time_to' => $book_to
				 
				 ); 
		
		$booking_id =  $getbookingwp->order->create_reservation($order_data);	
		
		//service			
		$service = $getbookingwp->service->get_one_service($service_id);
		
		//create order					  
		$order_data_tran = array('user_id' => $user_id,
						 'transaction_key' => $transaction_key,
						 'amount' => $amount,
						 'booking_id' => $booking_id ,
						 'product_name' => $p_name ,
						 'status' => 'pending',		
						 'service_id' => $service_id ,
						 'staff_id' => $staff_id ,				
						 'method' => $payment_method,
						 ); 						 
						 
						
		$order_id = $getbookingwp->order->create_order($order_data_tran);	
		
		// Get Order
		$rowOrder = $getbookingwp->order->get_order_pending($transaction_key);								
	
		/*Update Appointment*/						
		$getbookingwp->appointment->update_appointment_status($booking_id,1);
		
		//get appointment			
		$appointment = $getbookingwp->appointment->get_one($booking_id);
		
		$currency = $getbookingwp->get_option('currency_symbol');		
		$time_format = $getbookingwp->service->get_time_format();		
		$booking_time = date($time_format, strtotime($appointment->booking_time_from ))	;		
		$booking_day = date('l, j F, Y', strtotime($appointment->booking_time_from ));		
				
		/*Notify Admin Only of Appointment*/		
					
		$staff_member = get_user_by( 'id', $staff_id );
		$client = get_user_by( 'id', $client_id );					
										
		$getbookingwp->messaging->send_booking_notification_on_admin($staff_member, $client, $service, $appointment,$rowOrder, $getbwp_notify_client );
		
		
		$google_client_id = $getbookingwp->get_option('google_calendar_client_id');
		$google_client_secret = $getbookingwp->get_option('google_calendar_client_secret');
				
		//google calendar				
		if(isset($getbwpcomplement) && $google_client_id!='' && $google_client_secret!='' )
		{				
					
			$getbwpcomplement->googlecalendar->create_event($booking_id,$order_data);						
				
		}
				
		
		$html .= '<p>'.__('The appointment has been created.','get-bookings-wp').'</p>';
		$html .= '<p><strong>'.__('Appointment Details.','get-bookings-wp').'</strong></p>';
		
		$html .= '<p>'.__('Service: '.$service->service_title.'','get-bookings-wp').'</p>' ;	
		$html .= '<p>'.__('Date: '.$booking_day.'','get-bookings-wp').'</p>' ;
		$html .= '<p>'.__('Time: '.$booking_time.'','get-bookings-wp').'</p>' ;
		$html .= '<p>'.__('With: '.$staff_member->display_name.'','get-bookings-wp').'</p>' ;
		$html .= '<p>'.__('Cost: '.$currency.$rowOrder->order_amount.'','get-bookings-wp').'</p>';

		$html = wp_kses($html, $getbookingwp->allowed_html);		
		$response = array('booking_id' => $booking_id, 'content' => $html);
		print_r( json_encode($response) );		
		
		die();
	
	}
	
	
	public function get_public_booking_form ($atts) 	{
		global $wpdb, $getbookingwp, $getbwpcomplement;
		
		extract( shortcode_atts( array(
		
			'staff_id' => NULL,
			'service_id' => NULL,
			'available_legend' => __('Avaliable Services','get-bookings-wp'),
			'available_text' => __('Please choose one of these services','get-bookings-wp'),
			'activate_woocommerce' => 'no',		
			'display_steps' => 'yes',	
			'only_staff_list' => 'no',	
			'hidde_staff_photo' => 'no',
			'book_from_staff_profile' => 'no',		
			'form_id' => NULL, 
			'location_id' => NULL,
			'redirect_url' => NULL,
			'field_legends' => 'yes',
			'placeholders' => 'yes',
			'template_id' => NULL,
			'category_ids' => NULL,
			'auto_display_slots' => 'no',
			'auto_display_staff' => 'no'	,
			'template' => 'default'		
			
			
		), $atts ) );
		
		
		
		if($template==''||  $template=='default'){
			$template='appointment';
		}elseif( $template=='sidebar'){
			$template='appointment_side_bar';
		}elseif( $template=='dropdown'){
			$template='appointment_drop_down';

		}		
		
		//turn on output buffering to capture script output
        ob_start();		
		
		$theme_path = get_template_directory();		
		
		if(file_exists($theme_path."/getbwp/".$template.".php"))	{			
			include($theme_path."/getbwp/".$template.".php");
		
		}else{			
			include(getbookingpro_path."templates/".$template.".php");		
		}
		
		
        $content = ob_get_clean();
		
		return $content ;
		
	
	}
	
	
	public function update_appointment_status ($id,$status)
	{
		global $wpdb,  $getbookingwp;
		
		$query = "UPDATE " . $wpdb->prefix ."getbwp_bookings SET booking_status = '$status' WHERE booking_id = '$id' ";
		$wpdb->query( $query );
	
	}
	
	public function get_cancel_link_of_appointment ($appointment_key, $appointment_id)
	{
		global   $getbookingwp;		
		
		$site_url =site_url("/");		
		$link = $site_url.'?getbwpancelappointment='.$appointment_key.'&bupid='.$appointment_id;
		
		$link = '<a href="'.$link.'">'.$link.'</a>';
		
		return $link;
	
	}
	
	public function get_approval_link_of_appointment ($appointment_key, $appointment_id)
	{
		global   $getbookingwp;		
		
		$site_url =site_url("/");		
		$link = $site_url.'?getbwpapprovalappointment='.$appointment_key.'&bupid='.$appointment_id;
		
		$link = '<a href="'.$link.'">'.$link.'</a>';
		
		return $link;
	
	}
	
	
	
	

	
	public function get_one_service ($service_id) 
	{
		global $wpdb, $getbookingwp;
		
		$sql = ' SELECT serv.*, cate.* FROM ' . $wpdb->prefix . 'getbwp_services  serv ' ;		
		$sql .= " RIGHT JOIN ".$wpdb->prefix ."getbwp_categories cate ON (cate.cate_id = serv.service_category_id)";
		$sql .= ' WHERE cate.cate_id = serv.service_category_id' ;			
		$sql .= ' AND serv.service_id = %d  ' ;		
		
		$sql = $wpdb->prepare($sql,array($service_id));				
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				return $row;			
			
			}
			
		}	
	
	}
	
	public function get_one ($booking_id) 
	{
		global $wpdb, $getbookingwp;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'getbwp_bookings  ' ;		
		$sql .= " WHERE booking_id = %d" ;			
					
		$sql = $wpdb->prepare($sql,array($booking_id));			
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				return $row;			
			
			}
			
		}	
	
	}
	
	public function get_one_with_key ($key) 
	{
		global $wpdb, $getbookingwp;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'getbwp_bookings  ' ;		
		$sql .= " WHERE order_key = %s" ;			
					
		$sql = $wpdb->prepare($sql,array($key));				
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				return $row;			
			
			}
			
		}	
	
	}
	
	public function get_all_with_cart ($cart_id){
		global $wpdb, $getbookingwp;		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'getbwp_bookings  ' ;		
		$sql .= " WHERE booking_cart_id = %s" ;
		$sql = $wpdb->prepare($sql,array($cart_id));		
		$res = $wpdb->get_results($sql);
		return $res;
	}
	
	/**
     * Get data for FullCalendar.
     *
     * @return json
     */
    public function get_all_staff_appointments()  {
		global $wpdb, $getbookingwp;
		
        $result        = array();
        $staff_members = array();
		
        $one_day       = new DateInterval( 'P1D' );		
        $start_date    = new DateTime( $_REQUEST['start'] );
        $end_date      = new DateTime( $_REQUEST['end'] );
		
		$location_id =$_REQUEST['location_id'];
		$staff_id_selected =$_REQUEST['staff_id'];
		
		if($staff_id_selected=='undefined' || $staff_id_selected=='null'){$staff_id_selected='';}        
        if($location_id=='undefined' || $location_id=='null'){$location_id='';}
		
        // FullCalendar sends end date as 1 day further.
        $end_date->sub( $one_day );
     		
		//get all staff members		
		$staff_members = $getbookingwp->userpanel->get_staff_list_fc($location_id);

        foreach ( $staff_members as $staff ) 
		{
			$staff_id = $staff->ID;
			$item_start_time = '';
			
			if(isset($staff_id_selected) && $staff_id_selected!='' && $staff_id!=$staff_id_selected){
                
				continue;				
			}
			
            /** Get All appointments for this user/staff member */
            $result = array_merge( $result, $getbookingwp->userpanel->getAppointmentsForFC( $start_date, $end_date, $staff_id ) );

            // Schedule.
            $items = $getbookingwp->userpanel->get_working_hours($staff_id);
			
            $day   = clone $start_date;
            // Find previous day end time.
            $last_end = clone $day;
            $last_end->sub( $one_day );
            $w = $day->format( 'N' );            
			
			//we need to get the end time for this week day					
			
			if(isset($items[$w])){

				$end_time = $items[$w]['end_time'];	

			}else{

				$end_time = null;	


			}
			
            if ( $end_time !== null ) 
			{
                $end_time = explode( ':', $end_time );
                $last_end->setTime( $end_time[0], $end_time[1] );
				
            } else {
				
                $last_end->setTime( 24, 0 );
            }
			
            // Do the loop.
            while ( $day <= $end_date ) 
			{
                do {
					
                    /**  */
					
					if(isset($items[ $day->format( 'N' )]))
					{
						$item = $items[ $day->format( 'N' )];
						$item_start_time = $item['start_time'];
					}
					
                   // if ( $item->get( 'start_time' ) && ! $staff->isOnHoliday( $day ) ) 
				   if ( $item_start_time && $item_start_time!=''  ) 
					{
                        $start = $last_end->format( 'Y-m-d H:i:s' );
                        $end   = $day->format( 'Y-m-d '.$item_start_time );
                        
						if ( $start < $end ) 
						{
                          /* $result[] = array(
                                'start'     => $start,
                                'end'       => $end,
                                'display' => 'background',
                                'resourceId'   => $staff_id,
                            ); */
                        }
						
                        $last_end = clone $day;
                        $end_time = explode( ':', $item[ 'end_time'] );
                        $last_end->setTime( $end_time[0], $end_time[1] );

                        // Breaks.
                       /* foreach ( $item->getBreaksList() as $break ) 
						{
                            $result[] = array(
                                'start'     => $day->format( 'Y-m-d '.$break['start_time'] ),
                                'end'       => $day->format( 'Y-m-d '.$break['end_time'] ),
                                'display' => 'background',
                                'resourceId'   => $staff_id,
                            );
                        }*/

                        break;
                    }

                   $result[] = array(
                        'start'     => $last_end->format( 'Y-m-d H:i:s' ),
                        'end'       => $day->format( 'Y-m-d 24:00:00' ),
                        'display' => 'background',
                        'resourceId'   => $staff_id,
                    ); 
					
                    $last_end = clone $day;
                    $last_end->setTime( 24, 0 );

                } while ( 0 );

                $day->add( $one_day );
            }

            if ( $last_end->format( 'H' ) != 24 )
			{
                $result[] = array(
                    'start'     => $last_end->format( 'Y-m-d H:i:s' ),
                    'end'       => $last_end->format( 'Y-m-d 24:00:00' ),
                    'display' => 'background',
                    'resourceId'   =>$staff_id,
                );  
            }
			
			
			
        } //end foreach

        wp_send_json( $result );
    }
	
	function get_week_date_range ($current_day)
	{
		$range = array();
		$range = array('from' => date("Y-m-d",strtotime('monday this week', $current_day)), 'to' => date("Y-m-d",strtotime("sunday this week", $current_day)));
		return $range;

	
	
	}
	
	public function get_appointments_total_by_status($status, $staff_id =null)
	{
		
		global $wpdb, $getbookingwp;
		
		// 0 pending, 1 approved, 2 cancelled
		
		$total = 0;
		
		$sql =  'SELECT count(*) as total FROM ' . $wpdb->prefix . 'getbwp_bookings   ' ;

		$cond = " WHERE  booking_status = %s ";
		$sql .= $wpdb->prepare($cond,array($status));
		
		if($staff_id!='')	
		{
			$cond = ' AND  booking_staff_id = %s  ';
			$sql .= $wpdb->prepare($cond,array($staff_id));
			
		}
			
		$appointments = $wpdb->get_results($sql );
		
		foreach ( $appointments as $appointment )
		{
				$total= $appointment->total;			
			
		}
					
		
		return $total;
	
	
	}
	
	
	
		
	public function get_appointments_planing_total($when)
	{
		
		global $wpdb, $getbookingwp;
		
		$total = 0;
		
		if($when=='today')
		{
			 $date = date( 'Y-m-d ', current_time( 'timestamp', 0 ) );
			 		
		}elseif($when=='tomorrow'){
			
			$ini_date = date( 'Y-m-d ', current_time( 'timestamp', 0 ) );
			$date=  date("Y-m-d", strtotime("$ini_date + 1 day"));
		
		}					
       		
		if($when=='week')
		{
			$dt_min = new DateTime("last sunday");
			$dt_max = clone($dt_min);
			$dt_max->modify('+6 days');
			
			$date_from =$dt_min->format('Y-m-d');
			$date_to =$dt_max->format('Y-m-d');
			

			
			$sql =  'SELECT count(*) as total, appo.*, usu.* FROM ' . $wpdb->prefix . 'getbwp_bookings appo  ' ;				
			$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";					
			$sql .= " WHERE DATE(appo.booking_time_from) >= %s AND DATE(appo.booking_time_to) <= %s AND usu.ID = appo.booking_staff_id AND appo.booking_status = '1' ";
		
			$sql = $wpdb->prepare($sql,array($date_from, $date_to));

		}elseif($when=='all'){
			
			
			$sql =  'SELECT count(*) as total, appo.*, usu.* FROM ' . $wpdb->prefix . 'getbwp_bookings appo  ' ;				
			$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";					
			$sql .= " WHERE  usu.ID = appo.booking_staff_id   ";	
				
			
		}else{
			
			$sql =  'SELECT count(*) as total, appo.*, usu.* FROM ' . $wpdb->prefix . 'getbwp_bookings appo  ' ;				
			$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";					
			$sql .= " WHERE DATE(appo.booking_time_from) = %s AND usu.ID = appo.booking_staff_id AND  appo.booking_status = '1' ";
			$sql = $wpdb->prepare($sql,array($date));	
		
		}	
			
		$appointments = $wpdb->get_results($sql );
		
		foreach ( $appointments as $appointment )
		{
				$total= $appointment->total;			
			
		}
					
		
		return $total;
	
	
	}
	
	public function get_sales_total_by_day($date)
	{
		
		global $wpdb, $getbookingwp;
		
		$total = 0;
		
		$sql =  'SELECT count(*) as total, appo.*, usu.* FROM ' . $wpdb->prefix . 'getbwp_bookings appo  ' ;				
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";					
		$sql .= " WHERE DATE(appo.booking_time_from) = %s AND usu.ID = appo.booking_staff_id AND  appo.booking_status = '1' ";
		
		$sql = $wpdb->prepare($sql,array($date));	
		$appointments = $wpdb->get_results($sql );
		
		foreach ( $appointments as $appointment )
		{
				$total= $appointment->total;			
			
		}
					
		
		return $total;
	}
	
	public function get_graph_total_monthly () 
	{
		global $wpdb, $getbookingwp;
		
		$date_format =  $getbookingwp->get_int_date_format();		
		$days_of_month = date("t");		
		$day = 1; 
		
		$vals='';
		while($day <= $days_of_month) {
			
			//get sales
			$date = date("Y").'-'.date("m").'-'.$day;
			
			//$date = date("Y").'-2-'.$day;
			
			$total = $this->get_sales_total_by_day($date);
			$day_format =$day;			
			$vals .= "['".$day_format."', $total]";			
			$day++;
			
			if($day <= $days_of_month){
				
				$vals .= ',';		
			}
		} 
		
		return $vals;		
		
	}
	
	public function get_appointment_status_options()
	{
		
		global $wpdb, $getbookingwp;
		
		$html = '';
		
		$appointment_id = sanitize_text_field($_POST['appointment_id']);
		
		$html .='<div class="getbwp-appointment-status-update">';
		$html .='<ul>';
		$html .='<li><a href="#" class="getbwp-adm-change-appoint-status-opt" appointment-id="'.$appointment_id.'" appointment-status="0" title="'.__('Change Status','get-bookings-wp').'"><i class="fa fa-edit"></i><span> '.__('Pending','get-bookings-wp').'</span></a></li>';
		
		$html .='<li><a href="#" class="getbwp-adm-change-appoint-status-opt" appointment-id="'.$appointment_id.'" appointment-status="1" title="'.__('Change Status','get-bookings-wp').'"><i class="fa fa-check"></i><span> '.__('Approved','get-bookings-wp').'</span></a></li>';
		
		$html .='<li><a href="#" class="getbwp-adm-change-appoint-status-opt" appointment-id="'.$appointment_id.'" appointment-status="2" title="'.__('Change Status','get-bookings-wp').'"><i class="fa fa-remove"></i><span> '.__('Cancelled','get-bookings-wp').'</span></a></li>';
		
		$html .='<li><a href="#" class="getbwp-adm-change-appoint-status-opt" appointment-id="'.$appointment_id.'" appointment-status="3" title="'.__('Change Status','get-bookings-wp').'"><i class="fa fa fa-eye-slash"></i><span> '.__('No-Show','get-bookings-wp').'</span></a></li>';
		
		$html .='</ul>';
		$html .='</div>';
				
				
		echo wp_kses($html, $getbookingwp->allowed_html);
		die();
	
	}
	
	public function get_appointments_quick()
	{
		
		global $wpdb, $getbookingwp;
		
		$html = '';
		
		$status = sanitize_text_field($_POST['status']);
		$type = sanitize_text_field($_POST['type']);
		
		if($type=='bystatus')		
		{
			
			$html = $this->get_appointments_by_status($status, $type);
			
			
		}elseif($type=='byunpaid'){
			
			
			$html = $this->get_unpaid_orders($status, $type);
		
		
		
		}
		
		echo wp_kses($html, $getbookingwp->allowed_html);
		die();
	
	}
	
	public function get_status_legend($status)
	{
		global  $getbookingwp;
		
		//0 Pending , 1 Approved, 2 Cancelled, 3 No-Show
		
		$legend ='';
		
		if($status==0)
		{			
			$legend ="<span class='getbwp-app-legend-pending'>".__("Pending",'get-bookings-wp')."</span>";
			
		}elseif($status==1){
			
			$legend ="<span class='getbwp-app-legend-approved'>".__("Approved",'get-bookings-wp')."</span>";
			
		}elseif($status==2){
			
			$legend ="<span class='getbwp-app-legend-cancelled'>".__("Cancelled",'get-bookings-wp')."</span>";
		
		}elseif($status==3){
			
			$legend ="<span class='getbwp-app-legend-noshow'>".__("No-Show",'get-bookings-wp')."</span>";
			
		}
		
		return $legend;
		
	}
	
	
	public function update_appointment_status_ed()
	{
		global $wpdb, $getbookingwp;
		
		$status = sanitize_text_field($_POST['appointment_status']);
		$appointment_id = sanitize_text_field($_POST['appointment_id']);
		
		$sql = $wpdb->prepare('UPDATE ' . $wpdb->prefix . 'getbwp_bookings SET booking_status =%d WHERE booking_id=%d ;',array($status,$appointment_id));		
		$results = $wpdb->query($sql);
		
		
		//change appointment status		
		$appointment = $this->get_one($appointment_id);		
		$staff_id = $appointment->booking_staff_id;	
		$client_id = $appointment->booking_user_id;	
		$service_id = $appointment->booking_service_id;
		
				
		/*Get Service*/			
		$service = $getbookingwp->service->get_one_service($service_id);		
		$new_status = $this->get_status_legend($status);			
						
											 
		//get user				
		$staff_member = get_user_by( 'id', $staff_id );
		$client = get_user_by( 'id', $client_id );					
											
		$getbookingwp->messaging->send_appointment_status_changed($staff_member, $client, $service, $appointment, $new_status);	
		
		
		$appointment = $this->get_one($appointment_id);		
		$html = $this->get_status_legend($appointment->booking_status);
		echo wp_kses($html, $getbookingwp->allowed_html);
		die();
		
	}
	
	public function update_appointment_status_inline()
	{
		global $wpdb, $getbookingwp;
		
		$status = sanitize_text_field($_POST['appointment_status']);
		$appointment_id = sanitize_text_field($_POST['appointment_id']);
		
		$sql = $wpdb->prepare('UPDATE ' . $wpdb->prefix . 'getbwp_bookings SET booking_status =%d WHERE booking_id=%d ;',array($status,$appointment_id));
		
		$results = $wpdb->query($sql);
		die();
		
	}
	
	public function update_payment_status_inline()
	{
		global $wpdb, $getbookingwp;
		
		$status = sanitize_text_field($_POST['payment_status']);
		$payment_id = sanitize_text_field($_POST['payment_id']);
		$sql = $wpdb->prepare('UPDATE ' . $wpdb->prefix . 'getbwp_orders SET order_status =%s WHERE order_id=%d ;',array($status,$payment_id));
		$results = $wpdb->query($sql);
		die();
		
	}
	
	
	
	public function get_appointments_by_status($status, $type)
	{
		
		global $wpdb, $getbookingwp;
				
		$appointments_re = array();
		$staff_service_details = array();
		
		$time_format =  $getbookingwp->service->get_time_format();
       		
		$sql =  'SELECT appo.*, usu.*, serv.*	 FROM ' . $wpdb->prefix . 'getbwp_bookings appo  ' ;				
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";	
		$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_services serv ON (serv.service_id = appo.booking_service_id)";		
		$sql .= " WHERE serv.service_id = appo.booking_service_id  AND  appo.booking_status = %s  ORDER BY appo.booking_time_from   asc ";	
			
		$sql = $wpdb->prepare($sql,array($status));
		$appointments = $wpdb->get_results($sql );		
		
		$html = '';
		
		$html .= '<div class="getbwp-quick-list-appointments">';
		
		
		if ( !empty( $appointments ) )
		{
			$html .= '<ul>';
			
			 foreach ( $appointments as  $appointment ) 
			 {
				 $date_from=  date("Y-m-d", strtotime($appointment->booking_time_from));
				 
				 $staff = $getbookingwp->userpanel->get_staff_member($appointment->booking_staff_id);
				 
				 $html .= '<li>';
				 
				  $html .= '<span class="getbwp-quick-appointment-action" id="getbwp-break-add-1">';
				  
				  $html .= '<a href="#" class="getbwp-appo-change-status" getbwp-type="'.$type.'" getbwp-status="'.$status.'" title="'.__("Cancel Appointment",'get-bookings-wp').'" appointment-id="'.$appointment->booking_id.'" appointment-status="2"><i class="fa fa-remove"></i></a>';
				  
				   $html .= '<a href="#" class="getbwp-appo-change-status" getbwp-type="'.$type.'" getbwp-status="'.$status.'" title="'.__("No-Show Appointment",'get-bookings-wp').'" appointment-id="'.$appointment->booking_id.'" appointment-status="3"><i class="fa fa-eye-slash"></i></a>';
				  
				 $html .= '<a href="#" class="getbwp-appo-change-status" getbwp-type="'.$type.'" getbwp-status="'.$status.'" title="'.__("Confirm Appointment",'get-bookings-wp').'" appointment-id="'.$appointment->booking_id.'" appointment-status="1" ><i class="fa fa-check"></i></a>';
				  
				 $html .= '</span>';
				 $html .= '<h3>'.date('l, j F, Y', strtotime($date_from)).'</h3>';
				 $html .= '<div class="getbwp-app-info"><strong>'.$appointment->service_title.'</strong> '. __("with",'get-bookings-wp').' <strong>'.$staff->display_name.'</strong> </div>';
				  
				 $html .= '</li>';
			
			
			 }
			 
			 $html .= '</ul>';
		
		 }else{
			 
			 $html = __("There are no appointments",'get-bookings-wp');		 
			 
		 }
		 
		$html .= '</div>';
		 
		return $html;
		 	
	
	}
	
	public function get_unpaid_orders($status, $type)
	{
		
		global $wpdb, $getbookingwp;
				
		$appointments_re = array();
		$staff_service_details = array();
		
		$time_format =  $getbookingwp->service->get_time_format();
       		
		$sql =  'SELECT ord.*,  usu.*, serv.* , appo.*	 FROM ' . $wpdb->prefix . 'getbwp_orders ord  ' ;
		$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_bookings appo ON (ord.order_booking_id = appo.booking_id)";				
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";	
		$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_services serv ON (serv.service_id = appo.booking_service_id)";	
			
		$sql .= " WHERE serv.service_id = appo.booking_service_id  AND  
		ord.order_booking_id = appo.booking_id  AND 
		ord.order_status = 'pending' ORDER BY appo.booking_time_from   asc ";
		
		
			
		$appointments = $wpdb->get_results($sql );		
		
		$html = '';
		
		$html .= '<div class="getbwp-quick-list-appointments">';
		
		
		if ( !empty( $appointments ) )
		{
			$html .= '<ul>';
			
			 foreach ( $appointments as  $appointment ) 
			 {
				 $booking_date=  date("Y-m-d", strtotime($appointment->booking_time_from));
				 $order_date=  date("Y-m-d", strtotime($appointment->order_date));
				 
				 $staff = $getbookingwp->userpanel->get_staff_member($appointment->booking_staff_id);
				 
				 $html .= '<li>';				 
				  $html .= '<span class="getbwp-quick-appointment-action" id="getbwp-break-add-1">';				  
				 				  
				 $html .= '<a href="#" class="getbwp-payment-change-status" getbwp-type="'.$type.'" getbwp-status="'.$status.'" title="'.__("Confirm Payment",'get-bookings-wp').'" payment-id="'.$appointment->order_id.'" order-status="confirmed" ><i class="fa fa-check"></i></a>';
				  
				 $html .= '</span>';
				 $html .= '<h3>'.date('l, j F, Y', strtotime($order_date)).'</h3>';
				 $html .= '<div class="getbwp-app-info"><strong>'.$appointment->service_title.'</strong> '. __("with",'get-bookings-wp').' <strong>'.$staff->display_name.'</strong> </div>';
				 $html .= '<div class="getbwp-app-info">'.__("Cost: ",'get-bookings-wp').'<strong>'.$appointment->order_amount.'</strong> </div>';
				 $html .= '<div class="getbwp-app-info">'.__("Appointment Date: ",'get-bookings-wp').'<strong>'.date('l, j F, Y', strtotime($booking_date)).'</strong> </div>';
				  
				 $html .= '</li>';
			
			
			 }
			 
			 $html .= '</ul>';
		
		 }else{
			 
			 $html = __("There are no unpaid orders",'get-bookings-wp');
			 
			 
			 
		 }
		 
		$html .= '</div>';
		 
		 return $html;
		 	
	
	}
	
	public function get_upcoming_appointments($how_many = 5)
	{
		
		global $wpdb, $getbookingwp;
				
		$appointments_re = array();
		$staff_service_details = array();
		
		$time_format =  $getbookingwp->service->get_time_format();
       		
		$sql =  'SELECT appo.*, usu.*, serv.*	 FROM ' . $wpdb->prefix . 'getbwp_bookings appo  ' ;				
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";	
		$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_services serv ON (serv.service_id = appo.booking_service_id)";		
		$sql .= " WHERE DATE(appo.booking_time_from) >= '".date('Y-m-d')."' AND serv.service_id = appo.booking_service_id  ORDER BY appo.booking_time_from asc ";
        
        $sql .= " LIMIT %d ";

		$sql = $wpdb->prepare($sql,array($how_many));			
		$appointments = $wpdb->get_results($sql );		
		
		return $appointments;
	
	
	}
	
	public function get_appointment_with_key_approval($key)
	{
		
		global $wpdb, $getbookingwp;
			
       	
		$sql =  'SELECT appo.*, usu.*, serv.*	 FROM ' . $wpdb->prefix . 'getbwp_bookings appo  ' ;				
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";	
		$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_services serv ON (serv.service_id = appo.booking_service_id)";		
		$sql .= " WHERE  appo.booking_key = %s  AND appo.booking_status = '0' AND usu.ID = appo.booking_staff_id AND 
		serv.service_id = appo.booking_service_id ";	
		
		$sql = $wpdb->prepare($sql,array($key));
	
		$appointments = $wpdb->get_results($sql );	
		
		if ( !empty( $appointments ) )
		{
			
			foreach ( $appointments as  $appointment ) 
			{
				return $appointment;
			
			}
		
		}else{
			
			return false;
			
		}
	
	}
	
	public function get_appointment_with_key_cancellation($key)
	{
		
		global $wpdb, $getbookingwp;
			
       	
		$sql =  'SELECT appo.*, usu.*, serv.*	 FROM ' . $wpdb->prefix . 'getbwp_bookings appo  ' ;				
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";	
		$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_services serv ON (serv.service_id = appo.booking_service_id)";		
		$sql .= " WHERE  appo.booking_key = %s  AND appo.booking_status <> '2' AND usu.ID = appo.booking_staff_id AND serv.service_id = appo.booking_service_id ";	
		
		
		$sql = $wpdb->prepare($sql,array($key));	
		$appointments = $wpdb->get_results($sql );	
		
		if ( !empty( $appointments ) )
		{
			
			foreach ( $appointments as  $appointment ) 
			{
				return $appointment;
			
			}
		
		}else{
			
			return false;
			
		}
	
	}
	
	public function get_appointment_with_key($key)
	{
		
		global $wpdb, $getbookingwp;			
       	
		$sql =  'SELECT appo.*, usu.*, serv.*	 FROM ' . $wpdb->prefix . 'getbwp_bookings appo  ' ;				
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";	
		$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_services serv ON (serv.service_id = appo.booking_service_id)";		
		$sql .= " WHERE  appo.booking_key = %s  ";	

		$sql = $wpdb->prepare($sql,array($key));	
		$appointments = $wpdb->get_results($sql );	
		
		if ( !empty( $appointments ) )
		{
			
			foreach ( $appointments as  $appointment ) 
			{
				return $appointment;
			
			}
		
		}else{
			
			return false;
			
			
		}
	
	
	}
	
	public function get_upcoming_app_list($how_many = 20)
	{
		global $wpdb, $getbookingwp;
		
		$appointments = $this->get_upcoming_appointments($how_many);
		
		$html = '';
		
		
		if ( !empty( $appointments ) )
		{
			$html .= '<ul>';
			
			 foreach ( $appointments as  $appointment ) 
			 {
				 $date_from=  date("Y-m-d", strtotime($appointment->booking_time_from));
				 
				 $staff = $getbookingwp->userpanel->get_staff_member($appointment->booking_staff_id);
				 
				 $html .= '<li>';
				 $html .= '<h3>'.date('l, j F, Y', strtotime($date_from)).'</h3>';
				 $html .= '<div class="getbwp-app-info"><strong>'.$appointment->service_title.'</strong> '. __("with",'get-bookings-wp').' <strong>'.$staff->display_name.'</strong> </div>';
				  
				 $html .= '</li>';
			
			
			 }
			 
			 $html .= '</ul>';
		
		 }else{
			 
			 $html = __("There are no upcoming appointments",'get-bookings-wp');
			 
			 
			 
		 }
		 
		
		 
		 return $html;
	
	}
	
	public function get_booking_location ($filter_id) 
	{
		global $wpdb, $getbookingwp;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'getbwp_filters  ' ;
		$sql .= ' WHERE filter_id = %s' ;	
		
		$sql = $wpdb->prepare($sql,array($filter_id));				
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				return $row;			
			
			}
			
		}	
	
	}
	
	
	/*Get all*/
	public function get_all ()
	{
		global $wpdb,  $getbwp_filter, $getbookingwp;
		
		$keyword = "";
		$month = "";
		$day = "";
		$year = "";
		$howmany = "";
		$ini = "";
		
		$getbwp_staff_calendar = "";
		
		$special_filter='';
		
		if(isset($_GET["keyword"]))
		{
			$keyword = sanitize_text_field($_GET["keyword"]);		
		}
		
		if(isset($_GET["month"]))
		{
			$month = sanitize_text_field($_GET["month"]);		
		}
		
		if(isset($_GET["day"]))
		{
			$day = sanitize_text_field($_GET["day"]);		
		}
		
		if(isset($_GET["year"]))
		{
			$year = sanitize_text_field($_GET["year"]);		
		}
		
		if(isset($_GET["howmany"]))
		{
			$howmany = sanitize_text_field($_GET["howmany"]);		
		}
		
		if(isset($_GET["special_filter"]))
		{
			$special_filter = sanitize_text_field($_GET["special_filter"]);		
		}
		
		if(isset($_GET["getbwp-staff-calendar"]))
		{
			$getbwp_staff_calendar = sanitize_text_field($_GET["getbwp-staff-calendar"]);		
		}
		
		$uri= sanitize_url($_SERVER['REQUEST_URI']) ;
		$url = explode("&ini=",$uri);
		
		if(is_array($url ))
		{
			if(isset($url["1"]))
			{
				$ini = $url["1"];
			    if($ini == ""){$ini=1;}
			
			}
		
		}		
		
		if($howmany == ""){$howmany=20;}
		
		//get total				
		$sql =  "SELECT count(*) as total, usu.*, serv.* , appo.* 	  " ;
		
		if($special_filter!="" && isset($getbwp_filter))
		{
			$sql .= ", bookmeta.*, bookfilter.* ";			
		}
		
		$sql .= " FROM " . $wpdb->prefix . "getbwp_bookings appo ";
						
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";	
		$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_services serv ON (serv.service_id = appo.booking_service_id)";	
		
		
		if($special_filter!="" && isset($getbwp_filter))
		{
			$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_bookings_meta bookmeta ON (bookmeta.meta_booking_id = appo.booking_id)";
			$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_filters bookfilter ON (bookfilter.filter_id = bookmeta.meta_booking_value)";
		}
			
		$sql .= " WHERE serv.service_id = appo.booking_service_id  ";
		
		
		if($special_filter!=""){

			$cond = " AND bookmeta.meta_booking_id = appo.booking_id AND 
			bookfilter.filter_id = bookmeta.meta_booking_value AND 
			bookmeta.meta_booking_value=%s AND 
			bookmeta.meta_booking_name='filter_id' ";	

		    $sql .= $wpdb->prepare($cond,array($special_filter));
		}
			
		if($keyword!="")
		{
			$sql .= " AND (ord.order_txt_id LIKE '%".$keyword."%' OR usu.display_name LIKE '%".$keyword."%' OR usu.user_email LIKE '%".$keyword."%' OR usu.user_login LIKE '%".$keyword."%'  )  ";
			
		}
		
		if($getbwp_staff_calendar!="")
		{			

			$sql .= $wpdb->prepare(" AND  appo.booking_staff_id = %s " ,array($getbwp_staff_calendar));
			
		}	
		
		
		if($day!=""){

			$sql .= $wpdb->prepare(" AND DAY(appo.booking_time_from) = %s " ,array($day));
		}
		if($month!=""){					

			$sql .= $wpdb->prepare(" AND MONTH(appo.booking_time_from) = %s " ,array($month));

		}		
		if($year!=""){		

			$sql .= $wpdb->prepare(" AND YEAR(appo.booking_time_from) = %s " ,array($year));
		}	
		
		$orders = $wpdb->get_results($sql );
		$orders_total = $this->fetch_result($orders);
		$orders_total = $orders_total->total;
		$this->total_result = $orders_total ;
		
		$total_pages = $orders_total;
				
		$limit = "";
		$current_page = $ini;
		$target_page =  site_url()."/wp-admin/admin.php?page=getbookingswp&tab=appointments";
		
		$how_many_per_page =  $howmany;
		
		$to = $how_many_per_page;
		
		//caluculate from
		$from = $this->calculate_from($ini,$how_many_per_page,$orders_total );
		
		//get all	
		
		$sql =  "SELECT appo.*, usu.*, serv.* 	  " ;
		
		if($special_filter!="" && isset($getbwp_filter))
		{
			$sql .= ", bookmeta.*, bookfilter.* ";			
		}
		
		$sql .= " FROM " . $wpdb->prefix . "getbwp_bookings appo ";
		
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = appo.booking_staff_id)";	
		$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_services serv ON (serv.service_id = appo.booking_service_id)";	
		
		if($special_filter!="" && isset($getbwp_filter))
		{
			$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_bookings_meta bookmeta ON (bookmeta.meta_booking_id = appo.booking_id)";
			$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_filters bookfilter ON (bookfilter.filter_id = bookmeta.meta_booking_value)";
		}
			
		$sql .= " WHERE serv.service_id = appo.booking_service_id    ";	
		
		if($special_filter!=""){

			$cond = " AND bookmeta.meta_booking_id = appo.booking_id AND 
			bookfilter.filter_id = bookmeta.meta_booking_value AND 
			bookmeta.meta_booking_value=%s AND 
			bookmeta.meta_booking_name='filter_id' ";	

		    $sql .= $wpdb->prepare($cond,array($special_filter));
		}

		if($keyword!="")
		{
			$sql .= " AND (ord.order_txt_id LIKE '%".$keyword."%' OR usu.display_name LIKE '%".$keyword."%' OR usu.user_email LIKE '%".$keyword."%' OR usu.user_login LIKE '%".$keyword."%'  )  ";
		}
		
		if($getbwp_staff_calendar!="")
		{			

			$sql .= $wpdb->prepare(" AND  appo.booking_staff_id = %s " ,array($getbwp_staff_calendar));
			
		}
		
		if($day!=""){

			$sql .= $wpdb->prepare(" AND DAY(appo.booking_time_from) = %s " ,array($day));
		}
		if($month!=""){					

			$sql .= $wpdb->prepare(" AND MONTH(appo.booking_time_from) = %s " ,array($month));

		}		
		if($year!=""){		

			$sql .= $wpdb->prepare(" AND YEAR(appo.booking_time_from) = %s " ,array($year));
		}		
		
		$sql .= " ORDER BY appo.booking_id DESC";		
		
	    if($from != "" && $to != ""){	

			$sql .=  $wpdb->prepare(" LIMIT %d,%d " ,array($from,$to));
		}

	 	if($from == 0 && $to != ""){	

			$sql .=  $wpdb->prepare(" LIMIT %d,%d " ,array($from,$to));
		}		
					
		$orders = $wpdb->get_results($sql );
		
		return $orders ;
		
	
	}
	
	public function fetch_result($results)
	{
		if ( empty( $results ) )
		{
		
		
		}else{
			
			
			foreach ( $results as $result )
			{
				return $result;			
			
			}
			
		}
		
	}
	
	public function calculate_from($ini, $howManyPagesPerSearch, $total_items)	
	{
		if($ini == ""){$initRow = 0;}else{$initRow = $ini;}
		
		if($initRow<= 1) 
		{
			$initRow =0;
		}else{
			
			if(($howManyPagesPerSearch * $ini)-$howManyPagesPerSearch>= $total_items) {
				$initRow = $totalPages-$howManyPagesPerSearch;
			}else{
				$initRow = ($howManyPagesPerSearch * $ini)-$howManyPagesPerSearch;
			}
		}
		
		
		return $initRow;
		
		
	}

	
}
$key = "appointment";
$this->{$key} = new GetBookingsWPAppointment();
?>