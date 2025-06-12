<?php
class GetBookingsWPBreak{
	
	
	function __construct()	{
				
		$this->ini_module();
		
		add_action( 'wp_ajax_getbwp_get_break_add',  array( &$this, 'get_break_add_frm' ));
		add_action( 'wp_ajax_getbwp_break_add_confirm',  array( &$this, 'break_add_confirm' ));
		add_action( 'wp_ajax_getbwp_get_current_staff_breaks',  array( &$this, 'get_current_staff_breaks' ));
		add_action( 'wp_ajax_getbwp_delete_break',  array( &$this, 'delete_break' ));	
	}
	
	public function ini_module(){
		global $wpdb;	
					
		     // Create table for breaks
			$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'getbwp_staff_availability_breaks (
				  `break_id` int(11) NOT NULL AUTO_INCREMENT,
				  `break_staff_id` int(11) NOT NULL,
				  `break_title` varchar(300) NOT NULL,
				  `break_staff_day` int(11) NOT NULL,				 
				  `break_time_from` time NOT NULL,
				  `break_time_to` time NOT NULL,
				  PRIMARY KEY (`break_id`)
			) ENGINE=MyISAM COLLATE utf8_general_ci;';

		$wpdb->query( $query );
	}
	
	
	
	public function break_add_confirm()	{
		global  $getbookingwp , $wpdb;
		
		$staff_id = sanitize_text_field($_POST['staff_id']);
		$day_id = sanitize_text_field($_POST['day_id']);
		
		$from = sanitize_text_field($_POST['from']).':00';
		$to = sanitize_text_field($_POST['to']).':00';
				
		$html = '';		

		$sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'getbwp_staff_availability_breaks  WHERE break_staff_id=%d AND break_staff_day = %d AND break_time_from=%s AND break_time_to=%s;',array($staff_id,$day_id ,$from ,$to));
		$results = $wpdb->get_results($sql);
		
		if ( empty( $results ))	{				
			$new_record = array('break_id' => NULL,	
								'break_staff_id' => $staff_id,
								'break_staff_day' => $day_id,
								'break_time_from' => $from,
								'break_time_to'   => $to);								
			$wpdb->insert( $wpdb->prefix . 'getbwp_staff_availability_breaks', $new_record, array( '%d', '%d', '%d', '%s', '%s'));
			$html = __('Done!', 'get-bookings-wp')	;
		}else{
			$html = __('ERROR. Duplicated Break!', 'get-bookings-wp')	;			
		}
		
		echo wp_kses($html, $getbookingwp->allowed_html);
		die();
	}
	
	
	public function get_break_add_frm($staff_id = null, $day_id = null ){
		global  $getbookingwp;
		$staff_id = sanitize_text_field($_POST['staff_id']);		
		$day_id = sanitize_text_field($_POST['day_id']);		
		$html = '<div class="getbwp-add-break-cont">';
		$html .='<input type="hidden" value="'.$day_id.'" id="getbwp_day_id">';
		$html .=''.$this->get_breaks_drop_downs($day_id,'getbwp-break-from-'.$day_id ,'getbwp_select_start', $staff_id). '<span> '.__('to', 'get-bookings-wp').' </span>' .$this->get_breaks_drop_downs($day_id,'getbwp-break-to-'.$day_id ,'getbwp_select_end', $staff_id).'';
		$html .= '<button name="getbwp-btn-add-break-confirm" id="getbwp-btn-add-break-confirm" class="getbwp-button-submit-breaks" day-id="'.$day_id.'">'.__('Add','get-bookings-wp').'	</button>';
		$html .= '<span id="getbwp-break-message-add-'.$day_id.'"></span>';
		$html .= '</div>';
		echo wp_kses($html, $getbookingwp->allowed_html);
		die();
	}
	
	//returns the business hours drop down
	public function get_breaks_drop_downs($day, $cbox_id, $select_start_to_class, $staff_id){
		global  $getbookingwp;
		$html='';
		$hours = 24; //amount of hours working in day			
		$min_minutes=15	;
		$hours = (60/$min_minutes) *$hours;		
		$min_minutes=$min_minutes*60;		
		$html .= '<select id="'.$cbox_id.'" name="'.$cbox_id.'" class="'.$select_start_to_class.'">';
		if($select_start_to_class=='getbwp_select_start'){
			$from_to_value = 'from';
		}else{
			$from_to_value = 'to';			
		}
			
		//check selected value
		$selected_value = $getbookingwp->service->get_business_hour_option($day, $from_to_value, $staff_id);		
		
		for($i = 0; $i < $hours ; $i++)	{ 		
			$minutes_to_add = $min_minutes * $i; // add 30 - 60 - 90 etc.
			$timeslot = date('H:i:s', strtotime('midnight')+$minutes_to_add);	
			$selected = '';				
			if($selected_value==date('H:i', strtotime($timeslot))){
				$selected = 'selected="selected"';
			}
			$html .= '<option value="'.date('H:i', strtotime($timeslot)).'" '.$selected.'  >'.date('h:i A', strtotime($timeslot)).'</option>';
		}
		$html .='</select>';
		return $html;
	}
	
	public function get_staff_breaks($staff_id){
		global $wpdb, $getbookingwp;
		$html='';
		$html .= '<ul class="getbwp-details-staff-sections">';
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="getbwp-break-details-header" widget-id="1"><h3> '.__('Monday','get-bookings-wp').'<h3>';
		$html .='<span class="getbwp-breaks-add" id="getbwp-widgets-icon-close-open-id-1"  day-id="1" >'.__('Add Break','get-bookings-wp').'</span>';
		$html .= '</div>';
		$html .='<div id="getbwp-break-add-break-1" class="getbwp-add-new-break"></div>';	
		$html .='<div id="getbwp-break-adm-cont-id-1" class="getbwp-breaks-details">';
		$html .= $this->get_current_staff_breaks($staff_id , 1 );
		$html .= '</div>';
		$html .='</li>';

		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="getbwp-break-details-header" widget-id="1"><h3> '.__('Tuesday','get-bookings-wp').'<h3>';
		$html .='<span class="getbwp-breaks-add"  day-id="2" >'.__('Add Break','get-bookings-wp').'</span>';
		$html .= '</div>';
		
		$html .='<div id="getbwp-break-add-break-2" class="getbwp-add-new-break"></div>';	
		$html .='<div id="getbwp-break-adm-cont-id-2" class="getbwp-breaks-details">';
		$html .= $this->get_current_staff_breaks($staff_id , 2 );
		$html .= '</div>';
		$html .='</li>';
		
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="getbwp-break-details-header" widget-id="1"><h3> '.__('Wednesday','get-bookings-wp').'<h3>';
		$html .='<span class="getbwp-breaks-add"  day-id="3" >'.__('Add Break','get-bookings-wp').'</span>';
		$html .= '</div>';
		
		$html .='<div id="getbwp-break-add-break-3" class="getbwp-add-new-break"></div>';			
		$html .='<div id="getbwp-break-adm-cont-id-3" class="getbwp-breaks-details">';
		$html .= $this->get_current_staff_breaks($staff_id , 3 );
		$html .= '</div>';
		$html .='</li>';
		
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="getbwp-break-details-header" widget-id="1"><h3> '.__('Thursday ','get-bookings-wp').'<h3>';
		$html .='<span class="getbwp-breaks-add"  day-id="4" >'.__('Add Break','get-bookings-wp').'</span>';
		$html .= '</div>';
		
		$html .='<div id="getbwp-break-add-break-4" class="getbwp-add-new-break"></div>';			
		$html .='<div id="getbwp-break-adm-cont-id-4" class="getbwp-breaks-details">';
		$html .= $this->get_current_staff_breaks($staff_id , 4 );
		$html .= '</div>';
		$html .='</li>';
		
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="getbwp-break-details-header" widget-id="1"><h3> '.__('Friday','get-bookings-wp').'<h3>';
		$html .='<span class="getbwp-breaks-add" day-id="5" >'.__('Add Break','get-bookings-wp').'</span>';
		$html .= '</div>';
		
		$html .='<div id="getbwp-break-add-break-5" class="getbwp-add-new-break"></div>';			
		$html .='<div id="getbwp-break-adm-cont-id-5" class="getbwp-breaks-details">';
		$html .= $this->get_current_staff_breaks($staff_id , 5 );
		$html .= '</div>';
		$html .='</li>';
		
		
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="getbwp-break-details-header" widget-id="1"><h3> '.__('Saturday','get-bookings-wp').'<h3>';
		$html .='<span class="getbwp-breaks-add"  day-id="6" >'.__('Add Break','get-bookings-wp').'</span>';
		$html .= '</div>';
		
		$html .='<div id="getbwp-break-add-break-6" class="getbwp-add-new-break"></div>';			
		$html .='<div id="getbwp-break-adm-cont-id-6" class="getbwp-breaks-details">';
		$html .= $this->get_current_staff_breaks($staff_id , 6 );
		$html .= '</div>';
		$html .='</li>';
		
		$html .='<li class="left_widget_customizer_li">';
		$html .='<div class="getbwp-break-details-header" widget-id="1"><h3> '.__('Sunday','get-bookings-wp').'<h3>';
		$html .='<span class="getbwp-breaks-add" day-id="7" >'.__('Add Break','get-bookings-wp').'</span>';
		$html .= '</div>';
		
		$html .='<div id="getbwp-break-add-break-7" class="getbwp-add-new-break"></div>';			
		$html .='<div id="getbwp-break-adm-cont-id-7" class="getbwp-breaks-details">';
		$html .= $this->get_current_staff_breaks($staff_id , 7 );
		$html .= '</div>';
		$html .='</li>';
		$html .='</ul>';
		return $html;
	}
	
	
	public function delete_break(){
		global  $getbookingwp , $wpdb;
		
		$staff_id = sanitize_text_field($_POST['staff_id']);
		$break_id = sanitize_text_field($_POST['break_id']);
		$sql = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'getbwp_staff_availability_breaks  WHERE break_staff_id=%d AND break_id = %d ;',array($staff_id,$break_id));
		$results = $wpdb->query($sql);
		die();
	}	
	
	public function get_current_staff_breaks($staff_id = null, $day_id = null){
		global  $getbookingwp , $wpdb;

		$action = '';
		if(isset($_POST['action'])){
			$action = sanitize_text_field($_POST['action']);		
		}
		
		
		$time_format = $getbookingwp->service->get_time_format();
		
		if($action=='getbwp_get_current_staff_breaks'){	
			$staff_id = sanitize_text_field($_POST['staff_id']);
			$day_id = sanitize_text_field($_POST['day_id']);
		}		
						
		$html = '';		

		$sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'getbwp_staff_availability_breaks  WHERE break_staff_id=%d AND break_staff_day = %d ;',array($staff_id,$day_id));
		$results = $wpdb->get_results($sql);
		
		if ( !empty( $results )){
			$html .= '<ul>';
			foreach ( $results as $row ){
				$html .= '<li><i class="fa fa-clock-o getbwp-clock-remove"></i>'.date($time_format,strtotime($row->break_time_from)).' - '.date($time_format,strtotime($row->break_time_to));
				$html .= '<span class="getbwp-breaks-remove" id="getbwp-break-add-'.$day_id.'"><a href="#" class="getbwp-break-delete-btn" title="'.__("Delete Break", 'get-bookings-wp').'" break-id="'.$row->break_id.'" day-id="'.$day_id.'"><i class="fa fa-remove"></i></a></span>';
				$html .= '</li>';
			}
			$html .= '</ul>';			
						
		}else{
			$html = __("There are no breaks for this day.", 'get-bookings-wp')	;			
		}
		
		if($action=='getbwp_get_current_staff_breaks'){
			echo wp_kses($html, $getbookingwp->allowed_html);
			die();
		}else{			
			return $html;
		}
	}
}
$key = "breaks";
$this->{$key} = new GetBookingsWPBreak();
?>