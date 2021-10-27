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

    $('.btn.btn-login').on('click',function(e) {
        e.preventDefault();
        const url = this.href;
        windowPopup(url)
            .then(res => {
                location.href = res;
            })
            .catch(e => console.error(e));
    })

    function windowPopup(url)
    {
        const WIDTH = 480;
        const HEIGHT = 600;
        const screenLeft = window.screenLeft || window.screenX;
        const screenTop = window.screenTop || window.screenY;
        const widthBrowser = window.outerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        const heightBrowser = window.outerHeight || document.documentElement.clientHeight || document.body.clientHeight;
        const left = Math.max(0,(widthBrowser / 2) - (WIDTH / 2) + screenLeft);
        const top = Math.max(0,(heightBrowser / 2) - (HEIGHT / 2) + screenTop);
        return new Promise((resolve,reject) => {
            const windowPopup = window.open(url,"Login Popup", `width=${WIDTH},height=${HEIGHT},top=${top},left=${left},scrollbars=no,resizable=0`);
            if(windowPopup.focus)
                windowPopup.focus();

            const checkPopup = setInterval(() => {
                if(windowPopup.closed)
                {
                    clearInterval(checkPopup);
                    return;
                }

                let href = "";
                try
                {
                    href = windowPopup.location.href;
                }
                catch (e){}
                if(!href || href == 'about:blank')
                    return;
                if(href.startsWith(location.origin))
                {
                    clearInterval(checkPopup);
                    windowPopup.close();
                    resolve(windowPopup.location.href);
                }
            },50);
        });
    }
});
