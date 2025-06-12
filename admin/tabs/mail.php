<?php 
global $getbookingwp,   $getbwpcomplement;
?>
<h3><?php _e('Advanced Email Options','get-bookings-wp'); ?></h3>
<form method="post" action="" id="b_frm_settings" name="b_frm_settings">
<input type="hidden" name="update_settings" />
<input type="hidden" name="reset_email_template" id="reset_email_template" />
<input type="hidden" name="email_template" id="email_template" />
<?php wp_nonce_field('getbwp-action', 'getbwp_nonce' ); ?>



  <p><?php _e('Here you can control how Get Bookings WP will send the notification to your users.','get-bookings-wp'); ?></p>



 <h3><?php _e('Privacy','get-bookings-wp'); ?></h3>
 
 <div class="getbwp-sect  ">  
   <table class="form-table">
<?php 
 


$this->create_plugin_setting(
	'select',
	'getbwp_noti_admin',
	__('Send Email Notifications to Admin?:','get-bookings-wp'),
	array(
		'yes' => __('YES','get-bookings-wp'),
		'no' => __('NO','get-bookings-wp') 
		),
		
	__('This allows you to block email notifications that are sent to the admin.','get-bookings-wp'),
  __('This allows you to block email notifications that are sent to the admin.','get-bookings-wp')
       );
	   
$this->create_plugin_setting(
	'select',
	'getbwp_noti_staff',
	__('Send Email Notifications to Staff Members?:','get-bookings-wp'),
	array(
		'yes' => __('YES','get-bookings-wp'),
		'no' => __('NO','get-bookings-wp') 
		),
		
	__('This allows you to block email notifications that are sent to the staff members.','get-bookings-wp'),
  __('This allows you to block email notifications that are sent to the staff members.','get-bookings-wp')
       );
	   

$this->create_plugin_setting(
	'select',
	'getbwp_noti_client',
	__('Send Email Notifications to Clients?:','get-bookings-wp'),
	array(
		'yes' => __('YES','get-bookings-wp'),
		'no' => __('NO','get-bookings-wp') 
		),
		
	__('This allows you to block email notifications that are sent to the clients.','get-bookings-wp'),
  __('This allows you to block email notifications that are sent to the clients.','get-bookings-wp')
       );
	   

?>
 </table>

 
 </div>
 
 
<div class="getbwp-sect  ">  
   <table class="form-table">
<?php 
 

$this->create_plugin_setting(
        'input',
        'messaging_send_from_name',
        __('Send From Name','get-bookings-wp'),array(),
        __('Enter the your name or company name here.','get-bookings-wp'),
        __('Enter the your name or company name here.','get-bookings-wp')
);

$this->create_plugin_setting(
        'input',
        'messaging_send_from_email',
        __('Send From Email','get-bookings-wp'),array(),
        __('Enter the email address to be used when sending emails.','get-bookings-wp'),
        __('Enter the email address to be used when sending emails.','get-bookings-wp')
);

$this->create_plugin_setting(
	'select',
	'getbwp_smtp_mailing_mailer',
	__('Mailer:','get-bookings-wp'),
	array(
		'mail' => __('Use the PHP mail() function to send emails','get-bookings-wp'),
		'smtp' => __('Send all Get Bookings WP emails via SMTP','get-bookings-wp'), 
		'mandrill' => __('Send all Get Bookings WP emails via Mandrill','get-bookings-wp'),
		'third-party' => __('Send all Get Bookings WP emails via Third-party plugin','get-bookings-wp'), 
		
		),
		
	__('Specify which mailer method Get Bookings WP should use when sending emails.','get-bookings-wp'),
  __('Specify which mailer method Get Bookings WP should use when sending emails.','get-bookings-wp')
       );
	   
$this->create_plugin_setting(
                'checkbox',
                'getbwp_smtp_mailing_return_path',
                __('Return Path','get-bookings-wp'),
                '1',
                __('Set the return-path to match the From Email','get-bookings-wp'),
                __('Set the return-path to match the From Email','get-bookings-wp')
        ); 
?>
 </table>

 
 </div>
 
 <h3><?php _e('SMTP Settings','get-bookings-wp'); ?></h3>
 
 <div class="getbwp-sect  ">
  <p> <strong><?php _e('This options should be set only if you have chosen to send email via SMTP','get-bookings-wp'); ?></strong></p>
 
  <table class="form-table">
 <?php
