<?php
global $getbookingwp, $getbwpultimate, $getbwpcomplement;

$va = get_option('getbwp_c_key');
$domain = sanitize_text_field($_SERVER['SERVER_NAME']);
	
?>


 <div class="getbwp-sect getbwp-welcome-panel ">
 
 <?php if($va=='' && isset($getbwpultimate)){ //user is running either professional or utlimate?>
 
  <h3><?php _e('Recommendation!','get-bookings-wp'); ?></h3>
   <p><?php _e("You're running either Professional or Enterprise version which doesn't require a serial number to each one of your websites. However, if you don't create a serial number for this domain :",'get-bookings-wp'); ?><strong> <?php echo esc_attr($domain ); ?></strong>, <?php _e(" you won't be able to update the plugin automatically through the WP Update Section. So.. we highly recommend you creating a serial number for your domain.",'get-bookings-wp'); ?></p>

  <?php }?>
  
  
  <?php if($va!='' && isset($getbwpcomplement)){ //user is running a validated copy?>

    <div class="getbwp-validation-sect ">
        
        <h3><?php _e('Congratulations!','get-bookings-wp'); ?></h3>
        <p><?php _e("Your copy has been validated!",'get-bookings-wp'); ?></p>       
        <p><?php _e("You should start receiving notices every time the plugin is updated.",'get-bookings-wp'); ?></p>
   </div>
   <?php }else{?>  
   
   		
        <?php if($va=='' && isset($getbwpcomplement)){ //user is running either professional or utlimate?> 
            
            
            <div class="getbwp-validation-sect ">
   
        
                <h3><?php _e('Validate your copy','get-bookings-wp'); ?></h3>
                <p><?php _e("Please fill out the form below with the serial number generated when you registered your domain through your account at GetBookingsWP.com",'get-bookings-wp'); ?>. </p> 
                
                <p> <?php _e('INPUT YOUR SERIAL KEY','get-bookings-wp'); ?></p>
                <p><input type="text" name="p_serial" placeholder="<?php _e('type your serial number here','get-bookings-wp'); ?>" id="p_serial" style="width:400px" /></p>
                
                
                <p >
            <input type="submit" name="submit"  id="bupadmin-btn-validate-copy" class="button button-primary " value="<?php _e('CLICK HERE TO VALIDATE YOUR COPY','get-bookings-wp'); ?>"  /> &nbsp; 
            
            </p>

            <p><span id="loading-animation">  <img src="<?php echo esc_url(getbookingpro_url.'admin/images/loaderB16.gif')?>" width="16" height="16" /> &nbsp; <?php _e('Please wait ...','get-bookings-wp'); ?> </span></p>

            <p> <a href="https://doc.getbookingswp.com/installing-get-bookings-wp-pro-versions/" target="_blank"><?php _e('Learn how to create a serial number','get-bookings-wp'); ?></a></p> 
            <p> <a href="https://clients.getbookingswp.com/" target="_blank"><?php _e('LOGIN TO YOUR ACCOUNT','get-bookings-wp'); ?></a></p> 
                

           </div>
       
       <?php }else{?>
     
       

       <div class="getbwp-validation-sect ">
            <h3><?php _e('Validating your Plugin','get-bookings-wp'); ?></h3>
            <p ><?php _e("In order to validate the plugin you will need to purchase a licence on GetBookingsWP.com",'get-bookings-wp'); ?> </p>
            <p >  <a href="https://getbookingswp.com/pricing" target="_blank"><?php _e('Click here to purchase a serial number','get-bookings-wp'); ?></a></p>
       </div>
        <?php }?>
       
   <?php }?> 
       
       <p id='getbwp-validation-results'>
       
       </p>
                     
       
    
</div>  

