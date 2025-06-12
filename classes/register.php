<?php
class GetBookingsWPUserRegister {
	
	

	function __construct(){			
		add_action( 'init', array($this, 'getbwp_handle_hooks_actions') );			
		add_action( 'init', array($this, 'getbwp_handle_post') );		
		add_action( 'wp_ajax_getbwp_clear_cart',  array( &$this, 'kill_shopping_cart' ));
		add_action( 'wp_ajax_nopriv_getbwp_clear_cart',  array( &$this, 'kill_shopping_cart' ));
	}
	
	function getbwp_handle_hooks_actions ()	{
		if (function_exists('getbwp_registration_hook')) {		
			add_action( 'user_register', 'getbwp_registration_hook' );
		}
		
		if (function_exists('getbwp_after_login_hook')) {		
			add_action( 'wp_login', 'getbwp_after_login_hook' , 102,2);			
		}	
	}
	

	function getbwp_handle_post () {		
		
		/*Form is fired*/	    
		if (isset($_POST['getbwp-register-form'])) {
			
			/* Prepare array of fields */
			$this->prepare_request( sanitize_text_field($_POST) );
       			
			/* Validate, get errors, etc before we create account */
			$this->handle_errors();
			
			/* Create account */
			$this->handle_checkout_process();
				
		}
		
		
		
	}
		
	/*Prepare user meta*/
	function prepare_request ($array ) {
		foreach($array as $k => $v) {
			
			if ($k == 'getbwp-register' || $k == 'user_pass_confirm' || $k == 'user_pass' || $k == 'getbwp-register-form' || $k == 'book_from' || $k == 'book_to' || $k == 'getbwp_date') continue; 
			
			
			$this->usermeta[$k] = $v;
		}
		return $this->usermeta;
	}
	
	/*Handle/return any errors*/
	function handle_errors(){
	    global $getbookingwp;
		
		
		   foreach($this->usermeta as $key => $value) {
		    
		        /* Validate username */
		        if ($key == 'user_login'){
		            if (sanitize_text_field($value) == '') {
						
		                $this->errors[] = __('<strong>ERROR:</strong> Please enter a username.','get-bookings-wp');
						
		            } elseif (username_exists($value)) {
						
		               // $this->errors[] = __('<strong>ERROR:</strong> This username is already registered. Please choose another one.','get-bookings-wp');
		            }
		        }
		    
		        /* Validate email */
		        if ($key == 'user_email'){
		            if (sanitize_text_field($value) == '') {
		                $this->errors[] = __('<strong>ERROR:</strong> Please type your e-mail address.','get-bookings-wp');
						
		            } elseif (!is_email($value)){
		                $this->errors[] = __('<strong>ERROR:</strong> The email address isn\'t correct.','get-bookings-wp');
					
					} elseif ($value!=sanitize_email($_POST['user_email_2'])) {
		               // $this->errors[] = __('<strong>ERROR:</strong> The emails are different.','get-bookings-wp');
						
		            } elseif (email_exists($value)) 
					{
		                
		            }
		        }				
		    
		    }
			 
			$captcha_control = $getbookingwp->get_option("captcha_plugin");	
					    
			if($captcha_control!='none' && $captcha_control!=''){
				if(!is_in_post('no_captcha','yes'))	{
					if(!$getbookingwp->captchamodule->validate_captcha(post_value('captcha_plugin'))){
						$this->errors[] = __('<strong>ERROR:</strong> Please complete Captcha Test first.','xoousers');
					}
				}
				
			} 	
	
		
	}
	
	
	
	//validate password one letter and one number	
	function validate_password_numbers_letters ($myString)	{
		$ret = false;
		if (preg_match('/[A-Za-z]/', $myString) && preg_match('/[0-9]/', $myString)){
			$ret = true;
		}
		return $ret;
	}
	
	//at least one upper case character 	
	function validate_password_one_uppercase ($myString){	
		
		if( preg_match( '~[A-Z]~', $myString) ){
   			 $ret = true;
		} else {			
			$ret = false;		  
		}					
		return $ret;
	}
	
