<h3><?php _e('General Settings','get-bookings-wp'); ?></h3>
<form method="post" action="">
<input type="hidden" name="update_settings" />

<?php wp_nonce_field('getbwp-action', 'getbwp_nonce' ); ?>

<?php
global $getbookingwp, $getbwpcomplement;

 
?>


<div id="tabs-bupro-settings" class="getbwp-multi-tab-options">

<ul class="nav-tab-wrapper getbwp-nav-pro-features">
<li class="nav-tab getbwp-pro-li"><a href="#tabs-1" title="<?php _e('General','get-bookings-wp'); ?>"><?php _e('General','get-bookings-wp'); ?></a></li>

<li class="nav-tab getbwp-pro-li"><a href="#tabs-getbwp-business-hours" title="<?php _e('Business Hours','get-bookings-wp'); ?>"><?php _e('Business Hours','get-bookings-wp'); ?> </a></li>

<li class="nav-tab getbwp-pro-li"><a href="#tabs-getbwp-newsletter" title="<?php _e('Newsletter','get-bookings-wp'); ?>"><?php _e('Newsletter','get-bookings-wp'); ?> </a></li>


<li class="nav-tab getbwp-pro-li"><a href="#tabs-getbwp-googlecalendar" title="<?php _e('Google Calendar','get-bookings-wp'); ?>"><?php _e('Google Calendar','get-bookings-wp'); ?> </a></li>
<li class="nav-tab getbwp-pro-li"><a href="#tabs-getbwp-zoomintegraton" title="<?php _e('Zoom','get-bookings-wp'); ?>"><?php _e('Zoom','get-bookings-wp'); ?> </a></li>



<li class="nav-tab getbwp-pro-li"><a href="#tabs-getbwp-shopping" title="<?php _e('Shopping Cart','get-bookings-wp'); ?>"><?php _e('Shopping Cart','get-bookings-wp'); ?> </a></li>





</ul>


<div id="tabs-1">

