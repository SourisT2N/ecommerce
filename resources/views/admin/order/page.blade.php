<table class="table table-hover">
    <thead>
       <tr>
          <th>#</th>
          <th>Mã Đơn Hàng</th>
          <th>Trạng Thái Thanh Toán</th>
          <th>Trạng Thái Đơn Hàng</th>
          <th>Tổng Tiền</th>
          <th>Ngày Đặt Hàng</th>
          <th>Chức Năng</th>
       </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td><input type="checkbox" value="{{ $item->id }}"></td>
                <td>{{ $item->code_billing }}</td>
                <td>{{ $item->status_payment?'Đã Thanh Toán':'Chưa Thanh Toán' }}</td>
                <td>{{ $item->orderStatus->name }}</td>
                <td>{{ number_format($item->total,0,'','.') }} VNĐ</td>
                <td>{{ $item->created_at }}</td>
                <td>
                    <a href="{{ route('admin.orders.edit',$item->id) }}"><i class="icofont-edit btnEdit" style="color:#66ba66;"></i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div style="text-align:center;">
    {{ $data->links('admin.layouts.pagination',['query' => $query,'nameRoute' => $nameRoute]) }}
</div>
