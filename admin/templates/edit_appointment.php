<?php
global $getbookingwp, $getbwpcomplement;

//get appointment			
$appointment = $getbookingwp->appointment->get_one($appointment_id);
$staff_id = $appointment->booking_staff_id;	
$client_id = $appointment->booking_user_id;	
$service_id = $appointment->booking_service_id;
$booking_time_from = $appointment->booking_time_from;

$client = $getbookingwp->userpanel->get_one($client_id);

$currency = $getbookingwp->get_option('currency_symbol');		
$time_format = $getbookingwp->service->get_time_format();		
$booking_time = date($time_format, strtotime($booking_time_from ))	;		
$booking_day = date('D, j F, Y', strtotime($booking_time_from));
?>



<div class="getbwp-adm-new-appointment getbwp-adm-schedule-info-bar">

	 <strong><?php _e('Created on : ','get-bookings-wp')?></strong> <?php echo esc_attr(date('m/d/Y', strtotime($appointment->booking_date)));?> | <strong><?php _e('Appointment Date: ','get-bookings-wp');?></strong> <?php echo esc_attr($booking_day);?> <?php _e('at ','get-bookings-wp');?> <?php echo esc_attr($booking_time);?> | <strong><?php _e('Client: ','get-bookings-wp');?></strong>	<?php echo esc_attr($client->ID);?>, <?php echo esc_attr($client->display_name);?> (<?php echo esc_attr($client->user_email);?>)	           
             
</div>


<div class="getbwp-adm-new-appointment">	

    <div class="getbwp-adm-frm-blocks" >               
                   
        <div class="field-header"><?php _e('Select Service','get-bookings-wp')?></div>                   
        <?php 
        
        echo wp_kses($getbookingwp->service->get_categories_drop_down_admin($service_id), $getbookingwp->allowed_html);
        ?>                            
               
   </div>
   
    <div class="getbwp-adm-frm-blocks" >
            
        <div class="field-header"><?php _e('On or After','get-bookings-wp')?> </div> 
        <input type="text" class="bupro-datepicker" id="getbwp-start-date" value="<?php echo esc_attr(date($getbookingwp->get_date_picker_date(), strtotime($appointment->booking_time_from)))?>" />         
           
    </div>
        
     <div class="getbwp-adm-frm-blocks" id="getbwp-staff-booking-list" >
            
              <div class="field-header"><?php _e('With','get-bookings-wp')?>  </div> 
           
              <?php
              echo wp_kses($getbookingwp->userpanel->get_staff_list_front(), $getbookingwp->allowed_html);
              
              ?>          
     </div>  
     
      
           


</div>

<div class="getbwp-adm-bar-opt-edit">

<?php $app_status = $getbookingwp->appointment->get_status_legend($appointment->booking_status);?>

<p><strong><?php _e('Status','get-bookings-wp')?></strong>: <span id="getbwp-app-status"> <?php echo  wp_kses($app_status, $getbookingwp->allowed_html)?> </span> <span> <a href="#" id="getbwp-adm-update-appoint-status-btn" appointment-id="<?php echo esc_attr($appointment_id)?>" title="<?php _e('Change Status','get-bookings-wp')?>"><i class="fa fa-refresh"></i></a></span>  <p>

</div>
<div class="getbwp-adm-check-av-button"  > 
         
      <input type="checkbox" id="getbwp_re_schedule" name="getbwp_re_schedule" value="1"> <?php _e('Reschedule Appointment','get-bookings-wp')?>
         
</div>

 <div class="getbwp-adm-check-av-button"  id="getbwp-availability-box-btn" style="display:none"> 
         
       <button id="getbwp-adm-check-avail-btn-edit" class="getbwp-button-submit"><?php _e('Check Availability','get-bookings-wp')?></button>
         
</div> 

<div class="getbwp-adm-new-appointment" id="getbwp-availability-box" style="display:none">
<input type="hidden" id="getbwp_time_slot" value="">
<input type="hidden" id="getbwp_booking_date" value="">
<input type="hidden" id="getbwp_client_id" value="">
<input type="hidden" id="getbwp_service_staff" value="">
<input type="hidden" id="getbwp_custom_form" value="">
<input type="hidden" id="getbwp_appointment_id" value="<?php echo esc_attr($appointment_id);?>">

<h3><?php _e('Availability','get-bookings-wp')?> </h3>
    
    <div class="getbwp-adm-availa-box" id="getbwp-steps-cont-res-edit" >  
    
    <p> <?php _e('Please click on the Check Availability to display the available time slots.','get-bookings-wp')?> </p>      
                
               
               
    </div> 
    
     <div class="getbwp-adm-check-av-button-d"  > 
         
      <input type="checkbox" id="getbwp_notify_client_reschedule" name="getbwp_notify_client_reschedule" value="1" checked="checked"> <?php _e('Send Notification To Client','get-bookings-wp')?>
         
</div>
    
<div class="getbwp-adm-check-av-button-d"  id="getbwp-availability-box-btn"> 
         
       <button id="getbwp-adm-confirm-reschedule-btn" class="getbwp-button-submit-changes"><?php _e('Confirm Reschedule ','get-bookings-wp')?></button>
         
</div> 
    
    


</div>

<div class="getbwp-adm-new-appointment">

	<div class="getbwp-adm-extrainfo-box" id="getbwp-additioninfo-cont-res" >         
                
               <?php echo wp_kses($getbookingwp->appointment->get_appointment_edition_form_fields($appointment_id), $getbookingwp->allowed_html) ;?>
               
    </div>
    
    <div class="getbwp-adm-check-av-button"  id="getbwp-addpayment-box-btn" > 
         
       	<button id="getbwp-adm-update-info" class="getbwp-button-submit-changes"><?php _e('Update Info','get-bookings-wp')?></button>
         
		</div>
</div>

<?php if(isset($getbwpcomplement)){
	
	echo wp_kses($getbwpcomplement->payment->get_payments_module(), $getbookingwp->allowed_html) ;
	echo wp_kses($getbwpcomplement->note->get_admin_module(), $getbookingwp->allowed_html);
	?>


<?php }?> 



 <div class="getbwp-adm-check-av-button"  > 
         
      <input type="checkbox" id="getbwp_notify_client" name="getbwp_notify_client" value="1"> <?php _e('Send Notification To Client','get-bookings-wp')?>
         
</div>