<table class="table">
    <thead>
    <tr>
        <th scope="col">Mã Đơn Hàng</th>
        <th scope="col">Trạng Thái Thanh Toán</th>
        <th scope="col">Trạng Thái Đơn Hàng</th>
        <th scope="col">Tổng Tiền</th>
        <th scope="col">Ngày Đặt Hàng</th>
    </tr>
    </thead>
    <tbody>
    @if($orders->count())
        @foreach($orders as $order)
            <tr>
                <th scope="row"><a href="{{ route('user.order.show',['id' => $order->code_billing]) }}">{{ $order->code_billing }}</a></th>
                <td>{{ $order->status_payment?'Đã Thanh Toán':'Chưa Thanh Toán' }}</td>
                <td>{{ $order->orderStatus->name }}</td>
                <td>{{ number_format($order->total,0,'','.') }} VNĐ</td>
                <td>{{ $order->created_at }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <th scope="row" colspan="5" style="text-align: center;font-size: 1.5rem;">
                Lịch Sử Đơn Hàng Trống
            </th>
        </tr>
    @endif
    </tbody>
</table>
{{ $orders->links('user.layouts.pagination') }}
