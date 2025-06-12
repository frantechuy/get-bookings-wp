<?php
global $getbookingwp , $getbwp_filter, $getbwpultimate, $getbwpcomplement;
$currency_symbol =  $getbookingwp->get_option('paid_membership_symbol');
$date_format =  $getbookingwp->get_int_date_format();
$time_format =  $getbookingwp->service->get_time_format();

$appointments = $getbookingwp->appointment->get_all();

$pending = $getbookingwp->appointment->get_appointments_total_by_status(0);
$cancelled = $getbookingwp->appointment->get_appointments_total_by_status(2);
$noshow = $getbookingwp->appointment->get_appointments_total_by_status(3);
$unpaid = $getbookingwp->order->get_orders_by_status('pending');
$allappo = $getbookingwp->appointment->get_appointments_planing_total('all');


$howmany = "";
$year = "";
$month = "";
$day = "";
$special_filter = "";
$getbwp_staff_calendar = "";

if(isset($_GET["howmany"])){
	$howmany = sanitize_text_field($_GET["howmany"]);		
}

if(isset($_GET["month"])){
	$month =sanitize_text_field( $_GET["month"]);		
}

if(isset($_GET["day"])){
	$day = sanitize_text_field($_GET["day"]);		
}

if(isset($_GET["year"])){
	$year = sanitize_text_field($_GET["year"]);	
}

