$(document).ready(() => {
    $('.join').validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
              required: "Email là bắt buộc",
              email: "Email phải đúng định dạng"
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