	//at least one lower case character 	
	function validate_password_one_lowerrcase ($myString){	
		
		if( preg_match( '~[a-z]~', $myString) ){
   			 $ret = true;
		} else {			
			$ret = false;		  
		}					
		return $ret;	
	}
	
	
	public function genRandomStringActivation($length){
			
			$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZ";
			
			$real_string_legnth = strlen($characters) ;
			$string="ID";
			
			for ($p = 0; $p < $length; $p++){
				$string .= $characters[mt_rand(0, $real_string_legnth-1)];
			}
			
			return strtolower($string);
	}
	
		
	
	
	public function genRandomString(){
		$length = 5;
		$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZ";		
		$real_string_legnth = strlen($characters) ;
		$string="ID";
		
		for ($p = 0; $p < $length; $p++){
			$string .= $characters[mt_rand(0, $real_string_legnth-1)];
		}
		
		return strtolower($string);
	}
	
	
	/*Create user*/
	function handle_checkout_process(){
		global $getbookingwp, $getbwpcomplement, $getbwp_aweber;
		
		$template_id = sanitize_text_field($_POST['template_id']);		
		$show_cart = $getbookingwp->get_template_label("show_cart",$template_id);		
		
		if($show_cart==1){			
			$this->create_account_cart();		
		
		}else{			
			$this->create_account();			
		}		
	}
	
	
	/*Create order when using shopping cart*/
	function create_account_cart(){
		
		global $getbookingwp, $getbwpcomplement, $getbwp_aweber;
		session_start();
		
		$custom_form =  sanitize_text_field($_POST['getbwp-custom-form-id']);
		$filter_id =  sanitize_text_field($_POST['getbwp-filter-id']);
		$template_id =  sanitize_text_field($_POST['template_id']);		
		$full_number =  sanitize_text_field($_POST['full_number']);
		$full_number_prefix =  sanitize_text_field($_POST['full_number_prefix']);
		$full_number_iso =  sanitize_text_field($_POST['full_number_iso']);

		$user_email =  sanitize_text_field($_POST['user_email']);
		
			
			/* Create profile when there is no error */
			if (!isset($this->errors)) 
			{			
				/* Create account, update user meta */				
				$visitor_ip = sanitize_text_field($_SERVER['REMOTE_ADDR']);	
								
				if(email_exists($user_email))
				{
					
					$user_d = get_user_by( 'email', $user_email );
					$user_id  = $user_d->ID;
				
				
				}else{ // new user we have to create it.
				
					
					$sanitized_user_login = sanitize_email($_POST['user_email']);
				
					/* We create the New user */
					$user_pass = wp_generate_password( 12, false);
					$user_id = wp_create_user( $sanitized_user_login, $user_pass, sanitize_email($user_email) );	
					wp_update_user( array('ID' => $user_id, 'display_name' => sanitize_text_field($_POST['display_name'])) );
					
				}
				
				
								
				/* We assign the custom profile form for this user*/						
								
				if (  $user_id ) 
				{
					
					$visitor_ip = sanitize_text_field($_SERVER['REMOTE_ADDR']);
					update_user_meta($user_id, 'getbwp_user_registered_ip', $visitor_ip);					
					update_user_meta($user_id, 'getbwp_is_client', 1);												
										
					//set account status						
					$verify_key = $this->get_unique_verify_account_id();					
					update_user_meta ($user_id, 'getbwp_ultra_very_key', $verify_key);	
					
					update_user_meta ($user_id, 'reg_telephone', sanitize_text_field($_POST['telephone']));
					update_user_meta ($user_id, 'reg_telephone_code',$full_number);
					update_user_meta ($user_id, 'reg_telephone_prefix',$full_number_prefix);
					update_user_meta ($user_id, 'reg_telephone_iso',$full_number_iso);
					
					// 20/04/2017 option that allows users to login as a staff member and checked their appointments
					update_user_meta($user_id, 'getbwp_account_status', 'active');						
					update_user_meta($user_id, 'first_name',sanitize_text_field($_POST['display_name']));
					update_user_meta($user_id, 'last_name',sanitize_text_field($_POST['last_name']));	
					
					
					
				}
				
				$cart_id = 0;
				
				//create transaction key
				$transaction_key = session_id()."_".time();	
				
				$CURRENT_CART = $_COOKIE["GETBWP_SHOPPING_CART"];
				$CURRENT_CART = stripslashes($CURRENT_CART);
				$CURRENT_CART = json_decode($CURRENT_CART, true);
				
				if(count($CURRENT_CART)>0){				
					//let's create a cart					
					$cart_id =  $getbookingwp->order->create_cart($transaction_key);					
				}				
				
				
				foreach ($CURRENT_CART as $key => $ITEM)  
				{
					//create transaction key
					$transaction_key = $this->get_unique_verify_account_id();	
									
					//create reservation in reservation table					
					$service_id = $ITEM['service_id'];
					$day_id = $ITEM['book_date'];
					$staff_id = $ITEM['staff_id'];								
					$book_from = $ITEM['book_from'];
					$book_to = $ITEM['book_to'];					
					$quantity = $ITEM['book_qty'];
					
					//service			
				    $service = $getbookingwp->service->get_one_service($service_id);					
					
					$service_details = $getbookingwp->userpanel->get_staff_service_rate( $staff_id, $service_id ); 
					//$amount= $service_details['price']*$quantity;
					
					$amount_calc = $getbookingwp->service->calculate_service_price_cart($quantity,$service_id,$staff_id);		
					
					$amount= $amount_calc['amount'];
					
					$p_name =  $service->service_title;			
					
					$order_data = array(
					
							 'user_id' => $user_id,	
							 'transaction_key' => $transaction_key,					 
							 'amount' => $amount,
							 'service_id' => $service_id ,
							 'staff_id' => $staff_id ,
							 'template_id' => $template_id ,
							 'cart_id' => $cart_id ,
							 'product_name' => $p_name ,						 
							 'day' => $day_id,
							 'time_from' => $book_from,
							 'time_to' => $book_to,
							 'quantity' => $quantity
							 
							 ); 
							 
					
					$booking_id =  $getbookingwp->order->create_reservation($order_data);
					
					$google_client_id = $getbookingwp->get_option('google_calendar_client_id');
					$google_client_secret = $getbookingwp->get_option('google_calendar_client_secret');
					
					
					
					if($booking_id!='')
					
					{
						/*We've got a valid bookin id then let's create the meta informaion*/						
						foreach($this->usermeta as $key => $value) 
						{						
							 
							if (is_array($value))   // checkboxes
							{
								$value = implode(',', $value);
							}
							
							if ($key=='full_number' && $value=='') 
							{
								$value = sanitize_text_field($_POST['telephone']);
							}					
							
							$getbookingwp->appointment->update_booking_meta($booking_id, $key, sanitize_text_field($value));
						}
						
						if($custom_form!=''){
						
							$getbookingwp->appointment->update_booking_meta($booking_id, 'custom_form', $custom_form);					
						}
						
						if($filter_id!=''){
						
							$getbookingwp->appointment->update_booking_meta($booking_id, 'filter_id', $filter_id);				
						}
						
						
						//google calendar				
						if(isset($getbwpcomplement) && $google_client_id!='' && $google_client_secret!='' )
						{				
							
							$getbwpcomplement->googlecalendar->create_event($booking_id,$order_data);	
												
						
						}
						
						
					
					}
				
				
				} //END FOR EACH COOKIE
				
				if(isset($getbwpcomplement))
				{
				
					//mailchimp					 
					 if(isset($_POST["getbwp-mailchimp-confirmation"]) && $_POST["getbwp-mailchimp-confirmation"]==1)				 {
						 $list_id =  $getbookingwp->get_option('mailchimp_list_id');					 
						 $getbwpcomplement->newsletter->mailchimp_subscribe($user_id, $list_id);
						 update_user_meta ($user_id, 'getbwp_mailchimp', 1);				 						
						
					 }
					 
					 //aweber	
					 $list_id = get_option( "buproaw_aweber_list");				 
					 if(isset($_POST["getbwp-aweber-confirmation"]) && $_POST["getbwp-aweber-confirmation"]==1 && $list_id !='')				 {
						 
											 						 
						 $user_l = get_user_by( 'id', $user_id ); 				 
						 $getbwpcomplement->aweber->buproaw_subscribe($user_l, $list_id);
						 update_user_meta ($user_id, 'getbwp_aweber', 1);				 						
						
					 }
				
				}	

				//check if it's a paid sign up				
				if($getbookingwp->get_option('registration_rules')!=1)
				{
					//payment Method
					$payment_method = sanitize_text_field($_POST["getbwp_payment_method"]);	
									 
					
					//update status 					 
					 					  
					  $payment_procesor = false;
					  
					  if($_POST["getbwp_payment_method"]=='' || $_POST["getbwp_payment_method"]=='paypal')
					  {
						  $payment_procesor = true;
						  $payment_method="paypal";						  
						 
					
					  }elseif($_POST["getbwp_payment_method"]=='bank'){  
					  
					  	   $payment_method="bank";
						   $payment_procesor = false;
						   
					   }elseif($_POST["getbwp_payment_method"]=='stripe'){  
					  
					  	   $payment_method="stripe";
						   $payment_procesor = true;
						
					   }elseif($_POST["getbwp_payment_method"]=='authorize'){  
					  
					  	   $payment_method="authorize";
						   $payment_procesor = true;
					  }
					  
					  
					  //create order to to each one of the services.
					  $appointments_cart = $getbookingwp->appointment->get_all_with_cart($cart_id);
					  $amount = 0;
					  foreach ( $appointments_cart as $appointment )
					  {
							
							$order_data = array('user_id' => $appointment->booking_user_id,
								 'transaction_key' => $appointment->booking_key,
								 'amount' => $appointment->booking_amount,
								 'booking_id' => $appointment->booking_id ,
								 'cart_id' => $cart_id ,
								 'product_name' => $p_name ,
								 'status' => 'pending',		
								 'service_id' => $appointment->booking_service_id ,
								 'staff_id' => $appointment->booking_staff_id ,				
								 'method' => $payment_method,
								 'quantity' => $appointment->booking_qty); 	
								 
								
							$order_id = $getbookingwp->order->create_order($order_data);								
							$amount =$amount +$appointment->booking_amount;
							
							//print_r($appointment);
					
					
					   }
					   
					   //update cart with amount
					   $getbookingwp->order->update_cart_amount ($cart_id,$amount);
									
					 			 
					if($payment_method=="paypal" && $amount > 0 && $payment_procesor)
					{
						
						  $order_data = array(
								 'transaction_key' => $transaction_key,
								 'amount' => $amount,								 
								 'product_name' => $p_name 
								);
						
						  $ipn = $getbookingwp->paypal->get_ipn_cart($order_data, 'ini');
						  
						  $this->kill_shopping_cart();	  
						  
						  //redirect to paypal
						  header("Location: $ipn");
						  exit;
						  
					}elseif($payment_method=="stripe" && $amount > 0 && $payment_procesor){
						
						
						if(isset($getbwpcomplement))
						{
							$res = array();
							
							//service			
							$service = $getbookingwp->service->get_one_service($service_id);							
							$description = $service->service_title;						
							
							$getbwp_stripe_token = sanitize_text_field($_POST['getbwp_stripe_token']);								
							$res = 	$getbwpcomplement->stripe->charge_credit_card($getbwp_stripe_token, $description, $amount);
							
							if($res['result']=='ok')
							{
								$getbwpcomplement->stripe->process_order_cart($transaction_key, $res);
								
								//kill cart								
								$this->kill_shopping_cart();
																
								//redir
								$this->handle_redir_success_trans($transaction_key);								
							
							}else{						
							
								
								echo wp_kses(res['message'], $getbookingwp->allowed_html);
							
							}
						
						}
						
						
					}elseif($payment_method=="authorize" && $amount > 0 && $payment_procesor){
						
						
						if(isset($getbwpcomplement))
						{
							$res = array();
							
							//service			
							$service = $getbookingwp->service->get_one_service($service_id);							
							$description = $service->service_title;						
							
							$getbwp_authorize_token = sanitize_text_field($_POST['getbwp_authorize_token']);								
							$res = 	$getbwpcomplement->authorize->charge_credit_card($getbwp_authorize_token, $description, $amount);
							
							if($res['result']=='ok')
							{
								$getbwpcomplement->authorize->process_order($transaction_key, $res);
																
								//redir
								$this->handle_redir_success_trans($transaction_key);								
							
							}else{
								
								echo wp_kses($res['message']);								
							
							}
						
						}	
						
					
					}elseif($payment_method=="bank" && !$payment_procesor){
						
						//get al appointments of this cart						
						$appointments_cart = $getbookingwp->appointment->get_all_with_cart($cart_id);
						
						//send confirmation to all staff members and client service by service						
					    foreach ( $appointments_cart as $appointment ) {													 
							//service			
							$service = $getbookingwp->service->get_one_service($appointment->booking_service_id);												
							// Get Order
							$rowOrder = $getbookingwp->order->get_order_with_booking_id($appointment->booking_id);										 
							//get user				
							$staff_member = get_user_by( 'id', $appointment->booking_staff_id );
							$client = get_user_by( 'id', $appointment->booking_user_id );
							
							$getbookingwp->messaging->zoom_meeting_url = $this->zoom_meeting_url;											
							$getbookingwp->messaging->send_payment_confirmed_bank_cart($staff_member, $client, $service, $appointment, $rowOrder );
							
							
						} //end for
						
						
						//kill cookie
						$this->kill_shopping_cart();
						
						//redir
					    $this->handle_redir_success_trans_bank($transaction_key);
						   
						 				  
						 
					 }else{						 
						 
						 
					 }
					 
					 
					 
					 
				
				}else{
					
					//this is not a paid sign up
					
					//create order					  
					 $order_data = array('user_id' => $user_id,
						 'transaction_key' => $transaction_key,
						 'amount' => $amount,
						 'booking_id' => $booking_id ,
						 'product_name' => $p_name ,
						 'status' => 'pending',		
						 'service_id' => $service_id ,
						 'staff_id' => $staff_id ,				
						 'method' => 'free'); 						 
						 
						
					$order_id = $getbookingwp->order->create_order($order_data);	
					
					//service			
					$service = $getbookingwp->service->get_one_service($service_id);
						
					//get appointment			
					$appointment = $getbookingwp->appointment->get_one($booking_id);	
						
					// Get Order
					$rowOrder = $getbookingwp->order->get_order($transaction_key);	
					
					//Set initial status					
					$this->set_initial_booking_status($booking_id, 'free');								
										 
					//get user				
					$staff_member = get_user_by( 'id', $staff_id );
					$client = get_user_by( 'id', $user_id );					
											
					$getbookingwp->messaging->send_payment_confirmed($staff_member, $client, $service, $appointment, $rowOrder );	
					
					//redir
					$this->handle_redir_success_trans_free($transaction_key);				
									
				}				
				
			} //end error link++
			
	}
	
