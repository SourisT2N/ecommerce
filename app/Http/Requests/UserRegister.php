<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UserRegister extends FormRequest
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
            'name' => ['required','string','regex:/^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳýỵỷỹ\s|]{5,}$/'],
            'email' => "required|email|unique:users,email,".$this->user,
            'password' => 'required|between:5,255|confirmed',
            'g-recaptcha-response'=>'required|recaptcha'
        ];
    }

    public function messages()
    {
        return [
            'name.*' => ':attribute không được chứa ký tự số hoặc ký tự đặc biệt',
            'email.*' => ':attribute đã tồn tại',
            'password.*' => ':attribute phải từ 5 - 255 ký tự và phải khớp với nhau',
            'g-recaptcha-response.*' => 'Chưa xác thực captcha'
        ];
    }

    public function attributes()
    {
        return [
            'name' =>'Tên',
            'email' => 'Email',
            'password' => 'Mật Khẩu'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $error = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['error' => $error,'status' => 422],JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
