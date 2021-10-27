$(document).ready(() => {
    $('.join').validate({
        rules: {
            password : {
                required: true,
                minlength : 5
            },
            password_confirmation : {
                required: true,
                minlength : 5,
                equalTo : "input[name='password']"
            }
        },
        messages: {
            password:{
                required: "Password là bắt buộc",
                minlength: "Độ dài từ 5 trở lên"
            },
            password_confirmation: {
                required: "Password nhập lại là bắt buộc",
                minlength: "Độ dài từ 5 trở lên",
                equalTo: "Password nhập lại không khớp"
            }
        },
        submitHandler: function(form) {
            $.ajax({
                type: "POST",
                url: $(form).attr('action'),
                data: $(form).serialize(),
                dataType: "json",
                complete: function (res) {
                    grecaptcha.reset();
                    let data = res.responseJSON;
                    let message = "";
                    if(res.status >= 200 && res.status <= 300)
                    {
                        message = data.message;
                        $(form).trigger('reset');
                        $(form).find('.form-control').removeClass('valid');
                        toastr.success(message);
                        setTimeout(() => {
                            location.href = '/login';
                        },1000);
                        return;
                    }
                    else if(res.status == 422)
                        for(let key in data.error)
                            message += data.error[key].join(',') + "<br>";
                    else
                        message = data.error;
                    toastr.error(message);
                    setTimeout(() => {
                        location.reload();
                    },1000);
                }
            });
        }
    });
});
