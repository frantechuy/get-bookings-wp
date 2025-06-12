<?php
class GetBookingsWPShortCode {

	function __construct(){
		add_action( 'init',   array(&$this,'getbwp_shortcodes'));	
		add_action( 'init', array(&$this,'respo_base_unautop') );
	}
	
	/**
	* Add the shortcodes
	*/
	function getbwp_shortcodes(){
	    add_filter( 'the_content', 'shortcode_unautop');			
		add_shortcode( 'getbookingswp_appointment', array(&$this,'make_appointment') );		
	}
	
	/**
	* Don't auto-p wrap shortcodes that stand alone
	*/
	function respo_base_unautop() {
		add_filter( 'the_content',  'shortcode_unautop');
	}
	
	public function  make_appointment ($atts){
		global $getbookingwp;		
		return $getbookingwp->appointment->get_public_booking_form($atts);	
	}	
}
$key = "shortcode";
$this->{$key} = new GetBookingsWPShortCode();