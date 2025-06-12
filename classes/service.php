<?php
class GetBookingsWPService
{
	var $mBusinessHours;
	var $mDaysMaping;
	
	function __construct(){
				
		$this->ini_module();		
		add_action( 'wp_ajax_display_categories', array( &$this, 'get_ajax_admin_categories' ));
		add_action( 'wp_ajax_display_admin_services', array( &$this, 'get_ajax_admin_services' ));
		add_action( 'wp_ajax_getbwp_get_service', array( &$this, 'getbwp_get_service' ));	
		add_action( 'wp_ajax_getbwp_update_service', array( &$this, 'getbwp_update_service' ));
		add_action( 'wp_ajax_getbwp_update_global_business_hours', array( &$this, 'getbwp_update_global_business_hours' ));
		add_action( 'wp_ajax_getbwp_update_staff_business_hours', array( &$this, 'update_staff_business_hours' ));	
			
		add_action( 'wp_ajax_getbwp_book_step_2',  array( &$this, 'getbwp_book_step_2' ));
		add_action( 'wp_ajax_nopriv_getbwp_book_step_2',  array( &$this, 'getbwp_book_step_2' ));
		
		add_action( 'wp_ajax_getbwp_book_step_3',  array( &$this, 'getbwp_book_step_3' ));
		add_action( 'wp_ajax_nopriv_getbwp_book_step_3',  array( &$this, 'getbwp_book_step_3' ));	
		
		add_action( 'wp_ajax_getbwp_book_step_4',  array( &$this, 'getbwp_book_step_4' ));
		add_action( 'wp_ajax_nopriv_getbwp_book_step_4',  array( &$this, 'getbwp_book_step_4' ));
		
		add_action( 'wp_ajax_getbwp_book_step_show_cart',  array( &$this, 'getbwp_book_step_show_cart' ));
		add_action( 'wp_ajax_nopriv_getbwp_book_step_show_cart',  array( &$this, 'getbwp_book_step_show_cart' ));		
		
		add_action( 'wp_ajax_getbwp_book_step_2_hotels',  array( &$this, 'getbwp_book_step_2_hotels' ));
		add_action( 'wp_ajax_nopriv_getbwp_book_step_2_hotels',  array( &$this, 'getbwp_book_step_2_hotels' ));
		
		add_action( 'wp_ajax_getbwp_update_purchase_total',  array( &$this, 'update_purchase_total_inline' ));
		add_action( 'wp_ajax_nopriv_getbwp_update_purchase_total',  array( &$this, 'update_purchase_total_inline' ));
		
		add_action( 'wp_ajax_getbwp_delete_cart_item',  array( &$this, 'delete_cart_item' ));
		add_action( 'wp_ajax_nopriv_getbwp_delete_cart_item',  array( &$this, 'delete_cart_item' ));
		
		add_action( 'wp_ajax_getbwp_get_shopping_cart',  array( &$this, 'getbwp_get_shopping_cart_2' ));
		add_action( 'wp_ajax_nopriv_getbwp_get_shopping_cart',  array( &$this, 'getbwp_get_shopping_cart_2' ));
		
		add_action( 'wp_ajax_getbwp_display_cart_checkout',  array( &$this, 'getbwp_display_cart_checkout' ));
		add_action( 'wp_ajax_nopriv_getbwp_display_cart_checkout',  array( &$this, 'getbwp_display_cart_checkout' ));

		add_action( 'wp_ajax_getbwp_get_categories_front_list',  array( &$this, 'get_categories_front_list' ));
		add_action( 'wp_ajax_nopriv_getbwp_get_categories_front_list',  array( &$this, 'get_categories_front_list' ));	
		
		add_action( 'wp_ajax_getbwp_get_categories_front_list_dropdown',  array( &$this, 'get_categories_front_list_drop' ));
		add_action( 'wp_ajax_nopriv_getbwp_get_categories_front_list_dropdown',  array( &$this, 'get_categories_front_list_drop' ));	
		
		add_action( 'wp_ajax_getbwp_load_dw_of_staff',  array( &$this, 'get_cate_dw_ajax' ));
		add_action( 'wp_ajax_nopriv_getbwp_load_dw_of_staff',  array( &$this, 'get_cate_dw_ajax' ));	

		add_action( 'wp_ajax_getbwp_load_list_staff_serv',  array( &$this, 'get_staff_offering_service_ajax' ));
		add_action( 'wp_ajax_nopriv_getbwp_load_list_staff_serv',  array( &$this, 'get_staff_offering_service_ajax' ));


		add_action( 'wp_ajax_get_cate_dw_admin_ajax',  array( &$this, 'get_cate_dw_admin_ajax' ));	
		add_action( 'wp_ajax_getbwp_check_adm_availability',  array( &$this, 'getbwp_check_adm_availability' ));
		
		add_action( 'wp_ajax_getbwp_check_adm_availability_admin',  array( &$this, 'getbwp_check_adm_availability_admin' ));
		add_action( 'wp_ajax_getbwp_get_category_add_form',  array( &$this, 'get_category_add_form' ));
		add_action( 'wp_ajax_getbwp_add_category_confirm',  array( &$this, 'add_category_confirm' ));
		add_action( 'wp_ajax_getbwp_delete_category',  array( &$this, 'delete_category' ));
		add_action( 'wp_ajax_getbwp_delete_service',  array( &$this, 'delete_service' ));
		add_action( 'wp_ajax_getbwp_client_get_add_form',  array( &$this, 'client_get_add_form' ));
		
		add_action( 'wp_ajax_getbwp_get_service_pricing',  array( &$this, 'get_service_pricing' ));
		add_action( 'wp_ajax_getbwp_update_group_pricing_table',  array( &$this, 'update_group_pricing_table' ));
		add_action( 'wp_ajax_getbwp_sort_categories_list',  array( &$this, 'sort_categories_list' ));

		
		

	}
	
