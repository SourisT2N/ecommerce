<table class="table table-hover">
    <thead>
       <tr>
          <th>#</th>
          <th>Tên Trạng Thái</th>
          <th>Chức Năng</th>
       </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td><input type="checkbox" value="{{ $item->id }}"></td>
                <td>{{ $item->name }}</td>
                <td>
                    <i class="icofont-edit btnEdit" style="color:#66ba66;" data-id="{{ $item->id }}" data-toggle="modal" data-target="#modalInput" ></i>
                    <i class="icofont-ui-delete btn-delete" style="color:red" data-id="{{ $item->id }}" data-toggle="modal" data-target="#deleteForm"></i>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div style="text-align:center;">
    {{ $data->links('admin.layouts.pagination',['query' => $query,'nameRoute' => $nameRoute]) }}
</div>
