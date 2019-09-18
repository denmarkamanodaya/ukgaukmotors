// Instance the tour
var tour = new Tour({
    storage: false,
    backdrop: true,
    steps: [
        {
            element: "#startTour",
            title: "Welcome to your car feed",
            content: "Here you can set up and save custom search feeds.",
            orphan: true,
            placement: "top"
        },
        {
            element: "#sidebarText",
            title: "Add A Feed",
            content: "To add a feed use this form. Give it a title then select any of the available search fields."
        },
        {
            element: ".feedColFirst",
            title: "Your Feed",
            content: "To get you going we have added a feed of Ford Fiesta's.<p>Feel free to delete this at your leisure."
        },
        {
            element: ".feedColFirst .icons-list",
            onShow: function(tour) {
                $("a.dropdown-toggle").removeClass('tourWhiteColour');
                $("a.dropdown-toggle").addClass('tourBlackColour');
            },
            onHide: function(tour) {
                $("a.dropdown-toggle").removeClass('tourBlackColour');
                $("a.dropdown-toggle").addClass('tourWhiteColour');
            },
            title: "Feed Options",
            content: "Click here to access the feeds options.<p>From here you can change the title, delete the feed and also see the search fields.</p>"
        },
        {
            element: ".feedColFirst .recentFirst2",
            placement: "right",
            title: "Recent Vehicles",
            orphan: true,
            content: "Vehicles added to your feed within the past 24 hours will display a RECENT banner in the top right corner of its picture."
        },
        {
            element: ".feedColFirst .panel-heading",
            placement: "bottom",
            title: "Change Order",
            content: "And Finally.<p>Once you have more than one feed you can change the display order by clicking anywhere in the header here and dragging it to a new position.</p>"
        }

    ],
    onEnd: function(tour) {
        $.ajax(
            {
                url : baseUrl+'/members/mygarage/feed/tourComplete',
                type: "POST",
                data : { _token: token, tour: 'garage_feed_welcome'}
            }).done(function(data) {});
    }
});

// Initialize the tour
tour.init();

// Start the tour
tour.start();