<div class="getbwp-sect  getbwp-welcome-panel">
  <h3><?php _e('Premium  Settings','get-bookings-wp'); ?></h3>
  
    <?php if(isset($getbwpcomplement))
{?>

  <p><?php _e('This section allows you to set your company name, phone number and many other useful things such as set time slot, date format.','get-bookings-wp'); ?></p>
  
  <table class="form-table">
<?php

$active_feature = false;

if($active_feature){
$this->create_plugin_setting(
            'select',
            'gateway_payment_request_page',
            __('Payment Page for Appointments','get-bookings-wp'),
            $this->get_all_sytem_pages(),
            __("Select the page that will be used to request payments from your clients. The client will be taken to this page so they can submit their payment, once tha payment is confirmed then the appointment will change it's status to 'Approved'. Make sure this page contains this shortcode: [getbwp_payment_form]",'get-bookings-wp'),
            __('Select the page that will be used to request payments from your clients.','get-bookings-wp')
    );

}

$this->create_plugin_setting(
	'select',
	'what_display_in_admin_calendar',
	__('What To Display in Get Bookings WP Admin Calendar?','get-bookings-wp'),
	array(
		1 => __('Staff Name','get-bookings-wp'), 		
		2 => __('Client Name','get-bookings-wp')),
		
	__('You can set what will be displayed in the GET BOOKINGS WP Dashboard Calendar. You can set either Staff Name or Client Name','get-bookings-wp'),
  __('You can set what will be displayed in the GET BOOKINGS WP Dashboard Calendar. You can set either Staff Name or Client Name','get-bookings-wp')
       );

$days_min = array(
						'0' => __('Disabled.','get-bookings-wp'),
						'1' => __('1 hour.','get-bookings-wp'),
						'2' => __('2 hours.','get-bookings-wp'),
						'3' => __('3 hours.','get-bookings-wp'),
						'4' => __('4 hours.','get-bookings-wp'),
						'5' => __('5 hours.','get-bookings-wp'),
						'6' => __('6 hours.','get-bookings-wp'),		
		 				'7' => __('7 hours.','get-bookings-wp'),
						'8' => __('8 hours.','get-bookings-wp'),
						'9' => __('9 hours.','get-bookings-wp'),
                        '10' =>__('10 hours.','get-bookings-wp'),
						'11' =>__('11 hours.','get-bookings-wp'),
						'12' =>__('12 hours.','get-bookings-wp'),
                        '24' => __('1 day','get-bookings-wp'),
                        '48' => __('2 days.','get-bookings-wp'),
                        '72' => __('3 days.','get-bookings-wp'),
                        '96' =>__('4 days.','get-bookings-wp'),                       
                        '120' =>__('5 days','get-bookings-wp'),
						'144' =>__('6 days','get-bookings-wp'),
						'168' =>__('1 week.','get-bookings-wp'),
						'336' =>__('2 weeks.','get-bookings-wp'),
						'504' =>__('3 weeks.','get-bookings-wp'),
						'672' =>__('4 Weeks.','get-bookings-wp'),
                       
                    );
   
		
		$this->create_plugin_setting(
            'select',
            'getbwp_min_prior_booking',
            __('Minimum time requirement prior to booking:','get-bookings-wp'),
            $days_min,
            __('Set how late appointments can be booked (for example, require customers to book at least 1 hour before the appointment time).','get-bookings-wp'),
            __('Set how late appointments can be booked (for example, require customers to book at least 1 hour before the appointment time).','get-bookings-wp')
    );
	
	
	$this->create_plugin_setting(
	'select',
	'allow_timezone',
	__("Activate timezone detection?",'get-bookings-wp'),
	array(
		0 => __('NO','get-bookings-wp'), 		
		1 => __('YES','get-bookings-wp')),
		
	__("This will detect the client's timezone. Which is useful if you offer services on different locations with different hours.",'get-bookings-wp'),
  __("This will detect the client's timezone. Which is useful if you offer services on different locations with different hours.",'get-bookings-wp')
       );
	   
	  
   
		
	
?>
</table>

<?php }else{?>

<p><?php _e('These settings are included in the premium version of GetBookingsWP. If you find the plugin useful for your business please consider buying a licence for the full version.','get-bookings-wp'); ?>. Click <a href="https://getbookingswp.com/pricing">here</a> to upgrade </p>

<strong>The following settings are included in Premium Version</strong>
<p>- Google Calendar. </p>
<p>- Minimum time requirement prior to booking. </p>
<p>- Display either Staff Name or Cient name on Admin Calendar. </p>



<?php }?> 

  
</div>


<div class="getbwp-sect  getbwp-welcome-panel">
  <h3><?php _e('Miscellaneous  Settings','get-bookings-wp'); ?></h3>
  
  <p><?php _e('This section allows you to set your company name, phone number and many other useful things such as set time slot, date format.','get-bookings-wp'); ?></p>
  
  
  <table class="form-table">
<?php 


$this->create_plugin_setting(
        'input',
        'company_name',
        __('Company Name:','get-bookings-wp'),array(),
        __('Enter your company name here.','get-bookings-wp'),
        __('Enter your company name here.','get-bookings-wp')
);

$this->create_plugin_setting(
        'input',
        'company_phone',
        __('Company Phone Number:','get-bookings-wp'),array(),
        __('Enter your company phone number here.','get-bookings-wp'),
        __('Enter your company phone number here.','get-bookings-wp')
);

$this->create_plugin_setting(
        'input',
        'company_address',
        __('Company Address:','get-bookings-wp'),array(),
        __('Enter your company address here.','get-bookings-wp'),
        __('Enter your company address here.','get-bookings-wp')
);

$this->create_plugin_setting(
	'select',
	'registration_rules',
	__('Booking Type','get-bookings-wp'),
	array(
		4 => __('Paid Booking','get-bookings-wp'), 		
		1 => __('Free Booking','get-bookings-wp')),
		
	__('Free Booking allows users to book and appointment for free, the payment methods will not be displayed. ','get-bookings-wp'),
  __('Free Booking allows users to book and appointment for free, the payment methods will not be displayed.','get-bookings-wp')
       );
	   
	   
	    $this->create_plugin_setting(
	'select',
	'wp_head_present',
	__("Is wp_head in theme?",'get-bookings-wp'),
	array(
		1 => __('YES','get-bookings-wp'), 		
		0=> __('NO','get-bookings-wp')),
		
	__("This setting is useful for themes that doesn't include the wp_head functions, which is not the ideal for the best practice to develop WP themes.",'get-bookings-wp'),
  __("This setting is useful for themes that doesn't include the wp_head functions, which is not the ideal for the best practice to develop WP themes.",'get-bookings-wp')
       );
	   
	    $this->create_plugin_setting(
	'select',
	'country_detection',
	__("Country Detection Active?",'get-bookings-wp'),
	array(
		1 => __('YES','get-bookings-wp'), 		
		0=> __('NO','get-bookings-wp')),
		
	__("This settings us a third-party library to auto-fill the phone number field on the front-end booking form.",'get-bookings-wp'),
  __("This settings us a third-party library to auto-fill the phone number field on the front-end booking form.",'get-bookings-wp')
       );
	   
	   
