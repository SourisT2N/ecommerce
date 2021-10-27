$(document).ready(() => {
    loadCart();
    let count = 0;
    $(".cr").click(function() {
        let id = $(this).attr("data-id");
        $.post("/ajax/cart", {'data-id': id},
            function (res) {
                toastr.success(res.message);
                loadCart();
            },
            "json"
        )
        .fail(function(res){
            if(res.status < 400)
                return;
            if(res.status === 401)
                location.href = '/login';
            else
                toastr.error(res.responseJSON.error);
        });
    });

    $('.carts').on('click', '.input-group button[data-action="plus"]', function(){
        let that = this;
        let value =  parseInt($(that).parents('.input-group').find('input').val()) + 1 ;
        let item = $(that).parents('.media');
        let id = item.attr('data-id');
        $(`.media[data-id="${$(item).attr('data-id')}"]`).find('input').val(value);
        updateCart(id,value);
    });

    $('.carts').on('click', '.input-group button[data-action="minus"]', function(){
        let that = this;
        if( parseInt($(that).parents('.input-group').find('input').val()) > 1 ) {
            let value =  parseInt($(that).parents('.input-group').find('input').val()) - 1 ;
            let item = $(that).parents('.media');
            let id = item.attr('data-id');
            $(`.media[data-id="${$(item).attr('data-id')}"]`).find('input').val(value);
            updateCart(id,value);
        }
    });
    
    $('.carts').on('click', 'a.remove', function(e){
        e.preventDefault();
        let media = $(this).parents('.media');
        let id = media.attr('data-id');
        $.ajax({
            type: 'DELETE',
            url: "/ajax/cart/"+id,
            dataType: "json",
            success: function (res) {
                $(`.media[data-id="${id}"]`).fadeOut('300');
                $(`.media[data-id="${id}"]`).remove();
                $('.cart .label').html(`<i class="ion-bag"></i>${--count}`);
                if($('#orderTotal').length)
                    $('#orderTotal').text(`Tổng: ${(new Intl.NumberFormat('vi-VN')).format(res.total)} VNĐ`);
            }
        });
    });

function loadCart() 
{
    $.get("/ajax/cart",
        function (res) {
            // console.clear();
            count = res.data.length;
            if($('#orderTotal').length)
                $('#orderTotal').text(`Tổng: ${(new Intl.NumberFormat('vi-VN')).format(res.total)} VNĐ`);
            $('.cart .label').html(`<i class="ion-bag"></i>${count}`);
            $('.carts .content').html(renderHtml(res.data));
        },
        "json"
    );
}

function updateCart(id,value) 
{
    $.ajax({
        type: "PATCH",
        url: "/ajax/cart/" + id,
        data: {'count': value},
        dataType: "json",
        success: function (res) {
            if($('#orderTotal').length)
                $('#orderTotal').text(`Tổng: ${(new Intl.NumberFormat('vi-VN')).format(res.data.total)} VNĐ`);
            $(`.media[data-id="${id}"]`).find('.price').text(new Intl.NumberFormat('vi-VN').format(res.data.price) + ' VNĐ');
        }
    });
}

function renderHtml(data) {
    let str = data.map((e) => {
        return `
        <div class="media" data-id="${e['id']}">
          <div class="media-left">
            <a href="${e['url']}">
              <img class="media-object" src="${location.origin}/storage/${e['image_display']}" alt="${e['name']}"/>
            </a>
          </div>
          <div class="media-body">
            <h4 class="h4 media-heading text-hides">${e['name']}</h4>
            <label>${e['nameCategory']}</label>
            <p class="price">${new Intl.NumberFormat('vi-VN').format(e['price'])} VNĐ</p>
          </div>
          <div class="controls">
            <div class="input-group">
              <span class="input-group-btn">
                <button class="btn btn-default btn-sm" type="button" data-action="minus"><i class="ion-minus-round"></i></button>
              </span>
              <input type="text" class="form-control input-sm count-item" placeholder="Qty" value="${e['count']}" readonly="">
              <span class="input-group-btn">
                <button class="btn btn-default btn-sm" type="button" data-action="plus"><i class="ion-plus-round"></i></button>
              </span>
            </div><!-- /input-group -->

            <a class="remove" style="cursor:pointer;"> <i class="ion-trash-b"></i> Xoá </a>
          </div>
        </div>`
    }).join('');
    return str;
}

});
