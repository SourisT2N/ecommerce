<table class="table table-hover">
    <thead>
       <tr>
          <th>#</th>
          <th>Tên Người Dùng</th>
          <th>Email</th>
          <th>Vai Trò</th>
          <th>Xác Thực</th>
          <th>Trạng Thái</th>
          <th>Chức Năng</th>
       </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td><input type="checkbox" value="{{ $item->id }}"></td>
                <td><a>{{ $item->name }}</a></td>
                <td>{{ $item->email }}</td>
                <td>{{ implode('|', $item->roles->pluck('name')->toArray()) }}</td>
                <td><div class="label-main">
                    @php
                        $message = $item->status?'Đã Xác Thực':'Chưa Xác Thực';
                        $class = $item->status?'success':'danger';
                    @endphp
                    <label class="label label-md bg-{{ $class }}">{{ $message }}</label>
                </div></td>
                <td><div class="label-main">
                    @php
                        $message = $item->blocked?'Hoạt Động':'Đã Khoá';
                        $class = $item->blocked?'success':'danger';
                    @endphp
                    <label class="label label-md bg-{{ $class }}">{{ $message }}</label>
                </div></td>
                <td>
                    <a href="{{ route('admin.users.edit',$item->id) }}"><i class="icofont-edit btnEdit" style="color:#66ba66;"></i></a>
                    <i class="icofont-ui-delete btn-delete" style="color:red" data-id="{{ $item->id }}" data-toggle="modal" data-target="#deleteForm"></i>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div style="text-align:center;">
    {{ $data->links('admin.layouts.pagination',['query' => $query,'nameRoute' => $nameRoute]) }}
</div>
