$('#vehicleType').on('change', function() {
    var vType = this.value;
    var token = $("input[name=_token]").val();
    $.ajax({

        type:"POST",
        url:'/admin/ajax/vehicleType',
        data : { vehicleType: vType, _token: token},
        dataType: "json",
        success: function(data){
            $("#engine_size").empty();
            $("#engine_size").append(data.engineSize);
            $("#engine_size").trigger("chosen:updated");
            $("#body_type").empty();
            $("#body_type").append(data.bodyType);
            $("#body_type").trigger("chosen:updated");
        },
        complete: function(data){
            noModels();
        }
    })


});

$('#vehicleMake').on('change', function() {
    var vMake = this.value;
    var token = $("input[name=_token]").val();
    $.ajax({

        type:"POST",
        url:'/admin/ajax/vehicleModels',
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
        url:'/members/ajax/vehicleModelSearch/slug',
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

$('#vehicleModel').on('change', function() {
    var vMake = this.value;
    var token = $("input[name=_token]").val();
    $.ajax({

        type:"POST",
        url:'/admin/ajax/vehicleModelVarient',
        data : { vehicleModel: vMake, _token: token},
        dataType: "json",
        success: function(data){
            $("#vehicleVarient").empty();
            $("#vehicleVarient").append(data.drop);
            $("#vehicleVarient").trigger("chosen:updated");
        }
    })


});