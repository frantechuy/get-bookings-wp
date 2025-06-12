<?php 
global $getbookingwp,   $getbwpcomplement;
?>
<h3><?php _e('Payment Gateways Settings','get-bookings-wp'); ?></h3>
<form method="post" action="">
<input type="hidden" name="update_settings" />
<?php wp_nonce_field('getbwp-action', 'getbwp_nonce' ); ?>


<?php if(!isset($getbwpcomplement)){?>

  <div class="getbwp-validation-sect ">
        
        <h3><?php _e('Online Payment Options','get-bookings-wp'); ?></h3>
        <p><?php _e("Stripe & Paypal features are available only on pro versions",'get-bookings-wp'); ?>.</p>  
        <p> <a href="https://getbookingswp.com/pricing" target="_blank"><?php _e('CLICK HERE ','get-bookings-wp'); ?></a> <?php _e(" to upgrade your plugin.",'get-bookings-wp'); ?></p>      
        
   </div>

<?php }?>


<?php if(isset($getbwpcomplement)){?>
<div class="getbwp-sect ">
  <h3><?php _e('Stripe Settings','get-bookings-wp'); ?></h3>
  
  <p><?php _e("Stripe is a payment gateway for mechants. If you don't have a Stripe account, you can <a href='https://stripe.com/'> sign up for one account here</a> ",'get-bookings-wp'); ?></p>
  
  <p><?php _e('Here you can configure Stripe if you wish to accept credit card payments directly in your website. Find your Stripe API keys here <a href="https://dashboard.stripe.com/account/apikeys">https://dashboard.stripe.com/account/apikeys</a>','get-bookings-wp'); ?></p>
  
  
  <table class="form-table">
<?php 

$this->create_plugin_setting(
                'checkbox',
                'gateway_stripe_active',
                __('Activate Stripe','get-bookings-wp'),
                '1',
                __('If checked, Stripe will be activated as payment method','get-bookings-wp'),
                __('If checked, Stripe will be activated as payment method','get-bookings-wp')
        ); 


$this->create_plugin_setting(
        'input',
        'test_secret_key',
        __('Test Secret Key','get-bookings-wp'),array(),
        __('You can get this on stripe.com','get-bookings-wp'),
        __('You can get this on stripe.com','get-bookings-wp')
);

$this->create_plugin_setting(
        'input',
        'test_publish_key',
        __('Test Publishable Key','get-bookings-wp'),array(),
        __('You can get this on stripe.com','get-bookings-wp'),
        __('You can get this on stripe.com','get-bookings-wp')
);

$this->create_plugin_setting(
        'input',
        'live_secret_key',
        __('Live Secret Key','get-bookings-wp'),array(),
        __('You can get this on stripe.com','get-bookings-wp'),
        __('You can get this on stripe.com','get-bookings-wp')
);

$this->create_plugin_setting(
        'input',
        'live_publish_key',
        __('Live Publishable Key','get-bookings-wp'),array(),
        __('You can get this on stripe.com','get-bookings-wp'),
        __('You can get this on stripe.com','get-bookings-wp')
);


$this->create_plugin_setting(
        'input',
        'gateway_stripe_currency',
        __('Currency','get-bookings-wp'),array(),
        __('Please enter the currency, example USD.','get-bookings-wp'),
        __('Please enter the currency, example USD.','get-bookings-wp')
);

$this->create_plugin_setting(
        'textarea',
        'gateway_stripe_success_message',
        __('Custom Message','get-bookings-wp'),array(),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','get-bookings-wp'),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','get-bookings-wp')
);

$this->create_plugin_setting(
                'checkbox',
                'gateway_stripe_success_active',
                __('Custom Success Page Redirect ','get-bookings-wp'),
                '1',
                __('If checked, the users will be taken to this page once the payment has been confirmed','get-bookings-wp'),
                __('If checked, the users will be taken to this page once the payment has been confirmed','get-bookings-wp')
        ); 


$this->create_plugin_setting(
            'select',
            'gateway_stripe_success',
            __('Success Page','get-bookings-wp'),
            $this->get_all_sytem_pages(),
            __("Select the sucess page. The user will be taken to this page if the payment was approved by stripe.",'get-bookings-wp'),
            __('Select the sucess page. The user will be taken to this page if the payment was approved by stripe.','get-bookings-wp')
    );


$this->create_plugin_setting(
	'select',
	'enable_live_key',
	__('Mode','get-bookings-wp'),
	array(
		1 => __('Production Mode','get-bookings-wp'), 
		2 => __('Test Mode (Sandbox)','get-bookings-wp')
		),
		
	__('.','get-bookings-wp'),
  __('.','get-bookings-wp')
       );
	   



		
?>
</table>

  
</div>

<?php }?>