$this->create_plugin_setting(
                'checkbox',
                'gateway_free_success_active',
                __('Custom Success Page Redirect ','get-bookings-wp'),
                '1',
                __('If checked, the users will be taken to this page. This option is used only when you have set Free Bookins as Regitration Type ','get-bookings-wp'),
                __('If checked, the users will be taken to this page ','get-bookings-wp')
        ); 


$this->create_plugin_setting(
            'select',
            'gateway_free_success',
            __('Success Page for Free Bookings','get-bookings-wp'),
            $this->get_all_sytem_pages(),
            __("Select the sucess page. The user will be taken to this page right after the booking confirmation.",'get-bookings-wp'),
            __('Select the sucess page. The user will be taken to this page right after the booking confirmation.','get-bookings-wp')
    );
	
	
	$data_status = array(
		 				'0' => 'Pending',
                        '1' =>'Approved'
                       
                    );
$this->create_plugin_setting(
            'select',
            'gateway_free_default_status',
            __('Default Status for Free Appointments','get-bookings-wp'),
            $data_status,
            __("Set the default status an appointment will have when NOT using a payment method. You won't have to approve the appointments manually, they will get approved automatically.",'get-bookings-wp'),
            __('et the default status an appointment will have when NOT using a payment method.','get-bookings-wp')
    );	


	
$this->create_plugin_setting(
        'textarea',
        'gateway_free_success_message',
        __('Custom Message for Free Bookings','get-bookings-wp'),array(),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','get-bookings-wp'),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','get-bookings-wp')
);


$this->create_plugin_setting(
                'checkbox',
                'appointment_cancellation_active',
                __('Redirect Cancellation link? ','get-bookings-wp'),
                '1',
                __('If checked, the clients will be able to cancel the appointment by using the cancellation link displayed in the appointment details email and they will be redirected to your custom page specified above. ','get-bookings-wp'),
                __('If checked, the clients will be able to cancel the appointment by using the cancellation link displayed in the appointment details email. ','get-bookings-wp')
        );