	function kill_shopping_cart()
	{	
		unset($_COOKIE["GETBWP_SHOPPING_CART"]);		
		setcookie( "GETBWP_SHOPPING_CART", null, time() -3600, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
	
	}
	
		
	
	/*Create user*/
	function create_account() 
	{
		
		global $getbookingwp, $getbwpcomplement, $getbwp_aweber, $getbwp_zoom;
		session_start();
		
		$custom_form =  sanitize_text_field($_POST['getbwp-custom-form-id']);
		$filter_id =  sanitize_text_field($_POST['getbwp-filter-id']);
		$quantity =  sanitize_text_field($_POST['getbwp-purchased-qty']);
		$template_id =  sanitize_text_field($_POST['template_id']);
		$full_number =  sanitize_text_field($_POST['full_number']);
		$full_number_prefix =  sanitize_text_field($_POST['full_number_prefix']);
		$full_number_iso =  sanitize_text_field($_POST['full_number_iso']);
	
		
		if($quantity==''){$quantity=1;}
		
			
			/* Create profile when there is no error */
			if (!isset($this->errors)) {				
				
				/* Create account, update user meta */				
				$visitor_ip = sanitize_text_field($_SERVER['REMOTE_ADDR']);	
								
				if(email_exists($_POST['user_email'])){
					
					$user_d = get_user_by( 'email', sanitize_text_field($_POST['user_email']) );
					$user_id  = $user_d->ID;				
				
				}else{ // new user we have to create it.				
					
					$sanitized_user_login = sanitize_email($_POST['user_email']);				
					/* We create the New user */
					$user_pass = wp_generate_password( 12, false);
					$user_id = wp_create_user( $sanitized_user_login, $user_pass, sanitize_email($_POST['user_email']) );	
					wp_update_user( array('ID' => $user_id, 'display_name' => sanitize_text_field($_POST['display_name'])) );	
				
				}
				
				
								
				/* We assign the custom profile form for this user*/						
								
				if (  $user_id ) {
					
					$visitor_ip = sanitize_text_field($_SERVER['REMOTE_ADDR']);
					update_user_meta($user_id, 'getbwp_user_registered_ip', $visitor_ip);					
					update_user_meta($user_id, 'getbwp_is_client', 1);												
										
					//set account status						
					$verify_key = $this->get_unique_verify_account_id();					
					update_user_meta ($user_id, 'getbwp_ultra_very_key', $verify_key);	
					update_user_meta ($user_id, 'reg_telephone', sanitize_text_field($_POST['telephone']));
					update_user_meta ($user_id, 'reg_telephone_code', $full_number);
					update_user_meta ($user_id, 'reg_telephone_prefix', $full_number_prefix);
					update_user_meta ($user_id, 'reg_telephone_iso', $full_number_iso);						
					
					update_user_meta($user_id, 'first_name',sanitize_text_field($_POST['display_name']));
					update_user_meta($user_id, 'last_name',sanitize_text_field($_POST['last_name']));					
				}
				
				//create transaction
				$transaction_key = session_id()."_".time();			
						
				//create reservation in reservation table					
				$service_id = sanitize_text_field($_POST['service_id']);
				$day_id = sanitize_text_field($_POST['getbwp_date']);
				$staff_id = sanitize_text_field($_POST['staff_id']);								
				$book_from = sanitize_text_field($_POST['book_from']);
				$book_to = sanitize_text_field($_POST['book_to']);				
				
				$service_details = $getbookingwp->userpanel->get_staff_service_rate( $staff_id, $service_id ); 
				$amount= $service_details['price']*$quantity;				
				
				$order_data = array(
				
						 'user_id' => $user_id,	
						 'transaction_key' => $transaction_key,					 
						 'amount' => $amount,
						 'service_id' => $service_id ,
						 'staff_id' => $staff_id ,
						 'template_id' => $template_id ,
						 'product_name' => $p_name ,						 
						 'day' => $day_id,
						 'time_from' => $book_from,
						 'time_to' => $book_to,
						 'quantity' => $quantity,
						 'cart_id' =>$cart_id
						 
				); 
						 
				$booking_id =  $getbookingwp->order->create_reservation($order_data);				
				$google_client_id = $getbookingwp->get_option('google_calendar_client_id');
			    $google_client_secret = $getbookingwp->get_option('google_calendar_client_secret');		
				
				if($booking_id!='')	{
										
					foreach($this->usermeta as $key => $value) 	{						
						 
						if (is_array($value)) {
							$value = implode(',', $value);
						}
						
						if ($key=='full_number' && $value==''){
							$value = sanitize_text_field($_POST['telephone']);
						}
						
						$getbookingwp->appointment->update_booking_meta($booking_id, $key, sanitize_text_field($value));
					}
					
					if($custom_form!=''){
						$getbookingwp->appointment->update_booking_meta($booking_id, 'custom_form', $custom_form);					
					}
					
					if($filter_id!=''){					
						$getbookingwp->appointment->update_booking_meta($booking_id, 'filter_id', $filter_id);				
					}
					
					//google calendar				
					if(isset($getbwpcomplement) && $google_client_id!='' && $google_client_secret!='' )	{		
						$getbwpcomplement->googlecalendar->create_event($booking_id,$order_data);	
					}

					//zoom		
					$service = $getbookingwp->service->get_one_service($service_id);
					if(isset($getbwpcomplement) && isset($getbwpcomplement->zoom) && $service->service_meeting_zoom ==1 ){					
												
						$description = $service->service_title;
						$service_duration_minutes = $service->service_duration/60;

						$st_date = $day_id;
						$st_time = $book_from.':00';
						
						$start_time = $st_date.'T'.$st_time;
						$password = $getbwpcomplement->zoom->get_random_number(8);
						$duration = $service_duration_minutes;

						$topic = __('Booking Meetings', 'get-bookings-wp').' '.$description.' #'.$booking_id;						
						$agenda ='--'.  __('Booking ID:', 'get-bookings-wp').' '.$booking_id.'--';
						$agenda .='--'. __('Service', 'get-bookings-wp').' '.$description.'--';

						$arg = array(
								
							'topic'              => $topic,
							'agenda'              => $agenda,
							'type'                    =>   '2',
							'start_time'                => $start_time,			
							'password'                  => $password,
							'duration'                  => $duration,        		
						);

						$meeting = $getbwpcomplement->zoom->create_metting( $arg, $staff_id);
						$this->zoom_meeting_url = $meeting->join_url;	
						$this->meeting_id = $meeting->id;
						$getbookingwp->messaging->meeting_password =$password;
						$getbookingwp->messaging->zoom_meeting_url = $this->zoom_meeting_url;			
						$getbookingwp->appointment->update_booking_meta($booking_id, 'zoom_join_url',$this->zoom_meeting_url);
						$getbookingwp->appointment->update_booking_meta($booking_id, 'zoom_meeting_id',$this->meeting_id);
						$getbookingwp->appointment->update_booking_meta($booking_id, 'zoom_meeting_password',$this->meeting_password);
					}
					
				}
				
				if(isset($getbwpcomplement)){
				
					//mailchimp					 
					 if(isset($_POST["getbwp-mailchimp-confirmation"]) && $_POST["getbwp-mailchimp-confirmation"]==1) {
						 $list_id =  $getbookingwp->get_option('mailchimp_list_id');					 
						 $getbwpcomplement->newsletter->mailchimp_subscribe($user_id, $list_id);
						 update_user_meta ($user_id, 'getbwp_mailchimp', 1);				 						
					 }
					 
					 //aweber	
					 $list_id = get_option( "buproaw_aweber_list");				 
					 if(isset($_POST["getbwp-aweber-confirmation"]) && $_POST["getbwp-aweber-confirmation"]==1 && $list_id !=''){
						 $user_l = get_user_by( 'id', $user_id ); 				 
						 $getbwpcomplement->aweber->buproaw_subscribe($user_l, $list_id);
						 update_user_meta ($user_id, 'getbwp_aweber', 1);				 						
						
					 }
				
				}	

				//check if it's a paid sign up				
				if($getbookingwp->get_option('registration_rules')!=1){		
					
					//payment Method
					$payment_method = sanitize_text_field($_POST["getbwp_payment_method"]);					
					//update status 					 
					 					  
					  $payment_procesor = false;
					  
					  if($_POST["getbwp_payment_method"]=='' || $_POST["getbwp_payment_method"]=='paypal'){
						  $payment_procesor = true;
						  $payment_method="paypal";						  
						 
					
					  }elseif($_POST["getbwp_payment_method"]=='bank'){  
					  
					  	   $payment_method="bank";
						   $payment_procesor = false;
						   
					   }elseif($_POST["getbwp_payment_method"]=='stripe'){  
					  
					  	   $payment_method="stripe";
						   $payment_procesor = true;
						
					   }elseif($_POST["getbwp_payment_method"]=='authorize'){  
					  
					  	   $payment_method="authorize";
						   $payment_procesor = true;
					  }
					  
					  
					  //create order					  
					  $order_data = array('user_id' => $user_id,
						 'transaction_key' => $transaction_key,
						 'amount' => $amount,
						 'booking_id' => $booking_id ,
						 'product_name' => $p_name ,
						 'status' => 'pending',		
						 'service_id' => $service_id ,
						 'staff_id' => $staff_id ,				
						 'method' => $payment_method,
						 'quantity' => $quantity); 						 
						 
						
					$order_id = $getbookingwp->order->create_order($order_data);	  
					 			 
					if($payment_method=="paypal" && $amount > 0 && $payment_procesor){
						  $ipn = $getbookingwp->paypal->get_ipn_link($order_data, 'ini');		  
						  
						  //redirect to paypal
						  header("Location: $ipn");
						  exit;
						  
					}elseif($payment_method=="stripe" && $amount > 0 && $payment_procesor){
												
						
						if(isset($getbwpcomplement)){							
							
							session_start();
							$getbwp_payment_method_intent =  sanitize_text_field($_SESSION['payment_intent_id']);;	
													
							//service			
							$service = $getbookingwp->service->get_one_service($service_id);							
							$description = $service->service_title;						
							
							$intent = 	$getbwpcomplement->stripe->get_transaction_intent($getbwp_payment_method_intent);
							
							
							if($intent->status == 'succeeded'){
								$getbwpcomplement->stripe->process_order($transaction_key, $intent);							
								$this->handle_redir_success_trans($transaction_key);								
							
							}else{				
								
								echo wp_kses($intent->status, $getbookingwp->allowed_html);
							
							}
						
						}
						
						
					}elseif($payment_method=="authorize" && $amount > 0 && $payment_procesor){
						
						
						if(isset($getbwpcomplement)){
							$res = array();
							
							//service			
							$service = $getbookingwp->service->get_one_service($service_id);							
							$description = $service->service_title;						
							
							$getbwp_authorize_token = sanitize_text_field($_POST['getbwp_authorize_token']);								
							$res = 	$getbwpcomplement->authorize->charge_credit_card($getbwp_authorize_token, $description, $amount);
							
							if($res['result']=='ok'){
								$getbwpcomplement->authorize->process_order($transaction_key, $res);																
								$this->handle_redir_success_trans($transaction_key);								
							
							}else{

								echo wp_kses($res['message'], $getbookingwp->allowed_html);							
							}
						}	
						
					
					}elseif($payment_method=="bank" && !$payment_procesor){							
					
						 
						//service			
						$service = $getbookingwp->service->get_one_service($service_id);
						
						//get appointment			
						$appointment = $getbookingwp->appointment->get_one($booking_id);	
						
						// Get Order
						$rowOrder = $getbookingwp->order->get_order($transaction_key);										
										 
						//get user				
						$staff_member = get_user_by( 'id', $staff_id );
						$client = get_user_by( 'id', $user_id );	
						
						$getbookingwp->messaging->zoom_meeting_url = $this->zoom_meeting_url;											
						$getbookingwp->messaging->send_payment_confirmed_bank($staff_member, $client, $service, $appointment, $rowOrder );
						
						//redir
					     $this->handle_redir_success_trans_bank($transaction_key);
						   
					 }else{						 
						 
						 //paid membership but free plan selected						 
					  
					 }

				}else{
					
					//this is not a paid sign up
					
					//create order					  
					 $order_data = array('user_id' => $user_id,
						 'transaction_key' => $transaction_key,
						 'amount' => $amount,
						 'booking_id' => $booking_id ,
						 'product_name' => $p_name ,
						 'status' => 'pending',		
						 'service_id' => $service_id ,
						 'staff_id' => $staff_id ,				
						 'method' => 'free'); 						 
						 
						
					$order_id = $getbookingwp->order->create_order($order_data);	
					
					//service			
					$service = $getbookingwp->service->get_one_service($service_id);
						
					//get appointment			
					$appointment = $getbookingwp->appointment->get_one($booking_id);	
						
					// Get Order
					$rowOrder = $getbookingwp->order->get_order($transaction_key);	
					
					//Set initial status					
					$this->set_initial_booking_status($booking_id, 'free');								
										 
					//get user				
					$staff_member = get_user_by( 'id', $staff_id );
					$client = get_user_by( 'id', $user_id );
					
					$getbookingwp->messaging->zoom_meeting_url = $this->zoom_meeting_url;											
					$getbookingwp->messaging->send_payment_confirmed($staff_member, $client, $service, $appointment, $rowOrder );	
					
					//redir
					$this->handle_redir_success_trans_free($transaction_key);				
									
				}				
			} 
	}
	
	public function set_initial_booking_status($booking_id, $method){
		global $getbookingwp ;
		
		if($method=='free'){
			
			$status = $getbookingwp->get_option('gateway_free_default_status');
			if($status==''){$status=0;}
			
		}else{
			
			$status=0;			
		
		}
		
		/*Update Appointment*/						
		$getbookingwp->appointment->update_appointment_status($booking_id,$status);
	
	}
	//this is the custom redirecton when not using payments
	public function handle_redir_success_trans_free($key){
		global $getbookingwp, $wp_rewrite ;
		
		$wp_rewrite = new WP_Rewrite();		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$url = '';
		$my_success_url = '';		
		
		if($getbookingwp->get_option('gateway_free_success_active')=='1'){			
			$sucess_page_id = $getbookingwp->get_option('gateway_free_success');
			$my_success_url = get_permalink($sucess_page_id);		
		}
		
		if($my_success_url=="")	{
			$url = sanitize_url($_SERVER['REQUEST_URI'].'?getbwp_payment_status=ok&getbwp_payment_method=&getbwp_order_key='.$key);
				
		}else{
					
			$url = $my_success_url;				
				
		}
		
		wp_redirect( $url );
		exit;
		 
	}	
	
	//this is the custom redirecton when not using payments
	public function handle_redir_success_trans_bank($key){
		global $getbookingwp, $wp_rewrite ;
		
		$wp_rewrite = new WP_Rewrite();		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$url = '';
		$my_success_url = '';		
		
		if($getbookingwp->get_option('gateway_bank_success_active')=='1'){			
			$sucess_page_id = $getbookingwp->get_option('gateway_bank_success');
			$my_success_url = get_permalink($sucess_page_id);		
		}
		
		if($my_success_url=="")	{
			$url = sanitize_url($_SERVER['REQUEST_URI'].'?getbwp_payment_status=ok&getbwp_payment_method=bank&getbwp_order_key='.$key);
				
		}else{
					
			$url = $my_success_url;				
				
		}
		 		  
		wp_redirect( $url );
		exit;
		  
		 
	}
	
	//this is the custom redirecton for stripe
	public function handle_redir_success_trans($key)
	{
		global $getbookingwp, $wp_rewrite ;
		
		$wp_rewrite = new WP_Rewrite();		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$url = '';
		$my_success_url = '';		
		
		if($getbookingwp->get_option('gateway_stripe_success_active')=='1')		
		{			
			$sucess_page_id = $getbookingwp->get_option('gateway_stripe_success');
			$my_success_url = get_permalink($sucess_page_id);		
		}
		
		if($my_success_url=="")
		{
			$url = sanitize_url($_SERVER['REQUEST_URI'].'?getbwp_payment_status=ok&getbwp_payment_method=stripe&getbwp_order_key='.$key);
				
		}else{
					
			$url = $my_success_url;				
				
		}
		
		 		  
		wp_redirect( $url );
		exit;
		  
		 
	}
	
	
	public function get_unique_verify_account_id()
	{
		  $rand = $this->genRandomStringActivation(8);
		  $key = session_id()."_".time()."_".$rand;
		  
		  return $key;
		  
		 
	  }
	
	
	public function redirect_blocked_user()
	{
		global $getbookingwp, $wp_rewrite ;
		
		$wp_rewrite = new WP_Rewrite();
		
		require_once(ABSPATH . 'wp-includes/link-template.php');		
					    
		//check redir		
		$account_page_id = $getbookingwp->get_option('uultra_ip_defender_redirect_page');
		$my_account_url = get_permalink($account_page_id);
				
		if($my_account_url=="")
		{
			$url = sanitize_url($_SERVER['REQUEST_URI']);
				
		}else{
					
			$url = $my_account_url;				
				
		}
				
		wp_redirect( $url );
		exit;
	
	}
	/*Get errors display*/
	function get_errors() {
		global $getbookingwp;
		$display = null;
		if (isset($this->errors) && count($this->errors)>0) 
		{
		$display .= '<div class="getbwp-errors">';
			foreach($this->errors as $newError) {
				
				$display .= '<span class="getbwp-error xoouserultra-error-block"><i class="usersultra-icon-remove"></i>'.$newError.'</span>';
			
			}
		$display .= '</div>';
		} else {
		
			$this->registered = 1;
			
			$uultra_settings = get_option('getbwp_options');

            // Display custom registraion message
            if (isset($uultra_settings['msg_register_success']) && !empty($uultra_settings['msg_register_success']))
			{
                $display .= '<div class="getbwp-success"><span><i class="fa fa-ok"></i>' . remove_script_tags($uultra_settings['msg_register_success']) . '</span></div>';
            
			}else{
				
                $display .= '<div class="getbwp-success"><span><i class="fa fa-ok"></i>'.__('Registration successful. Please check your email.','xoousers').'</span></div>';
            }

            // Add text/HTML setting to be displayed after registration message
            if (isset($uultra_settings['html_register_success_after']) && !empty($uultra_settings['html_register_success_after'])) 
			
			{
                $display .= '<div class="getbwp-success-html">' . remove_script_tags($uultra_settings['html_register_success_after']) . '</div>';
            }
			
			
			
			if (isset($_POST['redirect_to'])) {
				wp_redirect( sanitize_url($_POST['redirect_to']) );
			}
			
		}
		return $display;
	}

}

$key = "register";
$this->{$key} = new GetBookingsWPUserRegister();