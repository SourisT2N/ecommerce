<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderRequest extends FormRequest
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
            'name' => 'required|min:5',
            'phone' => ['required','regex:/^0(86|96|97|98|32|33|34|35|36|37|38|39|88|91|94|83|84|85|81|82|89|90|93|70|79|77|76|78|92|56|58|99|59|87)\d{7}$/'],
            'province' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'address' => 'required|min:5',
            'payment' => 'required|exists:payments,id',
            'orderId' =>  Rule::requiredIf(request('payment') != 1)
        ];
    }

    public function messages()
    {
        return [
            'name.*' => 'Độ Dài :attribute Từ 5 Trở Lên',
            'phone.*' => ':attribute Không Hợp Lệ',
            'province.*' => ':attribute Không Được Để Trống',
            'district.*' => ':attribute Không Được Để Trống',
            'ward.*' => ':attribute Không Được Để Trống',
            'address.*' => 'Độ Dài :attribute Từ 5 Trở Lên',
            'payment.*' => 'Không Tồn Tại :attribute',
            'orderId.*' => 'Thiếu Mã Đơn Hàng'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên',
            'phone' => 'Số Điện Thoại',
            'province' => 'Tỉnh/Thành Phố',
            'district' => 'Quận/Huyện',
            'ward' => 'Phường/Xã',
            'address' => 'Địa Chỉ',
            'payment' => 'Phương Thức Thanh Toán'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $error = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['error' => $error,'status' => 422],
        JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
