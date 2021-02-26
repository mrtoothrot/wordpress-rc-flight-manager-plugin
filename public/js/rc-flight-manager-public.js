//console.log("rc-flight-manager-public.js loaded!");
//console.log("rc_flight_manager_vars.ajax_url on script load:");
//console.log(rc_flight_manager_vars.ajax_url);

//jQuery(".button_takeover_schedule").click(function() {
//	console.log("Button button_takeover_schedule clicked!");
//});

//jQuery(".button_takeover_schedule").click(function() {
//	console.log("Button button_takeover_schedule clicked!");
//	console.log("ID: " + this.id);
//})

//(function( $ ) {
//	'use strict';
//	console.log("rc_flight_manager_vars.ajax_url in jQuery:");
//	console.log(rc_flight_manager_vars.ajax_url);
//	
//	$("button").click(function() {
//		console.log("Button button_takeover_schedule clicked!");
//		//	   console.log("ID: " + this.id);
//	})
//})( jQuery );




(function( $ ) {
	'use strict';
//
//	/**
//	 * All of the code for your public-facing JavaScript source
//	 * should reside in this file.
//	 *
//	 * Note: It has been assumed you will write jQuery code here, so the
//	 * $ function reference has been prepared for usage within the scope
//	 * of this function.
//	 *
//	 * This enables you to define handlers, for when the DOM is ready:
//	 *
//	 * $(function() {
//	 *
//	 * });
//	 *
//	 * When the window is loaded:
//	 *
//	 * $( window ).load(function() {
//	 *
//	 * });
//	 *
//	 * ...and/or other possibilities.
//	 *
//	 * Ideally, it is not considered best practise to attach more than a
//	 * single DOM-ready or window-load handler for a particular page.
//	 * Although scripts in the WordPress core, Plugins and Themes may be
//	 * practising this, we should strive to set a better example in our own work.
//	 */
//
//	// Some logging to verify if js is loaded
	console.log("rc_flight_manager_vars.ajax_url in jQuery:");
	console.log(rc_flight_manager_vars.ajax_url);
	//console.log( $ )	
//
	// Function called if any button with class "button_takeover_schedule" is called:
	$("#table_rc_flight_manager_schedule").on("click", ".button_takeover_schedule", (function() {
		console.log("button_takeover_schedule clicked!");
		var schedule_id = $(this).data("schedule_id");
		console.log("schedule_id = " + schedule_id)

		var data = {
			'action'   		: 'button_takeover', // the name of your PHP function!
			'schedule_id'   : schedule_id        // a random value we'd like to pass
		};
		
		$.post(ajaxurl, data, function (response) {
			console.log("Response = " + response);
			var receivingElement = "#table_row_schedule_id_" + schedule_id;
			console.log("receiving HTML Element: " + receivingElement);
			$(receivingElement).html(response);
		});

	   //console.log("ID: " + this.id);
//	   //console.log("Name: " + this.name);
//	   //console.log("Value: " + this.value);
//	   //var dutyId = "dutyId_" + this.value;
//	
//	   // Open a popup
	   //alert(`ID: ${this.id}<br>data-schedule_id:${test}`);

//	   //var data = {
//	   //   'action'   : 'takeover_duty',   // the name of your PHP function!
//	   //   'id'       : this.value         // a random value we'd like to pass
//	   //   };
//	   //
//	   //jQuery.post(ajaxurl, data, function (response) {
//	   //   console.log(response);
//	   //   receivingElement = "#" + dutyId;
//	   //   console.log("dutyId: " + dutyId);
//	   //   console.log("receiving HTML Element: " + receivingElement);
//	   //   jQuery(receivingElement).html(response);
//	   //});
	}));
})( jQuery );
