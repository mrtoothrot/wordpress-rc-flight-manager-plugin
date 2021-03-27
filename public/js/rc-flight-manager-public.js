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
	var ajaxurl = rc_flight_manager_vars.ajax_url;
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

	// If assign button is clicked, show user selection
	$("#table_rc_flight_manager_schedule").on("click", ".button_assign_schedule", (function() {
		console.log("button_assign_schedule clicked!");
		var schedule_id = $(this).data("schedule_id");
		var selection_id = "user_div_id_" + schedule_id;
		console.log("schedule_id = " + schedule_id)
		console.log("selection_id = " + selection_id)

		// Hide all buttons
		$(".button_swap_schedule").hide();
		$(".button_handover_schedule").hide();
		$(".button_takeover_schedule").hide();
		$(".button_assign_schedule").hide();

		// Hide all selections
		$(".div_swap_schedule").addClass('hidden');
		$(".div_handover_schedule").addClass('hidden');
		$(".div_assign_schedule").addClass('hidden');
		
		// Let buttons disappear
		//$("#button_swap_schedule_id_" + schedule_id).hide();
		//$("#button_handover_schedule_id_" + schedule_id).hide();

		// Show selection
		$("#" + selection_id).removeClass('hidden');

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
		$(".button_assign_schedule").hide();

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
		$(".button_assign_schedule").hide();

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
		$(".div_assign_schedule").addClass('hidden');
		
		// Show all buttons
		$(".button_swap_schedule").show();
		$(".button_handover_schedule").show();
		$(".button_takeover_schedule").show();
		$(".button_assign_schedule").show();
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

			var data = {
				'action'   			: 'button_swap', // the name of your PHP function!
				'schedule_id'   	: id,		         // a random value we'd like to pass
				'swap_schedule_id' 	: serviceToSwap
			};
			
			$.post(ajaxurl, data, function (response) {
				console.log("Response = " + response);
				var responses = response.split(":SEP:", 2);
				var receivingElement1 = "#table_row_schedule_id_" + id;
				var receivingElement2 = "#table_row_schedule_id_" + serviceToSwap;
				console.log("receiving HTML Element for first service: " + receivingElement1);
				console.log("receiving HTML Element for second service: " + receivingElement2);
				$(receivingElement1).html(responses[0]);
				$(receivingElement2).html(responses[1]);
				
				// Show all buttons
				$(".button_swap_schedule").show();
				$(".button_handover_schedule").show();
				$(".button_takeover_schedule").show();
				$(".button_assign_schedule").show();
			});
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
				$(".button_assign_schedule").show();
			});
		}
		if ($(this).hasClass("assign_ok_button")) {
			var selection_id = "user_selection_id_" + id;
			// Get the selected user 
			var selection = document.getElementById(selection_id);
			var assignToUser = selection.value;
			console.log("service_id = " + id);
			console.log("selection_id = " + selection_id);
			console.log("assignToUser = " + assignToUser);

			var data = {
				'action'   		: 'button_assign',   // the name of your PHP function!
				'schedule_id'   : id,		         // a random value we'd like to pass
				'new_user' 		: assignToUser
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
				$(".button_assign_schedule").show();
			});
		}
		
	}));

	// Function called if any button with class "button_book_flightslot" is called:
	$("#table_rc_flight_manager_flightslots").on("click", ".button_book_flightslot", (function() {
		// Logging
		console.log("button_book_flightslot clicked!");
		var reservation_id = $(this).data("reservation_id");
		console.log("reservation_id = " + reservation_id)

		// Prepare AJAX request
		var data = {
			'action'   		: 'button_book_flightslot', // the name of your PHP function!
			'reservation_id': reservation_id            // a random value we'd like to pass
		};
		
		// Send AJAX request
		$.post(ajaxurl, data, function (response) {
			console.log("Response = " + response);
			var receivingElement = "#table_row_reservation_id_" + reservation_id;
			console.log("receiving HTML Element: " + receivingElement);
			$(receivingElement).html(response);
		});
	}));

	// Function called if any button with class "button_cancel_flightslot" is called:
	$("#table_rc_flight_manager_flightslots").on("click", ".button_cancel_flightslot", (function() {
		// Logging
		console.log("button_cancel_flightslot clicked!");
		var reservation_id = $(this).data("reservation_id");
		console.log("reservation_id = " + reservation_id)

		// Prepare AJAX request
		var data = {
			'action'   		: 'button_cancel_flightslot', // the name of your PHP function!
			'reservation_id': reservation_id            // a random value we'd like to pass
		};
		
		// Send AJAX request
		$.post(ajaxurl, data, function (response) {
			console.log("Response = " + response);
			var receivingElement = "#table_row_reservation_id_" + reservation_id;
			console.log("receiving HTML Element: " + receivingElement);
			$(receivingElement).html(response);
		});
	}));

	// ********************************
	// ****** Modal for add_date_btn
	// Get the modal
	var modal = document.getElementById("add_date_btn_modal");
	// Get the button that opens the modal
	var btn = document.getElementById("add_date_btn");
	// Get the <span> element that closes the modal
	var span = document.getElementsByClassName("close")[0];
	// When the user clicks the button, open the modal 
	$("#table_rc_flight_manager_schedule").on("click", "add_date_btn", (function() {
	//add_date_btn.onclick = function() {
		modal.style.display = "block";
	}));
	// When the user clicks on <span> (x), close the modal
	$("#table_rc_flight_manager_schedule").on("click", "span", (function() {
	//span.onclick = function() {
		modal.style.display = "none";
	}));
	// When the user clicks on abort button, close the modal
	$("#table_rc_flight_manager_schedule").on("click", "add_date_btn_abort", (function() {
	//add_date_btn_abort.onclick = function() {
		modal.style.display = "none";
	}));
	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal) {
	  	modal.style.display = "none";
		}
  	}
	// When the user clicks on ok button, run the AJAX request and close the model
	$("#table_rc_flight_manager_schedule").on("click", "add_date_btn_ok", (function() {
	//add_date_btn_ok.onclick = function() {
		// Logging
		console.log("add_date_btn_ok clicked!");
		var date_obj = new Date($('#addDateField').val());
		var year = date_obj.getFullYear();
		var month = date_obj.getMonth() + 1;
		var day = date_obj.getDate();
  		var date = [year, month, day].join('-');
		console.log("date picked = " + date)

		// Prepare AJAX request
		var data = {
			'action'   		: 'add_schedule_date'   , // the name of your PHP function!
			'date'			: date                    // a random value we'd like to pass
		};
		
		// Send AJAX request
		$.post(ajaxurl, data, function (response) {
			console.log("Response = " + response);
			if( response == 'FALSE') {
				alert("Date already exists!")
			}
			location.reload();
		});
		modal.style.display = "none";
	}));
	// ****** END add_date_btn modal
	// ********************************

	// ********************************
	// ****** Dropdown button
	/* When the user clicks on the button, toggle between hiding and showing the dropdown content */
	//.dropbtn.onclick = function() {
	//	document.getElementById("myDropdown").classList.toggle("show");
  	//}
	$("#table_rc_flight_manager_schedule").on("click", ".dropbtn", (function() {
		var schedule_id = $(this).data("schedule_id");
		var dd_button = "dropdown_id_" + schedule_id
		console.log("Change button pressed: " + dd_button)
		// Close all other dropdowns
		var dropdowns = document.getElementsByClassName("dropdown-content");
		var i;
		for (i = 0; i < dropdowns.length; i++) {
		  var openDropdown = dropdowns[i];
		  if (openDropdown.classList.contains('show')) {
			openDropdown.classList.remove('show');
		  }
		}	
		// Now open the clicked one
		document.getElementById(dd_button).classList.toggle("show");
	}));

  	// Close the dropdown menu if the user clicks outside of it
  	window.onclick = function(event) {
	if (!event.target.matches('.dropbtn')) {
	  var dropdowns = document.getElementsByClassName("dropdown-content");
	  var i;
	  for (i = 0; i < dropdowns.length; i++) {
		var openDropdown = dropdowns[i];
		if (openDropdown.classList.contains('show')) {
		  openDropdown.classList.remove('show');
		}
	  }
	}
  }
  // ****** END Dropdown
  // *****************************

})( jQuery );