if(isset($_GET["special_filter"])){
	$special_filter = sanitize_text_field($_GET["special_filter"]);		
}
if(isset($_GET["getbwp-staff-calendar"]))
{
	$getbwp_staff_calendar = sanitize_text_field($_GET["getbwp-staff-calendar"]);		
}


		
?>

        
       <div class="getbwp-sect getbwp-welcome-panel">
        
        <h3 class="appointment"><?php _e('Appointments','get-bookings-wp'); ?>(<?php echo esc_attr($getbookingwp->appointment->total_result);?>)</h3>
        
        
        <span class="getbwp-add-appo"><a href="#" id="getbwp-create-new-app" title="<?php _e('Add New Appointment ','get-bookings-wp'); ?>"><i class="fa fa-plus"></i></a></span>
        
       
       
        <form action="" method="get">
         <input type="hidden" name="page" value="getbookingswp" />
          <input type="hidden" name="tab" value="appointments" />
          <?php wp_nonce_field('getbwp-action', 'getbwp_nonce' ); ?>

        
        <div class="getbwp-ultra-success getbwp-notification"><?php _e('Success ','get-bookings-wp'); ?></div>
        
        
         <div class="getbwp-appointments-module-stats">
         
         	<ul>
            
             <li class="pending"><h3><?php _e('Pending','get-bookings-wp')?></h3><p class="totalstats"><?php echo esc_attr($pending) ?></p></li>
                <li class="cancelled"><h3><?php _e('Cancelled','get-bookings-wp')?></h3><p class="totalstats"><?php echo esc_attr($cancelled) ?></p></li>
                
                <li class="noshow"><h3><?php _e('No-Show','get-bookings-wp')?></h3><p class="totalstats"><?php echo esc_attr($noshow) ?></p> </li>
                
                <li class="total"><h3><?php _e('Total','get-bookings-wp')?></h3><p class="totalstats"><?php echo esc_attr($allappo) ?></p></li>
            
            </ul>
         
         
         </div>
         
         <div class="getbwp-appointments-module-filters">
         
              <select name="month" id="month">
               <option value="" selected="selected"><?php _e('All Months','get-bookings-wp'); ?></option>
               <?php
			  
			  $i = 1;
              
			  while($i <=12){
			  ?>
               <option value="<?php echo esc_attr($i)?>"  <?php if($i==$month) echo esc_attr('selected="selected"');?>><?php echo esc_attr($i)?></option>
               <?php 
			    $i++;
			   }?>
             </select>
             
             <select name="day" id="day">
               <option value="" selected="selected"><?php _e('All Days','get-bookings-wp'); ?></option>
               <?php
			  
			  $i = 1;
              
			  while($i <=31){
			  ?>
               <option value="<?php echo esc_attr($i)?>"  <?php if($i==$day) echo esc_attr('selected="selected"');?>><?php echo esc_attr($i)?></option>
               <?php 
			    $i++;
			   }?>
             </select>
             
             <select name="year" id="year">
               <option value="" selected="selected"><?php _e('All Years','get-bookings-wp'); ?></option>
               <?php
			  
			  $i =  date("Y")-3;
              $curent_year = date("Y");
              
			  while($i <=$curent_year+2){
			  ?>
               <option value="<?php echo esc_attr($i)?>" <?php if($i==$year) echo esc_attr('selected="selected"');?> ><?php echo esc_attr($i)?></option>
               <?php 
			    $i++;
			   }?>
             </select>
                
                        <?php if(isset($getbwpcomplement) && isset($getbwpultimate)){?>
            <select name="special_filter" id="special_filter">
               <option value="" selected="selected"><?php _e('All Locations','get-bookings-wp'); ?></option>
               <?php
			  
			  $filters = $getbwp_filter->get_all();
              
			 foreach ( $filters as $filter )
				{
			  ?>
               <option value="<?php echo esc_attr($filter->filter_id)?>" <?php if($special_filter==$filter->filter_id) echo esc_attr ('selected="selected"');?> ><?php echo esc_attr($filter->filter_name)?></option>
               <?php 
			    $i++;
			   }?>
             </select>
             
            <?php  }?>        
                       <?php echo wp_kses($getbookingwp->userpanel->get_staff_list_calendar_filter(), $getbookingwp->allowed_html);?> 
                       
                       <select name="howmany" id="howmany">
               <option value="20" <?php if(20==$howmany ||$howmany =="" ) echo esc_attr('selected="selected"');?>>20 <?php _e('Per Page','get-bookings-wp'); ?></option>
                <option value="40" <?php if(40==$howmany ) echo esc_attr('selected="selected"');?>>40 <?php _e('Per Page','get-bookings-wp'); ?></option>
                 <option value="50" <?php if(50==$howmany ) echo esc_attr('selected="selected"');?>>50 <?php _e('Per Page','get-bookings-wp'); ?></option>
                  <option value="80" <?php if(80==$howmany ) echo esc_attr('selected="selected"');?>>80 <?php _e('Per Page','get-bookings-wp'); ?></option>
                   <option value="100" <?php if(100==$howmany ) echo esc_attr('selected="selected"');?>>100 <?php _e('Per Page','get-bookings-wp'); ?></option>
                   
                   <option value="150" <?php if(150==$howmany ) echo esc_attr('selected="selected"');?>>150 <?php _e('Per Page','get-bookings-wp'); ?></option>
                   
                    <option value="200" <?php if(200==$howmany ) echo esc_attr('selected="selected"');?>>200 <?php _e('Per Page','get-bookings-wp'); ?></option>
                    <option value="250" <?php if(250==$howmany ) echo esc_attr('selected="selected"');?>>250 <?php _e('Per Page','get-bookings-wp'); ?></option>
                    
                    <option value="300" <?php if(300==$howmany ) echo esc_attr('selected="selected"');?>>300 <?php _e('Per Page','get-bookings-wp'); ?></option>
               
          </select>
          
                       <button name="getbwp-btn-calendar-filter-appo" id="getbwp-btn-calendar-filter-appo" class="getbwp-button-submit-changes"><?php _e('Filter','get-bookings-wp')?>	</button>
                </div>  
                
                
            
        
        
         </form>
         
                 
         
         </div>
         
         
         <div class="getbwp-sect getbwp-welcome-panel">
        
         <?php
			
			
				
				if (!empty($appointments)){
				
				
				?>
       
           <table width="100%" class="wp-list-table widefat fixed posts table-generic">
            <thead>
                <tr>
                    <th width="4%"><?php _e('#', 'get-bookings-wp'); ?></th>
                    <th width="4%">&nbsp;</th>
                    
                     <th width="13%"><?php _e('Date', 'get-bookings-wp'); ?></th>
                     
                     <?php if(isset($getbwp_filter) && isset($getbwpultimate)){?>
                     
                      <th width="11%"><?php _e('Location', 'get-bookings-wp'); ?></th>
                     
                     <?php	} ?>
                    
                    <th width="23%"><?php _e('Client', 'get-bookings-wp'); ?></th>
                    <th width="23%"><?php _e('Phone Number', 'get-bookings-wp'); ?></th>
                    <th width="23%"><?php _e('Provider', 'get-bookings-wp'); ?></th>
                     <th width="18%"><?php _e('Service', 'get-bookings-wp'); ?></th>
                    <th width="16%"><?php _e('At', 'get-bookings-wp'); ?></th>
                    
                     
                     <th width="9%"><?php _e('Status', 'get-bookings-wp'); ?></th>
                    <th width="9%"><?php _e('Actions', 'get-bookings-wp'); ?></th>
                </tr>
            </thead>
            
            <tbody>
            
            <?php 
			$filter_name= '';
			$phone= '';
			foreach($appointments as $appointment) {
				
				
				$date_from=  date("Y-m-d", strtotime($appointment->booking_time_from));
				$booking_time = date($time_format, strtotime($appointment->booking_time_from ))	.' - '.date($time_format, strtotime($appointment->booking_time_to ));
				 
				$staff = $getbookingwp->userpanel->get_staff_member($appointment->booking_staff_id);
				
				$client_id = $appointment->booking_user_id;				
				$client = get_user_by( 'id', $client_id );
				
				if(isset($appointment->filter_name))
				{
					$filter_name=$appointment->filter_name;
					
				}else{
					
					$filter_id = $getbookingwp->appointment->get_booking_meta($appointment->booking_id, 'filter_id');					
					$filter_n = $getbookingwp->appointment->get_booking_location($filter_id);

                    if(isset($filter_n->filter_name)){

                        $filter_name=$filter_n->filter_name;
                    }else{

                        $filter_name='';
                    }
					
					
				}
				
				//get phone
			
				$phone = $getbookingwp->appointment->get_booking_meta($appointment->booking_id, 'full_number');
				
				$comments = $getbookingwp->appointment->get_booking_meta($appointment->booking_id, 'special_notes');
				
				
					
			?>
              

                <tr>
                    <td><?php echo esc_attr($appointment->booking_id); ?></td>
                     <td>  <?php if($comments!=''){?><a href="#" class="getbwp-appointment-edit-module" appointment-id="<?php echo esc_attr($appointment->booking_id)?>" title="<?php _e('See Details','get-bookings-wp'); ?>"><i class="fa fa-envelope-o"></i></a> <?php }?></td>
                   
                     <td><?php echo  esc_attr(date($date_format, strtotime($date_from))); ?>      </td> 
                     
                      <?php if(isset($getbwp_filter) && isset($getbwpultimate)){?>
                      
                      <td><?php echo esc_attr($filter_name); ?> </td>
                       <?php	} ?>
                      
                    <td><?php echo esc_attr($client->display_name); ?> (<?php echo esc_attr($client->user_email); ?>)</td>
                    <td><?php echo esc_attr($phone); ?></td>
                    <td><?php echo esc_attr($staff->display_name); ?></td>
                    <td><?php echo esc_attr($appointment->service_title); ?> </td>
                    <td><?php echo  esc_attr($booking_time); ?></td>                  
                     
                      <td><?php echo  wp_kses($getbookingwp->appointment->get_status_legend($appointment->booking_status), $getbookingwp->allowed_html);
                      ?></td>
                   <td> <a href="#" class="getbwp-appointment-edit-module" appointment-id="<?php echo esc_attr($appointment->booking_id)?>" title="<?php _e('Edit','get-bookings-wp'); ?>"><i class="fa fa-edit"></i></a>&nbsp;<a href="#" class="getbwp-appointment-delete-module" appointment-id="<?php echo esc_attr($appointment->booking_id)?>" title="<?php _e('Delete','get-bookings-wp'); ?>"><i class="fa fa-trash-o"></i></a></td>
                </tr>
                
                
                <?php
					}
					
					} else {
			?>
			<p><?php _e('There are no appointments yet.','get-bookings-wp'); ?></p>
			<?php	} ?>

            </tbody>
        </table>
        
        
        </div>
        
           
    <div id="getbwp-spinner" class="getbwp-spinner" style="display:">
            <span> <img src="<?php echo esc_html(getbookingpro_url.'admin/images/loaderB16.gif')?>" width="16" height="16" /></span>&nbsp; <?php  _e('Please wait ...','get-bookings-wp')?>
	</div>
        
         <div id="getbwp-appointment-new-box" title="<?php _e('Create New Appointment','get-bookings-wp')?>"></div>
     <div id="getbwp-appointment-edit-box" title="<?php _e('Edit Appointment','get-bookings-wp')?>"></div>     
     <div id="getbwp-new-app-conf-message" title="<?php _e('Appointment Created','get-bookings-wp')?>"></div> 
     <div id="getbwp-new-payment-cont" title="<?php _e('Add Payment','get-bookings-wp')?>"></div>
     <div id="getbwp-confirmation-cont" title="<?php _e('Confirmation','get-bookings-wp')?>"></div>
     <div id="getbwp-new-note-cont" title="<?php _e('Add Note','get-bookings-wp')?>"></div>     
     <div id="getbwp-appointment-list" title="<?php _e('Pending Appointments','get-bookings-wp')?>"></div>
      <div id="getbwp-appointment-change-status" title="<?php _e('Appointment Status','get-bookings-wp')?>"></div>
      
      <div id="getbwp-client-new-box" title="<?php _e('Create New Client','get-bookings-wp')?>"></div>
               
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
			 var message_wait_availability ='<img src="<?php echo esc_html(getbookingpro_url.'admin/images/loaderB16.gif')?>" width="16" height="16" /></span>&nbsp; <?php  _e("Please wait ...","get-bookings-wp")?>'; 
			 
			 jQuery("#getbwp-spinner").hide();	
			  
		
	</script>
 
        