$this->create_plugin_setting(
            'select',
            'appointment_cancellation_redir_page',
            __('Cancellation Page','get-bookings-wp'),
            $this->get_all_sytem_pages(),
            __("Select the cancellation page. The appointment cancellation needs a page. Please create your cancellation page and set it here. IMPORTANT: Setting a page is very important, otherwise this feature will not work.",'get-bookings-wp'),
            __('Select the cancellation page. The appointment cancellation needs a page. Please create your cancellation page and set it here.','get-bookings-wp')
    );	
	
	
	$this->create_plugin_setting(
            'select',
            'appointment_admin_approval_page',
            __('Appointment Approval Page','get-bookings-wp'),
            $this->get_all_sytem_pages(),
            __("Select the approbation page for your appointments. Please create a page if you wish to let the admin to approve an appointment via email. <br><br><strong>IMPORTANT:</strong> Setting this page is very important, otherwise this feature will not work. <br><br><strong>IMPORTANT:</strong> Only the admin will receive the link to approve and appointment via email.",'get-bookings-wp'),
            __('Select the Approbation page for your appointments','get-bookings-wp')
    );	    


 $data = array(
		 				'm/d/Y' => date('m/d/Y'),                        
                        'Y/m/d' => date('Y/m/d'),
                        'd/m/Y' => date('d/m/Y'),                  
                       
                        'F j, Y' => date('F j, Y'),
                        'j M, y' => date('j M, y'),
                        'j F, y' => date('j F, y'),
                        'l, j F, Y' => date('l, j F, Y')
                    );
		$data_picker = array(
		 				'm/d/Y' => date('m/d/Y'),
						'd/m/Y' => date('d/m/Y')
                    );
					
		$data_admin = array(
		 				'm/d/Y' => date('m/d/Y'),
						'd/m/Y' => date('d/m/Y')
                    );
					
		 $data_time = array(
		 				'5' => 5,
                        '10' =>10,
                        '12' => 12,
                        '15' => 15,
                        '20' => 20,
                        '30' =>30,                       
                        '60' =>60,
						'90' =>90,
						'120' =>120
                       
        );
		
		$data_time_format = array(
		 				
                        'H:i' => date('H:i'),
                        'h:i A' => date('h:i A')
                    );
		 $days_availability = array(
						'1' => 1,
						'2' => 2,
						'3' => 3,
						'4' => 4,
						'5' => 5,
						'6' => 6,		
		 				'7' => 7,
                        '10' =>10,
                        '15' => 15,
                        '20' => 20,
                        '25' => 25,
                        '30' =>30,                       
                        '35' =>35,
						'40' =>40,
                       
                    );
   
		
		$this->create_plugin_setting(
            'select',
            'getbwp_date_format',
            __('Date Format:','get-bookings-wp'),
            $data,
            __('Select the date format to be used','get-bookings-wp'),
            __('Select the date format to be used','get-bookings-wp')
    );
	
	
	$this->create_plugin_setting(
            'select',
            'getbwp_date_picker_format',
            __('Date Picker Format:','get-bookings-wp'),
            $data_picker,
            __('Select the date format to be used on the Date Picker','get-bookings-wp'),
            __('Select the date format to be used on the Date Picker','get-bookings-wp')
    );
	
	$this->create_plugin_setting(
            'select',
            'getbwp_date_admin_format',
            __('Admin Date Format:','get-bookings-wp'),
            $data_admin,
            __('Select the date format to be used on the Date Picker','get-bookings-wp'),
            __('Select the date format to be used on the Date Picker','get-bookings-wp')
    );
	
	$this->create_plugin_setting(
            'select',
            'getbwp_time_format',
            __('Display Time Format:','get-bookings-wp'),
            $data_time_format,
            __('Select the time format to be used','get-bookings-wp'),
            __('Select the time format to be used','get-bookings-wp')
    );
	
	
	
		$this->create_plugin_setting(
	'select',
	'allow_bookings_outsite_business_hours',
	__('Allow booking outside business hours?','get-bookings-wp'),
	array(
		'yes' => __('YES','get-bookings-wp'), 		
		'no' => __('NO','get-bookings-wp')),
		
	__("Use this option if you don't wish to receive purchases on services that fall outside the business hours. The booking system calculates that the appointments have to end when the business hours stop. ",'get-bookings-wp'),
  __("Use this option if you don't wish to receive purchases on services that fall outside the business hours. The booking system calculates that the appointments have to end when the business hours stop.  ",'get-bookings-wp')
       );
	
	
	$this->create_plugin_setting(
	'select',
	'display_only_from_hour',
	__('Display only from hour?','get-bookings-wp'),
	array(
		'no' => __('NO','get-bookings-wp'), 		
		'yes' => __('YES','get-bookings-wp')),
		
	__("Use this option if you don't wish to display the the whole time range, example 08:30 – 09:00 ",'get-bookings-wp'),
  __("Use this option if you don't wish to display the the whole time range, example 08:30 – 09:00  ",'get-bookings-wp')
       );
	   
	   
	   $this->create_plugin_setting(
	'select',
	'phone_number_mandatory',
	__('Is Phone Number Mandatory?','get-bookings-wp'),
	array(
		'yes' => __('YES','get-bookings-wp'), 		
		'no' => __('NO','get-bookings-wp')),
		
	__("Use this option if you don't wish to require a phone number at the step 3 ",'get-bookings-wp'),
  __("Use this option if you don't wish to require a phone number at the step 3  ",'get-bookings-wp')
       );
	   
	    $this->create_plugin_setting(
	'select',
	'last_name_mandatory',
	__('Ask for Last Name on Checkout?','get-bookings-wp'),
	array(
		'yes' => __('YES','get-bookings-wp'), 		
		'no' => __('NO','get-bookings-wp')),
		
	__("Use this option if you don't wish to require a the last name of your client at the step 3 ",'get-bookings-wp'),
  __("Use this option if you don't wish to require a the last name of your client at the step 3 ",'get-bookings-wp')
       );
	
	
	
	$this->create_plugin_setting(
            'select',
            'getbwp_calendar_days_to_display',
            __('Days to display on Step 2:','get-bookings-wp'),
            $days_availability,
            __('Set how many days will be displayed on the step 2','get-bookings-wp'),
            __('Set how many days will be displayed on the step 2','get-bookings-wp')
    );
	
	
	
	
	$this->create_plugin_setting(
        'input',
        'currency_symbol',
        __('Currency Symbol','get-bookings-wp'),array(),
        __('Input the currency symbol: Example: $','get-bookings-wp'),
        __('Input the currency symbol: Example: $','get-bookings-wp')
);

