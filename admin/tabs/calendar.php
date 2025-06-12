<?php
global $getbookingwp, $getbwpcomplement, $getbwpultimate, $getbwp_filter, $wp_locale;

$how_many_upcoming_app = 5;


$currency_symbol =  $getbookingwp->get_option('paid_membership_symbol');
$date_format =  $getbookingwp->get_int_date_format();
$time_format =  $getbookingwp->service->get_time_format();

//today
$today = $getbookingwp->appointment->get_appointments_planing_total('today');
$tomorrow = $getbookingwp->appointment->get_appointments_planing_total('tomorrow');
$week = $getbookingwp->appointment->get_appointments_planing_total('week');

$pending = $getbookingwp->appointment->get_appointments_total_by_status(0);
$cancelled = $getbookingwp->appointment->get_appointments_total_by_status(2);
$noshow = $getbookingwp->appointment->get_appointments_total_by_status(3);
$unpaid = $getbookingwp->order->get_orders_by_status('pending');


$va = get_option('getbwp_c_key');
				
if($va==''  && isset($getbwpultimate)){					
	$this->display_ultimate_validate_copy();
}

$upcoming_appointments = $getbookingwp->appointment->get_upcoming_appointments($how_many_upcoming_app);



?>

  

<div class="getbwp-welcome-panel">



<?php if(!isset($getbwpcomplement)){?>
<p class="getbwp-extra-features"><?php _e('Do you need more features or manage multiple locations, google calendar integration, SMS reminders, change legends & colors?','get-bookings-wp')?> <a href="https://getbookingswp.com/pricing-compare-plans" target="_blank">Click here</a> to see higher versions.</p>

<?php }?>
        <div class="getbwp-sect getbwp-welcome-panel">       
        
        
        	<div id="full_calendar_wrapper">     
            
            <?php if(isset($getbwpcomplement) ){?>
            
                <div class="getbwp-calendar-filters">

                     
                <?php if(isset($getbwpultimate) ){?>

                       <?php echo wp_kses($getbwp_filter->get_all_calendar_filter(), $getbookingwp->allowed_html) ;?>     
                       
                <?php }?>  
                       <?php echo wp_kses($getbookingwp->userpanel->get_staff_list_calendar_filter(), $getbookingwp->allowed_html)  ;?> 
                       <button name="getbwp-btn-calendar-filter" id="getbwp-btn-calendar-filter" class="getbwp-button-submit-changes"><?php _e('Filter','get-bookings-wp')?>	</button>
                </div>  
            
            <?php }?>    
                
             <?php if(isset($getbwpcomplement) ){?>
            
                <div class="getbwp-calendar-staff-bar-filter">
                
                                
                       <?php echo wp_kses($getbookingwp->userpanel->get_staff_list_calendar_bar(), $getbookingwp->allowed_html)  ;?> 
                </div>  
            
             <?php }?>    
                
            	
                <div class="table-responsive">
                    

                        <div class="ab-loading-inner" style="display: none">
                            <span class="ab-loader"></span>
                        </div>
                        <div class="getbwp-calendar-element"></div>
                </div>  
                
            </div> 
        
        </div>
        
     <div id="getbwp-appointment-new-box" title="<?php _e('Create New Appointment','get-bookings-wp')?>"></div>
     <div id="getbwp-appointment-edit-box" title="<?php _e('Edit Appointment','get-bookings-wp')?>"></div>     
     <div id="getbwp-new-app-conf-message" title="<?php _e('Appointment Created','get-bookings-wp')?>"></div> 
     <div id="getbwp-new-payment-cont" title="<?php _e('Add Payment','get-bookings-wp')?>"></div>
     <div id="getbwp-confirmation-cont" title="<?php _e('Confirmation','get-bookings-wp')?>"></div>
     <div id="getbwp-new-note-cont" title="<?php _e('Add Note','get-bookings-wp')?>"></div>     
     <div id="getbwp-appointment-list" title="<?php _e('Pending Appointments','get-bookings-wp')?>"></div>
     
     <div id="getbwp-client-new-box" title="<?php _e('Create New Client','get-bookings-wp')?>"></div>
     <div id="getbwp-appointment-change-status" title="<?php _e('Appointment Status','get-bookings-wp')?>"></div> 
     
       
    
    <div id="getbwp-spinner" class="getbwp-spinner" style="display:">
            <span> <img src="<?php echo esc_url(getbookingpro_url.'admin/images/loaderB16.gif')?>" width="16" height="16" /></span>&nbsp; <?php  _e('Please wait ...','get-bookings-wp')?>
	</div>
    
    
    <script type="text/javascript">
	
			var err_message_payment_date ="<?php _e('Please select a payment date.','get-bookings-wp'); ?>";
			var err_message_payment_amount="<?php _e('Please input an amount','get-bookings-wp'); ?>"; 
			var err_message_payment_delete="<?php _e('Are you totally sure that you want to delete this payment?','get-bookings-wp'); ?>"; 
			
			var err_message_note_title ="<?php _e('Please input a title.','get-bookings-wp'); ?>";
			var err_message_note_text="<?php _e('Please input some text','get-bookings-wp'); ?>";
			var err_message_note_delete="<?php _e('Are you totally sure that you want to delete this note?','get-bookings-wp'); ?>"; 
			
			var gen_message_rescheduled_conf="<?php _e('The appointment has been rescheduled.','get-bookings-wp'); ?>"; 
			var gen_message_infoupdate_conf="<?php _e('The information has been updated.','get-bookings-wp'); ?>"; 
	
		     var err_message_start_date ="<?php _e('Please select a date.','get-bookings-wp'); ?>";
			 var err_message_service ="<?php _e('Please select a service.','get-bookings-wp'); ?>"; 
		     var err_message_time_slot ="<?php _e('Please select a time.','get-bookings-wp'); ?>";
			 var err_message_client ="<?php _e('Please select a client.','get-bookings-wp'); ?>";
			 var message_wait_availability ='<img src="<?php echo esc_url(getbookingpro_url).'admin/images/loaderB16.gif'?>" width="16" height="16" /></span>&nbsp; <?php _e("Please wait ...","get-bookings-wp")?>'; 
			  
		
	</script>
    
    <?php

$sales_val= $getbookingwp->appointment->get_graph_total_monthly();
$months_array = array_values( $wp_locale->month );
$current_month = date("m");
$current_month_legend = $months_array[$current_month -1];

?>    
