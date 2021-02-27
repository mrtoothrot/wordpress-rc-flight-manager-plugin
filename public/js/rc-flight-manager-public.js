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

	// If swap button is clicked, show service selection
	$("#table_rc_flight_manager_schedule").on("click", ".button_swap_schedule", (function() {
		console.log("button_swap_schedule clicked!");
		var schedule_id = $(this).data("schedule_id");
		var selection_id = "service_div_id_" + schedule_id;
		console.log("schedule_id = " + schedule_id)
		console.log("selection_id = " + selection_id)

		// Hide all buttons
		$(".button_swap_schedule").hide();
		$(".button_handover_schedule").hide();
		$(".button_takeover_schedule").hide();

		// Hide all selections
		$(".div_swap_schedule").addClass('hidden');
		$(".div_handover_schedule").addClass('hidden');
		
		// Let buttons disappear
		//$("#button_swap_schedule_id_" + schedule_id).hide();
		//$("#button_handover_schedule_id_" + schedule_id).hide();

		// Show selection
		$("#" + selection_id).removeClass('hidden');

	}));

	// If handover button is clicked, show user selection
	$("#table_rc_flight_manager_schedule").on("click", ".button_handover_schedule", (function() {
		console.log("button_handover_schedule clicked!");
		var schedule_id = $(this).data("schedule_id");
		var selection_id = "user_div_id_" + schedule_id;
		console.log("schedule_id = " + schedule_id)
		console.log("selection_id = " + selection_id)

		// Hide all buttons
		$(".button_swap_schedule").hide();
		$(".button_handover_schedule").hide();
		$(".button_takeover_schedule").hide();

		// Hide all selections
		$(".div_swap_schedule").addClass('hidden');
		$(".div_handover_schedule").addClass('hidden');
		
		// Let buttons disappear
		//$("#button_swap_schedule_id_" + schedule_id).hide();
		//$("#button_handover_schedule_id_" + schedule_id).hide();

		// Show selection
		$("#" + selection_id).removeClass('hidden');
		
	}));

	$("#table_rc_flight_manager_schedule").on("click", ".swap_disclaimer", (function() {
		var id = $(this).attr('value');
		//var id = $this.id;
		console.log("swap_disclaimer " + id + " clicked!");
		// If disclaimer is clicked, activate OK button
		if($("#swap_disclaimer_id_" + id).is(':checked')){
			console.log("swap_disclaimer checked!");
			$("#swap_ok_button_id_" + id).prop("disabled",false);
		}
		else {
			console.log("swap_disclaimer not checked!");
    		$("#swap_ok_button_id_" + id).prop("disabled",true);
		}
	}));

	$("#table_rc_flight_manager_schedule").on("click", ".handover_disclaimer", (function() {
		var id = $(this).attr('value');
		//var id = $this.id;
		console.log("handover_disclaimer " + id + " clicked!");
		// If disclaimer is clicked, activate OK button
		if($("#handover_disclaimer_id_" + id).is(':checked')){
			console.log("handover_disclaimer checked!");
			$("#handover_ok_button_id_" + id).prop("disabled",false);
		}
		else {
			console.log("handover_disclaimer not checked!");
    		$("#handover_ok_button_id_" + id).prop("disabled",true);
		}
	}));

	// If abort button is clicked go to default view
	$("#table_rc_flight_manager_schedule").on("click", ".abort_button", (function() {
		// Hide all selections
		$(".div_swap_schedule").addClass('hidden');
		$(".div_handover_schedule").addClass('hidden');
		
		// Show all buttons
		$(".button_swap_schedule").show();
		$(".button_handover_schedule").show();
		$(".button_takeover_schedule").show();
	}));

	// If ok button is clicked, update DB
	$("#table_rc_flight_manager_schedule").on("click", ".ok_button", (function() {
		var id = $(this).attr('value');
		var button_id = $(this).attr('id');
		console.log("ok_button clicked = " + button_id);
		
		// Hide all selections
		$(".div_swap_schedule").addClass('hidden');
		$(".div_handover_schedule").addClass('hidden');
		
		if ($(this).hasClass("swap_ok_button")) {
			var selection_id = "service_selection_id_" + id;
			// Get the selected duty 
			var selection = document.getElementById(selection_id);
			var serviceToSwap = selection.value;
			console.log("service_id = " + id);
			console.log("selection_id = " + selection_id);
			console.log("serviceToSwap = " + serviceToSwap);
		}
		if ($(this).hasClass("handover_ok_button")) {
			var selection_id = "user_selection_id_" + id;
			// Get the selected user 
			var selection = document.getElementById(selection_id);
			var handoverToUser = selection.value;
			console.log("service_id = " + id);
			console.log("selection_id = " + selection_id);
			console.log("handoverToUser = " + handoverToUser);

			var data = {
				'action'   		: 'button_handover', // the name of your PHP function!
				'schedule_id'   : id,		         // a random value we'd like to pass
				'new_user' 		: handoverToUser
			};
			
			$.post(ajaxurl, data, function (response) {
				console.log("Response = " + response);
				var receivingElement = "#table_row_schedule_id_" + id;
				console.log("receiving HTML Element: " + receivingElement);
				$(receivingElement).html(response);
				// Show all buttons
				$(".button_swap_schedule").show();
				$(".button_handover_schedule").show();
				$(".button_takeover_schedule").show();
			});
		}
		
	}));

})( jQuery );
