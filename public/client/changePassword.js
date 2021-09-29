$(document).ready(() => {
    $('#info-user').validate({
        rules: {
            password_current : {
                required: true,
                minlength : 5,
            },
            password_new : {
                required: true,
                minlength : 5,
            },
            password_new_confirmation : {
                required: true,
                minlength : 5,
                equalTo : "input[name='password_new']"
            }
        },
        messages: {
            password_current : {
                required: "Password cũ là bắt buộc",
                minlength: "Độ dài từ 5 trở lên"
            },
            password_new:{
                required: "Password mới là bắt buộc",
                minlength: "Độ dài từ 5 trở lên"
            },
            password_new_confirmation: {
                required: "Password mới nhập lại là bắt buộc",
                minlength: "Độ dài từ 5 trở lên",
                equalTo: "Password nhập lại không khớp"
            }
        }
    });

    $('#info-user').submit(function(e) {
        e.preventDefault();
        if(!$(this).valid())
            return;
        $.ajax({
            method: 'PATCH',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            complete: function (res) {
                let data = res.responseJSON;
                let message = "";
                if(res.status >= 200 && res.status <= 300)
                {
                    message = data.message;
                    toastr.success(message);
                    setTimeout(() => {
                        location.reload();
                    },2000)
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
    });
});
