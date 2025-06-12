(function ($) {

    let Calendar = function($container, options) {
        let obj  = this;
        jQuery.extend(obj.options, options);

        // Special locale for moment
       /* moment.locale('bupro', {
            months: obj.options.l10n.datePicker.monthNames,
            monthsShort: obj.options.l10n.datePicker.monthNamesShort,
            weekdays: obj.options.l10n.datePicker.dayNames,
            weekdaysShort: obj.options.l10n.datePicker.dayNamesShort,
            meridiem : function (hours, minutes, isLower) {
                return hours < 12
                    ? obj.options.l10n.datePicker.meridiem[isLower ? 'am' : 'AM']
                    : obj.options.l10n.datePicker.meridiem[isLower ? 'pm' : 'PM'];
            },
        }); */

        // Settings for Event Calendar
        let settings = {
            view: 'timeGridWeek',
            views: {
                dayGridMonth: {
                    dayHeaderFormat: function (date) {
                        return moment(date).locale('bupro').format('ddd');
                    },
                    displayEventEnd: true
                },
                timeGridDay: {
                    dayHeaderFormat: function (date) {
                        return moment(date).locale('bupro').format('dddd');
                    }
                },
            },
            hiddenDays: obj.options.l10n.hiddenDays,
            slotDuration:  obj.options.l10n.slotDuration,
            slotMinTime: obj.options.l10n.slotMinTime,
            slotMaxTime: obj.options.l10n.slotMaxTime,
            scrollTime: obj.options.l10n.scrollTime,
            flexibleSlotTimeLimits: true,
            slotLabelFormat: function (date) {
                return moment(date).locale('bupro').format(obj.options.l10n.mjsTimeFormat);
            },
            eventTimeFormat: function (date) {
                return moment(date).locale('bupro').format(obj.options.l10n.mjsTimeFormat);
            },
            dayHeaderFormat: function (date) {
                return moment(date).locale('bupro').format('ddd, D');
            },
            listDayFormat: function (date) {
                return moment(date).locale('bupro').format('dddd');
            },
            
           /// firstDay: GETNoL10n.startOfWeek,
            locale: obj.options.l10n.locale.replace('_', '-'),
            buttonText: {
                today: obj.options.l10n.today,
                dayGridMonth: obj.options.l10n.month,
                timeGridWeek: obj.options.l10n.week,
                timeGridDay: obj.options.l10n.day,
                resourceTimeGridDay: obj.options.l10n.day,
                listWeek: obj.options.l10n.list
            },
            noEventsContent: obj.options.l10n.noEvents,
           
            eventSources: [{
                url: ajaxurl,
                extraParams: function () {
                    return {
                        action: 'get_all_staff_appointments',
                        csrf_token: obj.options.l10n.csrf_token,
                        staff_id: obj.options.getStaffMemberIds(),
                        location_id: obj.options.getLocationIds(),
                        service_id: obj.options.getServiceIds()
                    };
                }
            }],
            
          /*  eventBackgroundColor: '#ccc',*/
            
          /*  eventMouseEnter: function(arg) {
                if (arg.event.display === 'auto' && arg.view.type !== 'listWeek') {
                    let $event = $(arg.el)
                    let $popover = $event.find('.getbwp-ec-popover');
                    let offset = $event.offset();
                    let top = Math.max($popover.outerHeight() + 40, Math.max($event.closest('.ec-body').offset().top, offset.top) - $(document).scrollTop());
                    $popover.css('top', (top - $popover.outerHeight() - 4) + 'px')
                    $popover.css('left', (offset.left + 2) + 'px')
                }
            }, */
            
            eventContent: function (arg) {
                if (arg.event.display === 'background') {
                    return '';
                }
                let event = arg.event;
                let props = event.extendedProps;
                let nodes = [];
                let $time = $('<div class="ec-event-time"/>');
                let $title = $('<div class="ec-event-title"/>');

               $time.append(props.header_text || arg.timeText);
                
                nodes.push($time.get(0));
                
                if (arg.view.type === 'listWeek') {
                    let dot = $('<div class="ec-event-dot"></div>').css('border-color', event.backgroundColor);
                    nodes.push($('<div/>').append(dot).get(0));
                }
                
                $title.append(props.desc || '');
                nodes.push($title.get(0));

                switch (props.overall_status) {
                    case 'pending':
                        $time.addClass('text-muted');
                        $title.addClass('text-muted');
                        break;
                    case 'rejected':
                    case 'cancelled':
                        $time.addClass('text-muted').wrapInner('<s>');
                        $title.addClass('text-muted');
                        break;
                }

                const $buttons = $('<div class="mt-2 d-flex"/>');
                
              
                
                $buttons.append(
                    $('<a class="btn btn-danger btn-sm text-white">').append('<i class="far fa-fw fa-trash-alt">')
                        .attr('title', obj.options.l10n.delete)
                        .on('click', function (e) {
                            e.stopPropagation();
                            
                            // Localize contains only string values
                            if (obj.options.l10n.recurring_appointments.active == '1' && props.series_id) {
                                
                                $(document.body).trigger('recurring_appointments.delete_dialog', [calendar, arg.event]);
                            } else {
                                
                                $('#bupro-delete-dialog').data('calEvent', arg.event).buproModal('show');
                            }
                        })
                );

             if (arg.view.type !== 'listWeek') {
                   // $buttons.addClass('border-top pt-2 justify-content-end');
                   // let $popover =  $('<div class="bupro-popover bs-popover-top bupro-ec-popover">')
                   // let $arrow = $('<div class="arrow" style="left:8px;">');
                  //  let $body = $('<div class="popover-body">');
                  //  $body.append(props.tooltip).append($buttons).css({minWidth: '200px'});
                  //  $popover.append($arrow).append($body);
                  //  nodes.push($popover.get(0));
                } else {
                   // $title.append($buttons);
                }

                return {domNodes: nodes};
            },
            
            eventClick: function (arg) {
                
                if (arg.event.display === 'background') {
                    return;
                }
                arg.jsEvent.stopPropagation();
                var visible_staff_id;
                if (arg.view.type === 'resourceTimeGridDay') {
                    visible_staff_id = 0;
                } else {
                    visible_staff_id = obj.options.getCurrentStaffId();
                }
                
               
                getbwp_edit_appointment_inline(arg.event.id,null,'no');


            },
            dateClick: function (arg) {
                let staff_id, visible_staff_id;
                if (arg.view.type === 'resourceTimeGridDay') {
                    staff_id = arg.resource.id;
                    visible_staff_id = 0;
                } else {
                    staff_id = visible_staff_id = obj.options.getCurrentStaffId();
                }
                
                //edit custom
                addAppointmentDialog(arg.date, staff_id, visible_staff_id);
            },
            noEventsClick: function (arg) {
                               
            },
            loading: function (isLoading) {
               
                if (isLoading) {
                  // BuproL10nAppDialog.refreshed = true;
                   // if (dateSetFromDatePicker) {
                    //    dateSetFromDatePicker = false;
                   // } else {
                       // calendar.setOption('highlightedDates', []);
                   // }
                    $('.getbwp-ec-loading').show();
                    jQuery("#getbwp-spinner").show();
                    
                } else {
                    $('.getbwp-ec-loading').hide();
                    jQuery("#getbwp-spinner").hide();
                    obj.options.refresh();
                }
                
                
            },
            viewDidMount: function (view) {
                calendar.setOption('highlightedDates', []);
                obj.options.viewChanged(view);
            },
            theme: function (theme) {
                theme.button = 'btn btn-default';
                theme.buttonGroup = 'btn-group';
                theme.active = 'active';
                return theme;
            }
        };


      let dateSetFromDatePicker = false;

        let calendar = new window.EventCalendar($container.get(0), $.extend(true, {}, settings, obj.options.calendar));

        // Init date picker for fast navigation in Event Calendar.
        
         
        $('.ec-toolbar .ec-title', $container).daterangepicker({
            parentEl        : '.getbwp-calendar-element',
            singleDatePicker: true,
            showDropdowns   : true,
            autoUpdateInput : false,
            locale          : obj.options.l10n.datePicker
        }).on('apply.daterangepicker', function (ev, picker) {
            
            dateSetFromDatePicker = true;
            if (calendar.view.type !== 'timeGridDay' && calendar.view.type !== 'resourceTimeGridDay') {
                calendar.setOption('highlightedDates', [picker.startDate.toDate()]);
            }
            
            calendar.setOption('date', picker.startDate.toDate());
        });

        /**
         * On delete appointment click.
         */
        $('#getbwp-delete-dialog').off().on('click', '#getbwp-delete', function () {
            var $modal   = $(this).closest('.getbwp-modal'),
                calEvent = $modal.data('calEvent'),
                ladda    = Ladda.create(this);
            ladda.start();
            $.ajax({
                type       : 'POST',
                url        : ajaxurl,
                data       : {
                    action        : 'getbwp_delete_appointment',
                    csrf_token    : obj.options.l10n.csrf_token,
                    appointment_id: calEvent.id
                },
                dataType   : 'json',
                xhrFields  : {withCredentials: true},
                crossDomain: 'withCredentials' in new XMLHttpRequest(),
                success    : function (response) {
                    ladda.stop();
                    calendar.removeEvent(calEvent.id);
                    
                   
                    
                    
                }
            });
        });

        // Export calendar
        this.ec = calendar;
    };

    var locationChanged = false;
    $('body').on('change', '#getbwp-appointment-location', function() {
        locationChanged = true;
    });

    Calendar.prototype.options = {
        calendar: {},
        getCurrentStaffId: function () { return -1; },
        getStaffMemberIds: function () { return [this.getCurrentStaffId()]; },
        getServiceIds: function () { return ['all']; },
       // getLocationIds: function () { return ['all']; },
        getLocationIds: function () { return this.getLocationIds(); },
        refresh: function () {},
        viewChanged: function () {},
        l10n: {}
    };

    window.GETBPCalendar = Calendar;
    
    
    
})(jQuery);