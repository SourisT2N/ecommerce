@component('mail::message')
# Xin Chào {{ $user->name }}

Để Lấy Lại Mật Khẩu Vui Lòng Nhấn Vào Liên Kết Dưới Đây

@component('mail::button', ['url' => route('user.reset',['code' => $token])])
Lấy Lại Mật Khẩu
@endcomponent

{{ config('app.name') }}
@endcomponent