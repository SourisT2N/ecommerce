$(document).ready(function () {
    let value;
    let xhr;
    loadProvince();
    
    $('.group-select').on('click', 'ul.dropdown > li', function() {
        
        let value = $(this).attr('data-value');
        let id = $(this).attr('province-id') || $(this).attr('district-id');
        let name = $(this).parents('.group-select').find('input').attr('name');
        if(name == 'province')
            loadDistrict(id);
        if(name == 'district')
            loadWard(id);
        $(this).parents('.group-select').find('input.select').val(value);

        $(this).parents('.group-select').attr('data-toggle','close');
        $(this).parents('.group-select').find('ul.dropdown').slideUp('fast');

    });
    $('#order').validate({
        rules: {
            name: {
                required: true,
                minlength: 5
            },
            phone: {
                required: true,
                regex: '^0(86|96|97|98|32|33|34|35|36|37|38|39|88|91|94|83|84|85|81|82|89|90|93|70|79|77|76|78|92|56|58|99|59|87)\\d{7}$'
            },
            province: {
                required: true
            },
            district: {
                required: true
            },
            ward: {
                required: true
            },
            address: {
                required: true,
                minlength: 5
            },
            payment: {
                required: true
            }
        },
        messages: {
            name: {
              required: "Tên Không Được Để Trống",
              minlength: "Độ Dài Từ 5 Trở Lên"
            },
            phone:{
                required: "Số Điện Thoại Không Được Để Trống",
                regex: 'Số Điện Thoại Không Hợp Lệ'
            },
            province: {
                required: "Tỉnh/Thành Phố Không Được Để Trống"
            },
            district: {
                required: "Quận/Huyện Không Được Để Trống"
            },
            ward: {
                required: "Phường/Xã Không Được Để Trống"
            },
            address: {
                required: 'Địa Chỉ Không Được Để Trống',
                minlength: 'Độ Dài Từ 5 Trở Lên'
            },
            payment: {
                required: 'Phương Thức Thanh Toán Không Được Để Trống',
            }
        },
        submitHandler: function(form) {
            submitForm(form)
        }
    });

    $('select[name="payment"]').change(function () {
        $('#btnPay').html('<div class="lds-ellipsis pull-right"><div></div><div></div><div></div><div></div></div>');
        if(this.value != 1)
        {
            value = this.value;
            xhr = loadTotal(value);
        }
        else
        {
            if(xhr)
                xhr.abort();
            $('#btnPay').html('<button class="btn btn-primary pull-right" type="submit">Thanh Toán</button>');
        }
    });

    $('.carts').on('click', '.input-group button[data-action="minus"]', function(){
        if(value && value != 1)
            xhr = loadTotal(value);
    });

    $('.carts').on('click', '.input-group button[data-action="plus"]', function(){
        if(value && value != 1)
            xhr = loadTotal(value);
    });

function loadTotal(value)
{
    $('#btnPay').html('<div class="lds-ellipsis pull-right"><div></div><div></div><div></div><div></div></div>');
    return $.get("/ajax/total/" + value,
        function (res) {
            $('#btnPay').html('<div style="width:200px" class="pull-right" id="paypal-button-container"></div>');
            loadPaypal(res.total);
        },
        "json"
    );
}

function loadProvince()
{
    fetch('https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/province',{
        headers:{
            'Content-Type': 'application/json',
            'token': '82937a41-eb00-11eb-9388-d6e0030cbbb7'
        }
    })
    .then((res) => res.json())
    .then((data) => {
        let str = data.data.map((val) => {
            return `<li province-id='${val.ProvinceID}' data-value="${val.ProvinceName}" >${val.ProvinceName}</li>`            
        }).join('');
        $('#province .dropdown').html(str);
    })
    .catch((e) => console.error(e));
}

function loadDistrict(id)
{
    fetch('https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/district?province_id='+id,
    {
        'headers':{
            'Content-Type': 'application/json',
            'token': '82937a41-eb00-11eb-9388-d6e0030cbbb7'
        }
    })
    .then((res) => res.json())
    .then(data => {
        let str = data.data.map((val) => {
            return `<li district-id='${val.DistrictID}' data-value="${val.DistrictName}" >${val.DistrictName}</li>`            
        }).join('');
        $('#district .dropdown').html(str);
    })
    .catch(e => console.error(e));
}

function loadWard(id)
{
    fetch('https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/ward?district_id='+id,
    {
        'headers':{
            'Content-Type': 'application/json',
            'token': '82937a41-eb00-11eb-9388-d6e0030cbbb7'
        }
    })
    .then((res) => res.json())
    .then(data => {
        let str = data.data.map((val) => {
            return `<li data-value="${val.WardName}" >${val.WardName}</li>`            
        }).join('');
        $('#ward .dropdown').html(str);
    })
    .catch(e => console.error(e));
}

function loadPaypal(total) {
    let actionsStatus;
    paypal.Buttons({
        style: {
          layout: 'horizontal',
          color: 'white',
          tagline: false,
          height: 32,
        },
        onInit: function(data,actions){
          actionsStatus = actions;
          actionsStatus.disable();
          return false;
        },
        onClick: function(data, actions){
          actionsStatus.enable();
          if(!$('#order').valid() || !total)
          {
            actionsStatus.disable();
            if(!total)
                  toastr.error('Chưa Có Sản Phẩm');
            return false;
          }
          return true;
        },
        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: total
              }
            }],
          });
        },
  
        onApprove: function(data, actions) {
          return actions.order.capture().then(function(details) {
              if(details.status == 'COMPLETED'){
                return submitForm(document.getElementById('order'),data.orderID);
              }
              else
                  toastr.error('Thanh Toán Thất Bại');
          });
        },
  
        onCancel: function (data) {
            toastr.error('Bạn Đã Huỷ Thanh Toán');
        }
    }).render('#paypal-button-container');
}

function submitForm(form,data = null) {
    $('#staticBackdrop').modal('show');
    let formData = new FormData(form);
    if(data)
        formData.append('orderId',data);
    $.ajax({
        type: "POST",
        url: $(form).attr("action"),
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
        complete: function (res) {
            $('#staticBackdrop').modal('hide');
            let data = res.responseJSON;
            let message = "";
            if(res.status >= 200 && res.status <= 300)
            {
                message = data.message;
                $('#orderTotal').text('Tổng: 0 VNĐ');
                $(form).trigger('reset');
                $(form).find('.form-control').removeClass('valid');
                toastr.success(message);
                $('.cart .label').html(`<i class="ion-bag"></i> 0`);
                $('.carts .content').html('');
                $('#btnPay').html('<button class="btn btn-primary pull-right" type="submit">Thanh Toán</button>');
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