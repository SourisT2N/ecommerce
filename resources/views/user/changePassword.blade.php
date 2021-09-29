@extends('user.layouts.main')
@section('title','Đổi Mật Khẩu')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h4>Đổi Mật Khẩu</h4>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form id="info-user" action="{{ route('user.update') }}">
                        <div class="form-group row">
                            <label for="password_current" class="col-4 col-form-label">Mật Khẩu Cũ</label>
                            <div class="col-8">
                                <input id="password_current" name="password_current" placeholder="Nhập Mật Khẩu Cũ" class="form-control here" type="password" autocomplete >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password_new" class="col-4 col-form-label">Mật Khẩu Mới</label>
                            <div class="col-8">
                                <input id="password_new" name="password_new" placeholder="Nhập Mật Khẩu Mới" class="form-control here" type="password" autocomplete >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password_new_confirmation" class="col-4 col-form-label">Nhập Lại Mật Khẩu</label>
                            <div class="col-8">
                                <input id="password_new_confirmation" name="password_new_confirmation" placeholder="Nhập Lại Mật Khẩu" class="form-control here" type="password" autocomplete >
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="offset-4 col-8">
                                <button name="submit" type="submit" class="btn btn-primary">Cập Nhật Thông Tin</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('footer')
    <script src="{{ asset('client/changePassword.js') }}"></script>
@endsection