$this->create_plugin_setting(
        'input',
        'getbwp_smtp_mailing_host',
        __('SMTP Host:','get-bookings-wp'),array(),
        __('Specify host name or ip address.','get-bookings-wp'),
        __('Specify host name or ip address.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'input',
        'getbwp_smtp_mailing_port',
        __('SMTP Port:','get-bookings-wp'),array(),
        __('Specify Port.','get-bookings-wp'),
        __('Specify Port.','get-bookings-wp')
); 


$this->create_plugin_setting(
	'select',
	'getbwp_smtp_mailing_encrytion',
	__('Encryption:','get-bookings-wp'),
	array(
		'none' => __('No encryption','get-bookings-wp'),
		'ssl' => __('Use SSL encryption','get-bookings-wp'), 
		'tls' => __('Use TLS encryption','get-bookings-wp'), 
		
		),
		
	__('Specify the encryption method.','get-bookings-wp'),
  __('Specify the encryption method.','get-bookings-wp')
       );
	   
$this->create_plugin_setting(
	'select',
	'getbwp_smtp_mailing_authentication',
	__('Authentication:','get-bookings-wp'),
	array(
		'false' => __('No. Do not use SMTP authentication','get-bookings-wp'),
		'true' => __('Yes. Use SMTP Authentication','get-bookings-wp'), 
		
		),
		
	__('Specify the authentication method.','get-bookings-wp'),
  __('Specify the authentication method.','get-bookings-wp')
       );

$this->create_plugin_setting(
        'input',
        'getbwp_smtp_mailing_username',
        __('Username:','get-bookings-wp'),array(),
        __('Specify Username.','get-bookings-wp'),
        __('Specify Username.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'input',
        'getbwp_smtp_mailing_password',
        __('Password:','get-bookings-wp'),array(),
        __('Input Password.','get-bookings-wp'),
        __('Input Password.','get-bookings-wp')
); 


 ?>
 
 </table>
 
 <?php if(isset($getbwpcomplement))
{?>
 <p><strong><?php _e('This options should be set only if you have chosen to send email via Mandrill','get-bookings-wp'); ?></strong></p>

</div>

<div class="getbwp-sect  ">
  <table class="form-table">
 <?php
$this->create_plugin_setting(
        'input',
        'getbwp_mandrill_api_key',
        __('Mandrill API Key:','xoousers'),array(),
        __('Specify Mandrill API. Find out more info here: https://mandrillapp.com/api/docs/','get-bookings-wp'),
        __('Specify Mandrill API.','get-bookings-wp')
); 

?>
 
 </table>
</div>

<?php }?>
<div class="getbwp-sect getbwp-sect-border  ">
  <h3><?php _e('Admin Message New Booking','get-bookings-wp'); ?> <span class="getbwp-main-close-open-tab"><a href="#" title="<?php _e('Close','get-bookings-wp'); ?>" class="getbwp-widget-home-colapsable" widget-id="1"><i class="fa fa-sort-desc" id="getbwp-close-open-icon-1"></i></a></span></h3>
  
  <p><?php _e('This is the welcome email that is sent to the admin when a new booking is generated.','get-bookings-wp'); ?></p>
<div class="getbwp-sect bp-messaging-hidden" id="getbwp-main-cont-home-1">  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_new_booking_subject_admin',
        __('Subject:','get-bookings-wp'),array(),
        __('Set Email Subject.','get-bookings-wp'),
        __('Set Email Subject.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_new_booking_admin',
        __('Message','get-bookings-wp'),array(),
        __('Set Email Message here.','get-bookings-wp'),
        __('Set Email Message here.','get-bookings-wp')
);


?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','get-bookings-wp'); ?>" class="getbwp_restore_template button" b-template-id='email_new_booking_admin'></td>

</tr>	

</table> 

</div>

</div>

<div class="getbwp-sect  getbwp-sect-border">
  <h3><?php _e('Staff Message New Booking','get-bookings-wp'); ?><span class="getbwp-main-close-open-tab"><a href="#" title="<?php _e('Close','get-bookings-wp'); ?>" class="getbwp-widget-home-colapsable" widget-id="2"><i class="fa fa-sort-desc" id="getbwp-close-open-icon-2"></i></a></span></h3>
  
  <p><?php _e('This is the welcome email that is sent to the staff member when a new booking is generated.','get-bookings-wp'); ?></p>
<div class="getbwp-sect bp-messaging-hidden" id="getbwp-main-cont-home-2">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_new_booking_subject_staff',
        __('Subject:','get-bookings-wp'),array(),
        __('Set Email Subject.','get-bookings-wp'),
        __('Set Email Subject.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_new_booking_staff',
        __('Message','get-bookings-wp'),array(),
        __('Set Email Message here.','get-bookings-wp'),
        __('Set Email Message here.','get-bookings-wp')
);

	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','get-bookings-wp'); ?>" class="getbwp_restore_template button" b-template-id='email_new_booking_staff'></td>

</tr>	
</table> 
</div>

</div>


<div class="getbwp-sect  getbwp-sect-border">
  <h3><?php _e('Client Message New Booking','get-bookings-wp'); ?> <span class="getbwp-main-close-open-tab"><a href="#" title="<?php _e('Close','get-bookings-wp'); ?>" class="getbwp-widget-home-colapsable" widget-id="3"><i class="fa fa-sort-desc" id="getbwp-close-open-icon-3"></i></a></span></h3>
  
  <p><?php _e('This is the welcome email that is sent to the client when a new booking is generated.','get-bookings-wp'); ?></p>
<div class="getbwp-sect bp-messaging-hidden" id="getbwp-main-cont-home-3">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_new_booking_subject_client',
        __('Subject:','get-bookings-wp'),array(),
        __('Set Email Subject.','get-bookings-wp'),
        __('Set Email Subject.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_new_booking_client',
        __('Message','get-bookings-wp'),array(),
        __('Set Email Message here.','get-bookings-wp'),
        __('Set Email Message here.','get-bookings-wp')
);



	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','get-bookings-wp'); ?>" class="getbwp_restore_template button" b-template-id='email_new_booking_client'></td>

</tr>	
</table> 
</div>
</div>

<div class="getbwp-sect  getbwp-sect-border">
  <h3><?php _e('Reschedule Message For Clients','get-bookings-wp'); ?> <span class="getbwp-main-close-open-tab"><a href="#" title="<?php _e('Close','get-bookings-wp'); ?>" class="getbwp-widget-home-colapsable" widget-id="4"><i class="fa fa-sort-desc" id="getbwp-close-open-icon-4"></i></a></span></h3>
  
  <p><?php _e('This message is sent to the CLIENT when an appointment is rescheduled.','get-bookings-wp'); ?></p>
 <div class="getbwp-sect bp-messaging-hidden" id="getbwp-main-cont-home-4">  
 
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_reschedule_subject',
        __('Subject:','get-bookings-wp'),array(),
        __('Set Email Subject.','get-bookings-wp'),
        __('Set Email Subject.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_reschedule',
        __('Message','get-bookings-wp'),array(),
        __('Set Email Message here.','get-bookings-wp'),
        __('Set Email Message here.','get-bookings-wp')
);



	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','get-bookings-wp'); ?>" class="getbwp_restore_template button" b-template-id='email_reschedule'></td>

</tr>	
</table> 
</div>

</div>

<div class="getbwp-sect  getbwp-sect-border">
  <h3><?php _e('Reschedule Message For Staff Member','get-bookings-wp'); ?><span class="getbwp-main-close-open-tab"><a href="#" title="<?php _e('Close','get-bookings-wp'); ?>" class="getbwp-widget-home-colapsable" widget-id="5"><i class="fa fa-sort-desc" id="getbwp-close-open-icon-5"></i></a></span></h3>
  
  <p><?php _e('This message is sent to the STAFF MEMBER when an appointment is rescheduled.','get-bookings-wp'); ?></p>
<div class="getbwp-sect bp-messaging-hidden" id="getbwp-main-cont-home-5">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_reschedule_subject_staff',
        __('Subject:','get-bookings-wp'),array(),
        __('Set Email Subject.','get-bookings-wp'),
        __('Set Email Subject.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_reschedule_staff',
        __('Message','get-bookings-wp'),array(),
        __('Set Email Message here.','get-bookings-wp'),
        __('Set Email Message here.','get-bookings-wp')
);



	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','get-bookings-wp'); ?>" class="getbwp_restore_template button" b-template-id='email_reschedule_staff'></td>

</tr>	
</table> 
</div>

</div>


<div class="getbwp-sect  getbwp-sect-border">
  <h3><?php _e('Reschedule Message For The Admin','get-bookings-wp'); ?> <span class="getbwp-main-close-open-tab"><a href="#" title="<?php _e('Close','get-bookings-wp'); ?>" class="getbwp-widget-home-colapsable" widget-id="6"><i class="fa fa-sort-desc" id="getbwp-close-open-icon-6"></i></a></span></h3>
  
  <p><?php _e('This message is sent to the ADMIN when an appointment is rescheduled.','get-bookings-wp'); ?></p>
<div class="getbwp-sect bp-messaging-hidden" id="getbwp-main-cont-home-6">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_reschedule_subject_admin',
        __('Subject:','get-bookings-wp'),array(),
        __('Set Email Subject.','get-bookings-wp'),
        __('Set Email Subject.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_reschedule_admin',
        __('Message','get-bookings-wp'),array(),
        __('Set Email Message here.','get-bookings-wp'),
        __('Set Email Message here.','get-bookings-wp')
);



	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','get-bookings-wp'); ?>" class="getbwp_restore_template button" b-template-id='email_reschedule_admin'></td>

</tr>	
</table> 
</div>

</div>



<div class="getbwp-sect  getbwp-sect-border">
  <h3><?php _e('Bank Payment Message For the Client','get-bookings-wp'); ?> <span class="getbwp-main-close-open-tab"><a href="#" title="<?php _e('Close','get-bookings-wp'); ?>" class="getbwp-widget-home-colapsable" widget-id="7"><i class="fa fa-sort-desc" id="getbwp-close-open-icon-7"></i></a></span></h3>
  
  <p><?php _e('This message will be sent to the client when the selected payment method is bank.','get-bookings-wp'); ?></p>
<div class="getbwp-sect bp-messaging-hidden" id="getbwp-main-cont-home-7">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_bank_payment_subject',
        __('Subject:','get-bookings-wp'),array(),
        __('Set Email Subject.','get-bookings-wp'),
        __('Set Email Subject.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_bank_payment',
        __('Message','get-bookings-wp'),array(),
        __('Set Email Message here.','get-bookings-wp'),
        __('Set Email Message here.','get-bookings-wp')
);



	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','get-bookings-wp'); ?>" class="getbwp_restore_template button" b-template-id='email_bank_payment'></td>

</tr>	
</table> 
</div>

</div>


<div class="getbwp-sect getbwp-sect-border ">
  <h3><?php _e('Bank Payment Message For the Admin','get-bookings-wp'); ?><span class="getbwp-main-close-open-tab"><a href="#" title="<?php _e('Close','get-bookings-wp'); ?>" class="getbwp-widget-home-colapsable" widget-id="8"><i class="fa fa-sort-desc" id="getbwp-close-open-icon-8"></i></a></span></h3>
  
  <p><?php _e('This message will be sent to the admin when the selected payment method is bank.','get-bookings-wp'); ?></p>
<div class="getbwp-sect bp-messaging-hidden" id="getbwp-main-cont-home-8">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_bank_payment_admin_subject',
        __('Subject:','get-bookings-wp'),array(),
        __('Set Email Subject.','get-bookings-wp'),
        __('Set Email Subject.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_bank_payment_admin',
        __('Message','get-bookings-wp'),array(),
        __('Set Email Message here.','get-bookings-wp'),
        __('Set Email Message here.','get-bookings-wp')
);



	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','get-bookings-wp'); ?>" class="getbwp_restore_template button" b-template-id='email_bank_payment_admin'></td>

</tr>	
</table> 
</div>

</div>

<div class="getbwp-sect  getbwp-sect-border">
  <h3><?php _e('Bank Payment Message For the Staff','get-bookings-wp'); ?><span class="getbwp-main-close-open-tab"><a href="#" title="<?php _e('Close','get-bookings-wp'); ?>" class="getbwp-widget-home-colapsable" widget-id="88"><i class="fa fa-sort-desc" id="getbwp-close-open-icon-88"></i></a></span></h3>
  
  <p><?php _e('This message will be sent to the staff member when the selected payment method is bank.','get-bookings-wp'); ?></p>
<div class="getbwp-sect bp-messaging-hidden" id="getbwp-main-cont-home-88">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_bank_payment_staff_subject',
        __('Subject:','get-bookings-wp'),array(),
        __('Set Email Subject.','get-bookings-wp'),
        __('Set Email Subject.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_bank_payment_staff',
        __('Message','get-bookings-wp'),array(),
        __('Set Email Message here.','get-bookings-wp'),
        __('Set Email Message here.','get-bookings-wp')
);



	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','get-bookings-wp'); ?>" class="getbwp_restore_template button" b-template-id='email_bank_payment_staff'></td>

</tr>	
</table> 
</div>

</div>


<div class="getbwp-sect  getbwp-sect-border">
  <h3><?php _e('Appointment Status Changed Admin Email','get-bookings-wp'); ?><span class="getbwp-main-close-open-tab"><a href="#" title="<?php _e('Close','get-bookings-wp'); ?>" class="getbwp-widget-home-colapsable" widget-id="9"><i class="fa fa-sort-desc" id="getbwp-close-open-icon-9"></i></a></span></h3>
  
  <p><?php _e('This message will be sent to the admin when status of an appointment changes.','get-bookings-wp'); ?></p>
<div class="getbwp-sect bp-messaging-hidden" id="getbwp-main-cont-home-9">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_appo_status_changed_admin_subject',
        __('Subject:','get-bookings-wp'),array(),
        __('Set Email Subject.','get-bookings-wp'),
        __('Set Email Subject.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_appo_status_changed_admin',
        __('Message','get-bookings-wp'),array(),
        __('Set Email Message here.','get-bookings-wp'),
        __('Set Email Message here.','get-bookings-wp')
);



	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','get-bookings-wp'); ?>" class="getbwp_restore_template button" b-template-id='email_appo_status_changed_admin'></td>

</tr>	
</table> 
</div>

</div>

<div class="getbwp-sect  getbwp-sect-border">
  <h3><?php _e('Appointment Status Changed Staff Email','get-bookings-wp'); ?> <span class="getbwp-main-close-open-tab"><a href="#" title="<?php _e('Close','get-bookings-wp'); ?>" class="getbwp-widget-home-colapsable" widget-id="10"><i class="fa fa-sort-desc" id="getbwp-close-open-icon-10"></i></a></span></h3>
  
  <p><?php _e('This message will be sent to the staff member when status of an appointment changes.','get-bookings-wp'); ?></p>
 <div class="getbwp-sect bp-messaging-hidden" id="getbwp-main-cont-home-10">  
 
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_appo_status_changed_staff_subject',
        __('Subject:','get-bookings-wp'),array(),
        __('Set Email Subject.','get-bookings-wp'),
        __('Set Email Subject.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_appo_status_changed_staff',
        __('Message','get-bookings-wp'),array(),
        __('Set Email Message here.','get-bookings-wp'),
        __('Set Email Message here.','get-bookings-wp')
);



	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','get-bookings-wp'); ?>" class="getbwp_restore_template button" b-template-id='email_appo_status_changed_staff'></td>

</tr>	
</table> 
</div>

</div>

<div class="getbwp-sect getbwp-sect-border ">
  <h3><?php _e('Appointment Status Changed Client Email','get-bookings-wp'); ?><span class="getbwp-main-close-open-tab"><a href="#" title="<?php _e('Close','get-bookings-wp'); ?>" class="getbwp-widget-home-colapsable" widget-id="11"><i class="fa fa-sort-desc" id="getbwp-close-open-icon-11"></i></a></span></h3>
  
  <p><?php _e('This message will be sent to the client when status of an appointment changes.','get-bookings-wp'); ?></p>
<div class="getbwp-sect bp-messaging-hidden" id="getbwp-main-cont-home-11">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_appo_status_changed_client_subject',
        __('Subject:','get-bookings-wp'),array(),
        __('Set Email Subject.','get-bookings-wp'),
        __('Set Email Subject.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_appo_status_changed_client',
        __('Message','get-bookings-wp'),array(),
        __('Set Email Message here.','get-bookings-wp'),
        __('Set Email Message here.','get-bookings-wp')
);



	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','get-bookings-wp'); ?>" class="getbwp_restore_template button" b-template-id='email_appo_status_changed_client'></td>

</tr>	
</table> 
</div>
</div>

<?php if(isset($getbwpcomplement))
{?>
<div class="getbwp-sect  getbwp-sect-border">
  <h3><?php _e('Staff Password Change','get-bookings-wp'); ?> <span class="getbwp-main-close-open-tab"><a href="#" title="<?php _e('Close','get-bookings-wp'); ?>" class="getbwp-widget-home-colapsable" widget-id="12"><i class="fa fa-sort-desc" id="getbwp-close-open-icon-12"></i></a></span></h3>
  
  <p><?php _e('This message will be sent to the staff member every time the password is changed in the staff account.','get-bookings-wp'); ?></p>
<div class="getbwp-sect bp-messaging-hidden" id="getbwp-main-cont-home-12">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_password_change_staff_subject',
        __('Subject:','get-bookings-wp'),array(),
        __('Set Email Subject.','get-bookings-wp'),
        __('Set Email Subject.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_password_change_staff',
        __('Message','get-bookings-wp'),array(),
        __('Set Email Message here.','get-bookings-wp'),
        __('Set Email Message here.','get-bookings-wp')
);



	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','get-bookings-wp'); ?>" class="getbwp_restore_template button" b-template-id='email_password_change_staff'></td>

</tr>	
</table> 
</div>

</div>


<div class="getbwp-sect  getbwp-sect-border">
  <h3><?php _e('Password Reset Link','get-bookings-wp'); ?>  <span class="getbwp-main-close-open-tab"><a href="#" title="<?php _e('Close','get-bookings-wp'); ?>" class="getbwp-widget-home-colapsable" widget-id="13"><i class="fa fa-sort-desc" id="getbwp-close-open-icon-13"></i></a></span></h3>
  
  <p><?php _e('This message will be sent to the staff member every time the password is changed in the staff account.','get-bookings-wp'); ?></p>
<div class="getbwp-sect bp-messaging-hidden" id="getbwp-main-cont-home-13">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_reset_link_message_subject',
        __('Subject:','get-bookings-wp'),array(),
        __('Set Email Subject.','get-bookings-wp'),
        __('Set Email Subject.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_reset_link_message_body',
        __('Message','get-bookings-wp'),array(),
        __('Set Email Message here.','get-bookings-wp'),
        __('Set Email Message here.','get-bookings-wp')
);



	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','get-bookings-wp'); ?>" class="getbwp_restore_template button" b-template-id='email_password_change_staff'></td>

</tr>	
</table> 
</div>

</div>


<div class="getbwp-sect getbwp-sect-border ">
  <h3><?php _e('Welcome Email For Staff Members','get-bookings-wp'); ?>  <span class="getbwp-main-close-open-tab"><a href="#" title="<?php _e('Close','get-bookings-wp'); ?>" class="getbwp-widget-home-colapsable" widget-id="14"><i class="fa fa-sort-desc" id="getbwp-close-open-icon-14"></i></a></span></h3>
  
  <p><?php _e('This message will be sent to the staff member and it includes a welcome message along with a reset link, this will allow the staff members to manage their appointments','get-bookings-wp'); ?></p>
<div class="getbwp-sect bp-messaging-hidden" id="getbwp-main-cont-home-14">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_welcome_staff_link_message_subject',
        __('Subject:','get-bookings-wp'),array(),
        __('Set Email Subject.','get-bookings-wp'),
        __('Set Email Subject.','get-bookings-wp')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_welcome_staff_link_message_body',
        __('Message','get-bookings-wp'),array(),
        __('Set Email Message here.','get-bookings-wp'),
        __('Set Email Message here.','get-bookings-wp')
);
	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','get-bookings-wp'); ?>" class="getbwp_restore_template button" b-template-id='email_welcome_staff_link_message_body'></td>

</tr>	
</table> 
</div>

</div>



<?php }?>

<p class="submit">
	<input type="submit" name="mail_setting_submit" id="mail_setting_submit" class="button button-primary" value="<?php _e('Save Changes','get-bookings-wp'); ?>"  />

</p>

</form>