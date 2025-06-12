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
    

    <div class="welcome-panel-content getbwp-f-welcome-panel-content">

    
        <div class="getbwp-main-sales-summary " id="getbwp-main-cont-home-111" >
        
       
        
        <!--Col1-->
       <div class="getbwp-main-dashcol-2" > 
           
           <div class="getbwp-main-tool-bar" >
               
               <ul>           

                   
                    <li class="newappo"><a id="getbwp-create-new-app" href="#"><span><i class="fa fa-plus"></i></span><?php _e('New','get-bookings-wp')?></a> </li>
                   
               </ul>
               
           </div>
            
             <div class="getbwp-main-quick-summary" >
             
            
          
         	   <ul>
                   <li>                    
                     
                      <p style=""> <?php echo esc_attr($today)?></p>  
                       <small><?php _e('Today','get-bookings-wp')?> </small>                  
                    </li>
                    
                    <li>                   
                     
                      <p style="color:"> <?php echo esc_attr($tomorrow)?></p> 
                       <small><?php _e('Tomorrow','get-bookings-wp')?> </small>                   
                    </li>
                
                	<li>                   
                     
                      <p style="color:"> <?php echo esc_attr($week)?></p> 
                       <small><?php _e('This Week','get-bookings-wp')?> </small>                   
                    </li>
                   
                    <li><a href="#"  class="getbwp-adm-see-appoint-list-quick" getbwp-status='0' getbwp-type='bystatus'>                    
                         
                          <p style="color: #333"> <?php echo esc_attr($pending)?></p>   
                          <small><?php _e('Pending','get-bookings-wp')?> </small>
                        </a>                
                    </li>
                   
                     <li>     
                        
                         <a href="#" class="getbwp-adm-see-appoint-list-quick" getbwp-status='3' getbwp-type='byunpaid'>              
                         
                          <p style="color: #F90000"> <?php echo esc_attr($unpaid)?></p> 
                          <small><?php _e('Unpaid','get-bookings-wp')?> </small>
                          
                           </a>                     
                   </li>
                   
                   
              </ul>
              
            </div>
            
          
            
          
          </div>
        <!-- End Col1-->
            
            
        
         <div class="getbwp-main-dashcol-1" >
          	 <div id='easywpm-gcharthome' style="width: 100%; height: 180px;">
          	 </div>
        </div>
        
        
        
        </div>
        
    </div>

</div>    

<div class="getbwp-welcome-panel">