<?php if(isset($getbwpcomplement))
{?>
<div class="getbwp-sect " style="display:none">
  <h3><?php _e('Authorize.NET AIM Settings','get-bookings-wp'); ?></h3>
  
  <p><?php _e(" ",'get-bookings-wp'); ?></p>
  
  <p><?php _e(' ','get-bookings-wp'); ?></p>
  
  
  <table class="form-table">
<?php 

$this->create_plugin_setting(
                'checkbox',
                'gateway_authorize_active',
                __('Activate Authorize','get-bookings-wp'),
                '1',
                __('If checked, Authorize will be activated as payment method','get-bookings-wp'),
                __('If checked, Authorize will be activated as payment method','get-bookings-wp')
        ); 



$this->create_plugin_setting(
        'input',
        'authorize_login',
        __('API Login ID','get-bookings-wp'),array(),
        __('You can get this on authorize.net','get-bookings-wp'),
        __('You can get this on authorize.net','get-bookings-wp')
);

$this->create_plugin_setting(
        'input',
        'authorize_key',
        __('API Transaction Key','get-bookings-wp'),array(),
        __('You can get this on authorize.net','get-bookings-wp'),
        __('You can get this on authorize.net','get-bookings-wp')
);


$this->create_plugin_setting(
        'input',
        'authorize_currency',
        __('Currency','get-bookings-wp'),array(),
        __('Please enter the currency, example USD.','get-bookings-wp'),
        __('Please enter the currency, example USD.','get-bookings-wp')
);

$this->create_plugin_setting(
        'textarea',
        'gateway_authorize_success_message',
        __('Custom Message','get-bookings-wp'),array(),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','get-bookings-wp'),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','get-bookings-wp')
);

$this->create_plugin_setting(
                'checkbox',
                'gateway_authorize_success_active',
                __('Custom Success Page Redirect ','get-bookings-wp'),
                '1',
                __('If checked, the users will be taken to this page once the payment has been confirmed','get-bookings-wp'),
                __('If checked, the users will be taken to this page once the payment has been confirmed','get-bookings-wp')
        ); 


$this->create_plugin_setting(
            'select',
            'gateway_authorize_success',
            __('Success Page','get-bookings-wp'),
            $this->get_all_sytem_pages(),
            __("Select the sucess page. The user will be taken to this page if the payment was approved by Authorize.net ",'get-bookings-wp'),
            __('Select the sucess page. The user will be taken to this page if the payment was approved by Authorize.net','get-bookings-wp')
    );


$this->create_plugin_setting(
	'select',
	'authorize_mode',
	__('Mode','get-bookings-wp'),
	array(
		1 => __('Production Mode','get-bookings-wp'), 
		2 => __('Test Mode (Sandbox)','get-bookings-wp')
		),
		
	__('.','get-bookings-wp'),
  __('.','get-bookings-wp')
       );
	   



		
?>
</table>

  
</div>

<?php }?>

