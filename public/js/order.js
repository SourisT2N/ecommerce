$(document).ready(function() {
    $('select[name="order"]').change(async function(){
        let order = $(this).val();
        let url = urlConvert({"order": order});
        if(!order)
            url.searchParams.delete('order');
        url.searchParams.delete('page');
        loadData(url,'.table-responsive');
    });

    $('form#search').submit(async function(e){
        e.preventDefault();
        let val = $('input[name="search"]').val().trim();
        let url = urlConvert({'s': val});
        if(!val)
            url.searchParams.delete('s');
        url.searchParams.delete('order');
        url.searchParams.delete('page');
        $('select[name="order"] option[value="0"]').prop('selected',true);
        loadData(url,'.table-responsive');
    });

    $('#blog').submit(function(e){
        e.preventDefault();
        $('#staticBackdrop').modal('show');
        let url = $(this).attr('action');
        $.ajax({
            type: "PATCH",
            url: url,
            data: $(this).serialize(),
            dataType: "json",
            complete: function (res) {
                let data = res.responseJSON;
                let type = 'danger';
                let message = "";
                if(res.status >= 200 && res.status <= 300)
                {
                    type = 'success';
                    message = data.message;
                    location.reload();
                }
                else if(res.status == 422)
                    for(let key in data.error)
                        message += data.error[key].join(',') + "<br>";
                else
                    message = data.error;
                notify(type,message);
                $('#staticBackdrop').modal('hide');
            }
        });
    });
});