<?php
class GetBookingsWPPayment {
	

	function __construct() {	
		
		add_action( 'init', array($this, 'handle_init' ) );		
			
	}
	
	public function handle_init(){
		if (isset($_POST['txn_id'])) {		
			$this->handle_paypal_ipn();		
		}		
	}
	
		
	/*handle ipn*/
	public function handle_paypal_ipn(){
				
		global $wpdb,  $getbookingwp;
		
		$req = 'cmd=_notify-validate';

		// Read the post from PayPal system and add 'cmd'
		$fullipnA = array();		
				
				
		// Assign posted variables to local variables
		$item_name = sanitize_text_field($_POST['item_name']);
		$item_number = sanitize_text_field($_POST['item_number']);
		$payment_status = sanitize_text_field($_POST['payment_status']);
		$payment_amount = sanitize_text_field($_POST['mc_gross']);
		$payment_currency = sanitize_text_field($_POST['mc_currency']);
		$txn_id = sanitize_text_field($_POST['txn_id']);
		$receiver_email = sanitize_text_field($_POST['receiver_email']);
		$payer_email = sanitize_text_field($_POST['payer_email']);
		$txn_type = sanitize_text_field($_POST['txn_type']);
		$pending_reason = sanitize_text_field($_POST['pending_reason']);
		$payment_type = sanitize_text_field($_POST['payment_type']);
		$custom_key = sanitize_text_field($_POST['custom']);
		
		//tweak for multi purchase
		$custom = explode("|", sanitize_text_field($_POST['custom']));
		
		$type = $custom[0];
		$custom_key = $custom[1];	
		
		if($this->check_ipn()) {
	  			
			
			/*VALID TRANSACTION*/			
			$errors = "";
			
			$paypal_email = $getbookingwp->get_option("gateway_paypal_email");
			$paypal_currency_code = $getbookingwp->get_option("gateway_paypal_currency");
			$business_email = $paypal_email;
			
			$is_cart = false;
			
			//check if this is a transaction by using shopping cart
			$shopping_cart = $getbookingwp->order->get_cart_with_key_status($custom_key,"0");
			
			$cart_id = $shopping_cart->cart_id;
			
			if($cart_id!="") {
				$is_cart = true;				
				$total_price = $shopping_cart->cart_amount;
				
				/*We have to notify client, admin and staff members of this purchase*/
				
				 
				
			}else{ //this is not a cart purchase
			
				// Get Order
				$rowOrder = $getbookingwp->order->get_order_pending($custom_key);
				
				if ($rowOrder->order_id==""){
					$errors .= " --- Order Key Not VAlid: " .$custom_key;
				}		
					
				$order_id = $rowOrder->order_id;								
				$total_price = $rowOrder->order_amount;  						
				$booking_id = 	$rowOrder->order_booking_id	;			
				
				//get appointment			
				$appointment = $getbookingwp->appointment->get_one($booking_id);
				$staff_id = $appointment->booking_staff_id;	
				$client_id = $appointment->booking_user_id;	
				$service_id = $appointment->booking_service_id;
				
				//service			
				$service = $getbookingwp->service->get_one_service($service_id);	
					
			} //end if cart
			
			/*Transaction Type*/			
			if($txn_type=="subscr_cancel" )	{
				//payment cancelled				
				$errors .= " --- Payment Failed";				
				
			}elseif($txn_type=="subscr_eot"){
				
				//payment cancelled				
				$errors .= " --- Payment Expired";				
			
			}elseif($txn_type=="failed"){
				
				//payment cancelled				
				$errors .= " --- Payment Failed";				
						
				
			}else{
				
				//sucesful transaction
				
				// check that payment_amount is correct		
				if ($payment_amount < $total_price) {
					$errors .= " --- Wrong Amount: Received $payment_amount$payment_currency; Expected: $total_price$paypal_currency_code";
					
				}
				
				// check currency						
				if ($payment_currency != $paypal_currency_code){
					$errors .= " --- Wrong Currency - Received: $payment_amount$payment_currency; Expected: $total_price$paypal_currency_code";
					
				}
			}
			
			if ($errors==""){
				if ($type=="ini"){
					
					if($is_cart) {						
						
						$appointments_cart = $getbookingwp->appointment->get_all_with_cart($cart_id);
					    
						foreach ( $appointments_cart as $appointment ) {
							
							$booking_id = $appointment->booking_id;
							
							//get users				
							$staff_member = get_user_by( 'id', $appointment->booking_staff_id );
							$client = get_user_by( 'id', $appointment->booking_user_id );
							
							//service			
							$service = $getbookingwp->service->get_one_service($appointment->booking_service_id);												
							/*Get Order*/	
							$rowOrder = $getbookingwp->order->get_order_with_booking_id($appointment->booking_id);							
							/*Update Order status*/	
							$order_id = 	$rowOrder->order_id;		
							$getbookingwp->order->update_order_status($order_id,'confirmed');
							
							/*Update Order With Payment Response*/				
							$getbookingwp->order->update_order_payment_response($order_id,$txn_id);
													
							 /*Update Appointment*/						
							$getbookingwp->appointment->update_appointment_status($booking_id,1);							
							
							/*Send Notifications*/
							$getbookingwp->messaging->send_payment_confirmed($staff_member, $client, $service, $appointment,$rowOrder );				
							
					
					     } //end for each
						 
						 /*Update Cart status*/	
						 $getbookingwp->order->update_cart_status($cart_id,'1');
						 
						 //kill cart session					
						 $getbookingwp->register->kill_shopping_cart();						 
						 
						
					}else{ //this is not a cart transaction we use common method to notify client, staff, admin
				
						/*Update Order status*/				
						$getbookingwp->order->update_order_status($order_id,'confirmed');
						
						/*Update Order With Payment Response*/				
						$getbookingwp->order->update_order_payment_response($order_id,$txn_id);	
						
						/*Update Appointment*/						
						$getbookingwp->appointment->update_appointment_status($booking_id,1);												
										
						//get user				
						$staff_member = get_user_by( 'id', $staff_id );
						$client = get_user_by( 'id', $client_id );					
											
						$getbookingwp->messaging->send_payment_confirmed($staff_member, $client, $service, $appointment,$rowOrder );	
					
					
					} //end if cart
					
				}		
				
			}else{
				
				//$getbookingwp->messaging->paypal_ipn_debug("IPN ERRORS: ".$errors);
				
			}
			
		}else{
			
			//$getbookingwp->messaging->paypal_ipn_debug("IPN NOT VERIFIED: ".$fullipn);			
			
			/*This is not a valid transaction*/
		}
		
		if($getbookingwp->get_option("getbwp_send_ipn_to_admin") =='yes'){						
			$getbookingwp->messaging->paypal_ipn_debug("IPN OUTPUT-------: ".$fullipn);		
		}
		
	}	