<div class="welcome-panel-content getbwp-f-welcome-panel-content">
	<h3 class="getbwp-welcome"><?php _e('Upcoming Appointments!','get-bookings-wp')?></h3>
    
    <span class="getbwp-main-close-open-tab"><a href="#" title="Close" class="getbwp-widget-home-colapsable" widget-id="1"><i class="fa fa-sort-asc " id="getbwp-close-open-icon-1" ></i></a></span>
	
	<div class="welcome-panel-column-container " id="getbwp-main-cont-home-1" >
    

    
   
    
      <?php
			
			
				
				if (!empty($upcoming_appointments)){
				
				
				?>
       
           <table width="100%" class="wp-list-table widefat fixed posts table-generic">
            <thead>
                <tr>
                  
                    
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
			foreach($upcoming_appointments as $appointment) {
				
				
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
				
				
					
			?>
              

                <tr>
                   
                   
                     <td><?php echo  esc_attr(date($date_format, strtotime($date_from))); ?>      </td> 
                     
                      <?php if(isset($getbwp_filter) && isset($getbwpultimate)){?>
                      
                      <td><?php echo esc_attr($filter_name); ?> </td>
                       <?php	} ?>
                      
                    <td><?php echo esc_attr($client->display_name); ?> (<?php echo esc_attr($client->user_email); ?>)</td>
                    <td><?php echo esc_attr($phone); ?></td>
                    <td><?php echo esc_attr($staff->display_name); ?></td>
                    <td><?php echo esc_attr($appointment->service_title); ?> </td>
                    <td><?php echo  esc_attr($booking_time); ?></td>    
                    
                  
                     
                      <td><?php echo wp_kses($getbookingwp->appointment->get_status_legend($appointment->booking_status), $getbookingwp->allowed_html); ?></td>
                   <td> <a href="#" class="getbwp-appointment-edit-module" appointment-id="<?php echo esc_attr($appointment->booking_id)?>" title="<?php _e('Edit','get-bookings-wp'); ?>"><i class="fa fa-edit"></i></a></td>
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
	</div>
    
    
</div>


<?php if(!isset($getbwpcomplement)){?>
<p class="getbwp-extra-features"><?php _e('Do you need more features or manage multiple locations, google calendar integration, SMS reminders, change legends & colors?','get-bookings-wp')?> <a href="https://getbookingswp.com/pricing-compare-plans" target="_blank">Click here</a> to see higher versions.</p>

<?php }?>
        
        
     <div id="getbwp-appointment-new-box" title="<?php _e('Create New Appointment','get-bookings-wp')?>"></div>
     <div id="getbwp-appointment-edit-box" title="<?php _e('Edit Appointment','get-bookings-wp')?>"></div>     
     <div id="getbwp-new-app-conf-message" title="<?php _e('Appointment Created','get-bookings-wp')?>"></div> 
     <div id="getbwp-new-payment-cont" title="<?php _e('Add Payment','get-bookings-wp')?>"></div>
     <div id="getbwp-confirmation-cont" title="<?php _e('Confirmation','get-bookings-wp')?>"></div>
     <div id="getbwp-new-note-cont" title="<?php _e('Add Note','get-bookings-wp')?>"></div>     
     <div id="getbwp-appointment-list" title="<?php _e('Pending Appointments','get-bookings-wp')?>"></div>
     
     <div id="getbwp-client-new-box" title="<?php _e('Create New Client','get-bookings-wp')?>"></div>
           <div id="getbwp-appointment-change-status" title="<?php _e('Appointment Status','get-bookings-wp')?>"></div>

     
     
       
    
    <div id="getbwp-spinner" class="getbwp-spinner" style="display:none">
            <span> <img src="<?php echo esc_attr(getbookingpro_url.'admin/images/loaderB16.gif')?>" width="16" height="16" /></span>&nbsp; <?php  _e('Please wait ...','get-bookings-wp')?>
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
			var message_wait_availability ='<img src="<?php echo esc_url(getbookingpro_url.'admin/images/loaderB16.gif')?>" width="16" height="16" /></span>&nbsp; <?php  _e("Please wait ...","get-bookings-wp")?>'; 
			  
		
	</script>
    
    <?php

$sales_val= $getbookingwp->appointment->get_graph_total_monthly();
$months_array = array_values( $wp_locale->month );
$current_month = date("m");
$current_month_legend = $months_array[$current_month -1];

?>

<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
		  
        var data = google.visualization.arrayToDataTable([
          ["<?php _e('Day','get-bookings-wp')?>", "<?php _e('Bookings','get-bookings-wp')?>"],
         <?php echo wp_kses($sales_val,  $getbookingwp->allowed_html)?>
        ]);

        var options = {
        
                  hAxis: {title: '<?php printf(__( 'Month: %s', 'get-bookings-wp' ),
                 $current_month_legend);?> ',  titleTextStyle: {color: '#333'},  textStyle: {fontSize: '9'}},
          
                vAxis: {minValue: 0},		 
                series: {
                        0: {
                            // set options for the first data series
                            color: '#57c1e2'
                        }
                        
                    },
		            legend: { position: "none" }
        };

        var chart_1 = new google.visualization.AreaChart(document.getElementById('easywpm-gcharthome'));
        chart_1.draw(data, options);			
		
		
		
      }
    </script>    
