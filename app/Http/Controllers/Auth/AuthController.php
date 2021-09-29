<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegister;
use App\Http\Requests\UserUpdate;
use App\Models\User;
use App\Notifications\UserActivation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\UserActivation as UA;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Socialite;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        try
        {
            $validate = Validator::make($request->all(),[
                'email' => 'required|email',
                'password' => 'required|between:5,255',
                'g-recaptcha-response'=>'required|recaptcha'
            ],
            [
                'email.*' => ':attribute không đúng định dạnh',
                'password.*' => ':attribute từ 5 - 255 ký tự',
                'g-recaptcha-response.*' => 'Chưa xác thực captcha'
            ],
            [
                'email' => 'Email',
                'password' => 'Mật Khẩu'
            ]);
            if($validate->fails())
                throw new ValidationException($validate);
            $data = Arr::except($validate->validated(),'g-recaptcha-response');
            $user = User::where(['email' => $data['email']])->first();
            if(!$user)
                throw new AuthenticationException('Tài Khoản Hoặc Mật Khẩu Không Đúng');
            if(!$user->status || !$user->blocked)
                throw new AuthenticationException(!$user->status?'Bạn Chưa Xác Thực Tài Khoản':'Tài Khoản Bạn Đã Khoá. Vui Lòng Liên Hệ Admin');

            if(!Auth::attempt($data))
                throw new AuthenticationException('Tài Khoản Hoặc Mật Khẩu Không Đúng');

            $request->session()->regenerate();
            return response()->json(['message' => 'Đăng Nhập Thành Công','status' => 200],JsonResponse::HTTP_OK);
        }
        catch (Exception $e)
        {
            if($e instanceof ValidationException)
                return response()->json(['error' => $e->errors(),'status' => 422],JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            if($e instanceof AuthenticationException)
                return response()->json(['error' => $e->getMessage(),'status' => 401],JsonResponse::HTTP_UNAUTHORIZED);
            return response()->json(['error' => $e->getMessage(),'status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register(UserRegister $request)
    {
        try
        {
            $data = Arr::except($request->validated(),['g-recaptcha-response','password_confirmation']);
            $data['name'] = Str::title($data['name']);
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data)->assignRole('user');
            $user->notify(new UserActivation($user,'email'));
            return response()->json(['message' => 'Đăng Ký Thành Công Kiểm Tra Email','status' => 201],JsonResponse::HTTP_CREATED);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => 'Lỗi Server','status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function forgot(Request $request)
    {
        try
        {
            $validate = Validator::make($request->all(),[
                'email' => 'required|email',
                'g-recaptcha-response'=>'required|recaptcha'
            ],
            [
                'email.*' => 'Không Đúng Định Dạng Email',
                'g-recaptcha-response.*' => 'Chưa xác thực captcha'
            ]);
            if($validate->fails())
                throw new ValidationException($validate);
            $data = Arr::except($validate->validated(),'g-recaptcha-response');
            $user = User::where($data)->first();
            if(!$user)
                throw new ModelNotFoundException();
            $user->notify(new UserActivation($user,'reset'));
            return response()->json(['message' => 'Một Liên Kết Để Đặt Lại Mật Khẩu Của Bạn Đã Được Gửi Đến '.$user->email
            ,'status' => 200],JsonResponse::HTTP_OK);
        }
        catch(Exception $e)
        {
            if($e instanceof ValidationException)
                return response()->json(['error' => $e->errors(),'status' => 422],JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Email Chưa Được Đăng Ký','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => 'Lỗi Server','status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function resetPassword($code,Request $request)
    {
        try
        {
            $validate = Validator::make($request->all(),[
                'password' => 'required|between:5,255|confirmed',
                'g-recaptcha-response'=>'required|recaptcha'
            ],
            [
                'password.*' => 'Mật khẩu phải từ 5 - 255 ký tự và phải khớp với nhau',
                'g-recaptcha-response.*' => 'Chưa xác thực captcha'
            ]);
            if($validate->fails())
                throw new ValidationException($validate);

            $data = Arr::except($validate->validated(),['g-recaptcha-response','password_confirmation']);
            $data['password'] = bcrypt($data['password']);
            $token = UA::where(['activation_code'=> $code,'type' => 'reset'])->with('user')->first();

            if(!$token)
                throw new ModelNotFoundException();
            $token->user->update($data);
            $token->delete();
            return response()->json(['message' => 'Đổi Mật Khẩu Thành Công','status' => 200],JsonResponse::HTTP_OK);
        }
        catch (Exception $e)
        {
            if($e instanceof ValidationException)
                return response()->json(['error' => $e->getMessage(),'status' => 422],JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

            if($e instanceof ModelNotFoundException)
                return response()->json(['error' => 'Lỗi Xác Thực','status' => 404],JsonResponse::HTTP_NOT_FOUND);
            return response()->json(['error' => 'Lỗi Server','status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function redirect($provider)
    {
        try
        {
            return Socialite::driver($provider)->redirect();
        }
        catch (Exception $e)
        {
            abort(500);
        }
    }

    public function callback($provider)
    {
        $getInfo = Socialite::driver($provider)->user();
        $user = User::where('email',$getInfo->email)->first();
        if(!$user)
            $user = User::create(
            [
                'name' => $getInfo->name,
                'email' => $getInfo->email,
                'password' => bcrypt(Str::random(20)),
                'status' => 1
            ])->assignRole('user');
        Auth::login($user);
        return redirect()->route('index');
    }

    public function updateInfo(UserUpdate $request)
    {
        try
        {
            $dataValidated = Arr::except($request->validated(),['password_new_confirmation','password_current']);
            if(!count($dataValidated))
                throw new ValidationException('Dữ liệu không được để trống');
            if(Arr::has($dataValidated,'name') && !Arr::has($dataValidated,'password_new'))
                $data = ['name' => Str::title($dataValidated['name'])];
            elseif (!Arr::has($dataValidated,'name') && Arr::has($dataValidated,'password_new'))
                $data = ['password' => bcrypt($dataValidated['password_new'])];
            else
                throw new ValidationException('Dữ liệu nhập vào không đúng');
            Auth::user()->update($data);
            return response()->json(['message' => Arr::has($data,'name')?'Cập nhập thông tin thành công':'Đổi mật khẩu thành công','status' => 202],
                JsonResponse::HTTP_ACCEPTED);
        }
        catch (Exception $e)
        {
            if($e instanceof ValidationException)
                return response()->json(['error' => $e->validator,'status' => 422],JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            return response()->json(['error' => 'Lỗi server','status' => 500],JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
