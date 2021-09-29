@component('mail::message')
# Xin Chào {{ $user->name }}

# Bạn Vừa Đặt 1 Đơn Hàng
Thông Tin Đơn Hàng:<br>

Người Nhận: {{ $order->name }}<br>

Số Điện Thoại: {{ $order->phone }}<br>

Tỉnh/Thành Phố: {{ $order->province }}<br>

Quận/Huyện: {{ $order->district }}<br>

Phường/Xã: {{ $order->ward }}<br>

Phương Thức Thanh Toán: {{ $order->payments->name }}<br>

Trạng Thái Thanh Toán: {{ $order->status_payment?'Đã Thanh Toán':'Chưa Thanh Toán' }}<br>

Trạng Thái Đơn Hàng: {{ $order->orderStatus->name }}<br>

@component('mail::table')
| Tên Sản Phẩm       | Số Lượng                  | Giá                          |
| ------------------ |:-------------------------:| ----------------------------:|
@foreach ($order->details as $item)
| {{ $item->name }}  | {{ $item->pivot->count }}  | {{ number_format($item->pivot->price,0,'','.') }} VNĐ |
@endforeach
@endcomponent

# Tổng Tiền: {{ number_format($order->total,0,'','.')}} VNĐ

Cảm Ơn Đã Đặt Hàng,<br>
{{ config('app.name') }}
@endcomponent