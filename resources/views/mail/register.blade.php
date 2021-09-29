@component('mail::message')
# Xin Chào {{ $user->name }}

Vui Lòng Nhấn Vào Liên Kết Dưới Đây Để Hoàn Tất Đăng Ký

@component('mail::button', ['url' => route('user.code',['code' => $token])])
Xác Thực
@endcomponent

Cảm Ơn,<br>
{{ config('app.name') }}
@endcomponent