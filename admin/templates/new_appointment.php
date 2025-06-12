<?php
global $getbookingwp;
?>
<div class="getbwp-adm-new-appointment">	

    <div class="getbwp-adm-frm-blocks" >               
                   
        <div class="field-header"><?php _e('Select Service','get-bookings-wp')?></div>                   
        <?php echo wp_kses($getbookingwp->service->get_categories_drop_down_public(), $getbookingwp->allowed_html);?>                            
               
    </div>
   
    <div class="getbwp-adm-frm-blocks" >
            
        <div class="field-header"><?php _e('On or After','get-bookings-wp')?> </div> 
        <input type="text" class="bupro-datepicker" id="getbwp-start-date" value="<?php echo esc_attr(date( $getbookingwp->get_date_picker_date(), current_time( 'timestamp', 0 ) ))?>" />         
           
    </div>
        
     <div class="getbwp-adm-frm-blocks" id="getbwp-staff-booking-list" >
            
              <div class="field-header"><?php _e('With','get-bookings-wp')?>  </div> 
           
              <?php echo wp_kses($getbookingwp->userpanel->get_staff_list_front(), $getbookingwp->allowed_html)    ;?>          
     </div>  
     
      
           


</div>

<div class="getbwp-adm-new-appointment">

			<div class="field-header"><?php _e('Client','get-bookings-wp')?>  </div> 
           
              <input type="text" class="bupro-client-selector" id="bupclientsel" name="bupclientsel" placeholder="<?php _e('Input Name or Email Address','get-bookings-wp')?>" />
              
              <span class="getbwp-add-client-m"><a href="#" id="getbwp-btn-client-new-admin" title="Add New Client"><i class="fa fa-plus"></i></a></span> 

</div>

<div class="getbwp-adm-check-av-button"  > 

</div>

 <div class="getbwp-adm-check-av-button"  > 
         
       <button id="getbwp-adm-check-avail-btn" class="getbwp-button-submit"><?php _e('Check Availability','get-bookings-wp')?></button>
         
</div>   

<div class="getbwp-adm-new-appointment">
<input type="hidden" id="getbwp_time_slot" value="">
<input type="hidden" id="getbwp_booking_date" value="">
<input type="hidden" id="getbwp_client_id" value="">
<input type="hidden" id="getbwp_service_staff" value="">

<h3><?php _e('Availability','get-bookings-wp')?> </h3>
    
    <div class="getbwp-adm-availa-box" id="getbwp-steps-cont-res" >         
                
               
               
    </div>


</div>

 <div class="getbwp-adm-check-av-button"  > 
         
      <input type="checkbox" id="getbwp_notify_client" checked="checked" name="getbwp_notify_client" value="1"> <?php _e('Send Notification To Client','get-bookings-wp')?>
         
</div>