$this->create_plugin_setting(
	'select',
	'price_on_staff_list_front',
	__('Display service price on staff list?','get-bookings-wp'),
	array(
		'yes' => __('YES','get-bookings-wp'), 		
		'no' => __('NO','get-bookings-wp')),
		
	__("Use this option if you don't wish to display the service's price on the staff drop/down list ",'get-bookings-wp'),
  __("Use this option if you don't wish to display the service's price on the staff drop/down list ",'get-bookings-wp')
       );
	   
	   $this->create_plugin_setting(
	'select',
	'display_unavailable_slots_on_front',
	__('Display unavailable slots on booking form?','get-bookings-wp'),
	array(
		'yes' => __('YES','get-bookings-wp'), 		
		'no' => __('NO','get-bookings-wp')),
		
	__("Use this option if you don't wish to display the unavailable slots in the front-end booking form.",'get-bookings-wp'),
  __("Use this option if you don't wish to display the unavailable slots in the front-end booking form. ",'get-bookings-wp')
       );
	   
	   
	   $working_hours_time = array(
	                    '' => '',
		 				'5' => 5,
                        '10' =>10,
                        '12' => 12,
                        '15' => 15,
                        '20' => 20,
                        '30' =>30,                       
                        '60' =>60,
						'90' =>90,
						'120' =>120
                       
                    );
					
	
	 $this->create_plugin_setting(
            'select',
            'getbwp_calendar_working_hours_start',
            __('Staff Schedule Period:','get-bookings-wp'),
            $working_hours_time,
            __('This gives you flexibility to set the start working hour for your staff members','get-bookings-wp'),
            __('This gives you flexibility to set the start working hour for your staff members','get-bookings-wp')
    );
	   
	 $this->create_plugin_setting(
            'select',
            'getbwp_calendar_time_slot_length',
            __('Calendar Slot Length:','get-bookings-wp'),
            $data_time,
            __('Select the slot length to be used on the Calendar','get-bookings-wp'),
            __('Select the slot length to be used on the Calendar','get-bookings-wp')
    );
	
	
	$this->create_plugin_setting(
            'select',
            'getbwp_time_slot_length',
            __('Time slot length:','get-bookings-wp'),
            $data_time,
            __('Select the time interval that will be used in frontend and backend, e.g. in calendar, second step of the booking process, while indicating the working hours, etc.','get-bookings-wp'),
            __('Select the time interval that will be used in frontend and backend, e.g. in calendar, second step of the booking process, while indicating the working hours, etc.','get-bookings-wp')
    );
	
	
	$this->create_plugin_setting(
	'select',
	'getbwp_override_avatar',
	__('Use GetBookingsWP Avatar','get-bookings-wp'),
	array(
		'no' => __('No','get-bookings-wp'), 
		'yes' => __('Yes','get-bookings-wp'),
		),
		
	__('If you select "yes", GetBookingsWP will override the default WordPress Avatar','get-bookings-wp'),
  __('If you select "yes", GetBookingsWP will override the default WordPress Avatar','get-bookings-wp')
       );
	
	
	   $this->create_plugin_setting(
	'select',
	'avatar_rotation_fixer',
	__('Auto Rotation Fixer','get-bookings-wp'),
	array(
		'no' => __('No','get-bookings-wp'), 
		'yes' => __('Yes','get-bookings-wp'),
		),
		
	__("If you select 'yes', GetBookingsWP will Automatically fix the rotation of JPEG images using PHP's EXIF extension, immediately after they are uploaded to the server. This is implemented for iPhone rotation issues",'get-bookings-wp'),
  __("If you select 'yes', GetBookingsWP will Automatically fix the rotation of JPEG images using PHP's EXIF extension, immediately after they are uploaded to the server. This is implemented for iPhone rotation issues",'get-bookings-wp')
       );
	   $this->create_plugin_setting(
        'input',
        'media_avatar_width',
        __('Avatar Width:','get-bookings-wp'),array(),
        __('Width in pixels','get-bookings-wp'),
        __('Width in pixels','get-bookings-wp')
);

