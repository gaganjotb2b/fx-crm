/**
 * Conditional Custom Events
 * jQuery Plugin
 * based on setInterval()
 */
var root_eliment = false;
; (function ($, window, undefined) {
    // Define conditional events
    // var root_eliment = '';
    $.fn.appearConfig = function (child_element) {
        root_eliment = this;
        var cond_status = false;
        function defineCondEvents(element, events) {
            var intervals = [];
            var delay = 10;
            $.each(events, function (index, eventObj) {
                if (eventObj['condition'] && eventObj['condition']) {
                    intervals.push(
                        setInterval(function () {
                            if (eventObj['condition'].apply(element)) {
                                if (!eventObj.occured) {
                                    eventObj.occured = true;
                                    $(element).trigger(eventObj['name']);
                                }
                            }
                            else {
                                if (eventObj.occured) {
                                    eventObj.occured = false;
                                }
                            }
                        }, delay)
                    );
                }
            });

            return intervals;
        }

        // Remove defined conditional events
        function removeCondEvents(element, events) {
            $.each(events, function (index, intervalId) {
                clearInterval(intervalId);
            });
            return null;
        }

        // jQuery plugin wrapper
        $.fn.condEvents = function (events) {
            root_eliment = this;
            return this.each(function () {
                var defined = $.data(this, 'condevents');
                if (!defined && events) {
                    $.data(this, 'condevents', defineCondEvents(this, events));
                }
                else if (defined && !events) {
                    $.data(this, 'condevents', removeCondEvents(this, defined));
                }
                else {
                    return false;
                }
            });
        }

        // call condition on scroll
        // if (!$(child_element).is(':visible')) {
        //     console.log('not visible');
        // }
        $(root_eliment).scroll(function () {
            /* Check the location of each desired element */
            $(child_element).each(function (i) {
                var bottom_of_object = $(this).offset().top + $(this).outerHeight();
                var bottom_of_window = $(root_eliment).scrollTop() + $(root_eliment).height();

                /* If the object is completely visible in the window, fade it in */
                if (bottom_of_window > bottom_of_object) {
                    cond_status = true;
                    check_appar_condition(cond_status)
                }
                else {
                    cond_status = false;
                    check_appar_condition(cond_status);
                }
            });

        });
        function check_appar_condition(cond) {
            $(root_eliment).condEvents([
                {
                    // event name
                    name: 'hscrollshows',
                    // condition for event triggering
                    condition: function () {
                        return ($('body')[0].scrollWidth > $(window).width());
                    }
                },
                {
                    name: 'appear2',
                    condition: function () {
                        return (cond);
                    }
                }
            ]);
        }
        return this;
    }

}(jQuery, window));

// Example of usage:
// defining 'scrollbar shows' and 'scrollbar hides' events for window


// Adding events handlers
$(window).on('hscrollshows', function () { console.log('hscrollshows'); });
// initialize the root element
// $("main").appearConfig('.table-borderless').on('appear2', function () {
//     console.log('appear2');
//     console.log('my name');
// });
// condition.

// Evoking method without params will remove all defined
// conditional custom events of element
// $(window).condEvents();
