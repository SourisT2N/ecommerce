$(document).ready(function(){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
          var re = new RegExp(regexp);
          return this.optional(element) || re.test(value);
        }
    );
});
function dateConvert(date)
{
    let delta = Math.round((+new Date - new Date(date)) / 1000);
    let minute = 60,
    hour = minute * 60,
    day = hour * 24,
    week = day * 7,
    month = week * (new Date((new Date()).getYear(),(new Date()).getMonth() + 1, 0)).getDate(),
    year = month * 12;

    if(delta < minute)
        return delta + ' giây trước';
    else if(delta < hour)
        return Math.round(delta / minute) + ' phút trước';
    else if(delta < day)
        return Math.round(delta / hour) + ' giờ trước';
    else if(delta < week)
        return Math.round(delta / day) + ' ngày trước';
    else if(delta < month)
        return Math.round(delta / week) + ' tuần trước';
    else if(delta < year)
        return Math.round(delta / month) + ' tháng trước';
}
