<table class="table table-hover">
    <thead>
       <tr>
          <th>#</th>
          <th>Tên Blog</th>
          <th>Tên Blog Không Dấu</th>
          <th>Tổng Lượt Xem</th>
          <th>Ngày Đăng</th>
          <th>Ngày Cập Nhật</th>
          <th>Chức Năng</th>
       </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td><input type="checkbox" value="{{ $item->id }}"></td>
                <td><a>{{ $item->name }}</a></td>
                <td>{{ $item->name_not_utf8 }}</td>
                <td>{{ $item->count_view }}</td>
                <td>{{ $item->created_at }}</td>
                <td>{{ $item->updated_at }}</td>
                <td>
                    <a href="{{ route('admin.blogs.edit',$item->id) }}"><i class="icofont-edit btnEdit" style="color:#66ba66;"></i></a>
                    <i class="icofont-ui-delete btn-delete" style="color:red" data-id="{{ $item->id }}" data-toggle="modal" data-target="#deleteForm"></i>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div style="text-align:center;">
    {{ $data->links('admin.layouts.pagination',['query' => $query,'nameRoute' => $nameRoute]) }}
</div>