	function check_ipn() { 


		if(!empty(sanitize_text_field($_POST)) ){
			$ipn_response = true;
		}else{
			$ipn_response = false;
		}
	
		if ($ipn_response == false) {
			return false;
		}
	
		if ($ipn_response && $this->check_ipn_valid($ipn_response)) {
			header('HTTP/1.1 200 OK');	
			return true;
		}
   }
   
   function check_ipn_valid($ipn_response) {
	   
	   global $wpdb,  $getbookingwp;			   
	   $mode = $getbookingwp->get_option("gateway_paypal_mode");
	   
	   if ($mode==1) {
		   $url ='https://www.paypal.com/cgi-bin/webscr';	
	   
	   }else{	
	   
		   $url ='https://www.sandbox.paypal.com/cgi-bin/webscr'; 	
	   
	   }
		 
		// Get received values from post data		  
		$validate_ipn = array('cmd' => '_notify-validate');	   
		$validate_ipn += stripslashes_deep($ipn_response);
	
		// Send back post vars to paypal
	
		$params = array(
			'body' => $validate_ipn,
			'sslverify' => false,
			'timeout' => 60,
			'httpversion' => '1.1',
			'compress' => false,
			'decompress' => false,
			'user-agent' => 'paypal-ipn/'
		 );
	
		 // Post back to get a response
	
		 $response = wp_safe_remote_post($url, $params);
	
		 // check to see if the request was valid
	
		 if (!is_wp_error($response) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr($response['body'], 'VERIFIED')) {
	
			 return true;
	
		 }
	
		 return false;
	
   }
	
	function StopProcess()
	{
	
		exit;
	}
	
	function Array2Str($kvsep, $entrysep, $a)
	{
		$str = "";
		foreach ($a as $k=>$v)
		{
			$str .= "{$k}{$kvsep}{$v}{$entrysep}";
		}
		return $str;
	}
	
