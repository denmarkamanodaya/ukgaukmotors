jQuery.fn.calendarPicker = function(options) {
    // --------------------------  start default option values --------------------------
    if (!options.date) {
        options.date = new Date();
    }

    if (typeof(options.years) == "undefined")
        options.years=1;

    if (typeof(options.months) == "undefined")
        options.months=3;

    if (typeof(options.days) == "undefined")
        options.days=4;

    if (typeof(options.showDayArrows) == "undefined")
        options.showDayArrows=true;

    if (typeof(options.useWheel) == "undefined")
        options.useWheel=true;

    if (typeof(options.callbackDelay) == "undefined")
        options.callbackDelay=500;

    if (typeof(options.monthNames) == "undefined")
        options.monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    if (typeof(options.dayNames) == "undefined")
        options.dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    if (typeof(options.eventdates) == "undefined")
        options.eventdates = [];

    // --------------------------  end default option values --------------------------

    var calendar = {currentDate: options.date};
    calendar.options = options;

    //build the calendar on the first element in the set of matched elements.
    var theDiv = this.eq(0);//$(this);
    theDiv.addClass("calBox");

    //empty the div
    theDiv.empty();


    var divYears = $("<div>").addClass("calYear row");
    var divMonths = $("<div>").addClass("calMonth row");
    var divDays = $("<div>").addClass("calDay");


    theDiv.append(divYears).append(divMonths).append(divDays);

    calendar.changeDate = function(date) {
        $("#calEvents").html(' ');
        calendar.currentDate = date;

        var fillYears = function(date) {
            var year = date.getFullYear();
            var t = new Date();
            divYears.empty();
            var nc = options.years*2+1;
            var w = parseInt((theDiv.width()-4-(nc)*4)/nc)+"px";
            for (var i = year - options.years; i <= year + options.years; i++) {
                var d = new Date(date);
                d.setFullYear(i);
                var span = $("<span>").addClass("calElement").attr("millis", d.getTime()).html(i).css("width",w);
                if (d.getYear() == t.getYear())
                    span.addClass("today");
                if (d.getYear() == calendar.currentDate.getYear())
                    span.addClass("selected");
                divYears.append(span);
            }
        };

        var fillMonths = function(date) {
            var month = date.getMonth();
            var t = new Date();
            divMonths.empty();
            var oldday = date.getDay();
            var nc = options.months*2+1;
            var w = parseInt((theDiv.width()-4-(nc)*4)/nc)+"px";
            for (var i = -options.months; i <= options.months; i++) {
                var d = new Date(date);
                var oldday = d.getDate();
                d.setMonth(month + i);

                if (d.getDate() != oldday) {
                    d.setMonth(d.getMonth() - 1);
                    d.setDate(28);
                }
                var span = $("<span>").addClass("calElement").attr("millis", d.getTime()).html(options.monthNames[d.getMonth()]).css("width",w);
                if (d.getYear() == t.getYear() && d.getMonth() == t.getMonth())
                    span.addClass("today");
                if (d.getYear() == calendar.currentDate.getYear() && d.getMonth() == calendar.currentDate.getMonth())
                    span.addClass("selected");
                divMonths.append(span);

            }
        };

        var fillDays = function(date) {
            var day = date.getDate();
            var t = new Date();

            divDays.empty();
            var nc = options.days*2+1;
            var w = parseInt((theDiv.width()-4-(options.showDayArrows?12:0)-(nc)*4)/(nc-(options.showDayArrows?2:0)))+"px";

            for (var i = -options.days; i <= options.days; i++) {
                var d = new Date(date);
                d.setDate(day + i)
                var span = $("<span>").addClass("calElement").attr("millis", d.getTime())
                if (i == -options.days && options.showDayArrows) {
                    span.html('<i class="fas fa-angle-left"></i>').addClass("prev");
                } else if (i == options.days && options.showDayArrows) {
                    span.html('<i class="fas fa-angle-right"></i>').addClass("next");
                } else {
                    span.html("<div class='dayName col-md-12'>"+ options.dayNames[d.getDay()] +"</div><div class='dayNumber col-md-12'>" + d.getDate() + "</div><br><div class='dailyEvents col-md-12 de_"+ d.getFullYear() +"_"+ d.getMonth() +"_"+ d.getDate() +"'><span class='calendardotAdj'></span></div>").css("width",w);
                    if (d.getYear() == t.getYear() && d.getMonth() == t.getMonth() && d.getDate() == t.getDate())
                        span.addClass("today");
                    if (d.getYear() == calendar.currentDate.getYear() && d.getMonth() == calendar.currentDate.getMonth() && d.getDate() == calendar.currentDate.getDate())
                        span.addClass("selected");
                }
                divDays.append(span);

            }
        };

        var deferredCallBack = function() {
            if (typeof(options.callback) == "function") {
                if (calendar.timer)
                    clearTimeout(calendar.timer);
                getEvents(calendar);
                getMonthEvents(date);
                calendar.timer = setTimeout(function() {

                    options.callback(calendar);
                }, options.callbackDelay);
            }
        };

        fillYears(date);
        fillMonths(date);
        fillDays(date);

        deferredCallBack();

    };

    theDiv.click(function(ev) {
        var el = $(ev.target).closest(".calElement");
        if (el.hasClass("calElement")) {
            calendar.changeDate(new Date(parseInt(el.attr("millis"))));
        }
    });


    //if mousewheel
    if ($.event.special.mousewheel && options.useWheel) {
        divYears.mousewheel(function(event, delta) {
            var d = new Date(calendar.currentDate.getTime());
            d.setFullYear(d.getFullYear() + delta);
            calendar.changeDate(d);
            return false;
        });
        divMonths.mousewheel(function(event, delta) {
            var d = new Date(calendar.currentDate.getTime());
            d.setMonth(d.getMonth() + delta);
            calendar.changeDate(d);
            return false;
        });
        divDays.mousewheel(function(event, delta) {
            var d = new Date(calendar.currentDate.getTime());
            d.setDate(d.getDate() + delta);
            calendar.changeDate(d);
            return false;
        });
    }

    calendar.changeDate(options.date);
    return calendar;
};

