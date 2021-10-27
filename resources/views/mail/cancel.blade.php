@component('mail::message')
# Xin Chào {{ $user->name }}

# Bạn Vừa Huỷ Đơn Hàng Có Mã {{ $order->code_billing }}
Nhấn Vào Liên Kết Dưới Đây Để Xem Chi Tiết Đơn Hàng
@component('mail::button', ['url' => route('user.order.show',['id' => $order->code_billing])])
    Đơn Hàng
@endcomponent

Cảm Ơn Đã Đặt Hàng,<br>
{{ config('app.name') }}
@endcomponent
