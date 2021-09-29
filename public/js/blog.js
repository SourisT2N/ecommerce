$(document).ready(function(){
    $('select[name="order"]').change(async function(){
        let arrData = $(this).val().split('-');
        let url = urlConvert(arrData.length > 1?{"order": arrData[0],"type": arrData[1]}:{});
        if(arrData.length <= 1)
        {
            url.searchParams.delete('order');
            url.searchParams.delete('type');
        }
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
        url.searchParams.delete('type');
        url.searchParams.delete('page');
        $('select[name="order"] option[value="0"]').prop('selected',true);
        loadData(url,'.table-responsive');
    });

    $('#blog').submit(function(e){
        e.preventDefault();
        let formData = new FormData(this);
        if(!formData.get('password'))
            formData.delete('password');
        let url = $(this).attr('action');
        blog(formData,url);
    });

    $('.table-responsive').on('click','.btn-delete',function(){
        $('#deleteForm').attr('data-id',$(this).attr('data-id'));
    });

    $('#btnDelete').click(function(e) {
        let data = $('#deleteForm').attr('data-id');
        let url = location.origin + location.pathname;
        $('#deleteForm').modal('hide');
        deleteData(url,'.table-responsive',data);
    });

    $('#deleteBtn').click(function(e) {
        let arrData = [];
        $('.table-responsive input[type="checkbox"]:checked').each(function(){
            arrData.push($(this).val());
        });
        if(!arrData.length)
            notify('danger','Bạn chưa chọn mục để xoá');
        let arrId = arrData.join(',');
        let url = location.origin + location.pathname;
        deleteData(url,'.table-responsive',arrId);
    });
});

function blog(fdt,url)
{
    $('#staticBackdrop').modal('show');
    $.ajax({
        type: 'POST',
        url: url,
        data: fdt,
        contentType: false,
        processData: false,
        dataType: "json",
        complete: function(res)
        {
            let data = res.responseJSON;
            let type = 'danger';
            let message = "";
            if(res.status >= 200 && res.status <= 300)
            {
                type = 'success';
                message = data.message;
                $(('#blog')).trigger('reset');
                $('#body').text('');
                $("#body").summernote("code", "");
                if(fdt.has('_method'))
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
}