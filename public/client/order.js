$(document).ready(() => {
   $('#order').submit(function(e) {
       e.preventDefault();
       $.ajax({
           method: 'PATCH',
           url: $(this).attr('action'),
           dataType: 'json',
           complete: function (res)
           {
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
       })
   })
});