	public function get_redir_cancel_trans($key)
	{
		global $getbookingwp, $wp_rewrite, $post ;
		
		$wp_rewrite = new WP_Rewrite();		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$url = '';
		$my_success_url = '';			
		$post_slug=$post->post_slug;	
		
		if($getbookingwp->get_option('gateway_paypal_cancel_active')=='1')		
		{			
			$sucess_page_id = $getbookingwp->get_option('gateway_paypal_cancel');
			$my_success_url = get_permalink($sucess_page_id);		
		}
		
		if($my_success_url=="")
		{
			//$url = site_url("/").$post_slug.'?getbwp_payment_status=ok&getbwp_order_key='.$key;
            $url = site_url("/").$post_slug;
				
		}else{
					
			//$url = $my_success_url.'?getbwp_payment_status=ok&getbwp_order_key='.$key;
            $my_success_url;
				
		}		
		 		  
		//return urlencode($url);	
        return $url;
		 
	  }
	
	public function get_redir_success_trans($key)
	{
		global $getbookingwp, $wp_rewrite, $post ;
		
		$wp_rewrite = new WP_Rewrite();		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$url = '';
		$my_success_url = '';			
		$post_slug=$post->post_slug;	
		
		if($getbookingwp->get_option('gateway_paypal_success_active')=='1')		
		{			
			$sucess_page_id = $getbookingwp->get_option('gateway_paypal_success');
			$my_success_url = get_permalink($sucess_page_id);		
		}
		
		if($my_success_url=="")
		{
			$url = site_url("/").$post_slug.'?getbwp_payment_status=ok&getbwp_order_key='.$key;
				
		}else{
					
			$url = $my_success_url.'?getbwp_payment_status=ok&getbwp_order_key='.$key;				
				
		}		
		 		  
		return urlencode($url);		  
		 
	  }
	
	
	/*Get IPN*/
	public function get_ipn_link($order,$tran_type)
	{	
		
		global $wpdb,  $getbookingwp, $wp_rewrite;
		
		$wp_rewrite = new WP_Rewrite();		
		require_once(ABSPATH . 'wp-includes/link-template.php');	
		
		extract($order);
		
		$paypal_email = $getbookingwp->get_option("gateway_paypal_email");
		$currency_code = $getbookingwp->get_option("gateway_paypal_currency");		
					
		$service = $getbookingwp->service->get_one_service($service_id);
		$p_name = $service->cate_name.' - '.$service->service_title;
		
		$service_type= '0';
		
		$service_details = $getbookingwp->userpanel->get_staff_service_rate( $staff_id, $service_id );		 
	    $amount= $service_details['price']*$quantity;						
		$paypalcustom = $tran_type."|".$transaction_key;
		
		//get IPN Call Back URL:
		$web_url = site_url();
		$notify_url = $web_url."/?bupipncall=".$transaction_key;
		
		/*return sucess transaction - By default the user is taken to the backend*/		
		$sucess_url = $this->get_redir_success_trans($transaction_key);		
		$cancel_return = $this->get_redir_cancel_trans($transaction_key);		
				
				
		$mode = $getbookingwp->get_option("gateway_paypal_mode");
		
		if($mode==1)
		{			
			$mode = "www";			
			
		}else{
			
			$mode = "www.sandbox";
			$paypal_email = $getbookingwp->get_option("gateway_paypal_sandbox_email");
		
		}
		
		
		if($service_type=="1")
		{
			$type = "_xclick-subscriptions";			
			
			if($amount_setup>0)
			{
                $parameters = "cmd=".$type."&business=".$paypal_email."&currency_code=".$currency_code."&no_shipping=1&item_name=".$p_name."&return=".$sucess_url."&notify_url=".$notify_url."&custom=".$paypalcustom."&a1=".$amount_setup."&p1=".$package_period."&t1=".$package_time_period."&a3=".$amount."&p3=".$package_period."&t3=".$package_time_period."&src=1&sra=1"."&cancel_return=".$cancel_return;
                
                //$parameters = urlencode($parameters);
				
                //setup fee				
				$url = "https://".$mode.".paypal.com/webscr?".$parameters;				
			
			}else{
                
                
				$parameters = "cmd=".$type."&business=".$paypal_email."&currency_code=".$currency_code."&no_shipping=1&item_name=".$p_name."&return=".$sucess_url."&notify_url=".$notify_url."&custom=".$paypalcustom."&a3=".$amount."&p3=".$package_period."&t3=".$package_time_period."&src=1&sra=1"."&cancel_return=".$cancel_return;
                
                //$parameters = urlencode($parameters);   
                
				$url = "https://".$mode.".paypal.com/webscr?".$parameters;			
			
			}			
			
		}
		
				
		if($service_type=="0")
		{
			$type = "_xclick";
            
            $parameters ="cmd=".$type."&business=".$paypal_email."&currency_code=".$currency_code."&no_shipping=1&item_name=".$p_name."&return=".$sucess_url."&notify_url=".$notify_url."&custom=".$paypalcustom."&amount=".$amount."&p3=".$package_period."&t3=".$package_time_period."&src=1&sra=1"."&cancel_return=".$cancel_return;		
			
            
   
                
			$url = "https://".$mode.".paypal.com/webscr?".$parameters;	
            
		}
		
		
		return $url;
		
	}
	
	
	/*Get IPN*/
	public function get_ipn_cart($order,$tran_type)
	{	
		
		global $wpdb,  $getbookingwp, $wp_rewrite;
		
		$wp_rewrite = new WP_Rewrite();		
		require_once(ABSPATH . 'wp-includes/link-template.php');	
		
		extract($order);
		
		$paypal_email = $getbookingwp->get_option("gateway_paypal_email");
		$currency_code = $getbookingwp->get_option("gateway_paypal_currency");		
					
		$p_name = $getbookingwp->get_option("shopping_cart_description");
		$company_name = $getbookingwp->get_option('company_name');
		
		if($p_name==''){$p_name = $company_name.__(' - Purchase Details:', 'get-bookings-wp');}
		
		$service_type= '0';
		
		$paypalcustom = $tran_type."|".$transaction_key;
		
		//get IPN Call Back URL:
		$web_url = site_url();
		$notify_url = $web_url."/?getbwpipncall=".$transaction_key;
		
		/*return sucess transaction - By default the user is taken to the backend*/		
		$sucess_url = $this->get_redir_success_trans($transaction_key);		
		$cancel_return = $this->get_redir_cancel_trans($transaction_key);		
		
				
		$mode = $getbookingwp->get_option("gateway_paypal_mode");
		
		if($mode==1)
		{			
			$mode = "www";			
			
		}else{
			
			$mode = "www.sandbox";
			$paypal_email = $getbookingwp->get_option("gateway_paypal_sandbox_email");
		
		}
		
		
		if($service_type=="1")
		{
			$type = "_xclick-subscriptions";			
			
			if($amount_setup>0)
			{
				//setup fee				
				$url = "https://".$mode.".paypal.com/webscr?cmd=".$type."&business=".$paypal_email."&amp;currency_code=".$currency_code."&no_shipping=1&item_name=".$p_name."&return=".$sucess_url."&amp;notify_url=".$notify_url."&custom=".$paypalcustom."&a1=".$amount_setup."&p1=".$package_period."&t1=".$package_time_period."&a3=".$amount."&p3=".$package_period."&t3=".$package_time_period."&src=1&sra=1"."&cancel_return=".$cancel_return;				
			
			}else{
				
				$url = "https://".$mode.".paypal.com/webscr?cmd=".$type."&business=".$paypal_email."&amp;currency_code=".$currency_code."&no_shipping=1&item_name=".$p_name."&return=".$sucess_url."&amp;notify_url=".$notify_url."&custom=".$paypalcustom."&a3=".$amount."&p3=".$package_period."&t3=".$package_time_period."&src=1&sra=1"."&cancel_return=".$cancel_return;			
			
			}			
			
		}
		
				
		if($service_type=="0")
		{
			$type = "_xclick";
			
			$url = "https://".$mode.".paypal.com/webscr?cmd=".$type."&business=".$paypal_email."&amp;currency_code=".$currency_code."&no_shipping=1&item_name=".$p_name."&return=".$sucess_url."&amp;notify_url=".$notify_url."&custom=".$paypalcustom."&amount=".$amount."&p3=".$package_period."&t3=".$package_time_period."&src=1&sra=1"."&cancel_return=".$cancel_return;
		}
		
		
		return $url;
		
	}
	
	

	
	
	
}
$key = "paypal";
$this->{$key} = new GetBookingsWPPayment();