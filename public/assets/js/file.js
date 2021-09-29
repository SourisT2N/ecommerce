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

    $('form').submit(async function(e){
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
    
    $('.table-responsive').on('click','.btn-delete',function(){
        $('#deleteForm').attr('data-id',$(this).attr('data-id'));
    });

    $('#btnDelete').click(function(){
        let id = $('#deleteForm').attr('data-id');
        let url = location.origin + location.pathname;
        $('#deleteForm').modal('hide');
        deleteData(url,'.table-responsive',id);
    });

    $('#btnAdd').click(function(){
        $('#addForm').trigger('reset');
        $('#editBtn').attr('id','addBtn');
        $('#addBtn').text('Thêm');
    });

    $('.table-responsive').on('click','.btnEdit',async function(e){
        e.preventDefault();
        $('#addForm').trigger('reset');
        $('#addBtn').attr('id','editBtn');
        let id = $(this).attr('data-id');
        $('#editBtn').attr('data-id',id);
        $('#editBtn').text('Sửa');
        await $.ajax({
            type: "GET",
            url:  location.origin + location.pathname + `/${id}`,
            dataType: "json",
            success: function (res) {
                $('input[name="name"]').val(res.data.name);
            }
        });
    });

    $('.modal-footer').on('click','#addBtn',function(e){
        e.preventDefault();
        $('#modalInput').modal('hide');
        let data = $('input[name="name"]').val();
        if(!data)
            return;
        let url = location.origin + location.pathname;
        addData(url,"POST",{"name": data},'.table-responsive');
    });

    $('.modal-footer').on('click','#editBtn',function(e){
        e.preventDefault();
        $('#modalInput').modal('hide');
        let data = $('input[name="name"]').val();
        if(!data)
            return;
        let url = location.origin + location.pathname;
        addData(url,"PUT",{"name": data},'.table-responsive',$(this).attr('data-id'));
    });

    $('#deleteBtn').click(() => {
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