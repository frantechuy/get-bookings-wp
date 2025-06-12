<?php
global $getbookingwp, $getbwpcomplement;

$url = "https://getbookingswp.com/addons-list";			
		
$response = wp_remote_post(
            $url,
            array(
                'body' => array(
                    	'action' => 'validate',
					
                )
            )
);

		
		
$response = json_decode($response["body"]);		
$message =$response->{'message'}; 

	
?>


        
       <div class="getbwp-welcome-panel">
       
     

<div class="welcome-panel-content">


      <div style="text-align:center; padding:10px 0px 10px 0px">

          <a href="?page=getbookingswp">
                          
                          <img width="250px" src="<?php echo esc_url(getbookingpro_url.'admin/images/logo.svg');?>"  /> </a>

      </div>

	<h3 class="getbwp-extended">Get Bookings WP Add-ons</h3>
    <p class="getbwp-extended-p">Thank you very much for activating Get Bookings WP!</p> 
    <p class="getbwp-extended-p">Check the <a href="https://doc.getbookingswp.com/getting-started-with-getbookingswp/" target="_blank">Getting Started Guide</a></p>   
   
  
     <div class="welcome-panel-column-container">


        <div class="welcome-panel-column-pro-s">
            
            <h4>Staff Manage Their Appointments</h4>
            
            
                <p>Your Staff members will have a module that allows them to manage their own appointments. </p>

         
        </div>
    
        

    
	</div>
	</div>
    
</div>       
                                          