$this->create_plugin_setting(
        'input',
        'media_avatar_height',
        __('Avatar Height','get-bookings-wp'),array(),
        __('Height in pixels','get-bookings-wp'),
        __('Height in pixels','get-bookings-wp')
);
	
	
	
	 								
	
	  
		
?>
</table>


</div>


<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','get-bookings-wp'); ?>"  />
</p>




</div>

<div id="tabs-getbwp-zoomintegraton">
  
        <div class="getbwp-sect getbwp-welcome-panel ">
        <h3><?php _e('Zoom Video Conference API Settings','get-bookings-wp'); ?></h3>

        <?php if(!isset($getbwpcomplement))   {?>

        <p><?php _e('The Zoom Video Conference feature is disabled in the free version of GetBookingsWP. If you find the plugin useful for your business please consider buying a licence for the full version.','get-bookings-wp'); ?>. Click <a href="https://getbookingswp.com/pricing">here</a> to upgrade </p>

        <?php }?> 

        <p><?php _e('Zoom is available. Make sure you add these tags on the email templates:','get-bookings-wp'); ?>. </p>

        <p><strong>Zoom Meeting URL: {{getbwp_zoom_join_url}} <br>
        Zoom Meeting Password: {{getbwp_zoom_meeting_password}}</strong></p>
        </div>

</div>



<div id="tabs-getbwp-googlecalendar">
  
<div class="getbwp-sect getbwp-welcome-panel ">
<h3><?php _e('Google Calendar Settings','get-bookings-wp'); ?></h3>


  <?php if(isset($getbwpcomplement))
{?>

  
  <p><?php _e('This module gives you the capability to sync the plugin with Google Calendar. Each Staff member can have a different Google Calendar linked to their accounts.','get-bookings-wp'); ?></p>
  
  
<table class="form-table">
<?php 
   
		
$this->create_plugin_setting(
        'input',
        'google_calendar_client_id',
        __('Client ID','get-bookings-wp'),array(),
        __('Fill out this field with your Client ID obtained from the Developers Console','get-bookings-wp'),
        __('Fill out this field with your Client ID obtained from the Developers Console','get-bookings-wp')
);

$this->create_plugin_setting(
        'input',
        'google_calendar_client_secret',
        __('Client Secret','get-bookings-wp'),array(),
        __('Fill out this field with your Client Secret obtained from the Developers Console.','get-bookings-wp'),
        __('Fill out this field with your Client Secret obtained from the Developers Console.','get-bookings-wp')
);


$this->create_plugin_setting(
	'select',
	'google_calendar_template',
	__('What To Display in Google Calendar?','get-bookings-wp'),
	array(
		'service_name' => __('Service Name','get-bookings-wp'), 
		'staff_name' => __('Staff Name','get-bookings-wp'),
		'client_name' => __('Client Name','get-bookings-wp')
		),
		
	__("Set what information should be placed in the title of Google Calendar event",'get-bookings-wp'),
  __("Set what information should be placed in the title of Google Calendar event",'get-bookings-wp')
       );
	   
	   
	   $this->create_plugin_setting(
	'select',
	'google_calendar_debug',
	__('Debug Mode?','get-bookings-wp'),
	array(
		'no' => __('NO','get-bookings-wp'), 
		'yes' => __('YES','get-bookings-wp')
		),
		
	__("This option will display the detail of the error message if the Google Calendar Insert Method fails.",'get-bookings-wp'),
  __("This option will display the detail of the error message if the Google Calendar Insert Method fails.",'get-bookings-wp')
       );
	
?>
</table>


<p><strong><?php _e('Redirect URI','get-bookings-wp'); ?></strong></p>
<p><?php _e('Enter this URL as a redirect URI in the Developers Console','get-bookings-wp'); ?></p>

<p><strong><?php echo esc_url(get_admin_url().'admin.php?page=getbookingswp&tab=users');?> </strong></p>



<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','get-bookings-wp'); ?>"  />
</p>


<?php }else{?>

<p><?php _e('This function is disabled in the free version of GetBookingsWP. If you find the plugin useful for your business please consider buying a licence for the full version.','get-bookings-wp'); ?>. Click <a href="https://getbookingswp.com/pricing">here</a> to upgrade </p>
<?php }?> 


