(function() {
    tinymce.PluginManager.add('GETBWPShortcodes', function( editor, url ) {
        editor.addButton( 'getbwp_shortcodes_button', {
            title: 'Get Bookings WP Shortcodes',
            type: 'menubutton',
            icon: 'icon mce_getbwp_shortcodes_button',
            menu: [
                
                {
                    text: 'Booking Forms',
                    value: 'Text from menu item II',
                    onclick: function() {
                        editor.insertContent(this.value());
                    },
                    menu: [
                        {
                            text: 'Booking Form',
                            value: '[getbookingswp_appointment]',
                            onclick: function(e) {
                                e.stopPropagation();
                                editor.insertContent(this.value());
                            }       
                        },
                        
						
						
                    ]
                }
			
				
				
           ]
        });
    });
})();