	public function ini_module(){
		global $wpdb;
		
			$query = '
				CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'getbwp_services(
				  `service_id` int(11) NOT NULL AUTO_INCREMENT,
				  `service_title` varchar(300) NOT NULL,
				  `service_desc` varchar(300) NOT NULL,
				  `service_color` varchar(10) DEFAULT NULL,
				  `service_font_color` varchar(10) DEFAULT NULL,
				  `service_duration` int(11) NOT NULL,
				  `service_padding_before` int(11) NOT NULL DEFAULT "0",
				  `service_padding_after` int(11) NOT NULL DEFAULT "0",
				  `service_capacity` int(11) NOT NULL DEFAULT "1",
				  `service_allow_multiple` int(1) DEFAULT NULL,
				  `service_pricing_calculation_type` int(1) DEFAULT "1",
				  `service_category_id` int(11) NOT NULL,
				  `service_icon` varchar(50) NOT NULL,
				  `service_price` decimal(11,2) NOT NULL,
				  `service_price_2` decimal(11,2) DEFAULT "0",
				  `service_type` int(1) NOT NULL DEFAULT "0",
				  `service_private` int(1) NOT NULL DEFAULT "0",
				  `service_meeting_zoom` int(1) NOT NULL DEFAULT "0",
				  `service_order` int(11) NOT NULL DEFAULT "0",
				  `service_woo_product_id` int(11) NOT NULL DEFAULT "0",				  
				  PRIMARY KEY (`service_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
				';
				
			$wpdb->query( $query );				
			
			$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'getbwp_service_variable_pricing (
				`rate_id` int(11) NOT NULL AUTO_INCREMENT,
				`rate_service_id` int(11) NOT NULL,				
				`rate_price` decimal(11,2) NOT NULL,
				`rate_person` int(11) NOT NULL,
				 PRIMARY KEY (`rate_id`)
			) ENGINE=MyISAM COLLATE utf8_general_ci;';

		   $wpdb->query( $query );		   
		   
		   $query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'getbwp_categories (
				`cate_id` bigint(20) NOT NULL auto_increment,
				`cate_template_id` int(11) NOT NULL DEFAULT "0",
				`cate_name` varchar(300) NOT NULL 	,
				`cate_order` int(11) NOT NULL DEFAULT "0",						
				PRIMARY KEY (`cate_id`)
			) COLLATE utf8_general_ci;';

		   $wpdb->query( $query );
		    
		   
			$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'getbwp_service_rates (
				`rate_id` int(11) NOT NULL AUTO_INCREMENT,
				`rate_service_id` int(11) NOT NULL,
				`rate_staff_id` int(11) NOT NULL,
				`rate_price` decimal(11,2) NOT NULL,
				`rate_capacity` int(11) NOT NULL,
				`rate_comission` int(3) NOT NULL DEFAULT 0,
				 PRIMARY KEY (`rate_id`)
			) ENGINE=MyISAM COLLATE utf8_general_ci;';

		   $wpdb->query( $query );
		   
			$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'getbwp_staff_availability (
				  `avail_id` int(11) NOT NULL AUTO_INCREMENT,
				  `avail_staff_id` int(11) NOT NULL,
				  `avail_day` int(11) NOT NULL,
				  `avail_from` time NOT NULL,
				  `avail_to` time NOT NULL,
				  PRIMARY KEY (`avail_id`)
			) ENGINE=MyISAM COLLATE utf8_general_ci;';

		   $wpdb->query( $query );	

			$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'getbwp_filters (
				`filter_id` bigint(20) NOT NULL auto_increment,
				`filter_name` varchar(300) NOT NULL ,	
				`filter_email` varchar(300) NOT NULL ,									
				PRIMARY KEY (`filter_id`)
			) COLLATE utf8_general_ci;';

		   $wpdb->query( $query );	
		   
			$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'getbwp_filter_staff (
				`fstaff_id` bigint(20) NOT NULL auto_increment,
				`fstaff_staff_id` int(11) NOT NULL,
				`fstaff_location_id` int(11) NOT NULL,										
				PRIMARY KEY (`fstaff_id`)
			) COLLATE utf8_general_ci;';

		   $wpdb->query( $query );  
		   
		   
		   $this->update_table();
		   
		
	}
	
	function update_table(){
		global $wpdb;		
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'getbwp_services where field="service_padding_before" ';		
		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) )
		{	
			$sql = 'Alter table  ' . $wpdb->prefix . 'getbwp_services add column service_padding_before int (11) default 0 ; ';
			$wpdb->query($sql);
		}

		$sql ='SHOW columns from ' . $wpdb->prefix . 'getbwp_services where field="service_meeting_zoom" ';		
		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) ){	
			$sql = 'Alter table  ' . $wpdb->prefix . 'getbwp_services add column service_meeting_zoom int (1) default 0 ; ';
			$wpdb->query($sql);
		}		
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'getbwp_services where field="service_padding_after" ';		
		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) ){	
			$sql = 'Alter table  ' . $wpdb->prefix . 'getbwp_services add column service_padding_after int (11) default 0 ; ';
			$wpdb->query($sql);
		}
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'getbwp_services where field="service_allow_multiple" ';		
		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) ){	
			$sql = 'Alter table  ' . $wpdb->prefix . 'getbwp_services add column service_allow_multiple int (1) default 0 ; ';
			$wpdb->query($sql);
		}		
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'getbwp_services where field="service_private" ';		
		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) ){	
			$sql = 'Alter table  ' . $wpdb->prefix . 'getbwp_services add column service_private int (1) default 0 ; ';
			$wpdb->query($sql);
		}
		
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'getbwp_services where field="service_pricing_calculation_type" ';		
		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) ){	
			$sql = 'Alter table  ' . $wpdb->prefix . 'getbwp_services add column service_pricing_calculation_type int (1) default 1 ; ';
			$wpdb->query($sql);
		}
		
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'getbwp_services where field="service_price_2" ';		
		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) ){	
			$sql = 'Alter table  ' . $wpdb->prefix . 'getbwp_services add column service_price_2 decimal(11,2) default 0 ; ';
			$wpdb->query($sql);
		}		
		
		$sql ='SHOW columns from ' . $wpdb->prefix . 'getbwp_categories where field="cate_template_id" ';		
		$rows = $wpdb->get_results($sql);		
		if ( empty( $rows ) ){	
			$sql = 'Alter table  ' . $wpdb->prefix . 'getbwp_categories add column cate_template_id int (11) default 0 ; ';
			$wpdb->query($sql);
		}		
		
	}	
	
	public function get_ajax_admin_categories()	{
		global  $getbookingwp;
		$html = $this->get_admin_categories();	
		echo wp_kses($html, $getbookingwp->allowed_html) ;		
		die();
	}
	
	public function get_ajax_admin_services(){
		global  $getbookingwp;
		
		if(isset($_POST['cate_id'])){
			$cate_id = sanitize_text_field($_POST['cate_id']);
		
		}else{
			
			$cate_id = '';			
		}		
		
		$html = $this->get_admin_services($cate_id);	
		echo wp_kses($html, $getbookingwp->allowed_html) ;		
		die();		
	
	}
	
	
	public function getbwp_book_step_show_cart(){
		
		global  $getbookingwp;
		
		$response = array();
		
		$getbwp_date = sanitize_text_field($_POST['date_to_book']);	
		$service = $this->get_one_service($service_id);		
		$service_details = $getbookingwp->userpanel->get_staff_service_rate( $staff_id, $service_id ); 
		$amount= $service_details['price'];		
		
		$html='';
		
	
		$time_format = $this->get_time_format();	
			
		$staff_member = $getbookingwp->userpanel->get_staff_member($staff_id);	
		
		//parse content		
		$content_text = $getbookingwp->get_template_label("step3_texts",$template_id);		
		$content_text = $this->getbwp_parse_customizer_texts($content_text, $service, $staff_member, $date_from_l);

		$html .= '<div class="getbwpdeta-nav-bar"> ';

		$html .= '<div class="getbwpdeta-searchoption-left"> ';

			$html .= '<span class="input-group-append getwpnav-button-opt">
										<button class="btn btn-modern btn-block btn-modern-nuv mb-2 mb-2-nuv" id="getwp-res-front-to-locations"><span class="input-group-text input-group-text-getwpsearch">
											<i class="fa fa-arrow-left"></i>
										</span></button>
									</span>
									';


		$html .= '</div>';

		$html .= '<div class="getbwpdeta-date-searchoption"> ';
		$html .= '</div>';   //left col
		$html .= '</div>';   //end navbar

		
		
		$html .= '<div class="getbwp-selected-staff-booking-info">'; 
		
		$html .= $content_text;	
		
		
		$html .= '</div>';
		
					
		$html .= $getbookingwp->get_registration_form($order_data);

		$html = wp_kses($html, $getbookingwp->allowed_html);		
		$response = array('response' => 'OK', 'content' => $html);
		echo json_encode($response) ;	
		
		die();
		
		
	}
	
	//this displays the login or payment form
	public function getbwp_display_cart_checkout(){
		
		global  $getbookingwp;
		
		$response = array();
		$order_data = array();	
		
		$template_id = sanitize_text_field($_POST['template_id']);			
		$order_data = array(
						 'template_id' => $template_id); 
			
		$show_cart = 0;
		
		$html = '';		
		
		if($show_cart==1){
		
			$content_text = '';				
			$html .= '<div class="getbwp-selected-staff-booking-info">'; 		
			$html .= $content_text;		
			$html .= '</div>';		
			$html .= $getbookingwp->get_registration_form($order_data);			
		
		}

		$html = wp_kses($html, $getbookingwp->allowed_html);
		$response = array('response' => 'OK', 'content' => $html);
		echo json_encode($response) ;	
		
		die();
		
	}
	
	
	//this displays the login or payment form
	public function getbwp_book_step_3(){
		
		global  $getbookingwp;		
		$response = array();		
		$getbwp_date = sanitize_text_field($_POST['date_to_book']);
		$service_and_staff_id = sanitize_text_field($_POST['service_and_staff_id']);
		$time_slot = sanitize_text_field($_POST['time_slot']);
		$form_id = sanitize_text_field($_POST['form_id']);

		$location_id = '';

		if(isset($_POST['location_id'])){
			$location_id = sanitize_text_field($_POST['location_id']);
		}		
		
		$field_legends = sanitize_text_field($_POST['field_legends']);
		$placeholders = sanitize_text_field($_POST['placeholders']);
		$template_id = sanitize_text_field($_POST['template_id']);	
		$template = sanitize_text_field($_POST['template']);	
		$template = sanitize_text_field($_POST['template']);		
		$max_capacity = sanitize_text_field($_POST['max_capacity']);
		$max_available = sanitize_text_field($_POST['max_available']);		
		$woocommerce_active = sanitize_text_field($_POST['woocommerce_active']);

		$booking_form_template = sanitize_text_field($_POST['template']);
		
		$arr_ser = explode("-", $service_and_staff_id);			
		$service_id = $arr_ser[0]; 
		$staff_id = $arr_ser[1];
		
		$arr_time_slot = explode("-", $time_slot);			
		$book_from = $arr_time_slot[0]; 
		$book_to = $arr_time_slot[1];
		
		$service = $this->get_one_service($service_id);		
		$service_details = $getbookingwp->userpanel->get_staff_service_rate( $staff_id, $service_id ); 
		$amount= $service_details['price'];
		$currency = '';
		$currency_code = '';

		//staff member		
		$staff_member = $getbookingwp->userpanel->get_staff_member($staff_id);	
		
		$order_data = array('getbwp_date' => $getbwp_date,						 
						 'service_id' => $service_id,
						 'form_id' => $form_id,
						 'staff_id' => $staff_id ,
						 'location_id' => $location_id ,
						 'book_from' => $book_from ,
						 'book_to' => $book_to,	
						 'currency' => $currency ,
						 'currency_code' => $currency_code,						 
						 'getbwp_service_cost' =>$amount ,
						 'field_legends' => $field_legends,
						 'placeholders' => $placeholders,
						 'template_id' => $template_id,
						 'max_capacity' => $max_capacity,
						 'max_available' => $max_available); 
		
		
		$date_from_l =  $getbwp_date.' '.$book_from.':00';


				
		
		$html='';		

		$content_text ='';	

		$content_text = $getbookingwp->get_template_label("step3_texts",$template_id);		
		$content_text = $this->getbwp_parse_customizer_texts($content_text, $service, $staff_member, $date_from_l);
	
		$time_format = $this->get_time_format();
		$date_format = $this->get_date_format_conversion();
		//staff member		
		$staff_member = $getbookingwp->userpanel->get_staff_member($staff_id);	
		$show_cart =0;			
		if($show_cart==1 && $woocommerce_active=='no' ){		
			//add to cart and display sucess message	
			$content_text = '';			

			

			$html .= '<div class="getbwp-selected-staff-booking-info">'; 		
			$html .= $content_text;		
			$html .= '</div>';			
			$this->add_item_to_cart($order_data);			
			$html .= $this->getbwp_get_shopping_cart($template_id);
		}elseif($show_cart!=1 && $woocommerce_active=='yes'){
			//add item to woocommerce
			$checkout_url = $this->add_item_to_woocommerce($order_data, $service);
			$html .= $this->get_woo_commerce_add_sucess($checkout_url );
		}else{ //display the registration form

		
					
			$html .= '<div class="getbwpdeta-nav-bar"> ';
			$html .= '<div class="getbwpdeta-searchoption-left"> ';

			if($booking_form_template!='appointment_drop_down'){

				if($template=='appointment_side_bar'){

					//format date

					$getbwp_date = date($date_format , strtotime($getbwp_date));

					$html .= '<div class="nuv-deta-date-select getbwp-datebox-status"> ';
					$html .= '<input type="text" value="'.$getbwp_date.'" id="nuv-fron-date-picker" class="getbwp-datepicker nuv-date-field form-control">	';

						$html .= '<span  class="input-group-append nuv-nav-button-opt getbwp-filter-date-ico-trigger " >
							<span class="input-group-text">
							   <a id="getbwp-filter-date" class="getbwp-btn-next-step1" data-cate-id='.$service_id .' data-staff-id='.$staff_id.'><i class="fa fa-search"></i></a>
							</span>
						</span>
						';			
						$html .= '</div>'; 

						
					$html .= '<span class="input-group-append getwpnav-button-opt">
						<button class="btn btn-modern btn-block btn-modern-nuv mb-2 mb-2-nuv " 
						data-cate-id = "'.$service_id.'" data-staff-id= "'.$staff_id.'" id="getwp-res-front-to-min-rates"><span class="input-group-text input-group-text-getwpsearch">
							<i class="fa fa-arrow-left"></i>
						</span></button>
					</span>
					';

			}else{

					
				$html .= '<span class="input-group-append getwpnav-button-opt">
					<button class="btn btn-modern btn-block btn-modern-nuv mb-2 mb-2-nuv getbwp-btn-next-step1" 
					data-cate-id = "'.$service_id.'" data-staff-id= "'.$staff_id.'" id="getwp-res-front-to-locations"><span class="input-group-text input-group-text-getwpsearch">
						<i class="fa fa-arrow-left"></i>
					</span></button>
				</span>
				';



				}

			}

			

			$html .= '</div>';

			$html .= '<div class="getbwpdeta-date-searchoption"> ';	


			$html .= '</div>';   //left col
			$html .= '</div>';   //end navbar		
		
			$html .= '<div class="getbwp-selected-staff-booking-info">'; 		
			$html .= $content_text;		
			$html .= '</div>';		
		
			$html .= $getbookingwp->get_registration_form($order_data);
			
		}	
					
		
		$html = wp_kses($html, $getbookingwp->allowed_html);
		$response = array('response' => 'OK', 'content' => $html);
		echo json_encode($response) ;
		die();
		
	}

	function add_item_to_woocommerce($order_data, $service){

		global  $getbookingwp, $getbwpcomplement;	

		$woo_order = array( 'service_id' => $order_data['service_id'], 
							'product_id' =>  $service->service_woo_product_id ,
							'staff_id' => $order_data['staff_id'], 
							'currency' => $order_data['currency'], 
							'currency_code' => $order_data['currency_code'], 
							'book_date' => $order_data['getbwp_date'], 
							'from' => $order_data['book_from'] , 
							'to' => $order_data['book_to']  , 
							'book_qty' =>1); 					

		return $getbwpcomplement->woocommerce->classes->cart->add($woo_order);		

	}


	function get_woo_commerce_add_sucess($url){

		$html = '';
		$url = '<a href="'.$url.'">'.__("VIEW CART",'get-bookings-wp').'</a>';
		$message = __("The service has been added to the cart.",'get-bookings-wp');
		$html .= '<div class="getbwp-sucess-bookings-message-cont">';		
		$html .= '<div class="getbwp-sucess-bookings-message"><i class="fa fa-check getbwp-sucess-ico"></i></div>';	
		$html .= '<p>'.$message.'</p>';		
		$html .= '<p>'.$url.'</p>';	
		$html .= '</div>'; 
		return $html;
	}

	
	
	
	
	function getbwp_get_shopping_cart_2(){
		
		global  $getbookingwp;		
		$template_id = sanitize_text_field($_POST["template_id"]);		
		$html ='';				
		$html .= $this->getbwp_get_shopping_cart($template_id);		
		echo wp_kses($html, $getbookingwp->allowed_html);	
		die();		
	}
	

	
	
	//This gives us the service price depending on quantity and calculation type
	function calculate_service_price_cart($b_qty,$service_id,$staff_id)	{
		
		global  $getbookingwp;
		
		$currency_symbol =  $getbookingwp->get_option('paid_membership_symbol');		
			
		if($b_qty==''){$b_qty=1;}
		
		$service_details = $getbookingwp->userpanel->get_staff_service_rate( $staff_id, $service_id );
		$service = $getbookingwp->service->get_one_service($service_id);	
		
		if($service->service_pricing_calculation_type==1 || $service->service_pricing_calculation_type==''){
			//common calculation			
			$amount= $service_details['price'] * $b_qty;
			
		}elseif($service->service_pricing_calculation_type==2){
			
			//sum all pricing depending on quantity				
			$amount = $this->calculate_with_all_quantity($service_id, $b_qty,true );
			
		}elseif($service->service_pricing_calculation_type==3){
			
			//sum only one price depending on qty			
			$amount = $this->calculate_with_all_quantity($service_id, $b_qty, false );		
		
		}
		
		
		
		$response = array('response' => 'OK', 'amount' => $amount, 'amount_with_symbol' => $currency_symbol.$amount);
		return $response ;	
		
	
	}
	
	function update_purchase_total_inline(){
		
		global  $getbookingwp;
		
		$currency_symbol =  $getbookingwp->get_option('paid_membership_symbol');
		
			
		$b_qty = sanitize_text_field($_POST['b_qty']);
		$service_id = sanitize_text_field($_POST['service_id']);
		$staff_id = sanitize_text_field($_POST['staff_id']);
		
		if($b_qty==''){$b_qty=1;}
		
		$service_details = $getbookingwp->userpanel->get_staff_service_rate( $staff_id, $service_id );
		$service = $getbookingwp->service->get_one_service($service_id);	
		
		if($service->service_pricing_calculation_type==1 || $service->service_pricing_calculation_type==''){
			//common calculation			
			$amount= $service_details['price'] * $b_qty;
			
		}elseif($service->service_pricing_calculation_type==2){
			
			//sum all pricing depending on quantity				
			$amount = $this->calculate_with_all_quantity($service_id, $b_qty,true );
			
		}elseif($service->service_pricing_calculation_type==3){
			
			//sum only one price depending on qty			
			$amount = $this->calculate_with_all_quantity($service_id, $b_qty, false );		
		
		}	
		
		$response = array('response' => 'OK', 'amount' => $amount, 'amount_with_symbol' => $currency_symbol.$amount);
		echo json_encode($response) ;		
		die();	
	}
	
	function calculate_with_all_quantity($service_id, $b_qty, $sum_all=true ){
		
		global  $wpdb, $getbookingwp;
		
		$total = 0;
		
		//this is used on variable pricing depending on how many persons the client selects
		if($sum_all){
			
			$sql ="SELECT * FROM " . $wpdb->prefix . "getbwp_service_variable_pricing  
			WHERE rate_service_id=%s AND rate_person <= %s;";
			$sql = $wpdb->prepare($sql,array($service_id, $b_qty));
		
		}else{ //this will apply a unique price depending on how many persons the client selects
			
			$sql ="SELECT * FROM " . $wpdb->prefix . "getbwp_service_variable_pricing  
			WHERE rate_service_id=%s AND rate_person = %s;";
			$sql = $wpdb->prepare($sql,array($service_id, $b_qty));
			
		}
		
		$rates = $wpdb->get_results($sql);		
		
		if (!empty($rates)){			
			foreach($rates as $rate) {
				$total = $total+ $rate->rate_price;	
			}			
		}
		return $total;
	}
	
	//this displays thank you page
	public function getbwp_book_step_4(){
		
		global  $getbookingwp;		
			
		$order_key = sanitize_text_field($_POST['order_key']);
		$payment_method = sanitize_text_field($_POST['payment_method']);

		$appointment = $getbookingwp->appointment->get_appointment_with_key($order_key);
		
		$html ='';
		$message ='';	
		
		if($payment_method=='bank'){
			$message =$getbookingwp->get_option('gateway_bank_success_message'); 	
			
		}elseif($payment_method=='stripe'){
			$message =$getbookingwp->get_option('gateway_stripe_success_message'); 			
		}else{
			
			$message =$getbookingwp->get_option('gateway_free_success_message'); 
		}
		
		if($message==''){
			$message = __("Thank you for your booking. Please check your email.",'get-bookings-wp');
		}	

		$html .= '<div class="getbwp-sucess-bookings-message-cont">';		
		$html .= '<div class="getbwp-sucess-bookings-message"><i class="fa fa-check getbwp-sucess-ico"></i></div>';	
		$html .= '<p>'.$message.'</p>';		
		$html .= '<p class="getbwp-booknig-id">'.__("Your booking number is",'get-bookings-wp').': '.$appointment->booking_id.'</p>'; 		
		$html .= '</div>'; 	
		echo wp_kses($html, $getbookingwp->allowed_html) ;			
		die();
		
		
	}
	
	//used for reschedule
	public function getbwp_check_adm_availability_admin(){
		
		global  $getbookingwp;
		
		$business_hours = get_option('getbwp_business_hours');
		$time_format = $this->get_time_format();		
		
		$slot_length= $getbookingwp->get_option('getbwp_time_slot_length');
		$slot_length_minutes= $slot_length*60;
		
		$display_only_from_hour=  $getbookingwp->get_option('display_only_from_hour');
		$allow_bookings_outside_b_hours=  $getbookingwp->get_option('allow_bookings_outsite_business_hours');		
		
		$time_slots = array();		
		$b_category = sanitize_text_field($_POST['b_category']);			
		$b_staff = sanitize_text_field($_POST['b_staff']);
		$b_date = sanitize_text_field($_POST['b_date']);				
		
		$date_format = $this->get_date_format_conversion();	
		$date_f = DateTime::createFromFormat($date_format, $b_date);
		
		$html = '';
		
		//get days for this service		
		$date_from=  $date_f->format('Y-m-d');	
		$to_sum= $this->get_days_to_display();  
		$end_date=  date("Y-m-d", strtotime("$date_from + $to_sum day"));			
				
		//get random user		
		$staff_id = $this->get_prefered_staff($b_staff, $b_category);				
		
		// Schedule.
        $items_schedule = $getbookingwp->userpanel->get_working_hours($staff_id);
		
		//staff member		
		$staff_member = $getbookingwp->userpanel->get_staff_member($staff_id);			
		
		$cdiv = 0 ;				
		$service = $this->get_one_service($b_category);
		
		if($_POST['b_date']==''){		
			$html .='<p>'.__("Please select a date.",'get-bookings-wp').'</p>';			
			echo  wp_kses($html, $getbookingwp->allowed_html);
			die();
		
		}
		
		if($_POST['b_category']==''){		
			$html .='<p>'.__("Please select a service.",'get-bookings-wp').'</p>';			
			echo  wp_kses($html, $getbookingwp->allowed_html);
			die();
		
		}
		
		//Does the user offer this service?				
		if($getbookingwp->userpanel->staff_offer_service( $staff_id, $b_category )){
			$html .= '<div class="getbwp-selected-staff-booking-info">'; 
			$html .= '<p>'. __('Below you can find a list of available time slots for ','get-bookings-wp').'<strong>'.$service->service_title.'</strong> '.__('by ','get-bookings-wp').'<strong>'.$staff_member->display_name.'</strong>.'.'<p>';	
			$html .= '</div>';
			
			$available_previous =true;
			while (strtotime($date_from) < strtotime($end_date)){
				 $cdiv++;				 
				 $day_num_of_week = date('N', strtotime($date_from));	
				 
				 //is the staff member working on this day?			 
				  if(isset($items_schedule[$day_num_of_week])) {					   
					 
					  $html .= '<h3>'.$getbookingwp->commmonmethods->formatDate($date_from).'</h3>';	  
					  $html .= '<div class="getbwp-time-slots-divisor" id="getbwp-time-sl-div-'.$cdiv.'">';			  
					  $html .= '<ul class="getbwp-time-slots-available-list">';	
					  
					 //get available slots for this date				 
					 $time_slots = $this->get_time_slot_public_for_staff($day_num_of_week,  $staff_id, $b_category, $time_format);
					 
					 //check if staff member is in holiday this day					   
					  $is_in_holiday = $this->is_in_holiday($staff_id, $date_from);					  
					  
					  //staff hourly						 
					  $staff_hourly = $this->get_hourly_for_staf($staff_id, $day_num_of_week);	
					 
					 
					 $cdiv_range = 0 ;
					 					 
					 foreach($time_slots as $slot){
						 $cdiv_range++;							 
						 
						  $day_time_slot = date('Y-m-d', strtotime($date_from)).' '.$slot['from'].':00';
						  
						  $current_time_slot = $slot['from'].':00';
						  $increased_minutes = date('H:i:s', strtotime( $current_time_slot ) +$slot_length_minutes);
						  $to_slot_limit = $date_from.' '. $slot['to'].':00';
						  $day_time_slot_to = $to_slot_limit;
							 
						  $staff_time_slots = array();					 
						  $staff_time_slots = $this->get_time_slots_availability_for_day($staff_id, $b_category, $day_time_slot, $day_time_slot_to);	
						  
						 //check if staff member is on break time for this day.						
						$is_in_break = $this->is_in_break($staff_id, $day_num_of_week, $slot['from'] , $slot['to']);
						
						$is_slot_outside_working_hours = false;
						if($this->is_booking_outside_working_hours($staff_hourly, $time_to, $date_from ) && $allow_bookings_outside_b_hours=='no')
						{
							$is_slot_outside_working_hours = true;							
						}
						
						
						if($staff_time_slots['available']==0 || $is_in_break || $staff_time_slots['busy']==true  ||  $is_in_holiday)
						{							
							$available_slot =false;
									
						}else{								
									
							$available_slot =true;							
						}
							
						$time_from = $slot['from'];
						$time_to = $slot['to'];
						
						//padding before?
						if($service->service_padding_before!='' && $service->service_padding_before!=0 ){
							//previous is not available, then we need to add padding
							if(!$available_previous){
								$minutes_to_increate = $service->service_padding_before;								
								$increased_from = date('H:i:s', strtotime($time_from.':00')+$minutes_to_increate);
								$increased_from = date('H:i', strtotime($increased_from));							
								$time_from = $increased_from;
									
							}
								
						}						 
											 
						 
						 if($display_only_from_hour=='yes' || $display_only_from_hour=='' ) {
							  //reduced view
							 $time_to_display = ''.date($time_format, strtotime($time_from));
						 }else{
							 
							 $time_to_display = ''.date($time_format, strtotime($time_from)).' &ndash; '.date($time_format, strtotime($time_to)).'';					 			
						 
						 }
						 
						 
						 //is All Day event?						
						if($service->service_duration==86400){
							$time_from = '00:00';
						    $time_to = '23:59';						
						}	 
						
						
						if($time_to>$staff_hourly->avail_to || $time_to<$staff_hourly->avail_from){
							$display_unavailable = 'no';
							$is_slot_available = false;
						}
						
						
						if(!$is_slot_outside_working_hours)	{
						 
						 
							
							 $html .= '<li id="getbwp-time-slot-hour-range-'.$cdiv.'-'.$cdiv_range.'">';					
							 $html .= '<div class="getbwp-timeslot-time">'.$time_to_display.'</div>';
							 $html .= '<div class="getbwp-timeslot-count"><span class="spots-available">'.$staff_time_slots['label'].'</span></div>';
							 
							 $html .= '<span class="getbwp-timeslot-people">';
							 
							
							
							if($staff_time_slots['available']==0 || $is_in_break || $staff_time_slots['busy']==true ||  $is_in_holiday)
							{
								$button_class = 'getbwp-button-blocked ';
								$button_label = __('Unavailable','get-bookings-wp');
							
							}else{
								
								$button_class = 'getbwp-button getbwp-btn-book-app-admin ';
								$button_label = __('Select Time Slot','get-bookings-wp');
							
							}
							
							
							$html .= '<button class="new-appt '.$button_class.'" getbwp-data-date="'.date('Y-m-d', strtotime($date_from)).'" getbwp-data-timeslot="'.$time_from.'-'.$time_to.'" getbwp-data-service-staff="'.$b_category.'-'.$staff_id.'">'; //category-userid
							
							$html .= '<span class="button-timeslot"></span><span class="getbwp-button-text">'. $button_label.'</span></button>';
							
							
							
							 $html .= '</span>';						
							 $html .= '</li>';							 	
						 
						 $available_previous = $available_slot;
						 
						}
					 
					  }
					  
					  $html .='</ul>';			  			  
					  $html .= '</div>'; //end time slots divisor				  
				  
				  } //end if working	  
				  
				 
				 //increase date
				 $date_from = date ("Y-m-d", strtotime("+1 day", strtotime($date_from))); 			 
				 
				 
			 }  //end while
			 
		}else{
			
			
			$html .='<p>'.__("This Provider doesn't offer this service.",'get-bookings-wp').'</p>';
			
			
		
		}  //end if
		 		
		
		echo wp_kses($html, $getbookingwp->allowed_html) ;		
		die();		
	
	}
	
	public function get_padding_add_frm($service_id = null, $padding_before = null , $padding_after = null )
	{
		global  $getbookingwp, $getbwpcomplement;		
				
		$html = '';		
		$html .=''.$this->get_padding_drop_downs($service_id,  'getbwp-padding-before', $padding_before). '<span> </span>' .$this->get_padding_drop_downs($service_id,  'getbwp-padding-after', $padding_after).'';
		
		return $html;
		
	
	}
	
	//returns the business hours drop down
	public function get_padding_drop_downs($service_id,  $select_name,$actual_value)
	{
		global  $getbookingwp;
		
		$html = '';
		$selected = '';
		
		$max_hours = 43200; //12 hours in seconds		
		$min_minutes = 15;
		
		$min_minutes=$min_minutes*60;
		
		$html .= '<select name="'.$select_name.'" id="'.$select_name.'">';
		$html .= '<option value="" '.$selected.'>'.__("OFF",'get-bookings-wp').'</option>';
		
		for ($x = $min_minutes; $x <= $max_hours; $x=$x+$min_minutes)
		{
			$selected = '';
			if($actual_value==$x){$selected='selected="selected"';}
		
			$html .= '<option value="'.$x.'" '.$selected.'>'.$this->get_service_duration_format($x).'</option>';
			
		}
		
		$html .= '</select>';
		
		return $html;
		
	
	}
	
	public function get_date_format_conversion()
    {
		global  $getbookingwp;
		$date_format = $getbookingwp->get_option('getbwp_date_picker_format');
		
		if($date_format==''){
			
			$date_format = 'm/d/Y';
			
		}
        return $date_format;
    }
	
	//function used for the admin
	public function getbwp_check_adm_availability()
	{
		
		global  $getbookingwp;
		
		$business_hours = get_option('getbwp_business_hours');
		$time_format = $this->get_time_format();
		
		
		$slot_length= $getbookingwp->get_option('getbwp_time_slot_length');
		$slot_length_minutes= $slot_length*60;
		
		$display_only_from_hour=  $getbookingwp->get_option('display_only_from_hour');
		$allow_bookings_outside_b_hours=  $getbookingwp->get_option('allow_bookings_outsite_business_hours');
		
		$time_slots = array();		
		$b_category = sanitize_text_field($_POST['b_category']);
		$b_staff = sanitize_text_field($_POST['b_staff']);		
		$b_date = sanitize_text_field($_POST['b_date']);
	
		
		$date_format = $this->get_date_format_conversion();	
		$date_f = DateTime::createFromFormat($date_format, $b_date);
		
		
		$html = '';
		
		//get days for this service		
		$date_from=  $date_f->format('Y-m-d');		
		$to_sum= $this->get_days_to_display();  
		$end_date=  date("Y-m-d", strtotime("$date_from + $to_sum day"));			
				
		//get random user		
		$staff_id = $this->get_prefered_staff($b_staff, $b_category);				
		
		// Schedule.
        $items_schedule = $getbookingwp->userpanel->get_working_hours($staff_id);
		
		//staff member		
		$staff_member = $getbookingwp->userpanel->get_staff_member($staff_id);			
		
		$cdiv = 0 ;				
		$service = $this->get_one_service($b_category);
		
		if($_POST['b_date']=='')
		{		
			$html .='<p>'.__("Please select a date.",'get-bookings-wp').'</p>';			
			echo  wp_kses($html, $getbookingwp->allowed_html);
			die();
		
		}
		
		if($_POST['b_category']=='')
		{		
			$html .='<p>'.__("Please select a service.",'get-bookings-wp').'</p>';			
			echo wp_kses($html, $getbookingwp->allowed_html);
			die();
		
		}
		
		//Does the user offer this service?				
		if($getbookingwp->userpanel->staff_offer_service( $staff_id, $b_category ))
		{
			$html .= '<div class="getbwp-selected-staff-booking-info">'; 
			$html .= '<p>'. __('Below you can find a list of available time slots for ','get-bookings-wp').'<strong>'.$service->service_title.'</strong> '.__('by ','get-bookings-wp').'<strong>'.$staff_member->display_name.'</strong>.'.'<p>';	
			$html .= '</div>';
			
		
			while (strtotime($date_from) < strtotime($end_date)) 
			{
				 $cdiv++;
				 
				 $day_num_of_week = date('N', strtotime($date_from));	
				 
				 //is the staff member working on this day?			 
				  if(isset($items_schedule[$day_num_of_week]))
				  {					   
					 
					  $html .= '<h3>'.$getbookingwp->commmonmethods->formatDate($date_from).'</h3>';	  
					  $html .= '<div class="getbwp-time-slots-divisor" id="getbwp-time-sl-div-'.$cdiv.'">';  
					  $html .= '<ul class="getbwp-time-slots-available-list">';	
					  
					 //get available slots for this date				 
					 $time_slots = $this->get_time_slot_public_for_staff($day_num_of_week,  $staff_id, $b_category, $time_format);
					 
					 //check if staff member is in holiday this day					   
					 $is_in_holiday = $this->is_in_holiday($staff_id, $date_from);
					 
					  //staff hourly						 
					  $staff_hourly = $this->get_hourly_for_staf($staff_id, $day_num_of_week);
					 
					 
					 $cdiv_range = 0 ;
					 
					 $slot_previous = array();
					 $available_previous =  true;
					 
					// print_r($time_slots );
					 
					 foreach($time_slots as $slot)
					 {
						 $cdiv_range++;	
						 
						  $day_time_slot = date('Y-m-d', strtotime($date_from)).' '.$slot['from'].':00';
						  $current_time_slot = $slot['from'].':00';
						  $increased_minutes = date('H:i:s', strtotime( $current_time_slot ) +$slot_length_minutes);
						  $to_slot_limit = $date_from.' '. $slot['to'].':00';
						  $day_time_slot_to = $to_slot_limit;
						  
						  $staff_time_slots = array();					 
						  $staff_time_slots = $this->get_time_slots_availability_for_day($staff_id, $b_category, $day_time_slot, $day_time_slot_to);
						  
						 // print_r($staff_time_slots);
					  
					  	   //check if staff member is on break time for this day.						
					 	$is_in_break = $this->is_in_break($staff_id, $day_num_of_week, $slot['from'] , $slot['to']);
						
						$time_from = $slot['from'];
						$time_to = $slot['to'];
							
							
						//check if hour is available to book, we have to use the server time		 
						 $current_slot_time_stamp = strtotime($date_from.' '.$time_from.':00');		 
						 $current_site_time_stamp = strtotime(date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ));
						 
						 $is_passed = false;						 
						 if($current_site_time_stamp>$current_slot_time_stamp)
						 {							 
							 $is_passed = true;	
						 }	
						 
						 
						 $is_slot_outside_working_hours = false;
						if($this->is_booking_outside_working_hours($staff_hourly, $time_to, $date_from ) && $allow_bookings_outside_b_hours=='no')
						{
							$is_slot_outside_working_hours = true;							
						}
						
								
					  
				
						if($staff_time_slots['available']==0 || $is_in_break || $staff_time_slots['busy']==true || $is_in_holiday || $is_passed )
						   {							
								$available_slot =false;
									
							}else{								
									
								$available_slot =true;	
							}
								
							
							//padding before?
							if($service->service_padding_before!='' && $service->service_padding_before!=0)	{
								//previous is not available, then we need to add padding
								if(!$available_previous)
								{
									$minutes_to_increate = $service->service_padding_before;	
									
									$increased_from = date('H:i:s', strtotime($time_from.':00')+$minutes_to_increate);
									$increased_from = date('H:i', strtotime($increased_from));							
									$time_from = $increased_from;
										
								}
									
							}
							 
							 
						 
						 if($display_only_from_hour=='yes' || $display_only_from_hour=='' ) {
							 $time_to_display = ''.date($time_format, strtotime($time_from));
						 }else{
							 $time_to_display = ''.date($time_format, strtotime($time_from)).' &ndash; '.date($time_format, strtotime($time_to)).'';					 			
						 
						 }
						 
						 if(!$is_slot_outside_working_hours)  {
						 
						
							$html .= '<li id="getbwp-time-slot-hour-range-'.$cdiv.'-'.$cdiv_range.'">';					
							$html .= '<div class="getbwp-timeslot-time">'.$time_to_display.'</div>';
							$html .= '<div class="getbwp-timeslot-count"><span class="spots-available">'.$staff_time_slots['label'].'</span></div>';
							
							$html .= '<span class="getbwp-timeslot-people">';		 
							
							
							if($staff_time_slots['available']==0 || $is_in_break || $staff_time_slots['busy']==true || $is_in_holiday || $is_passed)
							{
								$button_class = 'getbwp-button-blocked ';
								$button_label = __('Unavailable','get-bookings-wp');
								$unavailable =true;
							
							}else{
								
								$button_class = 'getbwp-button getbwp-btn-book-app';
								$button_label = __('Select','get-bookings-wp');
								$unavailable =false;
							
							}
							
							//is All Day event?						
							if($service->service_duration==86400){
								$time_from = '00:00';
								$time_to = '23:59';						
							}
							
							if($time_to>$staff_hourly->avail_to || $time_to<$staff_hourly->avail_from){
								$display_unavailable = 'no';
								$is_slot_available = false;
							}
							
							$html .= '<button class="new-appt '.$button_class.'" getbwp-data-date="'.date('Y-m-d', strtotime($date_from)).'" getbwp-data-timeslot="'.$time_from.'-'.$time_to.'" getbwp-data-service-staff="'.$b_category.'-'.$staff_id.'">'; //category-userid
							$html .= '<span class="button-timeslot"></span><span class="getbwp-button-text">'. $button_label.'</span></button>';
								
							$html .= '</span>';						
							$html .= '</li>';
							
							$slot_previous = $slot ;	
							$available_previous = $available_slot;	 	
						 
						 
						 } //end if display slot
						 
					 
					  }
					  
					  $html .='</ul>';			  			  
					  $html .= '</div>'; //end time slots divisor
				  
				  
				  } //end if working			  
				  
				 
				 //increase date
				 $date_from = date ("Y-m-d", strtotime("+1 day", strtotime($date_from))); 			 
				 
				 
			 }  //end while
			 
		}else{
			
			
			$html .='<p>'.__("This Provider doesn't offer this service.",'get-bookings-wp').'</p>';		
			
		
		}  //end if
		 		
		
		echo wp_kses($html, $getbookingwp->allowed_html) ;		
		die();		
	
	}
	
	public function get_days_to_display(){
		global  $getbookingwp;
		
		$days = $getbookingwp->get_option('getbwp_calendar_days_to_display');
		
		if($days==''){			
			$days = 7;				
		}		
		
		return $days;
		
	}
	
	public function getbwp_parse_customizer_texts($text, $service, $provider = NULL , $date_from = NULL){
		global  $getbookingwp;
		
		$time_format = $this->get_time_format();		
				
		$from_at = date($time_format, strtotime($date_from));
		$from_date = $getbookingwp->commmonmethods->formatDate($date_from);
		
		$text = str_replace("[GETBWP_SERVICE]", $service->service_title,  $text);
		$text = str_replace("[GETBWP_PROVIDER]", $provider->display_name,  $text);
		
		$text = str_replace("[GETBWP_AT]", $from_at,  $text);
		$text = str_replace("[GETBWP_DAY]", $from_date,  $text);
		
		return $text;
		
	
	}
	
	function convert_from_another_time($source, $source_timezone, $dest_timezone){
		
		$offset = $dest_timezone - $source_timezone;
		
		if($offset == 0)
			return $source;
			
		$target = new DateTime($source_format);
		
	   
	   $hours_adjust = "+". $offset ." hours";
	   
	   $target = date('Y-m-d H:i:s',strtotime($hours_adjust,strtotime($source)));

		
		return $target;
	}
	
	public function getbwp_book_step_2(){
		
		global  $getbookingwp;
		
		$business_hours = get_option('getbwp_business_hours');
		$time_format = $this->get_time_format();		
		$slot_length= $getbookingwp->get_option('getbwp_time_slot_length');
		$slot_length_minutes= $slot_length*60;	
		
		$display_only_from_hour=  $getbookingwp->get_option('display_only_from_hour');		
		$display_unavailable= $getbookingwp->get_option('display_unavailable_slots_on_front');
		$allow_bookings_outside_b_hours=  $getbookingwp->get_option('allow_bookings_outsite_business_hours');
		
		$response = array();
		$time_slots = array();		
		$b_category = sanitize_text_field($_POST['b_category']);		
		$b_staff = sanitize_text_field($_POST['b_staff']);		
		$template_id = sanitize_text_field($_POST['template_id']);
		$template = sanitize_text_field($_POST['template']);	

		$hidde_staff_photo = sanitize_text_field($_POST['hidde_staff_photo']);		
		$book_from_staff_profile = sanitize_text_field($_POST['book_from_staff_profile']);

		$booking_form_template = sanitize_text_field($_POST['template']);
		
		if(isset( $_POST['b_date'])){

			$b_date = sanitize_text_field($_POST['b_date']);	

		}else{

			$b_date = date("m/d/Y");
		}

		if(isset( $_POST['b_location'])){
			$b_location = sanitize_text_field($_POST['b_location']);

		}else{

			$b_location ='';
		}

		if(isset( $_POST['visitor_offset_time'])){
			$visitor_offset_time = sanitize_text_field($_POST['visitor_offset_time']);

		}else{
			$visitor_offset_time ='';
		}


		//convert into array
		$week_days_filter = array();

		if(isset($_POST['week_days']) && $_POST['week_days'] !=''){

			$week_days_filter=rtrim($_POST['week_days'],"-");
			$week_days_filter =explode("-", $week_days_filter);

		}
		
		$date_format = $this->get_date_format_conversion();	
		$date_f = DateTime::createFromFormat($date_format, $b_date);
						
		$html = '';
		
		//get days for this service		
		$date_from=  $date_f->format('Y-m-d');	
		$to_sum= $this->get_days_to_display();  
		$end_date=  date("Y-m-d", strtotime("$date_from + $to_sum day"));		
		
		//location and staff are empty
		if($b_location=='' && $b_staff ==''){
			//get random user		
			$staff_id = $this->get_prefered_staff($b_staff, $b_category);			
		
		//location set but staff member disabled
		}elseif($b_location!='' && $b_staff ==''){
			
			//get random user for this location		
			$staff_id = $this->get_random_staff_member_for_location($b_location,  $b_category);
						
		}else{			
					
			$staff_id = $b_staff;					
		}
	
		// Schedule.
        $items_schedule = $getbookingwp->userpanel->get_working_hours($staff_id);

		//staff member		
		$staff_member = $getbookingwp->userpanel->get_staff_member($staff_id);			
		
		$cdiv = 0 ;				
		$service = $this->get_one_service($b_category);

		if($b_date==''){		
			$html .='<p>'.__("Please select a date.",'get-bookings-wp').'</p>';				
			$response = array('response' => 'NOOK', 'content' => $html);		
			echo json_encode($response);
			die();		
		}
		
		if($_POST['b_category']==''){		
			$html .='<p>'.__("Please select a service.",'get-bookings-wp').'</p>';
			$response = array('response' => 'NOOK', 'content' => $html);		
			echo json_encode($response);			
			die();		
		}
		
		if($staff_id==''){		
			$html .='<p>'.__("Please select a provider.",'get-bookings-wp').'</p>';
			$response = array('response' => 'NOOK', 'content' => $html);		
			echo json_encode($response);			
			die();		
		}
		

		//parse content		
		$content_text = $getbookingwp->get_template_label("step2_texts",$template_id);		
		$content_text = $this->getbwp_parse_customizer_texts($content_text, $service, $staff_member);
		
		//minimized layout		
		$selected_layout ='';
		
		$class_day_divisor = '';
		$class_ul_divisor = '';
		$class_li_divisor = '';
		$class_h3 = '';
		$class_book_button = '';
		
		if($selected_layout==2){
			$bg_color = $getbookingwp->get_template_label("getbwp_cus_bg_color",$template_id);
			$class_day_divisor = ' getbwp-time-slots-divisor-reduced ';
			$class_ul_divisor = ' getbwp-time-slots-available-list-bupreduced ';
			$class_li_divisor = ' bupreduced ';
			$class_i_icon_bg = ' style=" color: '.$bg_color.' " ';
			$class_book_button = ' style=" display:none" ';
			$class_h3 = 'reduced';			
			
		}

		$html .= '<div class="getbwpdeta-nav-bar"> ';

			$html .= '<div class="getbwpdeta-searchoption-left"> ';

			if($book_from_staff_profile!='yes' && $booking_form_template !='appointment_drop_down'){


				if($template!='appointment_side_bar'){

					$html .= '<span class="input-group-append getwpnav-button-opt">
												<button class="btn btn-modern btn-block btn-modern-nuv mb-2 mb-2-nuv" cate-id="'.$b_category.'" id="getbwp-back-to-staff-serv"><span class="input-group-text input-group-text-getwpsearch">
													<i class="fa fa-arrow-left"></i>
												</span></button>
											</span>
											';
					}

				}

			$html .= '</div>';



		$html .= '</div>';   //end navbar

		if($book_from_staff_profile!='yes' && $hidde_staff_photo!='yes'){

			$html .= '<div class="getbwp-front-staff-pic"> ';	
			$html .= $getbookingwp->userpanel->get_user_pic( $staff_id, 80, 'getbwp-avatar', null, null, false);		
			$html .= '</div>'; 

		}
		//$html .= '<div class="getbwp-front-nav-titles"> ';	
		//	$html .= '<h3> '.__('Availability','get-bookings-wp').'</h3> ';//
		//$html .= '</div>';   //end navbar

	
		$wp_date_format = get_option( 'date_format' );
		
		//Does the user offer this service?				
		if($getbookingwp->userpanel->staff_offer_service( $staff_id, $b_category )){

			$date_select_box_css='';
			if($template=='appointment_side_bar'){
				$date_select_box_css='getbwp-datebox-status';

			}

			$html .= '<div class="getbwp-selected-staff-booking-info">'; 			
			$html .= $content_text;			
			$html .= '</div>';	

			
			//hide date picker depending on  template
			$html .= '<div class="nuv-deta-date-select '.$date_select_box_css.'"> ';
			$html .= __("I'm available on or after",'get-bookings-wp').'<br>';

			$html .= '<input type="text" value="'.$b_date.'" id="nuv-fron-date-picker" class="getbwp-datepicker nuv-date-field form-control">	';
			
			$html .= '<span  class="input-group-append nuv-nav-button-opt getbwp-filter-date-ico-trigger" >
							<span class="input-group-text">
							   <a id="getbwp-filter-date" class="getbwp-btn-next-step1" data-cate-id='.$b_category.' data-staff-id='.$b_staff.'><i class="fa fa-search"></i></a>
							</span>
						</span>
						';			
			$html .= '</div>'; 


			if($template=='appointment_side_bar'){
				$html .= '<div class=" getbwp-datebox-center-status"> ';
					$html .= '<div class="nuv-deta-date-select   ui-datepicker-wrapper "> ';
					$html .='  <div id="nuv-fron-date-picker-c" defaultDate="'.$b_date.'"  class="nuv-fron-date-picker-d"></div>';
					$html .= '</div>'; 
				$html .= '</div>'; 

			}

					
			
		    $available_previous = true;
			while (strtotime($date_from) < strtotime($end_date)) {

				 $cdiv++;				 
				 $day_num_of_week = date('N', strtotime($date_from));	

				 $filter_day = true;
				 if (!in_array($day_num_of_week, $week_days_filter) && $booking_form_template=='appointment_drop_down') {
					$filter_day = false;
				 }
				 
				  if(isset($items_schedule[$day_num_of_week]) && $filter_day ){

					//date format					  
					$current_day = $getbookingwp->commmonmethods->get_formated_date($date_from);
					 			  
					$html .= '<div class="getbwp-time-slots-divisor '.$class_day_divisor.'" id="getbwp-time-sl-div-'.$cdiv.'">';	
					$html .= '<h3 class="getbwp-current-day">'.$current_day.'</h3>';					    
					$html .= '<ul class="getbwp-time-slots-available-list '.$class_ul_divisor.'">';	
					  
					  //get available slots for this date				 
					  $time_slots = $this->get_time_slot_public_for_staff($day_num_of_week,  $staff_id, $b_category, $time_format);
					 
					 //check if staff member is in holiday this day					   
					  $is_in_holiday = $this->is_in_holiday($staff_id, $date_from);	
					  
					  //staff hourly						 
					  $staff_hourly = $this->get_hourly_for_staf($staff_id, $day_num_of_week);	
				 
					 $cdiv_range = 0 ;
					 
					 $at_least_one_available=false;
					 $flag_morning =true;
					 $flag_afternoon =true;
					 $flag_nigth =true;
					 
					 foreach($time_slots as $slot){

						 $cdiv_range++;						 
						 
						 $day_time_slot = date('Y-m-d', strtotime($date_from)).' '.$slot['from'].':00'; 
						 $current_time_slot = $slot['from'].':00';
						 $increased_minutes = date('H:i:s', strtotime( $current_time_slot ) +$slot_length_minutes);
						 
						 $to_slot_limit = $date_from.' '. $slot['to'].':00';						
						 $day_time_slot_to = $to_slot_limit;
						 
						 $staff_time_slots = array();					 
						 $staff_time_slots = $this->get_time_slots_availability_for_day($staff_id, $b_category, $day_time_slot, $day_time_slot_to);	
					  
					     //check if staff member is on break time for this day.						
					      $is_in_break = $this->is_in_break($staff_id, $day_num_of_week, $slot['from'] , $slot['to']);
					   
					     //check if staff member is working on special schedule.						
					     $is_in_special_schedule = $this->is_in_special_schedule($staff_id, $date_from, $slot['from'] , $slot['to']);
					   
					   	 if($staff_time_slots['available']==0 || $is_in_break || $staff_time_slots['busy']==true || $is_in_holiday || $is_in_special_schedule )
					     {							
							$available_slot =false;							
								
						  }else{												
								
							$available_slot =true;							
						  }
							
						$time_from = $slot['from'];
						$time_to = $slot['to'];
						$time_to_overlap = $slot['to_overlap'];
						
						$time_from_display = $slot['from_display'];
						$time_to_display = $slot['to_display'];
						
						//padding before?
						if($service->service_padding_before!='' && $service->service_padding_before!=0 ){
							//previous is not available, then we need to add padding
							//if(!$available_previous)
							//{
								$minutes_to_increate = $service->service_padding_before;									
								$increased_from = date('H:i:s', strtotime($time_from.':00')+$minutes_to_increate);
								$increased_from = date('H:i', strtotime($increased_from));							
								$time_from = $increased_from;
									
							//}
								
						}				
							 
						 
						 if($display_only_from_hour=='yes' || $display_only_from_hour=='' ) {
							  //reduced view
							 $time_to_display = ''.date($time_format, strtotime($time_from));
						 }else{
							 
							 $time_to_display = ''.date($time_format, strtotime($time_from)).' &ndash; '.date($time_format, strtotime($time_to)).'';					 			
						 
						 }
						 
						 
						 //check if hour is available to book, we have to use the server time	
						 $time_from_flag = $time_from.':00';	 
						 $current_slot_time_stamp = strtotime($date_from.' '.$time_from.':00');		 
						 $current_site_time_stamp = strtotime(date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ));

						 $datetime1 = new DateTime($date_from.' '.$time_from.':00');
						 $datetime_mana = new DateTime($date_from.' '.'12:00:00');
						 $datetime_tard = new DateTime($date_from.' '.'18:00:00');
						 $datetime_noit = new DateTime($date_from.' '.'12:00:00');

						$legend_hourly ='';
						
						if ($datetime1 < $datetime_mana && $flag_morning ) {
    						$legend_hourly_morning =__('Morning','get-bookings-wp');
							$html .= '<h5>'.$legend_hourly_morning.'</h5>';
							$flag_morning =false;
						}

						if ($datetime1 >= $datetime_mana && $datetime1 < $datetime_tard && $flag_afternoon) {
    						$legend_hourly_afternoon =__('Afternoon','get-bookings-wp');
							$html .= '<h5>'.$legend_hourly_afternoon.'</h5>';
							$flag_afternoon =false;
						}

						if ($datetime1 >= $datetime_tard  && $flag_nigth) {
    						$legend_hourly_nigth =__('Evening','get-bookings-wp');
							$html .= '<h5>'.$legend_hourly_nigth.'</h5>';
							$flag_nigth =false;
						}
						 
						 $is_passed = false;						 
						 if($current_site_time_stamp>$current_slot_time_stamp) {							 
							 $is_passed = true;	 						
						 }
						 
						 //min prior to booking?						 
						 $min_hours_prior_booking = $this->check_prior_to_booking($current_slot_time_stamp);
						 
						 //special scheduling?						 
						 $li_class = '';					 	
						
						if($staff_time_slots['available']==0 || $is_in_break || $is_passed || $staff_time_slots['busy']==true || $is_in_holiday || $is_in_special_schedule || !$min_hours_prior_booking)
						{
							$button_class = 'getbwp-button-blocked ';
							$button_label = __('Unavailable','get-bookings-wp');
							$li_avail_icon = '';							
							$class_disable_price_line = ' style=" text-decoration: line-through " ';							
							$is_slot_available = false;
							$li_class = 'getbwp-unavailable-slot';
						
						}else{
							
							$button_class = 'getbwp-button getbwp-btn-book-app';
							$button_label = __('Book Appointment','get-bookings-wp');
							$li_class = 'getbwp-btn-book-app-li';	
							
							//used in minified mode
							$li_avail_icon = 'fa fa-check-square-o';
							$class_disable_price_line = ' ';	
							$is_slot_available = true;				
						}
						
						//is All Day event?						
						if($service->service_duration==86400){
							$time_from = '00:00';
						    $time_to = '23:59';						
						}	
						
						
						$is_slot_outside_working_hours = false;
						if($this->is_booking_outside_working_hours($staff_hourly, $time_to, $date_from ) && $allow_bookings_outside_b_hours=='no' )
						{
							$is_slot_outside_working_hours = true;							
						}
						
						
						//do we have to hide the slot in the fron-end?						
						if($display_unavailable=='no' && !$is_slot_available){	

							$is_slot_visible = false;
							
						}else{
							
							$is_slot_visible = true;							
						}						
						
						
				       if($is_slot_visible && !$is_slot_outside_working_hours){

						   
						 $at_least_one_available=true;							 	 
						
						 $html .= '<li class="'.$li_class.' '.$class_li_divisor.'"   id="getbwp-time-slot-hour-range-'.$cdiv.'-'.$cdiv_range.'" getbwp-data-date="'.date('Y-m-d', strtotime($date_from)).'" getbwp-max-capacity="'.$staff_time_slots['capacity'].'" getbwp-max-available="'.$staff_time_slots['available'].'" getbwp-data-timeslot="'.$time_from .'-'.$time_to.'" getbwp-data-service-staff="'.$b_category.'-'.$staff_id.'" >';
						
						 if($selected_layout==2) //minified		
						 {							 
							$html .= ' <span class="getbwp-front-mini-icons">							
							<i class="'.$li_avail_icon.'" '.$class_i_icon_bg.'></i> </span>';							
						 }
						 
						 					
						 $html .= '<div class="getbwp-timeslot-time" '.$class_disable_price_line.'>'.$time_to_display.'</div>';
						 $html .= '<div class="getbwp-timeslot-count"><span class="spots-available">'.$staff_time_slots['label'].'</span></div>';
										
						 $html .= '</li>';	
						 
					 	} // end if						 
						 
						 $available_previous =$available_slot;					 
					 
					 }
					  
					  $html .='</ul>';			  			  
					  $html .= '</div>'; //end time slots divisor
					  
					   //is the whole day signed off			 
					   if(!$at_least_one_available) {

							 $html .='<p class="getbwp-unavailable-slot">'.__("There are no available time slots on this day.",'get-bookings-wp').'</p>';
							 
					   }
				  
				  
				  } //end if working			  
				  
				 
				 //increase date
				 $date_from = date ("Y-m-d", strtotime("+1 day", strtotime($date_from)));			 
				 
				 
			 }  //end while			 
			 
			
			 
		}else{
			
			
			$html .='<p>'.__("This Provider doesn't offer this service.",'get-bookings-wp').'</p>';
			
			
		
		}  //end if
		 		
		
		$response = array('response' => 'OK', 'content' => $html);
		echo json_encode($response) ;		
		die();		
	
	}

	function sort_categories_list($order){
		
		global $wpdb;

		$order = sanitize_text_field($_POST['order']);	
		$typeslist = explode(',',  $order);
		$counter = 1;		
	
		foreach ($typeslist as $cate_id) 
		{
			
			$sql = 'UPDATE ' . $wpdb->prefix . 'getbwp_categories  SET 
			cate_order = "'.$counter.'"	WHERE cate_id="'.(int)$cate_id.'" ';
			$wpdb->query($sql);

			$counter++;

		}	

		die();				
	}

	function get_first_service_list(){

		global $wpdb;

		$val = 0;

		$sql ="SELECT cate_id, cate_order FROM " . $wpdb->prefix . "getbwp_categories  ORDER BY cate_order LIMIT 1	;";	
		$rows = $wpdb->get_results($sql);
			
		foreach ( $rows as $row ){

			$val = $row->cate_id;
			
		}

		

		return $val;
	}
	
	//this will check if the user is within a special schedule	
	function is_booking_outside_working_hours($staff_hourly, $time_to, $date_from )
	{
		
		global  $wpdb, $getbookingwp, $getbwpcomplement;
		
		$is_outside_working_hours = false;
		
		
		if($time_to>date('H:i',strtotime($staff_hourly->avail_to)) || $time_to<date('H:i',strtotime($staff_hourly->avail_from)))
						{
					//$display_unavailable = 'no';
					$is_outside_working_hours = true;
					
					
				}else{
					
					
					
					$is_outside_working_hours = false;
					
				}
		
		return $is_outside_working_hours;  
		
	}
	
	//this will check if the user is within a special schedule	
	function is_in_special_schedule($staff_id, $day, $from_time, $to_time)
	{
		
		global  $wpdb, $getbookingwp, $getbwpcomplement;
		
		$from_time = $from_time.':00';
		$to_time = $to_time.':00';
		
		$ret = false;  
				
		if(isset($getbwpcomplement))
		{
				
			$sql ="SELECT * FROM " . $wpdb->prefix . "getbwp_staff_availability_rules  
			WHERE special_schedule_date = %s AND 
			special_schedule_staff_id = %d  AND  
			(special_schedule_time_to > %s  AND 
			special_schedule_time_from < %s  );";			
				
			$sql = $wpdb->prepare($sql,array($day, $staff_id, $from_time, $to_time));	
			$rows = $wpdb->get_results($sql);
			
			if ( !empty( $rows )) 
			{			
				$ret = true;	
						
			}else{
				
				$ret = false;
				
			}
		
		}
		
		
		return $ret;
		
		
	
	}
	
	public function check_prior_to_booking($current_slot_time_stamp)
	{
		global  $getbookingwp, $getbwpcomplement;
		
		
		if(isset($getbwpcomplement))
		{
			
						
			//min time in hours
			$min_hours_prior = $getbookingwp->get_option('getbwp_min_prior_booking');
			
			if($min_hours_prior>=24) //by days
			{
				$current_site_time_stamp = strtotime(date( 'Y-m-d', current_time( 'timestamp', 0 ) ));
				$current_slot_time_stamp = strtotime(date( 'Y-m-d', $current_slot_time_stamp ));	
							
			}else{ //by hours
			
				$current_site_time_stamp = strtotime(date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ));		
			}
			
			if($min_hours_prior!=0 && $min_hours_prior!=''  ) //we need to check prior hours
			{
				
				$diff =  $current_slot_time_stamp - $current_site_time_stamp;
				$diff_in_hrs = $diff/3600;
				
				if($diff_in_hrs<=$min_hours_prior)
				{					
					return false;	///prior time			
				
				}else{
					
					return true;				
				}
				
							
			}else{ /// do not check	
			
			
				return true;
				
			
			}	
			
		
		}else{
			
			return true;
			
		}
		
		
	}
	
	public function getbwp_book_step_2_hotels()	{
		
		global  $getbookingwp;
		
		$business_hours = get_option('getbwp_business_hours');
		$time_format = $this->get_time_format();
		
		$slot_length= $getbookingwp->get_option('getbwp_time_slot_length');
		$slot_length_minutes= $slot_length*60;	
		
		$display_only_from_hour=  $getbookingwp->get_option('display_only_from_hour');		
		$display_unavailable= $getbookingwp->get_option('display_unavailable_slots_on_front');
		$allow_bookings_outside_b_hours=  $getbookingwp->get_option('allow_bookings_outsite_business_hours');
		
		$response = array();
		
		$time_slots = array();		
		$b_category = sanitize_text_field($_POST['b_category']);
		$b_date = sanitize_text_field($_POST['b_date']);		
		$b_staff = sanitize_text_field($_POST['b_staff']);
		$b_location = sanitize_text_field($_POST['b_location']);
		$template_id = sanitize_text_field($_POST['template_id']);
		
		$date_format = $this->get_date_format_conversion();	
		$date_f = DateTime::createFromFormat($date_format, $b_date);
						
		$html = '';
		
		//get days for this service		
		$date_from=  $date_f->format('Y-m-d');	
		$to_sum= $this->get_days_to_display();  
		$end_date=  date("Y-m-d", strtotime("$date_from + $to_sum day"));			
		
		// Schedule.
        $items_schedule = $getbookingwp->userpanel->get_working_hours($staff_id);
		
		//staff member		
		$staff_member = $getbookingwp->userpanel->get_staff_member($staff_id);			
		
		$cdiv = 0 ;				
		$service = $this->get_one_service($b_category);
		
		if($_POST['b_date']==''){		
			$html .='<p>'.__("Please select a date.",'get-bookings-wp').'</p>';	
			
			$response = array('response' => 'NOOK', 'content' => $html);		
			echo json_encode($response);
			die();		
		}
		
				
		//parse content		
		$content_text = $getbookingwp->get_template_label("step2_texts",$template_id);		
		$content_text = $this->getbwp_parse_customizer_texts($content_text, $service, $staff_member);
		

		
		$class_day_divisor = '';
		$class_ul_divisor = '';
		$class_li_divisor = '';
		$class_h3 = '';
		$class_book_button = '';
				
		
		$wp_date_format = get_option( 'date_format' );
		
		//Does the user offer this service?				
		if($getbookingwp->userpanel->staff_offer_service( $staff_id, $b_category )){
			$html .= '<div class="getbwp-selected-staff-booking-info">';		
			$html .= $content_text;		
			$html .= '</div>';		
			
		    $available_previous = true;
			while (strtotime($date_from) < strtotime($end_date)){
				 $cdiv++;
				 
				 $day_num_of_week = date('N', strtotime($date_from));	
				 
				 //is the staff member working on this day?			 
				  if(isset($items_schedule[$day_num_of_week])){			 
					 			  
					  $html .= '<div class="getbwp-time-slots-divisor '.$class_day_divisor.'" id="getbwp-time-sl-div-'.$cdiv.'">';	
					  
					  if($selected_layout==2){
					  	  $html .= '<h3 class="'.$class_h3.'">'.date($wp_date_format, strtotime($date_from)).'</h3>';					  
					  }else{
						  $html .= '<h3>'.$getbookingwp->commmonmethods->formatDate($date_from).'</h3>';
					  }
					    
					  $html .= '<ul class="getbwp-time-slots-available-list '.$class_ul_divisor.'">';	
					  
					 //get available slots for this date				 
					 $time_slots = $this->get_time_slot_public_for_staff($day_num_of_week,  $staff_id, $b_category, $time_format);
					 
					 //check if staff member is in holiday this day					   
					  $is_in_holiday = $this->is_in_holiday($staff_id, $date_from);	
					  
					   //staff hourly						 
					  $staff_hourly = $this->get_hourly_for_staf($staff_id, $day_num_of_week);		   
					 
					 
					 $cdiv_range = 0 ;
					 
					 foreach($time_slots as $slot){
						 $cdiv_range++;						 
						 
						 $day_time_slot = date('Y-m-d', strtotime($date_from)).' '.$slot['from'].':00';						  
						 
						 $current_time_slot = $slot['from'].':00';
						 $increased_minutes = date('H:i:s', strtotime( $current_time_slot ) +$slot_length_minutes);
						 
						 $to_slot_limit = $date_from.' '. $slot['to'].':00';						
						 $day_time_slot_to = $to_slot_limit; 

						  	 
						 $staff_time_slots = array();					 
						 $staff_time_slots = $this->get_time_slots_availability_for_day($staff_id, $b_category, $day_time_slot, $day_time_slot_to);	
					  
					  //check if staff member is on break time for this day.						
					   $is_in_break = $this->is_in_break($staff_id, $day_num_of_week, $slot['from'] , $slot['to']);
					   
					   if($staff_time_slots['available']==0 || $is_in_break || $staff_time_slots['busy']==true || $is_in_holiday )
					   {							
							$available_slot =false;
							
								
						}else{												
								
							$available_slot =true;							
						}
							
						$time_from = $slot['from'];
						$time_to = $slot['to'];
						
						$time_from_display = $slot['from_display'];
						$time_to_display = $slot['to_display'];
						
						//padding before?
						if($service->service_padding_before!='' && $service->service_padding_before!=0 ){
							//previous is not available, then we need to add padding
							if(!$available_previous){
								$minutes_to_increate = $service->service_padding_before;								
								$increased_from = date('H:i:s', strtotime($time_from.':00')+$minutes_to_increate);
								$increased_from = date('H:i', strtotime($increased_from));							
								$time_from = $increased_from;									
							}								
						}					
							 
						 
						 if($display_only_from_hour=='yes' || $display_only_from_hour=='' ){
							  //reduced view
							 $time_to_display = ''.date($time_format, strtotime($time_from));
						 }else{
							 
							 $time_to_display = ''.date($time_format, strtotime($time_from)).' &ndash; '.date($time_format, strtotime($time_to)).'';					 			
						 
						 }
						 
						 
						 //check if hour is available to book, we have to use the server time			 
						 $current_slot_time_stamp = strtotime($date_from.' '.$time_from.':00');			 
						 $current_site_time_stamp = strtotime(date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ));
						 
						 $is_passed = false;						 
						 if($current_site_time_stamp>$current_slot_time_stamp){							 
							 $is_passed = true;	 						
						 }
						 
						 $li_class = '';					 	
						
						if($staff_time_slots['available']==0 || $is_in_break || $is_passed || $staff_time_slots['busy']==true || $is_in_holiday)
						{
							$button_class = 'getbwp-button-blocked ';
							$button_label = __('Unavailable','get-bookings-wp');
							$li_avail_icon = '';							
							$class_disable_price_line = ' style=" text-decoration: line-through " ';

							$li_class = 'getbwp-unavailable-slot';								
							$is_slot_available = false;
						
						}else{
							
							$button_class = 'getbwp-button getbwp-btn-book-app';
							$button_label = __('Book Appointment','get-bookings-wp');
							$li_class = 'getbwp-btn-book-app-li';	
							
							//used in minified mode
							$li_avail_icon = 'fa fa-check-square-o';
							$class_disable_price_line = ' ';	
							$is_slot_available = true;				
						}
						
						//is All Day event?						
						if($service->service_duration==86400){
							$time_from = '00:00';
						    $time_to = '23:59';						
						}
						
						

						//if($time_to>$staff_hourly->avail_to || $time_to<$staff_hourly->avail_from)
						////{
							//$display_unavailable = 'no';
							//$is_slot_available = false;
						//}
						
						$is_slot_outside_working_hours = false;
						if($this->is_booking_outside_working_hours($staff_hourly, $time_to, $date_from ) && $allow_bookings_outside_b_hours=='no')
						{
							$is_slot_outside_working_hours = true;
							
						}
						
						
						//do we have to hide the slot in the fron-end?						
						if($display_unavailable=='no' && !$is_slot_available){							
							$is_slot_visible = false;							
						}else{							
							$is_slot_visible = true;							
						}
						
				       if($is_slot_visible && !$is_slot_outside_working_hours){			
						 	 
						
						 $html .= '<li class="'.$li_class.' '.$class_li_divisor.'"   id="getbwp-time-slot-hour-range-'.$cdiv.'-'.$cdiv_range.'" getbwp-data-date="'.date('Y-m-d', strtotime($date_from)).'" getbwp-max-capacity="'.$staff_time_slots['capacity'].'" getbwp-max-available="'.$staff_time_slots['available'].'" getbwp-data-timeslot="'.$time_from .'-'.$time_to.'" getbwp-data-service-staff="'.$b_category.'-'.$staff_id.'" >';
						 
						 //
						 
						 if($selected_layout==2) //minified		
						 {							 
							$html .= ' <span class="getbwp-front-mini-icons">							
							<i class="'.$li_avail_icon.'" '.$class_i_icon_bg.'></i> </span>';							
						 }
						 
						 					
						 $html .= '<div class="getbwp-timeslot-time" '.$class_disable_price_line.'>'.$time_to_display.'</div>';
						 $html .= '<div class="getbwp-timeslot-count"><span class="spots-available">'.$staff_time_slots['label'].'</span></div>';
						 
						 $html .= '<span class="getbwp-timeslot-people" '.$class_book_button.'>';	
						 
						
						 $html .= '<button class="new-appt '.$button_class.'" getbwp-data-date="'.date('Y-m-d', strtotime($date_from)).'" getbwp-max-capacity="'.$staff_time_slots['capacity'].'" getbwp-max-available="'.$staff_time_slots['available'].'" getbwp-data-timeslot="'.$time_from .'-'.$time_to.'" getbwp-data-service-staff="'.$b_category.'-'.$staff_id.'">'; //category-userid
						
						$html .= '<span class="button-timeslot"></span><span class="getbwp-button-text">'. $button_label.'</span></button>';
						
						 $html .= '</span>';						
						 $html .= '</li>';	
						 
					 } // end if
						 
						 
						 $available_previous =$available_slot;
						 
						 
					 
					  }
					  
					  $html .='</ul>';			  			  
					  $html .= '</div>'; //end time slots divisor
				  
				  
				  } //end if working			  
				  
				 
				 //increase date
				 $date_from = date ("Y-m-d", strtotime("+1 day", strtotime($date_from))); 			 
				 
				 
			 }  //end while
			 
		}else{
			
			
			$html .='<p>'.__("This Provider doesn't offer this service.",'get-bookings-wp').'</p>';
			
			
		
		}  //end if

		
		$html = wp_kses($html, $getbookingwp->allowed_html);	
		
		$response = array('response' => 'OK', 'content' => $html);
		echo json_encode($response) ;		
		die();		
	
	}
	
	
	function delete_category()
	{
		
		global  $wpdb, $getbookingwp;
		
		$category = sanitize_text_field($_POST['cate_id']);
						
		$sql ="DELETE FROM " . $wpdb->prefix . "getbwp_categories WHERE cate_id=%d ;";			
		$sql = $wpdb->prepare($sql,array($category));	
		$rows = $wpdb->get_results($sql);
		die();
	
	}
	
	function delete_service(){
		
		global  $wpdb, $getbookingwp;
		
		$service = sanitize_text_field($_POST['service_id']);						
		$sql ="DELETE FROM " . $wpdb->prefix . "getbwp_services WHERE service_id=%d ;";			
		$sql = $wpdb->prepare($sql,array($service));	
		$rows = $wpdb->get_results($sql);
		die();
	
	}
	
	
	//this will check if the user is in holiday 	
	function is_in_holiday($staff_id, $date){
		
		global  $wpdb, $getbookingwp, $getbwpcomplement;
		
		
		if(isset($getbwpcomplement))
		{
			return $getbwpcomplement->dayoff->is_in_holiday($staff_id, $date);
							
		}else{
			
			return false;		
				
		}		
	
	}
	
	//this will check if the user is in break time 	
	function is_in_break($staff_id, $day, $from_time, $to_time)
	{
		
		global  $wpdb, $getbookingwp;
		
		$from_time = $from_time.':00';
		$to_time = $to_time.':00';
		
		$ret = false;
				
		$sql ="SELECT * FROM " . $wpdb->prefix . "getbwp_staff_availability_breaks  
		WHERE break_staff_day=%d AND break_staff_id = %d  AND  (break_time_to > '".$from_time."'  AND 	break_time_from < '".$to_time."'  );";
		
			
		$sql = $wpdb->prepare($sql,array($day, $staff_id));	
		$rows = $wpdb->get_results($sql);
		
		if ( !empty( $rows ))
		{			
			$ret = true;			
		}
		
		
		return $ret;
		
		
	
	}
	
	function get_total_bookings($staff_id, $service_id, $day, $day_to){
		
		global  $wpdb, $getbookingwp;
		
		
		$res = array();	
		
		$total_groups =0;
		$total_individual =0;
		
		//get total on quantity row	for this service only	
		$sql ="SELECT SUM(booking_qty) as total FROM " . $wpdb->prefix . "getbwp_bookings  
		WHERE booking_staff_id = %d  AND  booking_service_id = %d AND booking_status <> '2' AND (booking_time_to > '".$day."'  AND 	booking_time_from < '".$day_to."'  );";	

		$sql = $wpdb->prepare($sql,array($staff_id,$service_id));	
		$rows = $wpdb->get_results($sql);
		$booked = $wpdb->num_rows;

		if ( !empty( $rows )) {
			foreach ( $rows as $row ){
				$total_groups = $row->total;		
			}
		}
		
		
		
		//get total bookins individually
		$sql ="SELECT count(*) as total FROM " . $wpdb->prefix . "getbwp_bookings  
		WHERE booking_staff_id = %d  AND  booking_service_id = %d AND booking_status <> '2' AND (booking_time_to > '".$day."'  AND 	booking_time_from < '".$day_to."'  );";		
			
		$sql = $wpdb->prepare($sql,array($staff_id,$service_id));	
		$rows = $wpdb->get_results($sql);
	
		if ( !empty( $rows )) {
			foreach ( $rows as $row ){
				$total_individual = $row->total;		
			}
		}
		
		if($total_individual !=0){
			
		}
		
		$res = array('total_groups'=>$total_groups, 
				     'total_individual'=>$total_individual, 
					);	
		
		
		
		return $res;
	}
	
	function is_staff_available($staff_id, $service_id, $day, $day_to){
		global  $wpdb, $getbookingwp;
		
		//Is the staff member busy?
		$sql ="SELECT * FROM " . $wpdb->prefix . "getbwp_bookings  
		WHERE booking_staff_id = %d  AND  booking_service_id <> %d AND booking_status <> '2' AND  (booking_time_to > '".$day."'  AND 	booking_time_from < '".$day_to."'  );";	
				
		$sql = $wpdb->prepare($sql,array($staff_id, $service_id));	
		$rows = $wpdb->get_results($sql);			
		$booked = $wpdb->num_rows;	
		
		if ( !empty( $rows )) // the staff member is busy in this time.
		{			
			$busy = true;		
				
		}else{
			
			$busy = false;		
		}
		
		if($busy){	
			}
		
		return $busy;	
		
	}
	
	//this will give me availability for this service 	
	function get_time_slots_availability_for_day($staff_id, $service_id, $day, $day_to){
		
		global  $wpdb, $getbookingwp;
				
		$res = array();	
		$booking_totals = array();
		
		//we need to add a setting so pending orders are not being calculated.		
		
		$booking_totals = $this->get_total_bookings($staff_id, $service_id, $day, $day_to);	
		
		//Is staff offering a different service at the same time?	
	    $busy = $this->is_staff_available($staff_id, $service_id, $day, $day_to);	
		
		//Is this a group booking services?
		$service = $this->get_one_service($service_id);
		
		if($service->service_allow_multiple==1){			
			$booked = $booking_totals['total_groups'];			
		}else{ //one at once booking		
			$booked = $booking_totals['total_individual'];			
		}
		
		$staff_service_details = $getbookingwp->userpanel->get_staff_service_rate( $staff_id, $service_id );			
		$appointment_capacity = $staff_service_details['capacity'];	
        
		$available_slots = $appointment_capacity - $booked;
		
		if($available_slots<0 ){$available_slots = 0;}		
		
		//label
		$s = '';		
		if($available_slots>1 || $available_slots==0 ){$s = 's';}
		
		
		$label = sprintf(__('%s available ','get-bookings-wp'),$available_slots,$s);
			
		$res = array('price'=>$staff_service_details['price'], 
				             'capacity'=>$staff_service_details['capacity'] , 
							 'booked'=>$booked,
							 'label'=>$label,
							 'available'=>$available_slots,
							 'busy'=>$busy
							 );	
		return $res;
	
	}
	
	function get_time_format()	{
		global  $getbookingwp;	
		$data = $getbookingwp->get_option('getbwp_time_format');		
		if($data==''){
			$data = 'h:i A';		
		}		
		return $data;
	}	
	
	//returns an array with time slots for this user
	public function get_time_slot_public_for_staff($day,  $staff_id, $service_id, $time_format){
		global  $getbookingwp;
		
		$time_slots = array();		
		$hours = 24; //amount of hours working in day		
		$selected_value = '';
		
		//get duration of this category		
		$service = $this->get_one_service($service_id);			
		
		
		if($service->service_duration==''){
			$min_minutes = $getbookingwp->get_option('getbwp_time_slot_length');
			$service_minutes = 1800; //30 minutes
		
		}else{			
			
			$min_minutes = $getbookingwp->get_option('getbwp_time_slot_length');			
			$service_minutes = $service->service_duration;	
		
		}
		
		//check if consecutives
				
		if($min_minutes ==''){$min_minutes=15;}	
				
		$hours = (60/$min_minutes) *$hours;		
		$min_minutes=$min_minutes*60; //seconds
		
		
		
					
		//check selected value
		$selected_value_from = $this->get_business_hour_option($day, 'from', $staff_id);		
		$selected_value_to= $this->get_business_hour_option($day, 'to', $staff_id);
		
		for($i = 0; $i <= $hours ; $i++)
		{ 		
			$minutes_to_add_display = $min_minutes * $i; // add 30 - 60 - 90 etc.
			$minutes_to_add = $min_minutes  ; // add 30 - 60 - 90 etc.		
			
				
			$timeslot = date('H:i:s', strtotime(0)+$minutes_to_add_display );						
			$timeslot_display = date('H:i:s', strtotime(0)+$minutes_to_add_display);
			
			$endTime = date('H:i:s', strtotime($timeslot)+$service_minutes);			
			$endTime_display = date('H:i:s', strtotime($timeslot_display)+$minutes_to_add);
						
			$time_slot_hours_mins = date('H:i', strtotime($timeslot_display));	
			
			//improvement	
			$endTime_overlapped = date('H:i:s', strtotime($timeslot)+$service_minutes*2);	
			
			
			if($time_slot_hours_mins >= $selected_value_from && $time_slot_hours_mins < $selected_value_to)
			{
				$from_value	=date('H:i', strtotime($timeslot));	
				$to_value	=date('H:i', strtotime($endTime ));
				$to_value_overlap	=date('H:i', strtotime($endTime_overlapped ));	
								
				//to display
				$from_value_display	=date('H:i', strtotime($timeslot_display));	
				$to_value_display	=date('H:i', strtotime($endTime_display ));	
												
				$time_slots[] = array('from' =>  $from_value, 'to' => $to_value, 'to_overlap' => $to_value_overlap, 
				'from_display' =>  $from_value_display, 
				'to_display' => $to_value_display);
			
			}
			
			
		}	
		
		
		return $time_slots;
	
	}
	
	function get_availability_for_user($b_staff, $date_from, $b_category){
		
		global $wpdb, $getbookingwp;
		
		
	
	}
	
	function get_prefered_staff($staff_id = null, $service_id){
		global $wpdb, $getbookingwp;
		
		if($staff_id=='')
		{
			//get random staff providing this service			
			$staff_members = array();			
			$staff_members = $this->get_staff_offering_service($service_id);			
			$staff_id = $staff_members[array_rand($staff_members)];	
		
		}
		
		return $staff_id;
	
	}
	
	
	
	public function update_staff_business_hours()
	{
		global $wpdb, $getbookingwp;
		
		$staff_id = sanitize_text_field($_POST['staff_id']);		
		
		$getbwp_mon_from = sanitize_text_field($_POST['getbwp_mon_from']);
		$getbwp_mon_to = sanitize_text_field($_POST['getbwp_mon_to']);		
		$getbwp_tue_from = sanitize_text_field($_POST['getbwp_tue_from']);
		$getbwp_tue_to = sanitize_text_field($_POST['getbwp_tue_to']);		
		$getbwp_wed_from = sanitize_text_field($_POST['getbwp_wed_from']);
		$getbwp_wed_to = sanitize_text_field($_POST['getbwp_wed_to']);		
		$getbwp_thu_from = sanitize_text_field($_POST['getbwp_thu_from']);
		$getbwp_thu_to = sanitize_text_field($_POST['getbwp_thu_to']);
		$getbwp_fri_from = sanitize_text_field($_POST['getbwp_fri_from']);
		$getbwp_fri_to = sanitize_text_field($_POST['getbwp_fri_to']);		
		$getbwp_sat_from = sanitize_text_field($_POST['getbwp_sat_from']);
		$getbwp_sat_to = sanitize_text_field($_POST['getbwp_sat_to']);		
		$getbwp_sun_from = sanitize_text_field($_POST['getbwp_sun_from']);
		$getbwp_sun_to = sanitize_text_field($_POST['getbwp_sun_to']);
		
		$business_hours = array();
		
		if($getbwp_mon_from!=''){$business_hours[1] = array('from' =>$getbwp_mon_from, 'to' =>$getbwp_mon_to);}
		if($getbwp_tue_from!=''){$business_hours[2] = array('from' =>$getbwp_tue_from, 'to' =>$getbwp_tue_to);}
		if($getbwp_wed_from!=''){$business_hours[3] = array('from' =>$getbwp_wed_from, 'to' =>$getbwp_wed_to);}
		if($getbwp_thu_from!=''){$business_hours[4] = array('from' =>$getbwp_thu_from, 'to' =>$getbwp_thu_to);}
		if($getbwp_fri_from!=''){$business_hours[5] = array('from' =>$getbwp_fri_from, 'to' =>$getbwp_fri_to);}
		if($getbwp_sat_from!=''){$business_hours[6] = array('from' =>$getbwp_sat_from, 'to' =>$getbwp_sat_to);}
		if($getbwp_sun_from!=''){$business_hours[7] = array('from' =>$getbwp_sun_from, 'to' =>$getbwp_sun_to);}
		
		
		if($staff_id!='')
		{
			//clean 			
			$sql = 'DELETE FROM ' . $wpdb->prefix . 'getbwp_staff_availability  WHERE avail_staff_id="'.(int)$staff_id.'" ';			$wpdb->query($sql);		
			
			
			if($getbwp_mon_from!='')
			{
				
				$new_record = array('avail_id' => NULL,	'avail_staff_id' => $staff_id,
								'avail_day' => '1','avail_from' => $getbwp_mon_from,'avail_to'   => $getbwp_mon_to);
				$wpdb->insert( $wpdb->prefix . 'getbwp_staff_availability', $new_record, array( '%d', '%s', '%s', '%s', '%s'));			
			}
			
			
			if($getbwp_tue_from!='')
			{		
			
				//2			
				$new_record = array('avail_id' => NULL,	'avail_staff_id' => $staff_id,
									'avail_day' => '2','avail_from' => $getbwp_tue_from,'avail_to'   => $getbwp_tue_to);
						
				$wpdb->insert( $wpdb->prefix . 'getbwp_staff_availability', $new_record, array( '%d', '%s', '%s', '%s', '%s'));			
			}
			
			if($getbwp_wed_from!='')
			{			
				//3			
				$new_record = array('avail_id' => NULL,	'avail_staff_id' => $staff_id,
									'avail_day' => '3','avail_from' => $getbwp_wed_from,'avail_to'   => $getbwp_wed_to);
						
				$wpdb->insert( $wpdb->prefix . 'getbwp_staff_availability', $new_record, array( '%d', '%s', '%s', '%s', '%s'));
			
			}
			
			if($getbwp_thu_from!='')
			{
			
				//4			
				$new_record = array('avail_id' => NULL,	'avail_staff_id' => $staff_id,
									'avail_day' => '4','avail_from' => $getbwp_thu_from,'avail_to'   => $getbwp_thu_to);
						
				$wpdb->insert( $wpdb->prefix . 'getbwp_staff_availability', $new_record, array( '%d', '%s', '%s', '%s', '%s'));			
			}
			
			if($getbwp_fri_from!='')
			{
		
				//5			
				$new_record = array('avail_id' => NULL,	'avail_staff_id' => $staff_id,
									'avail_day' => '5','avail_from' => $getbwp_fri_from,'avail_to'   => $getbwp_fri_to);
						
				$wpdb->insert( $wpdb->prefix . 'getbwp_staff_availability', $new_record, array( '%d', '%s', '%s', '%s', '%s'));
			
			}
			
			if($getbwp_sat_from!='')
			{
			
				//6		
				$new_record = array('avail_id' => NULL,	'avail_staff_id' => $staff_id,
									'avail_day' => '6','avail_from' => $getbwp_sat_from,'avail_to'   => $getbwp_sat_to);
						
				$wpdb->insert( $wpdb->prefix . 'getbwp_staff_availability', $new_record, array( '%d', '%s', '%s', '%s', '%s'));
			
			}
			
			
			if($getbwp_sun_from!='')
			{			
			
				//7		
				$new_record = array('avail_id' => NULL,	'avail_staff_id' => $staff_id,
									'avail_day' => '7','avail_from' => $getbwp_sun_from,'avail_to'   => $getbwp_sun_to);
						
				$wpdb->insert( $wpdb->prefix . 'getbwp_staff_availability', $new_record, array( '%d', '%s', '%s', '%s', '%s'));
			}
			
			
		}
		
		
	
		//print_r($business_hours);			
		
		die();
	
	
	}
	
	
	public function getbwp_update_global_business_hours(){
		global $wpdb, $getbookingwp;
		
		$getbwp_mon_from = sanitize_text_field($_POST['getbwp_mon_from']);
		$getbwp_mon_to = sanitize_text_field($_POST['getbwp_mon_to']);		
		$getbwp_tue_from = sanitize_text_field($_POST['getbwp_tue_from']);
		$getbwp_tue_to = sanitize_text_field($_POST['getbwp_tue_to']);		
		$getbwp_wed_from = sanitize_text_field($_POST['getbwp_wed_from']);
		$getbwp_wed_to = sanitize_text_field($_POST['getbwp_wed_to']);		
		$getbwp_thu_from = sanitize_text_field($_POST['getbwp_thu_from']);
		$getbwp_thu_to = sanitize_text_field($_POST['getbwp_thu_to']);
		$getbwp_fri_from = sanitize_text_field($_POST['getbwp_fri_from']);
		$getbwp_fri_to = sanitize_text_field($_POST['getbwp_fri_to']);		
		$getbwp_sat_from = sanitize_text_field($_POST['getbwp_sat_from']);
		$getbwp_sat_to = sanitize_text_field($_POST['getbwp_sat_to']);		
		$getbwp_sun_from = sanitize_text_field($_POST['getbwp_sun_from']);
		$getbwp_sun_to = sanitize_text_field($_POST['getbwp_sun_to']);
		
		$business_hours = array();
		
		if($getbwp_mon_from!=''){$business_hours[1] = array('from' =>$getbwp_mon_from, 'to' =>$getbwp_mon_to);}
		if($getbwp_tue_from!=''){$business_hours[2] = array('from' =>$getbwp_tue_from, 'to' =>$getbwp_tue_to);}
		if($getbwp_wed_from!=''){$business_hours[3] = array('from' =>$getbwp_wed_from, 'to' =>$getbwp_wed_to);}
		if($getbwp_thu_from!=''){$business_hours[4] = array('from' =>$getbwp_thu_from, 'to' =>$getbwp_thu_to);}
		if($getbwp_fri_from!=''){$business_hours[5] = array('from' =>$getbwp_fri_from, 'to' =>$getbwp_fri_to);}
		if($getbwp_sat_from!=''){$business_hours[6] = array('from' =>$getbwp_sat_from, 'to' =>$getbwp_sat_to);}
		if($getbwp_sun_from!=''){$business_hours[7] = array('from' =>$getbwp_sun_from, 'to' =>$getbwp_sun_to);}
		
		update_option('getbwp_business_hours', $business_hours);		
		die();		
	}
	
	
	
	public function getbwp_update_service(){
		global $wpdb, $getbookingwp;
		
		$service_id = sanitize_text_field($_POST['service_id']);
		$service_title = sanitize_text_field($_POST['service_title']);
		$service_desc = sanitize_text_field($_POST['service_desc']);
		$service_duration = sanitize_text_field($_POST['service_duration']);
		$service_price = sanitize_text_field($_POST['service_price']);
		$service_price_2 = sanitize_text_field($_POST['service_price_2']);		
		$service_capacity = sanitize_text_field($_POST['service_capacity']);
		$service_category = sanitize_text_field($_POST['service_category']);
		$service_color = sanitize_text_field($_POST['service_color']);
		$service_font_color = sanitize_text_field($_POST['service_font_color']);		
		$service_padding_before = sanitize_text_field($_POST['service_padding_before']);
		$service_padding_after = sanitize_text_field($_POST['service_padding_after']);		
		$service_groups = sanitize_text_field($_POST['service_groups']);		
		$service_calculation = sanitize_text_field($_POST['service_calculation']);
		$service_meeting_zoom = sanitize_text_field($_POST['service_meeting_zoom']);
		
		if($service_calculation==''){$service_calculation=1;}			
		if($service_groups==''){$service_groups=0;}		
		if($service_padding_before==''){$service_padding_before=0;}
		if($service_padding_after==''){$service_padding_after=0;}
	
		if($service_id!='')	{			
			$sql = 'UPDATE ' . $wpdb->prefix . 'getbwp_services  SET service_title = "'.$service_title.'",	
			service_desc = "'.$service_desc.'",		
			service_duration = "'.$service_duration.'",
			service_price = "'.$service_price.'",
			service_price_2 = "'.$service_price_2.'", 
			service_allow_multiple = "'.$service_groups.'", 
			service_pricing_calculation_type = "'.$service_calculation.'", 
			service_capacity = "'.$service_capacity.'", 
			service_category_id = "'.$service_category.'", 
			service_color = "'.$service_color.'",
			service_font_color = "'.$service_font_color.'",
			service_padding_before = "'.$service_padding_before.'",
			service_padding_before = "'.$service_padding_before.'",
			service_meeting_zoom = "'.$service_meeting_zoom.'"			
			WHERE service_id="'.(int)$service_id.'" ';
			$wpdb->query($sql);
		
		
		}else{ //this is a new service
			
			
			$new_record = array('service_id' => NULL,	
								'service_title' => $service_title,
								'service_desc' => $service_desc,
								'service_duration' => $service_duration,
								'service_price' => $service_price,
								'service_price_2' => $service_price_2,
								'service_capacity'   => $service_capacity,
								'service_category_id'   => $service_category,
								'service_color'   => $service_color,
								'service_font_color'   => $service_font_color,
								'service_padding_before'   => $service_padding_before,
								'service_padding_after'   => $service_padding_after,
								'service_allow_multiple'   => $service_groups,
								'service_meeting_zoom'   => $service_meeting_zoom,
								'service_pricing_calculation_type'   => $service_calculation);								
									
			$wpdb->insert( $wpdb->prefix . 'getbwp_services', $new_record, array( '%d', '%s',  '%s',
								'%s', 
								'%s', 
								'%s', 
								'%s', 
								'%d', '%s', '%s', '%s', '%s' , '%s' , '%s' , '%s'
		));
			
		}
		
		
		
		
		die();
	
	
	}
	
	public function getbwp_get_service(){
		global $wpdb, $getbookingwp, $getbwpcomplement;
		
		$service_id = '';
		$category_id = '';
		
		if(isset($_POST['service_id'])){			
			$service_id = sanitize_text_field($_POST['service_id']);	
		}
		
		if(isset($_POST['category_id'])){			
			$category_id = sanitize_text_field($_POST['category_id']);
		}
		
		if($service_id!='') {		
			$service = $this->get_one_service($service_id);			
			$mess = __('Here you can update the information of this service. Once you have modified the information click on the save button.','get-bookings-wp');
		
		}else{
			
			$mess = __('Here you can create a new service. Once you have filled in the form click on the save button.','get-bookings-wp');
			$service = new stdClass(); 
			$service->service_title = '';
			$service->service_desc = '';
			$service->service_color = '';
			$service->service_font_color = '';
			$service->service_padding_before= '';
			$service->service_padding_after= '';
			$service->service_price = '';
			$service->service_capacity = '';
			$service->service_category_id = '';
			$service->service_allow_multiple = '';
			$service->service_private = '';
			$service->service_pricing_calculation_type = '';
			$service->service_duration = '';
			$service->service_id = '';		

			
		}
		
		$html = '';
		
		$html .= '<div class="getbwp-sect-adm-edit">';
		
		$html .= '<p>'.$mess.'</p>';
		
			$html .= '<div class="getbwp-edit-service-block">';						
			$html .= '<div class="getbwp-field-separator"><label for="getbwp-box-title">'.__('Title','get-bookings-wp').':</label><input type="text" name="getbwp-title" id="getbwp-title" class="getbwp-common-textfields" value="'.$service->service_title.'" /></div>';
			$html .= '<div class="getbwp-field-separator"><label for="getbwp-box-title">'.__('Description','get-bookings-wp').':</label><textarea name="getbwp-desc" id="getbwp-desc" class="getbwp-common-textfields" style="width: 50%;" /> '.$service->service_desc.'</textarea></div>';
			$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Background Color','get-bookings-wp').':</label><input name="getbwp-service-color" type="text" id="getbwp-service-color" value="'.$service->service_color.'" class="color-picker" data-default-color=""/></div>';
				
			$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Font Color','get-bookings-wp').':</label><input name="getbwp-service-font-color" type="text" id="getbwp-service-font-color" value="'.$service->service_font_color.'" class="color-picker" data-default-color=""/></div>';
			
								
			$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Duration','get-bookings-wp').':</label>'.$this->get_duration_drop_down($service->service_duration).'</div>';
			
			//padding
			
			//if(isset($getbwpcomplement))
			//{
				
				$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Padding time (before or after)','get-bookings-wp').':</label>'.$this->get_padding_add_frm($service_id , $service->service_padding_before, $service->service_padding_after).'</div>';
			
			//}		
			
			
			$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Price','get-bookings-wp').':</label><input type="text" name="getbwp-price" id="getbwp-price" class="getbwp-common-textfields" value="'.$service->service_price.'" /></div>';
			
						
			
				
			$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Capacity','get-bookings-wp').':</label><input type="number"   min="1" name="getbwp-capacity" id="getbwp-capacity" class="getbwp-common-textfields" value="'.$service->service_capacity.'"/></div>';
				
			$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Category','get-bookings-wp').':</label>'.$this->get_categories_drop_down($service->service_category_id).'</div>';
				
			$html .= '<input type="hidden" name="getbwp-service-id" id="getbwp-service-id" value="'.$service->service_id.'" />';				
			
			
		$html .= '</div>';
		
		
		if(isset($getbwpcomplement)){
			$sel_group_yes ='';
			$sel_group_no =''; 

			$sel_zoom_yes ='';
			$sel_zoom_no =''; 
				
			if($service->service_allow_multiple==1){					
				$sel_group_yes ='selected="selected"';					
			}
				
			if($service->service_allow_multiple==0 || $service->service_allow_multiple=='')	{					
			   $sel_group_no ='selected="selected"';					
			}

			if($service->service_meeting_zoom==1){					
				$sel_zoom_yes ='selected="selected"';					
			}
				
			if($service->service_meeting_zoom==0 || $service->service_meeting_zoom=='')	{					
			   $sel_zoom_no ='selected="selected"';					
			}
			
				//private
				
			$isprivate_yes ='';
			$isprivate_no =''; 
				
			if($service->service_private==1){					
				$isprivate_yes ='selected="selected"';					
			}
				
			if($service->service_private==0 || $service->service_private==''){					
				$isprivate_no ='selected="selected"';					
			}
				
				
			
			$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Allow Group Bookings?','get-bookings-wp').':</label><select name="getbwp-groups"  id="getbwp-groups">
				<option value="0" '.$sel_group_no.'>'.__('NO','get-bookings-wp').'</option>
				<option value="1" '.$sel_group_yes.'>'.__('YES','get-bookings-wp').'</option>
		</select>
					
			</div>';

			$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Zoom Meeting?','get-bookings-wp').':</label><select name="getbwp-zoom"  id="getbwp-zoom">
				<option value="0" '.$sel_zoom_no.'>'.__('NO','get-bookings-wp').'</option>
				<option value="1" '.$sel_zoom_yes.'>'.__('YES','get-bookings-wp').'</option>
		</select>
					
			</div>';
			
			
				$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Is Private?','get-bookings-wp').':</label><select name="getbwp-isprivate"  id="getbwp-isprivate">


		  <option value="0" '.$isprivate_no.'>'.__('NO','get-bookings-wp').'</option>
		  <option value="1" '.$isprivate_yes.'>'.__('YES','get-bookings-wp').'</option>

		</select>
					
			</div>';
			
			//calculation method
			$calculation_method_1 ='';	
			$calculation_method_2 ='';
			$calculation_method_3 ='';
			
			if($service->service_pricing_calculation_type==1 || $service->service_pricing_calculation_type=='')	{					
					$calculation_method_1 ='selected="selected"';					
			}
			if($service->service_pricing_calculation_type==2 ){					
					$calculation_method_2 ='selected="selected"';					
			}
			
			if($service->service_pricing_calculation_type==3 ){					
					$calculation_method_3 ='selected="selected"';					
			}
			
			$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Calculation Way','get-bookings-wp').':</label><select name="getbwp-groups-calculation"  id="getbwp-groups-calculation">
		  <option value="1" '.$calculation_method_1.'>'.__('Common Method (Quantity X Price)','get-bookings-wp').'</option>
		  <option value="2" '.$calculation_method_2.'>'.__("Sum All Prices",'get-bookings-wp').'</option>
		  <option value="3" '.$calculation_method_3.'>'.__("Total Bassed on Quantity",'get-bookings-wp').'</option>
		</select>
					
			</div>';
			
			
			
			}else{
				
				$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Allow Group Bookings?','get-bookings-wp').':</label><span><i class="fa fa-info-circle "></i></span>'.__(' Available on Premium Versions','get-bookings-wp').'</div>';
				$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Zoom Integration?','get-bookings-wp').':</label><span><i class="fa fa-info-circle "></i></span>'.__(' Available on Premium Versions','get-bookings-wp').'</div>';
				$html .= '<div class="getbwp-field-separator"><label for="textfield">'.__('Private Services?','get-bookings-wp').':</label><span><i class="fa fa-info-circle "></i></span>'.__(' Available on Premium Versions','get-bookings-wp').'</div>';
			
			}	
		
		
		$html .= '</div>';
		
		
			
		echo wp_kses($html, $getbookingwp->allowed_html) ;		
		die();		
	
	}
	
	public function add_category_confirm(){		
		global $wpdb, $getbookingwp;
		
		$html='';
		$category_id = sanitize_text_field($_POST['category_id']);
		$category_name = sanitize_text_field($_POST['category_title']);
		
		if($category_id==''){
			$new_record = array('cate_id' => NULL,	
								'cate_name' => $category_name);								
			$wpdb->insert( $wpdb->prefix . 'getbwp_categories', $new_record, array( '%d', '%s'));				
			$html ='OK INSERT';
		
	    }else{
			
			$sql = $wpdb->prepare('UPDATE  ' . $wpdb->prefix . 'getbwp_categories SET cate_name =%s  WHERE cate_id = %d ;',array($category_name,$category_id));
			$results = $wpdb->query($sql);
			$html ='OK';			
		}
		
		echo  wp_kses($html, $getbookingwp->allowed_html);
		die();
	}
	
	public function get_category_add_form()
	{	
		
		global  $getbookingwp;
		
		$html = '';		
		
		$category_id = '';
		
		if(isset($_POST['category_id']))
		{
			$category_id = sanitize_text_field($_POST['category_id']);
			
		}
		
		$category_name = '';		
				
		if($category_id!='')		
		{
			//get payments			
			$category = $this->get_one_category( $category_id);
			$category_name =	$category->cate_name;
		}		
		
		$html .= '<p>'.__('Name:','get-bookings-wp').'</p>' ;	
		$html .= '<p><input type="text" id="but-category-name" value="'.$category_name.'"></p>' ;
		$html .= '<input type="hidden" id="getbwp_category_id" value="'.$category_id .'" />' ;		
			
		echo wp_kses($html, $getbookingwp->allowed_html) ;		
		die();		
	
	}
	
	public function client_get_add_form()
	{		
		global  $getbookingwp;
		
		$html = '';		
		
		$client_id = sanitize_text_field($_POST['client_id']);		
		$category_name = '';		
				
		if($client_id!='')		
		{

		}		
		
		$html .= '<p>'.__('Name:','get-bookings-wp').'</p>' ;	
		$html .= '<p><input type="text" id="client_name" value="'.$category_name.'"></p>' ;
		$html .= '<p>'.__('Last Name:','get-bookings-wp').'</p>' ;	
		$html .= '<p><input type="text" id="client_last_name" value="'.$category_name.'"></p>' ;
		$html .= '<p>'.__('Email:','get-bookings-wp').'</p>' ;	
		$html .= '<p><input type="text" id="client_email" value="'.$category_name.'"></p>' ;
		$html .= '<p id="getbwp-add-client-message"></p>' ;		
			
		echo wp_kses($html, $getbookingwp->allowed_html) ;		
		die();		
	
	}
	
	//returns the business hours drop down
	public function get_business_staff_business_hours($staff_id)
	{
		$this->mBusinessHours = get_option('getbwp_business_hours');
		$html = '';
		
		$html .=' <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td>'.__('Monday','get-bookings-wp').'</td>
			<td>'.$this->get_business_hours_drop_down_for_staff(1,'getbwp-mon-from' ,'getbwp_select_start', $staff_id). '<span> '.__('to', 'get-bookings-wp').' </span>' .$this->get_business_hours_drop_down_for_staff(1,'getbwp-mon-to' ,'getbwp_select_end', $staff_id).'</td>
		  </tr>
		  <tr>
			<td>'.__('Tuesday','get-bookings-wp').'</td>
			<td>'.$this->get_business_hours_drop_down_for_staff(2,'getbwp-tue-from' ,'getbwp_select_start', $staff_id). '<span> '.__('to', 'get-bookings-wp').' </span>' .$this->get_business_hours_drop_down_for_staff(2,'getbwp-tue-to' ,'getbwp_select_end', $staff_id).'</td>
		  </tr>
		  <tr>
			<td>'.__('Wednesday','get-bookings-wp').'</td>
			<td>'.$this->get_business_hours_drop_down_for_staff(3,'getbwp-wed-from' ,'getbwp_select_start', $staff_id). '<span> '.__('to', 'get-bookings-wp').' </span>' .$this->get_business_hours_drop_down_for_staff(3,'getbwp-wed-to' ,'getbwp_select_end', $staff_id).'</td>
		  </tr>
		  <tr>
			<td>'.__('Thursday ','get-bookings-wp').'</td>
			<td>'.$this->get_business_hours_drop_down_for_staff(4,'getbwp-thu-from' ,'getbwp_select_start', $staff_id). '<span> '.__('to', 'get-bookings-wp').' </span>' .$this->get_business_hours_drop_down_for_staff(4,'getbwp-thu-to' ,'getbwp_select_end', $staff_id).'</td>
		  </tr>
		  <tr>
			<td>'.__('Friday ','get-bookings-wp').'</td>
			<td>'.$this->get_business_hours_drop_down_for_staff(5,'getbwp-fri-from' ,'getbwp_select_start', $staff_id). '<span> '.__('to', 'get-bookings-wp').' </span>' .$this->get_business_hours_drop_down_for_staff(5,'getbwp-fri-to' ,'getbwp_select_end', $staff_id).'</td>
		  </tr>
		  <tr>
			<td>'.__('Saturday  ','get-bookings-wp').'</td>
			<td>'.$this->get_business_hours_drop_down_for_staff(6,'getbwp-sat-from' ,'getbwp_select_start', $staff_id). '<span> '.__('to', 'get-bookings-wp').' </span>' .$this->get_business_hours_drop_down_for_staff(6,'getbwp-sat-to' ,'getbwp_select_end', $staff_id).'</td>
		  </tr>
		  <tr>
			<td>'.__('Sunday ','get-bookings-wp').'</td>
			<td>'.$this->get_business_hours_drop_down_for_staff(7,'getbwp-sun-from' ,'getbwp_select_start', $staff_id) . '<span> '.__('to', 'get-bookings-wp').' </span>' .$this->get_business_hours_drop_down_for_staff(7,'getbwp-sun-to' ,'getbwp_select_end', $staff_id).'</td>
		  </tr>
		  </table>';
		  
		  $html .=' <p class="submit">
	<button name="getbwp-save-glogal-business-hours-staff" id="getbwp-save-glogal-business-hours-staff" class="getbwp-button-submit-changes" getbwp-staff-id= "'.$staff_id.'">'.__('Save Changes','get-bookings-wp').'	</button>&nbsp; <span id="getbwp-loading-animation-business-hours">  <img src="'.getbookingpro_url.'admin/images/loaderB16.gif" width="16" height="16" /> &nbsp; '.__('Please wait ...','get-bookings-wp').' </span>
	
	</p>';
		  
	  
		  return $html;
	
	}
	
		
	//returns the business hours drop down
	public function get_business_hours_global_settings()
	{
		$this->mBusinessHours = get_option('getbwp_business_hours');
		$html = '';
		
		$html .=' <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td>'.__('Monday','get-bookings-wp').'</td>
			<td>'.$this->get_business_hours_drop_down(1,'getbwp-mon-from' ,'getbwp_select_start'). '<span> '.__('to', 'get-bookings-wp').' </span>' .$this->get_business_hours_drop_down(1,'getbwp-mon-to' ,'getbwp_select_end').'</td>
		  </tr>
		  <tr>
			<td>'.__('Tuesday','get-bookings-wp').'</td>
			<td>'.$this->get_business_hours_drop_down(2,'getbwp-tue-from' ,'getbwp_select_start'). '<span> '.__('to', 'get-bookings-wp').' </span>' .$this->get_business_hours_drop_down(2,'getbwp-tue-to' ,'getbwp_select_end').'</td>
		  </tr>
		  <tr>
			<td>'.__('Wednesday','get-bookings-wp').'</td>
			<td>'.$this->get_business_hours_drop_down(3,'getbwp-wed-from' ,'getbwp_select_start'). '<span> '.__('to', 'get-bookings-wp').' </span>' .$this->get_business_hours_drop_down(3,'getbwp-wed-to' ,'getbwp_select_end').'</td>
		  </tr>
		  <tr>
			<td>'.__('Thursday ','get-bookings-wp').'</td>
			<td>'.$this->get_business_hours_drop_down(4,'getbwp-thu-from' ,'getbwp_select_start'). '<span> '.__('to', 'get-bookings-wp').' </span>' .$this->get_business_hours_drop_down(4,'getbwp-thu-to' ,'getbwp_select_end').'</td>
		  </tr>
		  <tr>
			<td>'.__('Friday ','get-bookings-wp').'</td>
			<td>'.$this->get_business_hours_drop_down(5,'getbwp-fri-from' ,'getbwp_select_start'). '<span> '.__('to', 'get-bookings-wp').' </span>' .$this->get_business_hours_drop_down(5,'getbwp-fri-to' ,'getbwp_select_end').'</td>
		  </tr>
		  <tr>
			<td>'.__('Saturday  ','get-bookings-wp').'</td>
			<td>'.$this->get_business_hours_drop_down(6,'getbwp-sat-from' ,'getbwp_select_start'). '<span> '.__('to', 'get-bookings-wp').' </span>' .$this->get_business_hours_drop_down(6,'getbwp-sat-to' ,'getbwp_select_end').'</td>
		  </tr>
		  <tr>
			<td>'.__('Sunday ','get-bookings-wp').'</td>
			<td>'.$this->get_business_hours_drop_down(7,'getbwp-sun-from' ,'getbwp_select_start') . '<span> '.__('to', 'get-bookings-wp').' </span>' .$this->get_business_hours_drop_down(7,'getbwp-sun-to' ,'getbwp_select_end').'</td>
		  </tr>
		  </table>';
		  
	  
		  return $html;
	
	}
	
	function get_business_hour_option($day, $from_to, $staff_id = null)
	{
		$business_hours = $this->mBusinessHours;
		
		$value = '';
		
		if(!isset($staff_id))
		{	
			
			if(isset($business_hours[$day])) //we have the week's day
			{					
				$value =  $business_hours[$day][$from_to];				
				if($business_hours[$day][$from_to]=='24:00:00'){$value='24:00';}
				
			}
		
		}else{
			
			//get the value for this day and this staff
			$hourly = $this->get_hourly_for_staf($staff_id, $day);	
			
			if(!$hourly) //not hourly, we retreive a predefined value
			{
				$value =  '';							
				
			}else{	//we retreive from the database
			
				if($from_to=='from')
				{
					$value =   date('H:i', strtotime($hourly->avail_from));	
					
				}else{
						
					$value =  date('H:i', strtotime($hourly->avail_to));										
					if($hourly->avail_to=='24:00:00'){$value='24:00';}
					
				}			
			}
		
		}
		
		return $value;
	
	}
	
	public function get_hourly_for_staf($staff_id, $day)
	{
		global $wpdb, $getbookingwp;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'getbwp_staff_availability  WHERE avail_staff_id = %s
		  AND avail_day= %s ' ;
		$sql = $wpdb->prepare($sql,array($staff_id, $day));	
		$rows = $wpdb->get_results($sql);
		
		if ( !empty( $rows ) )
		{
			foreach ( $rows as $row )
			{				
				return $row;
				
			}
		}else{
			
			return false;
		
		
		}
	
	
	}
	
	
	//returns the business hours drop down
	public function get_business_hours_drop_down_for_staff($day, $cbox_id, $select_start_to_class, $staff_id)
	{
		global  $getbookingwp;

		$html = '';
		$selected = '';
		//getbwp_calendar_working_hours_start
		
		$hours = 24; //amount of hours working in day		
		$min_minutes = $getbookingwp->get_option('getbwp_time_slot_length');
		
		//added on 11-28-2017 to give flexibility on the schedule		
		$min_minutes_schedule = $getbookingwp->get_option('getbwp_calendar_working_hours_start');
		
		if($min_minutes_schedule ==''){
			
			if($min_minutes ==''){$min_minutes=15;}	
			
		}else{
			
			$min_minutes=$min_minutes_schedule ;			
		
		}
		
		$min_minutes_set=$min_minutes;
				
		$hours = (60/$min_minutes) *$hours;		
		$min_minutes=$min_minutes*60;		
		
		
		$html .= '<select id="'.$cbox_id.'" name="'.$cbox_id.'" class="'.$select_start_to_class.'">';
		
		//get default value for this week's day		
		if($select_start_to_class=='getbwp_select_start')
		{
			$from_to_value = 'from';		
			
		}else{
				
			$from_to_value = 'to';			
			
		}
		

		$html .= '<option '.$selected.' value="">'.__('OFF','get-bookings-wp').'</option>';

			
		//check selected value
		$selected_value = $this->get_business_hour_option($day, $from_to_value, $staff_id);		
		
		for($i = 0; $i <= $hours ; $i++)
		{ 		
			$minutes_to_add = $min_minutes * $i; // add 30 - 60 - 90 etc.
			$timeslot = date('H:i:s', strtotime('midnight')+$minutes_to_add);	
			
						
			$selected = '';				
			if($selected_value==date('H:i', strtotime($timeslot)))
			{
				$selected = 'selected="selected"';
				
			}elseif($selected_value=='24:00' && date('H:i', strtotime($timeslot)) =='00:00'){
				
				$selected = 'selected="selected"';
			
		    }
			
			if( ($from_to_value == 'to' && $i == 48 && $min_minutes_set==30) || ($from_to_value == 'to' && $i == 96 && $min_minutes_set==15) || ($from_to_value == 'to' && $i == 24 && $min_minutes_set==60) )
			{
				$sel_value ='24:00';
							
			}else{
								
				$sel_value =date('H:i', strtotime($timeslot));			
				
			}	
			
			$html .= '<option value="'.$sel_value.'" '.$selected.'  >'.date('h:i A', strtotime($timeslot)).'</option>';
			
		}
		
		$html .='</select>';		
		return $html;
	
	}
	
	//returns the business hours drop down
	public function get_business_hours_drop_down($day, $cbox_id, $select_start_to_class)
	{
		global  $getbookingwp;
		
		$hours = 24; //amount of hours working in day		
		$min_minutes = $getbookingwp->get_option('getbwp_time_slot_length');
		
		$selected = "";
		$html = "";
				
		if($min_minutes =='')
		{
			$min_minutes=15;						
		}
		
		$min_minutes_set=$min_minutes;
				
		$hours = (60/$min_minutes) *$hours;		
		$min_minutes=$min_minutes*60;
		
		//get default value for this week's day		
		if($select_start_to_class=='getbwp_select_start')
		{
			$from_to_value = 'from';		
			
		}else{
				
			$from_to_value = 'to';			
			
		}		
		
		
		$html .= '<select id="'.$cbox_id.'" name="'.$cbox_id.'" class="'.$select_start_to_class.'">';				
		$html .= '<option '.$selected.' value="">'.__('OFF','get-bookings-wp').'</option>';		
			
		//check selected value
		$selected_value = $this->get_business_hour_option($day, $from_to_value);		
		
		for($i = 0; $i <= $hours ; $i++)
		{ 		
			$minutes_to_add = $min_minutes * $i; // add 30 - 60 - 90 etc.
			$timeslot = date('H:i:s', strtotime('midnight')+$minutes_to_add);	
			
			$selected = '';				
			if($selected_value==date('H:i', strtotime($timeslot)))
			{
				$selected = 'selected="selected"';
				
			}elseif($selected_value=='24:00' && date('H:i', strtotime($timeslot)) =='00:00'){
				
				$selected = 'selected="selected"';
			
		    }
			
			if( ($from_to_value == 'to' && $i == 48 && $min_minutes_set==30) || ($from_to_value == 'to' && $i == 96 && $min_minutes_set==15) || ($from_to_value == 'to' && $i == 24 && $min_minutes_set==60))
			{
				$sel_value ='24:00';
							
			}else{
								
				$sel_value =date('H:i', strtotime($timeslot));			
				
			}	
			
			$html .= '<option value="'.$sel_value.'" '.$selected.'  >'.date('h:i A', strtotime($timeslot)).'</option>';
			
		}
		
		$html .='</select>';
		return $html;
	}
	
		
	
	
	
	public function get_admin_categories()
	{
		$rows = $this->get_all_categories();
		
		$html = '';
		$html .='<h3>'.__('Categories','get-bookings-wp').' ('.count($rows).')</h3>';
		$html .='<span class="getbwp-add-service"><a href="#" id="getbwp-add-category-btn" title="'.__('Add New Category','get-bookings-wp').'" ><i class="fa fa-plus"></i></a></span>';
		$html .='<ul id="category-list-sortable" >';
				
		if ( !empty( $rows ) )
		{
			foreach ( $rows as $row )
			{
				$html .= '<li  id="'.$row->cate_id.'">';				
				$html .='<span class="getbwp-action-service"><a href="#" class="getbwp-category-delete"  title="'.__('Delete','get-bookings-wp').'" category-id="'.$row->cate_id.'" ><i class="fa fa-trash-o"></i></a> <a href="#" class="getbwp-edit-category-btn" category-id="'.$row->cate_id.'" id="getbwp-eidt-category-btn" title="'.__('Edit','get-bookings-wp').'" ><i class="fa fa-edit"></i></a></span>';
				$html .= '<a href="#" class="getbwp-load-services-by-cate" data-id="'.$row->cate_id.'"><i class="fa fa-bars getbwp-fontawe-space"></i>  '.$row->cate_name.'</a>';
				$html .= '</li>';
			
			}
			
			
		}else{
		
			$html .= '<p>'.__('There are no categories','get-bookings-wp').'</p>';
	    }
		
		$html .='</ul>';    
		return $html ;	
		
	
	}
	
	public function get_admin_services($cate_id = null)
	{
		global $getbookingwp, $getbwpcomplement;
		$html = '';

		//get category
		$service =$getbookingwp->service->get_one_category($cate_id);		
		
		$rows = $this->get_all_services($cate_id);
		
		$html .='<div class="getbwp-service-header-bar">';
		$html .='<h3>'.$service->cate_name.' ('.count($rows).')</h3>';
		
		$html .='<span class="getbwp-add-service-m"><a href="#" id="getbwp-add-service-btn" title="'.__('Add New Service','get-bookings-wp').'" ><i class="fa fa-plus"></i></a></span>';
		$html .='</div>';
		
			
		
		if ( !empty( $rows ) ){

		$html .= '<table width="100%" class="wp-list-table widefat fixed posts table-generic">
            <thead>';
			
		$html .= '<thead>
                <tr >
				    <th width="2%"><div style:background-color:></div></th>
					
					 <th width="4%">'.__('ID', 'get-bookings-wp').'</div></th>
                    <th width="24%">'.__('Title', 'get-bookings-wp').'</th>
                    <th width="19%">'.__('Duration', 'get-bookings-wp').'</th>
                    <th width="26%">'.__('Price', 'get-bookings-wp').'</th>
                    <th width="13%">'.__('Capacity', 'get-bookings-wp').'</th>
                    <th width="16%">'.__('Category', 'get-bookings-wp').'</th>
					<th width="16%">'.__('Actions', 'get-bookings-wp').'</th>
                </tr>
            </thead>
            
            <tbody>';	
			
			foreach ( $rows as $row )
			{
				//duration 
				
				$duration = $this->get_service_duration_format($row->service_duration);
				
				
				$zoom='';
				if($row->service_meeting_zoom==1){

					$zoom='<span class="getbwp-zoom-ico">(zoom)</span>';

				}
				
				$html .= '<tr>
				    <td><div class="service-color-blet" style="background-color:'.$row->service_color.';" ></div></td>
					
					<td>'.$row->service_id.'</td>
                    <td>'.$row->service_title.'</td>
                    <td>'.$duration.'</td>
                    <td>'.$row->service_price.'</td>
                    <td>'.$row->service_capacity.' '.$zoom.'</td>
					<td>'.$row->cate_name.'</td>
                   <td><a href="#" class="getbwp-service-delete"  title="'.__('Delete','get-bookings-wp').'" service-id="'.$row->service_id.'" data-cate-id="'.$row->service_category_id.'" ><i class="fa fa-trash-o"></i></a>&nbsp;<a class="getbwp-admin-edit-service" href="#" id="" service-id="'.$row->service_id.'" ><span><i class="fa fa-edit fa-lg"></i></span></a>'; 
				   
				   if($row->service_allow_multiple==1 && isset($getbwpcomplement))
				   
				   {
				  	 $html .= '&nbsp;<a class="getbwp-admin-edit-pricing" href="#" id="" service-id="'.$row->service_id.'" ><span><i class="fa fa-users fa-lg"></i></span></a>';
				   
				   }
				   
				   $html .= ' </td>
                </tr>';			
			
			}
		}else{
		
			$html .= '<p>'.__('There are no services within this category','get-bookings-wp').'</p>';
				
	    }
		
        $html .= '</table>';
		
		return $html ;	
		
	
	}
	
	public function get_price_for_person ($service_id, $person_number) 
	{
		global $wpdb, $getbookingwp;
		
		$sql ="SELECT * FROM " . $wpdb->prefix . "getbwp_service_variable_pricing  
			WHERE rate_service_id = %s AND rate_person = %s ;";
		
		$sql = $wpdb->prepare($sql,array($service_id, $person_number));
		$res = $wpdb->get_results($sql);
		
		if (!empty($res))
		{
			
			foreach($res as $price) 
			{
				$service_price = $price->rate_price;				
				
			}		
		
		}else{
			
			$service_price = 0;	
			
		}
		
		
		return $service_price ;	
	
	}
	
	public function get_service_pricing ()
	{
		global $wpdb, $getbookingwp;
		
		$service_id = sanitize_text_field($_POST["service_id"]);
		
		$value= '';
		$html = '';
		
		$service =$getbookingwp->service->get_one_service($service_id);
		$service_capacity = $service->service_capacity;
		
	
		$html .= '<div class="getbwp-customizer">' ;
		$html .= '<table width="100%" class="wp-list-table widefat fixed posts table-generic">
            <thead>
                <tr>
                    <th width="50%" style="color:# 333">'.__('Person/s', 'get-bookings-wp').'</th>
                    <th width="50%">'.__('Price', 'get-bookings-wp').'</th>
                    
                </tr>
            </thead>
            
            <tbody>' ;	
			
			
				$html .='<ul>';
				
				$i = 1;
				while ($i <= $service_capacity) {
				
					
					$current_pricing = $this->get_price_for_person ($service_id, $i);
					
					$label = $i.__(' Person', 'get-bookings-wp') 	;
					
					if($i >1){$label = $label.'s';}
								
							
					 $html .= '<tr>
						 <td width="50%">'.$label.'</td>
						 <td width="50%"><input type="text" style="width:99%"  id="getbwp_pricing_id_'.$i.'"  name="getbwp_pricing['.$i.']" class="getbwp-servicepricing-textbox" value="'.$current_pricing.'"  /></td>
					   </tr>';
					   
					   $i++;
					   
					   
			   }	
			
			
		
		$html .= '<input type="hidden"  id="getbwp_pricing_service_id"  name="getbwp_pricing_service_id"  value="'.$service_id.'"  /></tbody>
        </table>';
        			
		
		$html .= '</div>' ;
		
		echo wp_kses($html, $getbookingwp->allowed_html);
		
		die();
		
		
	}
	
	public function update_group_pricing_table()
	{
		$service_id = sanitize_text_field($_POST['service_id'])	;		
		$pricing_list = sanitize_text_field($_POST["pricing_list"]); 	
		
		if($service_id!='')
		{
			//delete old pricing for this service
			
			$this->delete_service_pricing($service_id);						
			$pricing_list =rtrim($pricing_list,"|");
			$pricing_list = explode("|", $pricing_list);
			
			$persons=1;								
			foreach($pricing_list as  $price)
			{				
				$this->insert_service_pricing($service_id, $persons, $price);
				$persons++;				
			
			}	
			
		}
		
		print_r($pricing_list);	
		
		die();
	}
	
	function delete_service_pricing($service_id)
	{
		global  $wpdb, $getbookingwp;
		
		$sql = 'DELETE FROM ' . $wpdb->prefix . 'getbwp_service_variable_pricing  WHERE rate_service_id="'.(int)$service_id.'" ';
		
		$wpdb->query($sql);	
		
	}
	
	function insert_service_pricing($service_id, $persons, $price)
	{
		global  $wpdb, $getbookingwp;
		
    	$new_record = array('rate_id' => NULL,	
								'rate_service_id' => $service_id,
								'rate_person' => $persons,
								'rate_price' => $price,
								);								
									
		$wpdb->insert( $wpdb->prefix . 'getbwp_service_variable_pricing', $new_record, array( '%d', '%s', '%s', '%s'));
		
	}
	
	function get_categories_drop_down($category = null)
	{
		global  $getbookingwp;
		
		$html = '';
		
		$cate_rows = $this->get_all_categories();	
		
		$html .= '<select name="getbwp-category" id="getbwp-category">';
		
		foreach ( $cate_rows as $cate )
		{
			$selected = '';
			if($category==$cate->cate_id){$selected='selected="selected"';}
		
			$html .= '<option value="'.$cate->cate_id.'" '.$selected.'>'.$cate->cate_name.'</option>';
			
		}
		
		$html .= '</select>';
		
		return $html;
	
	}
	
	
	function get_staff_offering_service($service_id)
	{
		global  $getbookingwp, $wpdb;
		
		$html = array();
		
		$category_id = sanitize_text_field($_POST['b_category']);		
		
		$sql = ' SELECT serv.*,user.*  FROM ' . $wpdb->users . '  user ' ;		
		$sql .= "RIGHT JOIN ".$wpdb->prefix ."getbwp_service_rates serv ON (serv.rate_service_id = %s)";
		$sql .= ' WHERE user.ID = serv.rate_staff_id' ;					
		$sql .= ' ORDER BY user.display_name ASC  ' ;

		$sql = $wpdb->prepare($sql,array($service_id));		
		$users = $wpdb->get_results($sql);		
		
		if (!empty($users))
		{
			
			foreach($users as $user) 
			{
				$html[$user->ID] = $user->ID;				
				
			}
		
		
		}
		
		
		return $html;
		
	
	}
	
	function get_cate_dw_admin_ajax()
	{
		global  $getbookingwp, $wpdb;
		
		$html = '';
		
		$currency_symbol = $getbookingwp->get_currency_symbol();
		$display_price = $getbookingwp->get_option('price_on_staff_list_front');
		$price_label = '';
		$staff_id = '';
		
		$category_id = '';
		$appointment_id = '';
		if(isset($_POST['b_category']))
		{
			$category_id = sanitize_text_field($_POST['b_category']);
			
		}
		
		if(isset($_POST['appointment_id']))
		{
			$appointment_id = sanitize_text_field($_POST['appointment_id']);	
			
		}
		
		
		
		//get appointment	
		if($appointment_id!=''){

			$appointment = $getbookingwp->appointment->get_one($appointment_id);
			$staff_id = $appointment->booking_staff_id;	

		}		
		
		
		$sql = ' SELECT serv.*,user.*  FROM ' . $wpdb->users . '  user ' ;		
		$sql .= "RIGHT JOIN ".$wpdb->prefix ."getbwp_service_rates serv ON (serv.rate_service_id = %s)";
		$sql .= ' WHERE user.ID = serv.rate_staff_id' ;		
		$sql .= ' GROUP BY user.ID	  ' ;
					
		$sql .= ' ORDER BY user.display_name ASC  ' ;

		$sql = $wpdb->prepare($sql,array($category_id));
		$users = $wpdb->get_results($sql);

	
		$html = '';
		
		$html .= '<div class="field-header">'.__('With','get-bookings-wp').'</div>';		
		$html .= '<select name="getbwp-staff" id="getbwp-staff">';
		$html .= '<option value="" selected="selected" >'.__('Any', 'get-bookings-wp').'</option>';
		
		if (!empty($users))
		{			
			foreach($users as $user) 
			{
				$service_details = $getbookingwp->userpanel->get_staff_service_rate( $user->ID, $category_id );				
				$service_price = 	$service_details['price'];
				
				if($display_price=='' || $display_price=='yes')
				{
					$price_label= '('.$currency_symbol.''.$service_price.')';				
				}
				
				$selected='';
				if($staff_id==$user->ID)
				{
					$selected='selected';				
				}
						
				$html .= '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.' '.$price_label.'</option>';		
				
				
			}
			$html .= '</select>';
		
		
		}
		
		
		echo wp_kses($html, $getbookingwp->allowed_html);
		
		die();
	
	}
	
	//used when using service_id shortcode only	
	function get_cate_list_front($category_id, $template_id)
	{
		global  $getbookingwp, $wpdb;
		
		$html = '';
		
		$currency_symbol = $getbookingwp->get_currency_symbol();
		$display_price = $getbookingwp->get_option('price_on_staff_list_front');
		$price_label = '';
		
		$filter_id = sanitize_text_field($_POST['filter_id']);
		
		if($template_id!='')
		{
			$select_label = $getbookingwp->get_template_label("select_provider_label",$template_id);
		
		}else{
			
			$select_label = __('With','get-bookings-wp');			
		
		}
		
		$selected = '';	
		
		if($filter_id=='')
		{
			
			$sql = ' SELECT serv.*,user.*  FROM ' . $wpdb->users . '  user ' ;		
			$sql .= "RIGHT JOIN ".$wpdb->prefix ."getbwp_service_rates serv ON (serv.rate_service_id = '".$category_id.
			"')";
			$sql .= ' WHERE user.ID = serv.rate_staff_id' ;	
			$sql .= ' GROUP BY user.ID	  ' ;				
			$sql .= ' ORDER BY user.display_name ASC  ' ;
		
		}else{
			
			$sql = ' SELECT serv.*, user.*, staff_location.*  FROM ' . $wpdb->prefix . 'users  user ' ;		
			$sql .= "RIGHT JOIN ".$wpdb->prefix ."getbwp_service_rates serv ON (serv.rate_service_id = %s)";
			$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_filter_staff staff_location ON (staff_location.fstaff_staff_id = user.ID)";
			
			$sql .= " WHERE user.ID = serv.rate_staff_id AND staff_location.fstaff_staff_id = user.ID AND  staff_location.fstaff_location_id = %s " ;
			
			$sql .= ' GROUP BY user.ID	  ' ;
								
			$sql .= ' ORDER BY user.display_name ASC  ' ;
			
		}
		
		$sql = $wpdb->prepare($sql,array($category_id, $filter_id));
		$users = $wpdb->get_results($sql);

	
		$html = '';
		
		$html .= '<label>'.$select_label.'</label>';		
		$html .= '<select name="getbwp-staff" id="getbwp-staff">';
		$html .= '<option value="" selected="selected" >'.__('Any ', 'get-bookings-wp').'</option>';
		
		if (!empty($users))
		{			
			foreach($users as $user) 
			{
				$service_details = $getbookingwp->userpanel->get_staff_service_rate( $user->ID, $category_id );				
				$service_price = 	$service_details['price'];
				
				if($display_price=='' || $display_price=='yes')
				{
					$price_label= '('.$currency_symbol.''.$service_price.')';				
				}
						
				$html .= '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.' '.$price_label.'</option>';		
				
				
			}
			$html .= '</select>';
		
		
		}
		
		
		echo wp_kses($html, $getbookingwp->allowed_html);
		die();
	
	}
	
	function get_random_staff_member_for_location($filter_id , $service_id)
	{
		global  $getbookingwp, $wpdb;
		
		$staff_id = '';
		$staff_members = array();
		
	
		$sql = ' SELECT serv.*, user.*, staff_location.*  FROM ' . $wpdb->users . '  user ' ;		
		$sql .= "RIGHT JOIN ".$wpdb->prefix ."getbwp_service_rates serv ON (serv.rate_service_id = %s)";
		$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_filter_staff staff_location ON (staff_location.fstaff_staff_id = user.ID)";
			
		$sql .= " WHERE user.ID = serv.rate_staff_id AND staff_location.fstaff_staff_id = user.ID AND  staff_location.fstaff_location_id = %s " ;					
		$sql .= ' ORDER BY user.display_name ASC  ' ;

		$sql = $wpdb->prepare($sql,array($service_id, $filter_id));		
		$users = $wpdb->get_results($sql);
		
		if (!empty($users))
		{			
			foreach($users as $user) 
			{
				$staff_members[$user->ID] = $user->ID;				
				
			}	
		}		
		
		$staff_id = $staff_members[array_rand($staff_members)];		
		
		return $staff_id;
		
	
	}

	function get_staff_offering_service_ajax(){
		global  $getbookingwp, $wpdb;
		
		$html = '';
		
		$currency_symbol = $getbookingwp->get_currency_symbol();
		$display_price = $getbookingwp->get_option('price_on_staff_list_front');
		$price_label = '';
		
		$service_id = sanitize_text_field($_POST['b_category']);	
		$service = $this->get_one_service($service_id);

		$category = $this->get_one_category($service->service_category_id );
		$category_name =	$category->cate_name;

		if(isset( $_POST['filter_id'])){

			$filter_id = sanitize_text_field($_POST['filter_id']);

		}else{

			$filter_id ='';
		}
		
		if(isset($_POST['template_id'])){
			$template_id = sanitize_text_field($_POST['template_id']);
		
		}else{
			
			$template_id ='';	
		
		}
		
		$selected = '';	
		
		if($filter_id==''){
			
			$sql = ' SELECT serv.*,  user.*  FROM ' . $wpdb->users . '  user ' ;		
			$sql .= "RIGHT JOIN ".$wpdb->prefix ."getbwp_service_rates serv ON (serv.rate_service_id = %s)";			
			$sql .= ' WHERE user.ID = serv.rate_staff_id AND serv.rate_service_id = %s' ;
			$sql .= ' GROUP BY user.ID	  ' ;		
			$sql .= ' ORDER BY user.display_name ASC  ' ;
			$sql = $wpdb->prepare($sql,array($service_id, $service_id));	
			
		}else{
			
			$sql = ' SELECT serv.*, user.*, staff_location.*  FROM ' . $wpdb->users . '  user ' ;		
			$sql .= "RIGHT JOIN ".$wpdb->prefix ."getbwp_service_rates serv ON (serv.rate_service_id = %s)";
			$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_filter_staff staff_location ON (staff_location.fstaff_staff_id = user.ID)";
			$sql .= " WHERE user.ID = serv.rate_staff_id AND staff_location.fstaff_staff_id = user.ID AND  staff_location.fstaff_location_id =%s " ;	
			$sql .= ' GROUP BY user.ID	  ' ;
			$sql .= ' ORDER BY user.display_name ASC  ' ;

			$sql = $wpdb->prepare($sql,array($service_id, $filter_id));
			
		}
		
		$users = $wpdb->get_results($sql);
	
		$html = '';
		$html .= '<div class="getbwpdeta-nav-bar"> ';
			$html .= '<div class="getbwpdeta-searchoption-left"> ';
				$html .= '<span class="input-group-append getwpnav-button-opt">
											<button class="btn btn-modern btn-block btn-modern-nuv mb-2 mb-2-nuv" id="getbwp-back-to-servlist"><span class="input-group-text input-group-text-getwpsearch">
												<i class="fa fa-arrow-left"></i>
											</span></button>
										</span>
										';


			$html .= '</div>';

			$html .= '<div class="getbwpdeta-date-searchoption"> ';


						// $html .= $this->check_is_cart_icon();


			$html .= '</div>';   //left col

		$html .= '</div>';   //end navbar

		$html .= '<div class="getbwp-front-nav-titles"> ';	
			$html .= '<h3> '.__('Select Staff Members','get-bookings-wp').'</h3> ';//
			$html .= '<p class="getbwp-public-serv-name">'.$category_name.' - '.$service->service_title.'</p> ';//
		$html .= '</div>';   //end navbar
		
		if (!empty($users)){			
			$html .= '<div class="getbwp-front-staff-list">';	
			$html .= '<ul>';

			foreach($users as $user){
				$service_details = $getbookingwp->userpanel->get_staff_service_rate( $user->ID, $category_id );				
				$service_price = 	$service_details['price'];
				
				if($display_price=='' || $display_price=='yes'){
					$price_label= '('.$currency_symbol.''.$service_price.')';				
				}

				$user_profession = get_user_meta( $user->ID, 'u_profession', true );
						
				$html .= '<li class="getbwp-btn-next-step1 " data-cate-id="'.$service_id .'" data-staff-id="'.$user->ID.'">';
					$html .= '<div class="getbwp-front-staff-photo">';
					$html .= $getbookingwp->userpanel->get_user_pic( $user->ID, 100, 'getbwp-avatar', null, null, false);
					$html .= '</div>';					

					$html .= '<div class="getbwp-front-staff-name">';
					$html .=$user->display_name;
					$html .= '</div>';

					$html .= '<div class="getbwp-front-staff-profession">';
					$html .=$user_profession;
					$html .= '</div>';

					//$html .= '<div class="getbwp-front-staff-btn">';
					//$html .= '<button class="getbwp-btn-next-step1 btn-block" data-cate-id="'.$service_id .'" data-staff-id="'.$user->ID.'">'.__('SELECT', 'get-bookings-wp').'</button>';
					//$html .= '</div>';
				$html .= '</li>';
				
			}
			$html .= '</ul>';
			$html .= '</div>';
		
		}else{

			$html .= '<div class="getbwp-front-staff-list">';
			$html = __('There are no available staff members for this service','get-bookings-wp');	
			$html .= '</div>';



		}	
		
		echo wp_kses($html, $getbookingwp->allowed_html);
		die();
	
	}
	
	function get_cate_dw_ajax()	{
		global  $getbookingwp, $wpdb;
		
		$html = '';
		
		$currency_symbol = $getbookingwp->get_currency_symbol();
		$display_price = $getbookingwp->get_option('price_on_staff_list_front');
		$price_label = '';
		
		$category_id = sanitize_text_field($_POST['b_category']);
		$filter_id = sanitize_text_field($_POST['filter_id']);
		$template_id = sanitize_text_field($_POST['template_id']);
		
		if($template_id!='')
		{
			$select_label = $getbookingwp->get_template_label("select_provider_label",$template_id);
		
		}else{
			
			$select_label = __('Employee','get-bookings-wp');			
		
		}
		
		$selected = '';	
		
		if($filter_id=='')
		{
			
			$sql = ' SELECT serv.*,  user.*  FROM ' . $wpdb->users . '  user ' ;		
			$sql .= "RIGHT JOIN ".$wpdb->prefix ."getbwp_service_rates serv ON (serv.rate_service_id = %s)";			
			$sql .= ' WHERE user.ID = serv.rate_staff_id AND serv.rate_service_id = %s' ;
			$sql .= ' GROUP BY user.ID	  ' ;		
			$sql .= ' ORDER BY user.display_name ASC  ' ;
			$sql = $wpdb->prepare($sql,array($category_id, $category_id));			
			
		
		}else{
			
			$sql = ' SELECT serv.*, user.*, staff_location.*  FROM ' . $wpdb->users . '  user ' ;		
			$sql .= "RIGHT JOIN ".$wpdb->prefix ."getbwp_service_rates serv ON (serv.rate_service_id = %s)";
			$sql .= " RIGHT JOIN ". $wpdb->prefix."getbwp_filter_staff staff_location ON (staff_location.fstaff_staff_id = user.ID)";
			$sql .= " WHERE user.ID = serv.rate_staff_id AND staff_location.fstaff_staff_id = user.ID AND  staff_location.fstaff_location_id = %s " ;	
			$sql .= ' GROUP BY user.ID	  ' ;
			$sql .= ' ORDER BY user.display_name ASC  ' ;
			$sql = $wpdb->prepare($sql,array($category_id, $filter_id));	
			
		}
		
		
		$users = $wpdb->get_results($sql);
	
		$html = '';
		
		$html .= '<label>'.$select_label.'</label>';		
		$html .= '<select name="getbwp-staff" id="getbwp-staff" class="getbw-drop-down-cont-lstbox">';
		$html .= '<option value="" selected="selected" >'.__('First Available', 'get-bookings-wp').'</option>';
				
		if (!empty($users))
		{			
			foreach($users as $user) 
			{
				$service_details = $getbookingwp->userpanel->get_staff_service_rate( $user->ID, $category_id );				
				$service_price = 	$service_details['price'];
				
				if($display_price=='' || $display_price=='yes')
				{
					$price_label= '('.$currency_symbol.''.$service_price.')';				
				}
						
				$html .= '<option value="'.$user->ID.'" '.$selected.'>'.$user->display_name.' '.$price_label.'</option>';		
				
				
			}
			$html .= '</select>';
		
		
		}		
		
		
		echo  wp_kses($html, $getbookingwp->allowed_html);
		die();
	
	}

	public function get_week_days_to_check(){

		$display = '';

		
		$meta = 'book_recurrent_week_day';
		$dowMap = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
							  
		for ($x = 1; $x <= 7; $x++){

			$day_label  = $dowMap[$x-1]; 
		    $display .= '<div class="getbwp-checkbox getbwp-custom-field getbwp-dayscheck">
		  
		  <input type="checkbox"  checked  class="book_checked_week_day" title="'.$day_label.'" name="'.$meta.'[]" id="getbwp_multi_box_'.$meta.'_'.$x.'" value="'.$x.'" ';
		
			$display .= '/> <label for="getbwp_multi_box_'.$meta.'_'.$x.'"> '.$day_label .'</label> ';
			$display .= '</div>';			
		}

		return $display;
	}
	
	function get_categories_drop_down_public($service_id = null, $staff_id = null , $category_ids = null, $template_id = null)
	{
		global  $getbookingwp;
		
		$html = '';
		
		$cate_rows = $this->get_all_categories();
		
		//check if category restriction applied		
		$allowed_cate = array();		
		if($category_ids!='')
		{			
			$allowed_cate = explode(",", $category_ids);		
		}
		
		if($template_id!=''){
			
			$service_label =  $getbookingwp->get_template_label("select_service_label_drop",$template_id);	
		
		}else{
			
			$service_label =  __('Select Service','get-bookings-wp');		
		}
		
		
		$html .= '<select name="getbwp-category" id="getbwp-category">';
		$html .= '<option value="" selected="selected">'.$service_label .'</option>';
		
		foreach ( $cate_rows as $cate )
		{
			//is this user offering (display only the speficied categories)			
			if($category_ids!='' && !in_array($cate->cate_id,$allowed_cate))
				{
					continue;					
				}
			
			if($staff_id!='') //a staff id has been set, then we have to check if the user offers this service
			{
				//is this staff member offering this?	
				if(!$getbookingwp->userpanel->staff_offer_this_category( $staff_id, $cate->cate_id ))
				{
					
					continue;
				}			
			}
			
			
			$html .= '<optgroup label="'.$cate->cate_name.'" >';
			
			//get services						
			$servi_rows = $this->get_all_services($cate->cate_id);
			foreach ( $servi_rows as $serv )
			{
				$selected = '';
				
				
				if($staff_id!='') //check if the staff offers this service
				{
					if($getbookingwp->userpanel->staff_offer_service( $staff_id, $serv->service_id ))
					{
						$html .= '<option value="'.$serv->service_id.'" '.$selected.' >'.$serv->service_title.'</option>';					
					}					
				
				}else{
					
					$html .= '<option value="'.$serv->service_id.'" '.$selected.' >'.$serv->service_title.'</option>';
				
				}
			}
			
			$html .= '</optgroup>';
			
			
			
		}
		
		$html .= '</select>';
		
		return $html;
	
	}

	function get_categories_front_list_drop(){
		global  $getbookingwp;
		
		$html = '';

				
		$cate_rows = $this->get_all_categories();

		$html .= '<label>'.__('Service','get-bookings-wp').'</label>';		
		$html .= '<select name="getbwp-category" id="getbwp-category" class="getbw-drop-down-cont-lstbox">';
		$html .= '<option value="" selected="selected">'.__('Choose Service','get-bookings-wp').'</option>';
		
		foreach ( $cate_rows as $cate )		{		
			
			$html .= '<optgroup label="'.$cate->cate_name.'" >';
			
			//get services						
			$servi_rows = $this->get_all_services($cate->cate_id);
			foreach ( $servi_rows as $serv ){
				$selected = '';
				
						
				if($serv->service_id==$service_id){$selected = 'selected';}
				$html .= '<option value="'.$serv->service_id.'" '.$selected.' >--- '.$serv->service_title.'</option>';
				
			}
			
			$html .= '</optgroup>';
			
		}
		
		$html .= '</select>';
		
		echo  $html;
		die();
	
	}

	function get_categories_front_list(){
		global  $getbookingwp;
		
		$html = '';

		$category_ids = sanitize_text_field($_POST['category_ids']);
		$available_legend = sanitize_text_field($_POST['available_legend']);
		$available_text = sanitize_text_field($_POST['available_text']);
		
		$allowed_cate = array();		
		if($category_ids!=''){			
			$allowed_cate = explode(",", $category_ids);		
		}	
		
		$cate_rows = $this->get_all_categories();		

		
		foreach ( $cate_rows as $cate )	{
						
			if($category_ids!='' && !in_array($cate->cate_id,$allowed_cate)){
				continue;					
			}

			$html .= '<div  class="getbwp-fro-cate-list-colap" id="nuvcar-'.$cate->cate_id.'">';
			$html .= '<h4 class="getbwp-front-serv-header" widget-id="'.$cate->cate_id.'" >'.$cate->cate_name.'</h4>';

			$html .='<div class="card-actions">
											<a href="#" id="nucolap-ico-'.$cate->cate_id.'" widget-id="'.$cate->cate_id.'"  class="card-action-toggle  getwpfront-servwidget card-action " data-card-toggle=" "></a>
										</div>';

			$html .= '<div  id="getbwpcate-box-colap-'.$cate->cate_id.'" class="">';
			$html .= '<ul class="">';
			
			//get services						
			$servi_rows = $this->get_all_services($cate->cate_id);
			foreach ( $servi_rows as $serv ){		

				$description = $serv->service_desc;
				$description = substr($description,0,100);
			
				$duration = $this->get_service_duration_format($serv->service_duration);
				$price = $this->get_formated_price_with_currency($serv->service_price);

				$html .= ' <li class="getwpstores-front-serv-staff " style="animation-delay: 200ms;" data-nuve-rand-id="'.$serv->service_id.'" data-nuve-rand-key="'.$serv->service_id.'"">
				';

					//col 1 serv title and description
					$html .= '<div class=" nuservinfo-c1">';

					$html .= '<h4 class="card-title mb-1 text-4 font-weight-bold getbwp-serv-titles" >'.$serv->service_title.'</h4>';
					$html .= '<p class="card-text getbwp-serv-desc">'.$description.'</p>';

					$html .= '</div>';

					//col 2 duration, rate and book now button
					$html .= '<div class=" nuservinfo-c2">';

					$html .= '<div class="n-duration">';
					$html .= '<span class="getbwp-bussine-serv-duration">'.$duration.'<span>';
					$html .= '</div>';

					$html .= '<div class="n-price">';

					$html .= '<span class="getbwp-bussine-serv-price">'.$price.'<span>';

									
					$html .= '</div>';

					$html .= '<div class="n-book">';
					$html .= ' <button type="button" class="btn btn-light btn-block mb-2 getbwp-stores-front-serv-staff" data-nuve-rand-id="'.$serv->service_id.'" data-nuve-rand-key="'.$serv->service_id.'" data-location="'.$serv->service_id.'">'.__('Select','get-bookings-wp').'</button>';
					$html .= '</div>';


					$html .= '</div>'; //end col2
					$html .= ' </li>';
				
			}

			
			$html .= '</ul>';
			$html .= '</div>'; //end cate list


			$html .= '</div>';
			
		}		


		echo  wp_kses($html, $getbookingwp->allowed_html);
		die();
		
			
	}

	function get_formated_price_with_currency($price){

		global  $getbookingwp;
		$currency = $getbookingwp->get_option("currency_symbol");
		
		return $currency.''.$price;


	}

	
	
	function get_categories_drop_down_admin($service_id = null)
	{
		global  $getbookingwp;
		
		$html = '';
		
		$cate_rows = $this->get_all_categories();		
		
		$html .= '<select name="getbwp-category" id="getbwp-category">';
		$html .= '<option value="" selected="selected">'.__('Select a Service','get-bookings-wp').'</option>';
		
		foreach ( $cate_rows as $cate )
		{		
			
			$html .= '<optgroup label="'.$cate->cate_name.'" >';
			
			//get services						
			$servi_rows = $this->get_all_services($cate->cate_id);
			foreach ( $servi_rows as $serv )
			{
				$selected = '';
				
						
				if($serv->service_id==$service_id){$selected = 'selected';}
				$html .= '<option value="'.$serv->service_id.'" '.$selected.' >'.$serv->service_title.'</option>';
				
			}
			
			$html .= '</optgroup>';
			
		}
		
		$html .= '</select>';
		
		return $html;
	
	}
	
	function get_duration_drop_down($seconds = null)
	{
		global  $getbookingwp, $getbwpcomplement;
		
		$html = '';
		
		//$max_hours = 43200; //12 hours in seconds	
		$max_hours = 43200; //12 hours in seconds		
		$min_minutes = $getbookingwp->get_option('getbwp_time_slot_length');
		
		if($min_minutes ==''){$min_minutes=15;}		
		$min_minutes=$min_minutes*60;
		
		$html .= '<select name="getbwp-duration" id="getbwp-duration">';
		
		for ($x = $min_minutes; $x <= $max_hours; $x=$x+$min_minutes)
		{
			$selected = '';
			if($seconds==$x){$selected='selected="selected"';}
		
			$html .= '<option value="'.$x.'" '.$selected.'>'.$this->get_service_duration_format($x).'</option>';
			
		}
		
		if(isset($getbwpcomplement))
		{
			$selected = '';		
			if($seconds==86400){$selected='selected="selected"';}		
			$html .= '<option value="86400" '.$selected.'>'.__('All Day ','get-bookings-wp').'</option>';
		}
		
		
		
		$html .= '</select>';
		
		return $html;
	
	}
	
	function get_service_duration_format($seconds)
	{
		global $wpdb, $getbookingwp;
		
		$time_formated = $getbookingwp->commmonmethods->secondsToTime($seconds);
		
		
		if($seconds<3600) //less than an hour
		{
			$str = $time_formated["m"] . " min ";		
		
		}else{
			
			$str = $time_formated["h"] ." h ";
			
			
			if($time_formated["m"] > 0)
			 {
				$str =  $str." ".$time_formated["m"]." min ";
			
			}
			
		
		
		}
		
		
		
		return $str;
	
	
	}
	
	
	public function get_all_categories () 
	{
		global $wpdb, $getbookingwp;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'getbwp_categories ORDER BY cate_order ASC  ' ;
		$res = $wpdb->get_results($sql);
		return $res ;	
	
	}
	
	public function get_all_services ($cate_id = NULL) {
		global $wpdb, $getbookingwp;
		
		$sql = ' SELECT serv.*, cate.* FROM ' . $wpdb->prefix . 'getbwp_services  serv ' ;		
		$sql .= " RIGHT JOIN ".$wpdb->prefix ."getbwp_categories cate ON (cate.cate_id = serv.service_category_id)";
		$sql .= ' WHERE cate.cate_id = serv.service_category_id' ;
		
		if($cate_id!='')
		{
			
			$cond = ' AND serv.service_category_id = %s  ' ;		
			$sql .= $wpdb->prepare($cond, array($cate_id)); 
		}
		
		$sql .= ' ORDER BY serv.service_category_id ASC, serv.service_title ASC  ' ;
		
		$res = $wpdb->get_results($sql);
		return $res ;	
	
	}
	
	public function get_one_service ($service_id){
		global $wpdb, $getbookingwp;
		
		$sql = ' SELECT serv.*, cate.* FROM ' . $wpdb->prefix . 'getbwp_services  serv ' ;
		
		$sql .= " RIGHT JOIN ".$wpdb->prefix ."getbwp_categories cate ON (cate.cate_id = serv.service_category_id)";
		$sql .= ' WHERE cate.cate_id = serv.service_category_id' ;			
		$sql .= ' AND serv.service_id = %s  ' ;	
		
		$sql= $wpdb->prepare($sql, array($service_id));					
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				return $row;			
			
			}
			
		}	
	
	}
	
	public function get_one_category ($category_id){
		global $wpdb, $getbookingwp;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . 'getbwp_categories  ' ;
		$sql .= ' WHERE cate_id = %s' ;		
		
		$sql= $wpdb->prepare($sql, array($category_id));				
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				return $row;			
			
			}
			
		}	
	
	}

	
}
$key = "service";
$this->{$key} = new GetBookingsWPService();
?>