<?php if(isset($getbwpcomplement)){?>

<div class="getbwp-sect ">
  <h3><?php _e('PayPal','get-bookings-wp'); ?></h3>
  
  <p><?php _e('Here you can configure PayPal if you wish to accept paid registrations','get-bookings-wp'); ?></p>
    <p><?php _e("Please note: You have to set a right currency <a href='https://developer.paypal.com/docs/classic/api/currency_codes/' target='_blank'> check supported currencies here </a> ",'get-bookings-wp'); ?></p>
  
  
  <table class="form-table">
<?php 

$this->create_plugin_setting(
                'checkbox',
                'gateway_paypal_active',
                __('Activate PayPal','get-bookings-wp'),
                '1',
                __('If checked, PayPal will be activated as payment method','get-bookings-wp'),
                __('If checked, PayPal will be activated as payment method','get-bookings-wp')
        ); 

$this->create_plugin_setting(
	'select',
	'uultra_send_ipn_to_admin',
	__('The Paypal IPN response will be sent to the admin','get-bookings-wp'),
	array(
		'no' => __('No','get-bookings-wp'), 
		'yes' => __('Yes','get-bookings-wp'),
		),
		
	__("If 'yes' the admin will receive the whole Paypal IPN response. This helps to troubleshoot issues.",'get-bookings-wp'),
  __("If 'yes' the admin will receive the whole Paypal IPN response. This helps to troubleshoot issues.",'get-bookings-wp')
       );

$this->create_plugin_setting(
        'input',
        'gateway_paypal_email',
        __('PayPal Email Address','get-bookings-wp'),array(),
        __('Enter email address associated to your PayPal account.','get-bookings-wp'),
        __('Enter email address associated to your PayPal account.','get-bookings-wp')
);

$this->create_plugin_setting(
        'input',
        'gateway_paypal_sandbox_email',
        __('Paypal Sandbox Email Address','get-bookings-wp'),array(),
        __('This is not used for production, you can use this email for testing.','get-bookings-wp'),
        __('This is not used for production, you can use this email for testing.','get-bookings-wp')
);

$this->create_plugin_setting(
        'input',
        'gateway_paypal_currency',
        __('Currency','get-bookings-wp'),array(),
        __('Please enter the currency, example USD.','get-bookings-wp'),
        __('Please enter the currency, example USD.','get-bookings-wp')
);


$this->create_plugin_setting(
                'checkbox',
                'gateway_paypal_success_active',
                __('Custom Success Page Redirect ','get-bookings-wp'),
                '1',
                __('If checked, the users will be taken to this page once the payment has been confirmed','get-bookings-wp'),
                __('If checked, the users will be taken to this page once the payment has been confirmed','get-bookings-wp')
        ); 


$this->create_plugin_setting(
            'select',
            'gateway_paypal_success',
            __('Success Page','get-bookings-wp'),
            $this->get_all_sytem_pages(),
            __("Select the sucess page. The user will be taken to this page if the payment was approved by stripe.",'get-bookings-wp'),
            __('Select the sucess page. The user will be taken to this page if the payment was approved by stripe.','get-bookings-wp')
    );
	
	
	$this->create_plugin_setting(
                'checkbox',
                'gateway_paypal_cancel_active',
                __('Custom Cancellation Page Redirect ','get-bookings-wp'),
                '1',
                __('If checked, the users will be taken to this page if the payment is cancelled at PayPal website','get-bookings-wp'),
                __('If checked, the users will be taken to this page if the payment is cancelled at PayPal website','get-bookings-wp')
        ); 
		
		
		$this->create_plugin_setting(
            'select',
            'gateway_paypal_cancel',
            __('Cancellation Page','get-bookings-wp'),
            $this->get_all_sytem_pages(),
            __("Select the cancellation page. The user will be taken to this page if the payment is cancelled at PayPal Website",'get-bookings-wp'),
            __('Select the cancellation page. The user will be taken to this page if the payment is cancelled at PayPal Website','get-bookings-wp')
    );


$this->create_plugin_setting(
	'select',
	'gateway_paypal_mode',
	__('Mode','get-bookings-wp'),
	array(
		1 => __('Production Mode','get-bookings-wp'), 
		2 => __('Test Mode (Sandbox)','get-bookings-wp')
		),
		
	__('.','get-bookings-wp'),
  __('.','get-bookings-wp')
       );
	   





		
?>
</table>

  
</div>

<?php }?>


<div class="getbwp-sect ">
  <h3><?php _e('Bank Deposit/Cash Other','get-bookings-wp'); ?></h3>
  
  <p><?php _e('Here you can configure the information that will be sent to the client. This could be your bank account details.','get-bookings-wp'); ?></p>
  
  
  <table class="form-table">
<?php 

$this->create_plugin_setting(
                'checkbox',
                'gateway_bank_active',
                __('Activate Bank Deposit','get-bookings-wp'),
                '1',
                __('If checked, Bank Payment Deposit will be activated as payment method','get-bookings-wp'),
                __('If checked, Bank Payment Deposit will be activated as payment method','get-bookings-wp')
        ); 


$this->create_plugin_setting(
        'input',
        'gateway_bank_label',
        __('Custom Label','get-bookings-wp'),array(),
        __('Example: Bank Deposit , Cash, Wire etc.','get-bookings-wp'),
        __('Example: Bank Deposit , Cash, Wire etc.','get-bookings-wp')
);


$this->create_plugin_setting(
        'textarea',
        'gateway_bank_success_message',
        __('Custom Message','get-bookings-wp'),array(),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','get-bookings-wp'),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','get-bookings-wp')
);



$this->create_plugin_setting(
                'checkbox',
                'gateway_bank_success_active',
                __('Custom Success Page Redirect ','get-bookings-wp'),
                '1',
                __('If checked, the users will be taken to this page ','get-bookings-wp'),
                __('If checked, the users will be taken to this page ','get-bookings-wp')
        ); 


$this->create_plugin_setting(
            'select',
            'gateway_bank_success',
            __('Success Page','get-bookings-wp'),
            $this->get_all_sytem_pages(),
            __("Select the sucess page. The user will be taken to this page on purchase confirmation",'get-bookings-wp'),
            __('Select the sucess page. The user will be taken to this page on purchase confirmation','get-bookings-wp')
    );
	
	$data_status = array(
		 				'0' => 'Pending',
                        '1' =>'Approved'
                       
                    );
$this->create_plugin_setting(
            'select',
            'gateway_bank_default_status',
            __('Default Status for Local Payments','get-bookings-wp'),
            $data_status,
            __("Set the default status an appointment will have when using local payment method. You won't have to approve the appointments manually, they will get approved automatically.",'get-bookings-wp'),
            __('et the default status an appointment will have when using local payment method.','get-bookings-wp')
    );	

		
?>
</table>

  
</div>



<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','get-bookings-wp'); ?>"  />
	
</p>

</form>