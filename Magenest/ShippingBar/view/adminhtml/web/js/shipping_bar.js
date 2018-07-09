require(
    [
        'jquery',
    ],
    function ($) {

        $("#close").on('click', function () {
            $("#status").remove();
        });

        var value = $("#shippingstore_wheretodisplay_page_display_1").prop( "checked" );

        if (value) {
            $("#shippingstore_wheretodisplay_page_display_2").prop("disabled", true);
            $("#shippingstore_wheretodisplay_page_display_2").prop("checked", false);
            $("#shippingstore_wheretodisplay_page_display_3").prop("disabled", true);
            $("#shippingstore_wheretodisplay_page_display_3").prop("checked", false);
            $("#shippingstore_wheretodisplay_page_display_4").prop("disabled", true);
            $("#shippingstore_wheretodisplay_page_display_4").prop("checked", false);
            $("#shippingstore_wheretodisplay_page_display_5").prop("disabled", true);
            $("#shippingstore_wheretodisplay_page_display_5").prop("checked", false);
        } else {
            $("#shippingstore_wheretodisplay_page_display_2").prop("disabled", false);
            $("#shippingstore_wheretodisplay_page_display_3").prop("disabled", false);
            $("#shippingstore_wheretodisplay_page_display_4").prop("disabled", false);
            $("#shippingstore_wheretodisplay_page_display_5").prop("disabled", false);
        }

        $("#shippingstore_wheretodisplay_page_display_1").on('click', function () {
            var value = $("#shippingstore_wheretodisplay_page_display_1").prop( "checked" );
            if (value){
                $("#shippingstore_wheretodisplay_page_display_2").prop("disabled", true);
                $("#shippingstore_wheretodisplay_page_display_2").prop("checked", false);
                $("#shippingstore_wheretodisplay_page_display_3").prop("disabled", true);
                $("#shippingstore_wheretodisplay_page_display_3").prop("checked", false);
                $("#shippingstore_wheretodisplay_page_display_4").prop("disabled", true);
                $("#shippingstore_wheretodisplay_page_display_4").prop("checked", false);
                $("#shippingstore_wheretodisplay_page_display_5").prop("disabled", true);
                $("#shippingstore_wheretodisplay_page_display_5").prop("checked", false);

            } else {
                $( "#shippingstore_wheretodisplay_page_display_2" ).prop( "disabled", false );
                $( "#shippingstore_wheretodisplay_page_display_3" ).prop( "disabled", false );
                $( "#shippingstore_wheretodisplay_page_display_4" ).prop( "disabled", false );
                $( "#shippingstore_wheretodisplay_page_display_5" ).prop( "disabled", false );
            }

        });

        var value = $("#shippingstore_schedule_date_enabled").val();
        if (value == 0) {
            $("#shippingstore_schedule_date_start_date").prop("disabled", true);
            $("#shippingstore_schedule_date_end_date").prop("disabled", true);
        } else {
            $("#shippingstore_schedule_date_start_date").prop("disabled", false);
            $("#shippingstore_schedule_date_end_date").prop("disabled", false);
        }

        $("#shippingstore_schedule_date_enabled").change(function(){
            var value = $("#shippingstore_schedule_date_enabled").val();
            if (value == 0) {
                $("#shippingstore_schedule_date_start_date").prop("disabled", true);
                $("#shippingstore_schedule_date_end_date").prop("disabled", true);
            } else {
                $("#shippingstore_schedule_date_start_date").prop("disabled", false);
                $("#shippingstore_schedule_date_end_date").prop("disabled", false);
            }
        });

        var value = $("#shippingstore_schedule_week_enabled").val();
        if (value == 0) {
            $("#shippingstore_schedule_week_day_of_week_Monday").prop("disabled", true);
            $("#shippingstore_schedule_week_day_of_week_Tuesday ").prop("disabled", true);
            $("#shippingstore_schedule_week_day_of_week_Wednesday").prop("disabled", true);
            $("#shippingstore_schedule_week_day_of_week_Thursday").prop("disabled", true);
            $("#shippingstore_schedule_week_day_of_week_Friday").prop("disabled", true);
            $("#shippingstore_schedule_week_day_of_week_Saturday").prop("disabled", true);
            $("#shippingstore_schedule_week_day_of_week_Sunday").prop("disabled", true);
        } else {
            $("#shippingstore_schedule_week_day_of_week_Monday").prop("disabled", false);
            $("#shippingstore_schedule_week_day_of_week_Tuesday ").prop("disabled", false);
            $("#shippingstore_schedule_week_day_of_week_Wednesday").prop("disabled", false);
            $("#shippingstore_schedule_week_day_of_week_Thursday").prop("disabled", false);
            $("#shippingstore_schedule_week_day_of_week_Friday").prop("disabled", false);
            $("#shippingstore_schedule_week_day_of_week_Saturday").prop("disabled", false);
            $("#shippingstore_schedule_week_day_of_week_Sunday").prop("disabled", false);
        }

        $("#shippingstore_schedule_week_enabled").change(function(){
            var value = $("#shippingstore_schedule_week_enabled").val();
            if (value == 0) {
                $("#shippingstore_schedule_week_day_of_week_Monday").prop("disabled", true);
                $("#shippingstore_schedule_week_day_of_week_Tuesday ").prop("disabled", true);
                $("#shippingstore_schedule_week_day_of_week_Wednesday").prop("disabled", true);
                $("#shippingstore_schedule_week_day_of_week_Thursday").prop("disabled", true);
                $("#shippingstore_schedule_week_day_of_week_Friday").prop("disabled", true);
                $("#shippingstore_schedule_week_day_of_week_Saturday").prop("disabled", true);
                $("#shippingstore_schedule_week_day_of_week_Sunday").prop("disabled", true);
                $("#shippingstore_schedule_week_day_of_week_Monday").prop("checked", false);
                $("#shippingstore_schedule_week_day_of_week_Tuesday ").prop("checked", false);
                $("#shippingstore_schedule_week_day_of_week_Wednesday").prop("checked", false);
                $("#shippingstore_schedule_week_day_of_week_Thursday").prop("checked", false);
                $("#shippingstore_schedule_week_day_of_week_Friday").prop("checked", false);
                $("#shippingstore_schedule_week_day_of_week_Saturday").prop("checked", false);
                $("#shippingstore_schedule_week_day_of_week_Sunday").prop("checked", false);
            } else {
                $("#shippingstore_schedule_week_day_of_week_Monday").prop("disabled", false);
                $("#shippingstore_schedule_week_day_of_week_Tuesday ").prop("disabled", false);
                $("#shippingstore_schedule_week_day_of_week_Wednesday").prop("disabled", false);
                $("#shippingstore_schedule_week_day_of_week_Thursday").prop("disabled", false);
                $("#shippingstore_schedule_week_day_of_week_Friday").prop("disabled", false);
                $("#shippingstore_schedule_week_day_of_week_Saturday").prop("disabled", false);
                $("#shippingstore_schedule_week_day_of_week_Sunday").prop("disabled", false);
            }
        });

        var value = $("#shippingstore_schedule_day_enabled").val();
        if (value == 0) {
            $('select[data-ui-id="time-groups-schedule-day-fields-start-time-value-hour"]').prop("disabled", true);
            $('select[data-ui-id="time-groups-schedule-day-fields-start-time-value-minute"]').prop("disabled", true);
            $('select[data-ui-id="time-groups-schedule-day-fields-start-time-value-second"]').prop("disabled", true);
            $('select[data-ui-id="time-groups-schedule-day-fields-end-time-value-hour"]').prop("disabled", true);
            $('select[data-ui-id="time-groups-schedule-day-fields-end-time-value-minute"]').prop("disabled", true);
            $('select[data-ui-id="time-groups-schedule-day-fields-end-time-value-second"]').prop("disabled", true);


        } else {
            $('select[data-ui-id="time-groups-schedule-day-fields-start-time-value-hour"]').prop("disabled", false);
            $('select[data-ui-id="time-groups-schedule-day-fields-start-time-value-minute"]').prop("disabled", false);
            $('select[data-ui-id="time-groups-schedule-day-fields-start-time-value-second"]').prop("disabled", false);
            $('select[data-ui-id="time-groups-schedule-day-fields-end-time-value-hour"]').prop("disabled", false);
            $('select[data-ui-id="time-groups-schedule-day-fields-end-time-value-minute"]').prop("disabled", false);
            $('select[data-ui-id="time-groups-schedule-day-fields-end-time-value-second"]').prop("disabled", false);
        }

        $("#shippingstore_schedule_day_enabled").change(function(){
            var value = $("#shippingstore_schedule_day_enabled").val();
            if (value == 0) {
                $('select[data-ui-id="time-groups-schedule-day-fields-start-time-value-hour"]').prop("disabled", true);
                $('select[data-ui-id="time-groups-schedule-day-fields-start-time-value-minute"]').prop("disabled", true);
                $('select[data-ui-id="time-groups-schedule-day-fields-start-time-value-second"]').prop("disabled", true);
                $('select[data-ui-id="time-groups-schedule-day-fields-end-time-value-hour"]').prop("disabled", true);
                $('select[data-ui-id="time-groups-schedule-day-fields-end-time-value-minute"]').prop("disabled", true);
                $('select[data-ui-id="time-groups-schedule-day-fields-end-time-value-second"]').prop("disabled", true);


            } else {
                $('select[data-ui-id="time-groups-schedule-day-fields-start-time-value-hour"]').prop("disabled", false);
                $('select[data-ui-id="time-groups-schedule-day-fields-start-time-value-minute"]').prop("disabled", false);
                $('select[data-ui-id="time-groups-schedule-day-fields-start-time-value-second"]').prop("disabled", false);
                $('select[data-ui-id="time-groups-schedule-day-fields-end-time-value-hour"]').prop("disabled", false);
                $('select[data-ui-id="time-groups-schedule-day-fields-end-time-value-minute"]').prop("disabled", false);
                $('select[data-ui-id="time-groups-schedule-day-fields-end-time-value-second"]').prop("disabled", false);
            }
        });



    });