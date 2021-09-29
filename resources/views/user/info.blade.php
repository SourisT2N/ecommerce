@extends('user.layouts.main')
@section('title','Thông Tin Người Dùng')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h4>Thông Tin Của Bạn</h4>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form id="info-user" action="{{ route('user.update') }}">
                        <div class="form-group row">
                            <label for="name" class="col-4 col-form-label">Tên Của Bạn</label>
                            <div class="col-8">
                                <input id="name" name="name" placeholder="Nhập Tên Của Bạn" value="{{ Auth::user()->name }}" class="form-control here" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-4 col-form-label">Email</label>
                            <div class="col-8">
                                <input id="email" class="form-control here" value="{{ Auth::user()->email }}" type="text" disabled>
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
    <script src="{{ asset('client/user.js') }}"></script>
@endsection