</div>

</div>

<div id="tabs-getbwp-business-hours">
<div class="getbwp-sect  getbwp-welcome-panel">
  <h3><?php _e('Business Hours','get-bookings-wp'); ?></h3>  
  <p><?php _e('.','get-bookings-wp'); ?></p>
   
   <?php echo wp_kses($getbookingwp->service->get_business_hours_global_settings(), $getbookingwp->allowed_html);?>
  
  <p class="submit">
	<input type="button" name="ubp-save-glogal-business-hours" id="ubp-save-glogal-business-hours" class="button button-primary" value="<?php _e('Save Changes','get-bookings-wp'); ?>"  />&nbsp; <span id="getbwp-loading-animation-business-hours">  <img src="<?php echo esc_url(getbookingpro_url.'admin/images/loaderB16.gif')?>" width="16" height="16" /> &nbsp; <?php _e('Please wait ...','get-bookings-wp'); ?> </span>
</p>

    
  
  
</div>


</div>





<div id="tabs-getbwp-newsletter">
  
  
  
  <?php if(isset($getbwpcomplement))
{?>


<div class="getbwp-sect getbwp-welcome-panel ">
<h3><?php _e('Aweber Settings','get-bookings-wp'); ?></h3>
  
  <p><?php _e('Here you can activate your preferred newsletter tool.','get-bookings-wp'); ?></p>

<table class="form-table">
<?php 
   
$this->create_plugin_setting(
	'select',
	'newsletter_active',
	__('Activate Newsletter','get-bookings-wp'),
	array(
		'no' => __('No','get-bookings-wp'), 
		'aweber' => __('AWeber','get-bookings-wp'),
		'mailchimp' => __('MailChimp','get-bookings-wp'),
		),
		
	__('Just set "NO" to deactivate the newsletter tool.','get-bookings-wp'),
  __('Just set "NO" to deactivate the newsletter tool.','get-bookings-wp')
       );

	
?>
</table>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','get-bookings-wp'); ?>"  />
</p>


</div>


<div class="getbwp-sect getbwp-welcome-panel ">
<h3><?php _e('Aweber Settings','get-bookings-wp'); ?></h3>
  
  <p><?php _e('This module gives you the capability to subscribe your clients automatically to any of your Aweber List when they complete the purchase.','get-bookings-wp'); ?></p>
  
  
<table class="form-table">
<?php 
   
		

$this->create_plugin_setting(
        'input',
        'aweber_consumer_key',
        __('Consumer Key','get-bookings-wp'),array(),
        __('Fill out this field your list ID.','get-bookings-wp'),
        __('Fill out this field your list ID.','get-bookings-wp')
);

$this->create_plugin_setting(
        'input',
        'aweber_consumer_secret',
        __('Consumer Secret','get-bookings-wp'),array(),
        __('Fill out this field your list ID.','get-bookings-wp'),
        __('Fill out this field your list ID.','get-bookings-wp')
);




$this->create_plugin_setting(
                'checkbox',
                'aweber_auto_text',
                __('Auto Checked Aweber','get-bookings-wp'),
                '1',
                __('If checked, the user will not need to click on the mailchip checkbox. It will appear checked already.','get-bookings-wp'),
                __('If checked, the user will not need to click on the mailchip checkbox. It will appear checked already.','get-bookings-wp')
        );
$this->create_plugin_setting(
        'input',
        'aweber_text',
        __('Aweber Text','get-bookings-wp'),array(),
        __('Please input the text that will appear when asking users to get periodical updates.','get-bookings-wp'),
        __('Please input the text that will appear when asking users to get periodical updates.','get-bookings-wp')
);

	$this->create_plugin_setting(
        'input',
        'aweber_header_text',
        __('Aweber Header Text','get-bookings-wp'),array(),
        __('Please input the text that will appear as header when mailchip is active.','get-bookings-wp'),
        __('Please input the text that will appear as header when mailchip is active.','get-bookings-wp')
);
	
?>
</table>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','get-bookings-wp'); ?>"  />
</p>


</div>




<div class="getbwp-sect getbwp-welcome-panel ">
<h3><?php _e('MailChimp Settings','get-bookings-wp'); ?></h3>
  
  <p><?php _e('.','get-bookings-wp'); ?></p>
  
  
<table class="form-table">
<?php 
   
		
$this->create_plugin_setting(
        'input',
        'mailchimp_api',
        __('MailChimp API Key','get-bookings-wp'),array(),
        __('Fill out this field with your MailChimp API key here to allow integration with MailChimp subscription.','get-bookings-wp'),
        __('Fill out this field with your MailChimp API key here to allow integration with MailChimp subscription.','get-bookings-wp')
);

$this->create_plugin_setting(
        'input',
        'mailchimp_list_id',
        __('MailChimp List ID','get-bookings-wp'),array(),
        __('Fill out this field your list ID.','get-bookings-wp'),
        __('Fill out this field your list ID.','get-bookings-wp')
);



$this->create_plugin_setting(
                'checkbox',
                'mailchimp_auto_checked',
                __('Auto Checked MailChimp','get-bookings-wp'),
                '1',
                __('If checked, the user will not need to click on the mailchip checkbox. It will appear checked already.','get-bookings-wp'),
                __('If checked, the user will not need to click on the mailchip checkbox. It will appear checked already.','get-bookings-wp')
        );
$this->create_plugin_setting(
        'input',
        'mailchimp_text',
        __('MailChimp Text','get-bookings-wp'),array(),
        __('Please input the text that will appear when asking users to get periodical updates.','get-bookings-wp'),
        __('Please input the text that will appear when asking users to get periodical updates.','get-bookings-wp')
);

	$this->create_plugin_setting(
        'input',
        'mailchimp_header_text',
        __('MailChimp Header Text','get-bookings-wp'),array(),
        __('Please input the text that will appear as header when mailchip is active.','get-bookings-wp'),
        __('Please input the text that will appear as header when mailchip is active.','get-bookings-wp')
);
	
?>
</table>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','get-bookings-wp'); ?>"  />
</p>


</div>


<?php }else{?>

<p><?php _e('This function is disabled in the free version of GetBookingsWP. If you find the plugin useful for your business please consider buying a licence for the full version.','get-bookings-wp'); ?>. Click <a href="https://getbookingswp.com/pricing">here</a> to upgrade </p>
<?php }?>  