var eventdates = [];

function getMonthEvents(date)
{
    if (date === undefined) date = calendarPicker.currentDate;
    var eventDate = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate();
    if ('m_'+date.getMonth() in eventdates === false) {
        $.ajax(
            {
                url : eventsUrl+'/monthly',
                type: "POST",
                dataType: 'json',
                data: {
                    _token: csrf_token,
                    caldate: eventDate,
                    filters: getFilters()
                }
            }).done(function(data){
            eventdates['m_'+date.getMonth()] = data;
            setMonthlyEvents(date, eventdates);
        });
    } else {
        setMonthlyEvents(date, eventdates);
    }
}

function setMonthlyEvents(date, eventdates) {
    if (date === undefined) date = calendarPicker.currentDate;
    for(var val in eventdates) {
        $.each( eventdates[val], function( key, value ) {
            var evMonth = val.replace("m_", "");
            var array = value.split(',')
            var out = '';
            for (var i in array) {
                out = out.concat('<span class="calendardot" style="background-color: '+ array[i] +'"></span>');
            }
            $('.de_'+date.getFullYear()+'_'+evMonth+'_'+key).html(out);
        });
    }

}

function getEvents(cal)
{
    showCalSpinner();
    var eventDate = cal.currentDate.getFullYear()+'-'+(cal.currentDate.getMonth()+1)+'-'+cal.currentDate.getDate();
    $.ajax(
        {
            url : eventsUrl,
            type: "POST",
            data: {
                _token: csrf_token,
                caldate: eventDate,
                filters: getFilters()
            }
        }).done(function(data){
        if(data.status === 'success')
        {
            $("#calEvents").html(data.events);
        }
        else if(data.status === 'error')
        {
            hideCalSpinner();
        }
        else
        {
            hideCalSpinner();
        }

    });
}

$('input[name="category[]"]').click(function(){
    eventdates = [];
    var date = calendarPicker.currentDate;
    getMonthEvents();
    calendarPicker.changeDate(date);
    setMonthlyEvents();
});

$('#resetFilters').click(function() {
    eventdates = [];
    var date = calendarPicker.currentDate;
    $('#filter-list :checkbox:enabled').prop('checked', false);
    calendarPicker.eventdates = [];
    calendarPicker.eventdates = getMonthEvents();
    calendarPicker.changeDate(date);
});

function showCalSpinner() {
    $("#calEvents").html('<div class="col-md-12"><span class="calSpinner"><i class="far fa-spinner fa-pulse"></i></span></div>');
}

function hideCalSpinner() {
    $("#calEvents").html('');
}

function initMap(latitude,longitude,id) {
    if(latitude === 0) return;
    var uluru = {lat: latitude, lng: longitude};
    var map = new google.maps.Map(document.getElementById('map_'+id), {
        zoom: 16,
        center: uluru
    });
    var marker = new google.maps.Marker({
        position: uluru,
        map: map
    });
}

function getFilters()
{
    var filterIDs = [];
    $("#filter-list input:checkbox:checked").map(function(){
        filterIDs.push($(this).val());
    });
    return filterIDs;
}

