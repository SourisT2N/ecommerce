<div class="modal fade" id="modalInput" tabindex="-1" aria-labelledby="modalInput" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalInputLabel">{{ $title }}</h5>
        </div>
        <div class="modal-body">
          <form id="addForm">
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">{{ $name }}:</label>
              <input type="text" class="form-control" name="name">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
          <button type="button" class="btn btn-primary" id="addBtn">Thêm</button>
        </div>
      </div>
    </div>
</div>