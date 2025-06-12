<?php
global $getbookingwp, $getbwp_maintenance;

//$currency_symbol =  $getbookingswp->get_option('paid_membership_symbol');
$date_format =  $getbookingwp->get_int_date_format();
$time_format =  $getbookingwp->service->get_time_format();


?>
<div class="getbooginswp-sect ">

 <h3><?php _e('Appointments without a service assigned','get-bookings-wp'); ?></h3>
        
              <p><?php _e('This feature will help you to delete appointments without a service assigned. This happens when you delete a service that’s used by some appointment or client. Although, the plugin control this from happening, sometimes the service is deleted directly from the database causing inconsistences. ','get-bookings-wp'); ?></p>
        <?php 
		
		$appointments = $getbwp_maintenance->get_without_service();
		
		if ( !empty( $appointments ) )
		{
			
			$html = '<div class="getbooginswp-error">'. __("Some appointment(s) are linked to a non-existent service.", 'get-bookings-wp').'</div>';
			
			
            echo wp_kses($html, $getbookingwp->allowed_html)
		 ?>
				
				           <table width="100%" class="wp-list-table widefat fixed posts table-generic">
            <thead>
                <tr>
                    <th width="4%"><?php _e('#', 'get-bookings-wp'); ?></th>
                    
                     <th width="13%"><?php _e('Date', 'get-bookings-wp'); ?></th>                     
                                        
                    <th width="23%"><?php _e('Client', 'get-bookings-wp'); ?></th>
                    <th width="23%"><?php _e('Phone Number', 'get-bookings-wp'); ?></th>
                    <th width="23%"><?php _e('Provider', 'get-bookings-wp'); ?></th>
                     <th width="18%"><?php _e('Service', 'get-bookings-wp'); ?></th>
                    <th width="16%"><?php _e('At', 'get-bookings-wp'); ?></th>
                    
                     
                     <th width="9%"><?php _e('Status', 'get-bookings-wp'); ?></th>
                   
                </tr>
            </thead>
            
            <tbody>
            
            <?php 
			
			foreach ( $appointments as $appointment )
			{
				
				$date_from=  date("Y-m-d", strtotime($appointment->booking_time_from));
				$booking_time = date($time_format, strtotime($appointment->booking_time_from ))	.' - '.date($time_format, strtotime($appointment->booking_time_to ));
				 
				$staff = $getbookingwp->userpanel->get_staff_member($appointment->booking_staff_id);
				
				$client_id = $appointment->booking_user_id;				
				$client = get_user_by( 'id', $client_id );
				
				//get phone			
				$phone = $getbookingwp->appointment->get_booking_meta($appointment->booking_id, 'full_number');
			
			?>
              

                <tr>
                    <td><?php echo esc_attr($appointment->booking_id); ?></td>
                   
                     <td><?php echo  esc_attr(date($date_format, strtotime($date_from))); ?>      </td> 
                     
                                          
                    <td><?php echo esc_attr($client->display_name); ?> (<?php echo esc_attr($client->user_email); ?>)</td>
                    <td><?php echo esc_attr($phone); ?></td>
                    <td><?php echo esc_attr($staff->display_name); ?></td>
                    <td>N/A </td>
                    <td><?php echo  esc_attr($booking_time); ?></td>                  
                     
                      <td><?php echo  wp_kses($getbookingwp->appointment->get_status_legend($appointment->booking_status), $getbookingwp->allowed_html) ; ?></td>
                </tr>
                
                
                <?php
				
			}	 ?>
			
			
			</tbody>
        </table>
        
        <p class="submit">
	<input type="button" name="submit" id="getbwp_clean_app_without_service" class="button button-primary" value="<?php _e('Fix Inconsistency','get-bookings-wp'); ?>"  />
	
</p>

        
					
	<?php	}else{
			?>
            
			 <p><?php _e("Don't worry. Everything looks great!. Al the appointments are linked to a service.",'get-bookings-wp'); ?></p>
			
			
		<?php }
		?>
 

             

</div>





<div class="getbooginswp-sect ">

 <h3><?php _e('Appointments without a Staff member assigned','get-bookings-wp'); ?></h3>
        
              <p><?php _e('Here you will see a list of appointments that are assigned to staff members that were deleted manually or by using the WP Users Link. If this happens you can use this feature to fix them.','get-bookings-wp'); ?></p>
        
     
    <?php 
		
		$appointments = $getbwp_maintenance->get_without_user();
		
		if ( !empty( $appointments ) )
		{
			
			$html = '<div class="getbooginswp-error">'. __("Some appointment(s) are linked to a non-existent service.", 'get-bookings-wp').'</div>';
			
			echo wp_kses($html, $getbookingwp->allowed_html) ;
		 ?>
				
				           <table width="100%" class="wp-list-table widefat fixed posts table-generic">
            <thead>
                <tr>
                    <th width="4%"><?php _e('#', 'get-bookings-wp'); ?></th>
                    
                     <th width="13%"><?php _e('Date', 'get-bookings-wp'); ?></th>                     
                                        
                    <th width="23%"><?php _e('Client', 'get-bookings-wp'); ?></th>
                    <th width="23%"><?php _e('Phone Number', 'get-bookings-wp'); ?></th>
                    <th width="23%"><?php _e('Provider', 'get-bookings-wp'); ?></th>
                   
                    <th width="16%"><?php _e('At', 'get-bookings-wp'); ?></th>
                    
                     
                     <th width="9%"><?php _e('Status', 'get-bookings-wp'); ?></th>
                   
                </tr>
            </thead>
            
            <tbody>
            
            <?php 
			
			foreach ( $appointments as $appointment )
			{
				
				$date_from=  date("Y-m-d", strtotime($appointment->booking_time_from));
				$booking_time = date($time_format, strtotime($appointment->booking_time_from ))	.' - '.date($time_format, strtotime($appointment->booking_time_to ));
				 
				$staff = $getbookingwp->userpanel->get_staff_member($appointment->booking_staff_id);
				
				$client_id = $appointment->booking_user_id;				
				$client = get_user_by( 'id', $client_id );
				
				//get phone			
				$phone = $getbookingwp->appointment->get_booking_meta($appointment->booking_id, 'full_number');
			
			?>
              

                <tr>
                    <td><?php echo esc_attr($appointment->booking_id); ?></td>
                   
                     <td><?php echo date($date_format, strtotime($date_from)); ?>      </td> 
                     
                                          
                    <td><?php echo esc_attr($client->display_name); ?> (<?php echo esc_attr($client->user_email); ?>)</td>
                    <td><?php echo $phone; ?></td>
                    <td>N/A</td>
                   
                    <td><?php echo  esc_attr($booking_time); ?></td>                  
                     
                      <td><?php echo wp_kses($getbookingwp->appointment->get_status_legend($appointment->booking_status), $getbookingwp->allowed_html)?></td>
                </tr>
                
                
                <?php
				
			}	 ?>
			
			
			</tbody>
        </table>
        
        <p class="submit">
	<input type="button" name="submit" id="getbwp_clean_app_without_staff" class="button button-primary" value="<?php _e('Fix Inconsistency','get-bookings-wp'); ?>"  />
	
</p>

        
					
	<?php	}else{
			?>
            
			 <p><?php _e("Don't worry. Everything looks great!. Al the appointments are linked to an existen Staff provider.",'get-bookings-wp'); ?></p>
			
			
		<?php }
		?>
 


             

</div>

<p class="submit">
	<input type="button" name="submit_d" id="submit_d" class="button button-primary" value="<?php _e('Save Changes','get-bookings-wp'); ?>"  />
	
</p>
