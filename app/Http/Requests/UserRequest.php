<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'name' => ['required','string','regex:/^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳýỵỷỹ\s|]+$/'],
            'email' => "required|email|unique:users,email,".$this->user,
            'password' => [Rule::requiredIf(!$this->user),'min:5','max:255'],
            'roles' => 'required|array',
            'roles.*' => 'required|exists:roles,id',
            'status' => 'required|integer|between:0,1',
            'blocked' => 'required|integer|between:0,1',
        ];
    }

    public function messages()
    {
        return [
            'name.*' => ':attribute không được chứa ký tự số hoặc ký tự đặc biệt',
            'email.*' => ':attribute phải đúng định dạng email và chưa được đăng ký',
            'password.*' => ':attribute phải từ 5 - 255 ký tự',
            'roles.*' => ':attribute không được để trống và đã tồn tại',
            'roles.*.*' => ':attribute không tồn tại',
            'status.*' => ':attribute không đúng', 
            'blocked.*' => ':attribute không đúng', 
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên người dùng',
            'email' => 'Email',
            'password' => 'Mật khẩu',
            'roles' => 'Vai trò',
            'roles.*' => 'Vai trò',
            'status' => 'Trạng thái xác thực',
            'blocked' => 'Trạng thái người dùng'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->toArray();
        $errorRoles = $validator->errors()->get('roles.*');
        $count = 0;
        foreach($errorRoles as $key => $val)
        {
            if(!Arr::has($error,'roles') && $count == 0)
            {
                $count++;
                continue;
            }
            unset($error[$key]);
        }
        throw new HttpResponseException(response()
        ->json(['error' => $error,'status' => 422],
        JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
