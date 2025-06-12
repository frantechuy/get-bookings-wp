<?php
global $getbookingwp;
$currency_symbol =  $getbookingwp->get_option('paid_membership_symbol');
$orders = $getbookingwp->order->get_all();

$howmany = "";
$year = "";
$month = "";
$day = "";

if(isset($_GET["howmany"]))
{
	$howmany = sanitize_text_field($_GET["howmany"]);		
}

if(isset($_GET["month"]))
{
	$month = sanitize_text_field($_GET["month"]);		
}

if(isset($_GET["day"]))
{
	$day = sanitize_text_field($_GET["day"]);		
}

if(isset($_GET["year"]))
{
	$year = sanitize_text_field($_GET["year"]);		
}
		
?>

        
       <div class="getbwp-sect getbwp-welcome-panel">
        
        <h3><?php _e('Payments','get-bookings-wp'); ?></h3>
        
       
       
        <form action="" method="get">
         <input type="hidden" name="page" value="getbookingswp" />
          <input type="hidden" name="tab" value="orders" />
          <?php wp_nonce_field('getbwp-action', 'getbwp_nonce' ); ?>

        
        <div class="getbwp-ultra-success getbwp-notification"><?php _e('Success ','get-bookings-wp'); ?></div>
        
        <div class="user-ultra-sect-second user-ultra-rounded" >
        
                  
        
         
        
         
           <table width="100%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td width="17%"><?php _e('Keywords: ','get-bookings-wp'); ?></td>
             <td width="5%"><?php _e('Month: ','get-bookings-wp'); ?></td>
             <td width="5%"><?php _e('Day: ','get-bookings-wp'); ?></td>
             <td width="52%"><?php _e('Year: ','get-bookings-wp'); ?></td>
             <td width="21%">&nbsp;</td>
           </tr>
           <tr>
             <td><input type="text" name="keyword" id="keyword" placeholder="<?php _e('write some text here ...','get-bookings-wp'); ?>" /></td>
             <td><select name="month" id="month">
               <option value="" selected="selected"><?php _e('All','get-bookings-wp'); ?></option>
               <?php
			  
			  $i = 1;
              
			  while($i <=12){
			  ?>
               <option value="<?php echo esc_attr($i)?>"  <?php if($i==$month) echo 'selected="selected"';?>><?php echo esc_attr($i)?></option>
               <?php 
			    $i++;
			   }?>
             </select></td>
             <td><select name="day" id="day">
               <option value="" selected="selected"><?php _e('All','get-bookings-wp'); ?></option>
               <?php
			  
			  $i = 1;
              
			  while($i <=31){
			  ?>
               <option value="<?php echo esc_attr($i)?>"  <?php if($i==$day) echo 'selected="selected"';?>><?php echo esc_attr($i)?></option>
               <?php 
			    $i++;
			   }?>
             </select></td>
             <td><select name="year" id="year">
               <option value="" selected="selected"><?php _e('All','get-bookings-wp'); ?></option>
               <?php
			  
			  $i = date('Y')-5;
        $current_year  = date('Y');
              
			  while($i <= $current_year){
			  ?>
               <option value="<?php echo esc_attr($i)?>" <?php if($i==$year) echo 'selected="selected"';?> ><?php echo esc_attr($i)?></option>
               <?php 
			    $i++;
			   }?>
             </select></td>
             <td>&nbsp;</td>
           </tr>
          </table>
         
         <p>
         
         <button><?php _e('Filter','get-bookings-wp'); ?></button>
        </p>
        
       
        </div>
        
        
          <p> <?php _e('Total: ','get-bookings-wp'); ?> <?php echo esc_attr($getbookingwp->order->total_result);?> | <?php _e('Displaying per page: ','get-bookings-wp'); ?>: <select name="howmany" id="howmany">
               <option value="20" <?php if(20==$howmany ||$howmany =="" ) echo esc_attr('selected="selected"');?>>20</option>
                <option value="40" <?php if(40==$howmany ) echo esc_attr('selected="selected"');?>>40</option>
                 <option value="50" <?php if(50==$howmany ) echo esc_attr('selected="selected"');?>>50</option>
                  <option value="80" <?php if(80==$howmany ) echo esc_attr('selected="selected"');?>>80</option>
                   <option value="100" <?php if(100==$howmany ) echo esc_attr('selected="selected"');?>>100</option>
               
          </select></p>
        
         </form>
         
                 
         
         </div>
         
         
         <div class="getbwp-sect getbwp-welcome-panel">
        
         <?php
			
			
				
				if (!empty($orders)){
				
				
				?>
       
           <table width="100%" class="wp-list-table widefat fixed posts table-generic">
            <thead>
                <tr>
                    <th width="4%"><?php _e('#', 'get-bookings-wp'); ?></th>
                    <th width="6%"><?php _e('A. #', 'get-bookings-wp'); ?></th>
                     <th width="11%"><?php _e('Date', 'get-bookings-wp'); ?></th>
                    
                    <th width="23%"><?php _e('Client', 'get-bookings-wp'); ?></th>
                     <th width="18%"><?php _e('Service', 'get-bookings-wp'); ?></th>
                    <th width="16%"><?php _e('Transaction ID', 'get-bookings-wp'); ?></th>
                    
                     <th width="9%"><?php _e('Method', 'get-bookings-wp'); ?></th>
                     <th width="9%"><?php _e('Status', 'get-bookings-wp'); ?></th>
                    <th width="9%"><?php _e('Amount', 'get-bookings-wp'); ?></th>
                </tr>
            </thead>
            
            <tbody>
            
            <?php 
			foreach($orders as $order) {
				
				$client_id = $order->booking_user_id;				
				$client = get_user_by( 'id', $client_id );
					
			?>
              

                <tr>
                    <td><?php echo esc_attr($order->order_id); ?></td>
                    <td><?php echo  esc_attr($order->booking_id); ?></td>
                     <td><?php echo  esc_attr(date("m/d/Y", strtotime($order->order_date))); ?></td>
                    <td><?php echo esc_attr($client->display_name); ?> (<?php echo esc_attr($client->user_login); ?>)</td>
                    <td><?php echo esc_attr($order->service_title); ?> </td>
                    <td><?php echo esc_attr($order->order_txt_id); ?></td>
                     
                      <td><?php echo esc_attr($order->order_method_name); ?></td>
                      <td><?php echo esc_attr($order->order_status); ?></td>
                   <td> <?php echo esc_attr($currency_symbol.$order->order_amount); ?></td>
                </tr>
                
                
                <?php
					}
					
					} else {
			?>
			<p><?php _e('There are no transactions yet.','get-bookings-wp'); ?></p>
			<?php	} ?>

            </tbody>
        </table>
        
        
        </div>
        
