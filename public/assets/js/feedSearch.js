$('#vehicleMake').on('change', function() {
    var vMake = this.value;
    var token = $("input[name=_token]").val();
    $.ajax({

        type:"POST",
        url:'/members/ajax/vehicleModelSearchAll',
        data : { vehicleMake: vMake, _token: token},
        dataType: "json",
        success: function(data){
            $("#vehicleModel").empty();
            $("#vehicleModel").append(data.drop);
            $("#vehicleModel").trigger("chosen:updated");
        }
    })


});

$('#vehicleMakeData').on('change', function() {
    var vMake = this.value;
    var token = $("input[name=_token]").val();
    $.ajax({

        type:"POST",
        url:'/members/ajax/vehicleModelSearchAll/slug',
        data : { vehicleMake: vMake, _token: token},
        dataType: "json",
        success: function(data){
            $("#vehicleModel").empty();
            $("#vehicleModel").append(data.drop);
            $("#vehicleModel").trigger("chosen:updated");
        }
    })


});