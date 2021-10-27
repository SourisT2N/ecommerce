<table class="table table-hover">
    <thead>
       <tr>
          <th>#</th>
          <th>Tên Sản Phẩm</th>
          <th>Tên Danh Mục</th>
          <th>Tên Thương Hiệu</th>
          <th>Nơi Sản Xuất</th>
          <th>Ngày Đăng</th>
          <th>Chức Năng</th>
       </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td><input type="checkbox" value="{{ $item->id }}"></td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->categories->name }}</td>
                <td>{{ $item->suppliers->name }}</td>
                <td>{{ $item->countries->name }}</td>
                <td>{{ $item->created_at }}</td>
                <td>
                    <a href="{{ route('admin.products.edit',$item->id) }}"><i class="icofont-edit btnEdit" style="color:#66ba66;"></i></a>
                    <i class="icofont-ui-delete btn-delete" style="color:red" data-id="{{ $item->id }}" data-toggle="modal" data-target="#deleteForm"></i>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div style="text-align:center;">
    {{ $data->links('admin.layouts.pagination',['query' => $query,'nameRoute' => $nameRoute]) }}
</div>
