$('#vehicleMake').on('change', function() {
    var vMake = this.value;
    var token = $("input[name=_token]").val();
    $.ajax({

        type:"POST",
        url:'/ajax/vehicleModelSearch',
        data : { vehicleMake: vMake, _token: token},
        dataType: "json",
        success: function(data){
            $("#vehicleModel").empty();
            $("#vehicleModel").append(data.drop);
            $("#vehicleModel").trigger("chosen:updated");

        },
        complete: function(data){
            noModels();
        }
    })


});

$('#vehicleMakeData').on('change', function() {
    var vMake = this.value;
    var token = $("input[name=_token]").val();
    $.ajax({

        type:"POST",
        url:'/ajax/vehicleModelSearch/slug',
        data : { vehicleMake: vMake, _token: token},
        dataType: "json",
        success: function(data){
            $("#vehicleModel").empty();
            $("#vehicleModel").append(data.drop);
            $("#vehicleModel").trigger("chosen:updated");
        }
    })

});

function noModels() {
    var selected = $('#vehicleModel option:selected').text();
    if(selected == 'No Models Found'){
        $("#modelSearch").hide();
        $("#textSearch").show();
        $('#search').tooltip('show');
        //$("#vehicleMake").val("0");
        //$("#vehicleMake").trigger("chosen:updated");
    } else {
        $("#textSearch").hide();
        $("#modelSearch").show();
    }
}
$("#textSearch").hide();

function listTypeCheck()
{
    var show_auctions = $("#show_auctions").is(':checked');
    var show_classifieds = $("#show_classifieds").is(':checked');
    if(show_classifieds && !show_auctions)
    {
        getDealers('classified');
        $('.select-day').hide();
    }
    if(!show_classifieds && !show_auctions)
    {
        getDealers('all');
        $('.select-day').show();
    }
    if(!show_classifieds && show_auctions)
    {
        getDealers('auctioneer');
        $('.select-day').show();
    }
    if(show_classifieds && show_auctions)
    {
        getDealers('all');
        $('.select-day').show();
    }
    listTypeLocations(show_auctions, show_classifieds);
}

function getDealers(dealerType)
{
    var token = $("input[name=_token]").val();
    var selected = $( "#auctioneer" ).val();
    $.ajax({

        type:"POST",
        url:'/ajax/getDealers',
        data : { dealerType: dealerType, _token: token},
        dataType: "json",
        success: function(data){
            $("#auctioneer").empty();
            $("#auctioneer").append(data.drop);
            $("#auctioneer").val(selected);
            $("#auctioneer").trigger("chosen:updated");
        }
    })
}

function listTypeLocations(show_auctions, show_classifieds) {
    var token = $("input[name=_token]").val();
    $.ajax({

        type:"POST",
        url:'/ajax/vehicleLocations',
        data : { auctions: show_auctions, classifieds: show_classifieds, _token: token},
        dataType: "json",
        success: function(data){
            $("#location").empty();
            $("#location").append(data.drop);
            $("#location").trigger("chosen:updated");
        }
    })
}

$("#show_classifieds").on("click", function() {
    listTypeCheck();
});
$("#show_auctions").on("click", function() {
    listTypeCheck();
});
listTypeCheck();