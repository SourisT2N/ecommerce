$(document).ready(() => {
    $('#info-user').validate({
        rules: {
            name: {
                required: true,
                regex: '^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳýỵỷỹ\\s|]+$',
                minlength: 5
            },
        },
        messages: {
            name: {
                required: 'Tên là bắt buộc',
                minlength: 'Độ dài từ 5 trở lên',
                regex: "Tên không được chứa số hoặc ký tự đặc biệt"
            },
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
