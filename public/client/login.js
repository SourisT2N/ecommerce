$(document).ready(() => {
    $('.join').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password : {
                minlength : 5
            }
        },
        messages: {
            email: {
              required: "Email là bắt buộc",
              email: "Email phải đúng định dạng"
            },
            password:{
                required: "Password là bắt buộc",
                minlength: "Độ dài từ 5 trở lên"
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
                            location.href = '/';
                        },1000);
                        return;
                    }
                    else if(res.status == 422)
                        for(let key in data.error)
                            message += data.error[key].join(',') + "<br>";
                    else
                        message = data.error;
                    toastr.error(message);
                }
            });
        }
    });
});
