var h1;
function taber1(id) {
    document.getElementById(id).className;
    $('.formError').remove();
    console.log("hi")
    $("#HireDriver,#HireVehicle,#HireTaxi")[0].reset();

    if (h1) {
        if (h1 == id) {
            var style = document.getElementById(id + "1").style.display;
            if (style == "block") {
                document.getElementById(id + "1").style.display = "block";

            } else {
                document.getElementById(id + "1").style.display = "block";
            }
        } else {
            document.getElementById(id + "1").style.display = "block";
            document.getElementById(h1 + "1").style.display = "none";
            document.getElementById(h1).className = "deactive";
            document.getElementById(id).className = "active";
        }
    } else {
        document.getElementById("taber11").style.display = "none";
        document.getElementById(id + "1").style.display = "block";
        document.getElementById("taber1").className = "deactive";
        document.getElementById(id).className = "active";


    }
    h1 = id;

}
function getFormattedDate(date) {
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear().toString().slice(2);
    return  year + '-' + month + '-' + parseInt(parseInt(day));
}
Date.prototype.addHours= function(h){
    this.setHours(this.getHours()+h);
    return this;
}
function compare_date(end_date, start_date) {
    // split end date
    var user = end_date;
    var end_dt = end_date.split(" ");
    var end_time = end_dt[1].split(":");
    var end_date = end_dt[0].split("-");
    end_date = new Date(end_date[0], end_date[1] - 1, end_date[2], end_time[0], end_time[1]);
    // split start date
    var start_dt = start_date.split(" ");
    var start_time = start_dt[1].split(":");
    var start_date = start_dt[0].split("-");
    var start_date_time = new Date(start_date[0], start_date[1] - 1, start_date[2], start_time[0], start_time[1]);

    var start_date_one_hour_plus = new Date(start_date[0], start_date[1] - 1, start_date[2], start_time[0], start_time[1]).addHours(1);
    if (end_date < start_date_time) {
        $(".end_date_time_picker").val('');
        alert("Service end date should be greater then Service start date");
    } else if (end_date < start_date_one_hour_plus) {
        $(".end_date_time_picker").val('');
        alert("There should be at least one hour different between start & end date");
    } else {

    }
}





function compare_date_for_taxi(end_date, start_date) {
    // split end date
    var user = end_date;
    var end_dt = end_date.split(" ");
    var end_time = end_dt[1].split(":");
    var end_date = end_dt[0].split("-");
    end_date = new Date(end_date[0], end_date[1] - 1, end_date[2], end_time[0], end_time[1]);
    // split start date
    var start_dt = start_date.split(" ");
    var start_time = start_dt[1].split(":");
    var start_date = start_dt[0].split("-");
    var start_date_time = new Date(start_date[0], start_date[1] - 1, start_date[2], start_time[0], start_time[1]);
    var start_date_one_hour_plus = new Date(start_date[0], start_date[1] - 1, start_date[2], start_time[0], start_time[1]).addHours(1);
    var start_date_twenty_four_hour_plus = new Date(start_date[0], start_date[1] - 1, start_date[2], start_time[0], start_time[1]).addHours(24);
    if (end_date < start_date_time) {
        $(".end_date_time_taxi_picker").val('');
        alert("Service end date should be greater then Service start date");
    } else if (end_date < start_date_one_hour_plus) {
        $(".end_date_time_taxi_picker").val('');
        alert("There should be at least one hour different between start & end date");
    } else if (end_date > start_date_twenty_four_hour_plus) {
        $(".end_date_time_taxi_picker").val('');
        alert("you can't book a taxi for more then 24 hours");
    } else {

    }
}



