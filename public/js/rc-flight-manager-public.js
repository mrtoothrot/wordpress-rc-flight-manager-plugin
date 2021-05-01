(function($) {
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
    //console.log("rc_flight_manager_vars.ajax_url in jQuery:");
    //console.log(rc_flight_manager_vars.ajax_url);
    //console.log(rc_flight_manager_vars.nonce);
    var ajaxurl = rc_flight_manager_vars.ajax_url;
    var nonce = rc_flight_manager_vars.security_nonce;
    var weekdays = [false, false, false, false, false, true, true];
    //console.log( $ )	
    //

    // Function called if any button with class "rcfm_takeover_btn" is called:
    $("#table_rc_flight_manager_schedule").on("click", ".rcfm_takeover_btn", (function() {
        console.log("rcfm_takeover_btn clicked!");
        var schedule_id = $(this).data("schedule_id");
        console.log("schedule_id = " + schedule_id)
            //console.log("nonce = " + nonce)

        var data = {
            'action': 'button_takeover', // the name of your PHP function!
            'security_nonce': nonce, // the security nonce
            'schedule_id': schedule_id // a random value we'd like to pass
        };

        $.post(ajaxurl, data, function(response) {
            console.log("Response = " + response);
            if (response == 'FALSE') {
                alert("Failed to assign user to service!")
            } else {
                var receivingElement = "#table_row_schedule_id_" + schedule_id;
                console.log("receiving HTML Element: " + receivingElement);
                $(receivingElement).html(response);
            }
        });
    }));

    // Function called if any button with class "rcfm_delete_btn" is called:
    $("#table_rc_flight_manager_schedule").on("click", ".rcfm_delete_btn", (function() {
        console.log("rcfm_delete_btn clicked!");
        var schedule_id = $(this).data("schedule_id");
        console.log("schedule_id = " + schedule_id)

        var data = {
            'action': 'button_delete', // the name of your PHP function!
            'security_nonce': nonce, // the security nonce
            'schedule_id': schedule_id // a random value we'd like to pass
        };

        $.post(ajaxurl, data, function(response) {
            console.log("Response = " + response);
            if (response == 'FALSE') {
                alert("Failed to delete date!")
            } else {
                // Remove table row
                var row = "#table_row_schedule_id_" + schedule_id;
                $(row).html("");
            }
        });
    }));

    // Function called if any button with class "rcfm_update_comment_btn" is called:
    $("#table_rc_flight_manager_schedule").on("click", ".rcfm_update_comment_btn", (function() {
        console.log("rcfm_update_comment_btn clicked!");
        var schedule_id = $(this).data("schedule_id");
        console.log("schedule_id = " + schedule_id)

        var data = {
            'action': 'button_update_comment', // the name of your PHP function!
            'security_nonce': nonce, // the security nonce
            'schedule_id': schedule_id // a random value we'd like to pass
        };

        $.post(ajaxurl, data, function(response) {
            console.log("Response = " + response);
            var receivingElement = "#modal-container";
            console.log("receiving HTML Element: " + receivingElement);
            $(receivingElement).html(response);
            var modal = document.getElementById("update_comment_btn_modal");
            modal.style.display = "block";
            //	// When the user clicks on <span> (x), close the modal
            $("#schedule").on("click", "span", (function() {
                modal.style.display = "none";
            }));
            // When the user clicks on abort button, close the modal
            $("#schedule").on("click", "#update_comment_btn_abort", (function() {
                modal.style.display = "none";
            }));
            // When the user clicks anywhere outside of the modal, close it
            $(window).on("click", (function() {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }));
            //	// When the user clicks on ok button, run the AJAX request and close the model
            $("#schedule").on("click", "#update_comment_btn_ok", (function() {
                // Logging
                console.log("update_comment_btn_ok clicked!");
                console.log("schedule_id = " + schedule_id)
                var comment = $('#addCommentField').val();
                console.log("comment entered = " + comment)
                    // Prepare AJAX request
                var data = {
                    'action': 'update_comment', // the name of your PHP function!
                    'security_nonce': nonce, // the security nonce
                    'schedule_id': schedule_id, // a random value we'd like to pass
                    'comment': comment // a random value we'd like to pass
                };
                // Send AJAX request
                $.post(ajaxurl, data, function(response) {
                    console.log("Response = " + response);
                    if (response == 'FALSE') {
                        alert("Comment could not be saved!")
                    } else {
                        var receivingElement = "#table_row_schedule_id_" + schedule_id;
                        console.log("receiving HTML Element: " + receivingElement);
                        $(receivingElement).html(response);
                    }
                });
                // Exit modal
                modal.style.display = "none";
                // Unbind event handler 
                $("#schedule").off("click", "#update_comment_btn_ok");
                // Empty modal-container
                $("#modal-container").html("");
            }));
        });
    }));

    // Function called if any button with class "rcfm_assign_btn" is called:
    $("#table_rc_flight_manager_schedule").on("click", ".rcfm_assign_btn", (function() {
        console.log("rcfm_assign_btn clicked!");
        var schedule_id = $(this).data("schedule_id");
        console.log("schedule_id = " + schedule_id)

        var data = {
            'action': 'button_assign', // the name of your PHP function!
            'security_nonce': nonce, // the security nonce
            'schedule_id': schedule_id // a random value we'd like to pass
        };

        $.post(ajaxurl, data, function(response) {
            console.log("Response = " + response);
            var receivingElement = "#modal-container";
            console.log("receiving HTML Element: " + receivingElement);
            $(receivingElement).html(response);
            var modal = document.getElementById("assign_btn_modal");
            modal.style.display = "block";
            //	// When the user clicks on <span> (x), close the modal
            $("#schedule").on("click", "span", (function() {
                modal.style.display = "none";
            }));
            // When the user clicks on abort button, close the modal
            $("#schedule").on("click", "#assign_btn_abort", (function() {
                modal.style.display = "none";
            }));
            // When the user clicks anywhere outside of the modal, close it
            $(window).on("click", (function() {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }));
            //	// When the user clicks on ok button, run the AJAX request and close the model
            $("#schedule").on("click", "#assign_btn_ok", (function() {
                // Logging
                console.log("assign_btn_ok clicked!");
                console.log("schedule_id = " + schedule_id)
                var selection = document.getElementById("userSelectionField");
                var user = selection.value;
                console.log("User selected = " + user)
                    // Prepare AJAX request
                var data = {
                    'action': 'assign_user', // the name of your PHP function!
                    'security_nonce': nonce, // the security nonce
                    'schedule_id': schedule_id, // a random value we'd like to pass
                    'user_id': user // a random value we'd like to pass
                };
                // Send AJAX request
                $.post(ajaxurl, data, function(response) {
                    console.log("Response = " + response);
                    if (response == 'FALSE') {
                        alert("Could not assign user!")
                    } else {
                        var receivingElement = "#table_row_schedule_id_" + schedule_id;
                        console.log("receiving HTML Element: " + receivingElement);
                        $(receivingElement).html(response);
                    }
                });
                // Exit modal
                modal.style.display = "none";
                // Unbind event handler 
                $("#schedule").off("click", "#assign_btn_ok");
                // Empty modal-container
                $("#modal-container").html("");
            }));
        });
    }));

    // Function called if any button with class "rcfm_swap_btn" is called:
    $("#table_rc_flight_manager_schedule").on("click", ".rcfm_swap_btn", (function() {
        console.log("rcfm_swap_btn clicked!");
        var schedule_id = $(this).data("schedule_id");
        console.log("schedule_id = " + schedule_id)

        var data = {
            'action': 'button_swap', // the name of your PHP function!
            'security_nonce': nonce, // the security nonce
            'schedule_id': schedule_id // a random value we'd like to pass
        };

        $.post(ajaxurl, data, function(response) {
            console.log("Response = " + response);
            var receivingElement = "#modal-container";
            console.log("receiving HTML Element: " + receivingElement);
            $(receivingElement).html(response);
            var modal = document.getElementById("swap_btn_modal");
            modal.style.display = "block";
            //	// When the user clicks on <span> (x), close the modal
            $("#schedule").on("click", "span", (function() {
                modal.style.display = "none";
            }));
            // When the user clicks on abort button, close the modal
            $("#schedule").on("click", "#swap_btn_abort", (function() {
                modal.style.display = "none";
            }));
            // When the user clicks anywhere outside of the modal, close it
            $(window).on("click", (function() {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }));
            // Disable ok button until disclaimer is marked
            $("#schedule").on("click", ".disclaimer", (function() {
                var id = $(this).attr('id');
                //var id = $this.id;
                console.log("disclaimer " + id + " clicked!");
                // If disclaimer is clicked, activate OK button
                if ($("#" + id).is(':checked')) {
                    console.log("disclaimer checked!");
                    $(".modal_ok").prop("disabled", false);
                } else {
                    console.log("disclaimer not checked!");
                    $(".modal_ok").prop("disabled", true);
                }
            }));
            //	// When the user clicks on ok button, run the AJAX request and close the model
            $("#schedule").on("click", "#swap_btn_ok", (function() {
                // Logging
                console.log("swap_btn_ok clicked!");
                console.log("schedule_id = " + schedule_id)
                var selection = document.getElementById("serviceSelectionField");
                var swap_schedule_id = selection.value;
                console.log("Swap Schedule selected = " + swap_schedule_id)
                    // Prepare AJAX request
                var data = {
                    'action': 'swap', // the name of your PHP function!
                    'security_nonce': nonce, // the security nonce
                    'schedule_id': schedule_id, // a random value we'd like to pass
                    'swap_schedule_id': swap_schedule_id // a random value we'd like to pass
                };
                // Send AJAX request
                $.post(ajaxurl, data, function(response) {
                    console.log("Response = " + response);
                    if (response == 'FALSE') {
                        alert("Could not swap!")
                    } else {
                        console.log("Response = " + response);
                        var responses = response.split(":SEP:", 2);
                        var receivingElement1 = "#table_row_schedule_id_" + schedule_id;
                        var receivingElement2 = "#table_row_schedule_id_" + swap_schedule_id;
                        console.log("receiving HTML Element for first service: " + receivingElement1);
                        console.log("receiving HTML Element for second service: " + receivingElement2);
                        $(receivingElement1).html(responses[0]);
                        $(receivingElement2).html(responses[1]);
                    }
                });
                // Exit modal
                modal.style.display = "none";
                // Unbind event handler 
                $("#schedule").off("click", "#swap_btn_ok");
                // Empty modal-container
                $("#modal-container").html("");
            }));
        });
    }));

    // Function called if any button with class "rcfm_handover_btn" is called:
    $("#table_rc_flight_manager_schedule").on("click", ".rcfm_handover_btn", (function() {
        console.log("rcfm_handover_btn clicked!");
        var schedule_id = $(this).data("schedule_id");
        console.log("schedule_id = " + schedule_id)

        var data = {
            'action': 'button_handover', // the name of your PHP function!
            'security_nonce': nonce, // the security nonce
            'schedule_id': schedule_id // a random value we'd like to pass
        };

        $.post(ajaxurl, data, function(response) {
            console.log("Response = " + response);
            var receivingElement = "#modal-container";
            console.log("receiving HTML Element: " + receivingElement);
            $(receivingElement).html(response);
            var modal = document.getElementById("handover_btn_modal");
            modal.style.display = "block";
            //	// When the user clicks on <span> (x), close the modal
            $("#schedule").on("click", "span", (function() {
                modal.style.display = "none";
            }));
            // When the user clicks on abort button, close the modal
            $("#schedule").on("click", "#handover_btn_abort", (function() {
                modal.style.display = "none";
            }));
            // When the user clicks anywhere outside of the modal, close it
            $(window).on("click", (function() {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }));
            // Disable ok button until disclaimer is marked
            $("#schedule").on("click", ".disclaimer", (function() {
                var id = $(this).attr('id');
                //var id = $this.id;
                console.log("disclaimer " + id + " clicked!");
                // If disclaimer is clicked, activate OK button
                if ($("#" + id).is(':checked')) {
                    console.log("disclaimer checked!");
                    $(".modal_ok").prop("disabled", false);
                } else {
                    console.log("disclaimer not checked!");
                    $(".modal_ok").prop("disabled", true);
                }
            }));
            //	// When the user clicks on ok button, run the AJAX request and close the model
            $("#schedule").on("click", "#handover_btn_ok", (function() {
                // Logging
                console.log("handover_btn_ok clicked!");
                console.log("schedule_id = " + schedule_id)
                var selection = document.getElementById("userSelectionField");
                var swap_user = selection.value;
                console.log("Swap user selected = " + swap_user)
                    // Prepare AJAX request
                var data = {
                    'action': 'handover', // the name of your PHP function!
                    'security_nonce': nonce, // the security nonce
                    'schedule_id': schedule_id, // a random value we'd like to pass
                    'new_user': swap_user // a random value we'd like to pass
                };
                // Send AJAX request
                $.post(ajaxurl, data, function(response) {
                    console.log("Response = " + response);
                    if (response == 'FALSE') {
                        alert("Could not handover!")
                    } else {
                        console.log("Response = " + response);
                        var receivingElement = "#table_row_schedule_id_" + schedule_id;
                        console.log("receiving HTML Element: " + receivingElement);
                        $(receivingElement).html(response);
                    }
                });
                // Exit modal
                modal.style.display = "none";
                // Unbind event handler 
                $("#schedule").off("click", "#handover_btn_ok");
                // Empty modal-container
                $("#modal-container").html("");
            }));
        });
    }));

    // Function called if any button with class "button_book_flightslot" is called:
    $("#table_rc_flight_manager_flightslots").on("click", ".button_book_flightslot", (function() {
        // Logging
        console.log("button_book_flightslot clicked!");
        var reservation_id = $(this).data("reservation_id");
        console.log("reservation_id = " + reservation_id)

        // Prepare AJAX request
        var data = {
            'action': 'button_book_flightslot', // the name of your PHP function!
            'security_nonce': nonce, // the security nonce
            'reservation_id': reservation_id // a random value we'd like to pass
        };

        // Send AJAX request
        $.post(ajaxurl, data, function(response) {
            console.log("Response = " + response);
            if (response == 'FALSE') {
                alert("Failed to book flightslot!")
            } else {
                var receivingElement = "#table_row_reservation_id_" + reservation_id;
                console.log("receiving HTML Element: " + receivingElement);
                $(receivingElement).html(response);
            }
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
            'action': 'button_cancel_flightslot', // the name of your PHP function!
            'security_nonce': nonce, // the security nonce
            'reservation_id': reservation_id // a random value we'd like to pass
        };

        // Send AJAX request
        $.post(ajaxurl, data, function(response) {
            console.log("Response = " + response);
            if (response == 'FALSE') {
                alert("Failed to cancel flightslot!")
            } else {
                var receivingElement = "#table_row_reservation_id_" + reservation_id;
                console.log("receiving HTML Element: " + receivingElement);
                $(receivingElement).html(response);
            }
        });
    }));

    // ********************************
    // ****** Modal for add_date_btn
    // When the user clicks the button, open the modal 
    $("#schedule").on("click", "#add_date_btn", (function() {
        var modal = document.getElementById("add_date_btn_modal");
        console.log("add_date_btn clicked!");
        modal.style.display = "block";
    }));
    // When the user clicks on <span> (x), close the modal
    $("#schedule").on("click", "span", (function() {
        var modal = document.getElementById("add_date_btn_modal");
        modal.style.display = "none";
    }));
    // When the user clicks on abort button, close the modal
    $("#schedule").on("click", "#add_date_btn_abort", (function() {
        var modal = document.getElementById("add_date_btn_modal");
        modal.style.display = "none";
    }));
    // When the user clicks anywhere outside of the modal, close it
    $(window).on("click", (function() {
        var modal = document.getElementById("add_date_btn_modal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }));
    // When the user clicks on ok button, run the AJAX request and close the model
    $("#schedule").on("click", "#add_date_btn_ok", (function() {
        var modal = document.getElementById("add_date_btn_modal");
        // Logging
        console.log("add_date_btn_ok clicked!");
        var date_obj = new Date($('#addDateField').val());
        if (!isNaN(date_obj.getDate())) { // Only continue if valid date is selected
            var year = date_obj.getFullYear();
            var month = date_obj.getMonth() + 1;
            var day = date_obj.getDate();
            var date = [year, month, day].join('-');
            console.log("date picked = " + date)

            // Prepare AJAX request
            var data = {
                'action': 'add_schedule_date', // the name of your PHP function!
                'security_nonce': nonce, // the security nonce
                'date': date // a random value we'd like to pass
            };

            // Send AJAX request
            $.post(ajaxurl, data, function(response) {
                console.log("Response = " + response);
                if (response != 'OK') {
                    alert(response)
                }
                location.reload();
            });
            modal.style.display = "none";
        }
    }));
    // ****** END add_date_btn modal
    // ********************************


    // ********************************
    // ****** Modal for add_date_range_btn    
    // When the user clicks the button, open the modal 
    $("#schedule").on("click", "#add_date_range_btn", (function() {
        var modal = document.getElementById("add_date_range_btn_modal");
        console.log("add_date_range_btn clicked!");
        modal.style.display = "block";
    }));
    // When the user clicks on <span> (x), close the modal
    $("#schedule").on("click", "span", (function() {
        var modal = document.getElementById("add_date_range_btn_modal");
        //span.onclick = function() {
        modal.style.display = "none";
    }));
    // When the user clicks on abort button, close the modal
    $("#schedule").on("click", "#add_date_range_btn_abort", (function() {
        var modal = document.getElementById("add_date_range_btn_modal");
        modal.style.display = "none";
    }));
    // When the user clicks anywhere outside of the modal, close it
    $(window).on("click", (function() {
        var modal = document.getElementById("add_date_range_btn_modal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }));

    // Check weekday selection
    $("#schedule").on("click", ".weekdayselect", (function() {
        var day = $(this).attr('id');
        console.log("day " + day + " clicked!");

        if ($("#" + day).is(':checked')) {
            console.log(day + " checked!");
            weekdays[day] = true
        } else {
            console.log(day + " not checked!");
            weekdays[day] = false
        }
        console.log("Selected weekdays:" + weekdays)
    }));

    // When the user clicks on ok button, run the AJAX request and close the model
    $("#schedule").on("click", "#add_date_range_btn_ok", (function() {
        var modal = document.getElementById("add_date_range_btn_modal");
        // Logging
        console.log("add_date_range_btn_ok clicked!");
        var from_date_obj = new Date($('#fromDateField').val());
        var to_date_obj = new Date($('#toDateField').val());

        if (!(isNaN(from_date_obj.getDate()) || isNaN(to_date_obj.getDate()))) { // Only continue if valid dates are selected
            var year = from_date_obj.getFullYear();
            var month = from_date_obj.getMonth() + 1;
            var day = from_date_obj.getDate();
            var from_date = [year, month, day].join('-');

            var year = to_date_obj.getFullYear();
            var month = to_date_obj.getMonth() + 1;
            var day = to_date_obj.getDate();
            var to_date = [year, month, day].join('-');

            console.log("from date picked = " + from_date)
            console.log("to date picked = " + to_date)
            console.log("weekdays = " + weekdays)

            // Prepare AJAX request
            var data = {
                'action': 'add_schedule_date_range', // the name of your PHP function!
                'security_nonce': nonce, // the security nonce
                'fromdate': from_date, // a random value we'd like to pass
                'todate': to_date, // a random value we'd like to pass
                'weekdays': weekdays
            };

            // Send AJAX request
            $.post(ajaxurl, data, function(response) {
                console.log("Response = " + response);
                if (response != "OK") {
                    alert(response)
                }
                //location.reload();
            });
            modal.style.display = "none";
        }
    }));

    // ****** END add_date_range_btn modal
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

})(jQuery);