</div>



</div>


<div id="tabs-getbwp-shopping">
  
<div class="getbwp-sect getbwp-welcome-panel ">
<h3><?php _e('Shopping Cart Settings','get-bookings-wp'); ?></h3>


  <?php if(isset($getbwpcomplement))
{?>

  
  <p><?php _e('This module gives you the capability to allow users to purchase multiple services at once. There are some settings you can tweak on this section','get-bookings-wp'); ?></p>
  
  
<table class="form-table">
<?php 
   
$this->create_plugin_setting(
        'input',
        'shopping_cart_description',
        __('Purchase Description','get-bookings-wp'),array(),
        __('Here you can set a custom description that will be displayed when the client purchases multiple items by using the shopping cart features.','get-bookings-wp'),
        __('Here you can set a custom description that will be displayed when the client purchases multiple items by using the shopping cart features.','get-bookings-wp')
);


	
?>
</table>



<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','get-bookings-wp'); ?>"  />
</p>


<?php }else{?>

<p><?php _e('This function is disabled in the free version of GetBookingsWP. If you find the plugin useful for your business please consider buying a licence for the full version.','get-bookings-wp'); ?>. Click <a href="https://getbookingswp.com/pricing">here</a> to upgrade </p>
<?php }?> 


</div>

</div>


</form>