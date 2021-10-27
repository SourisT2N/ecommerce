<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductRequest extends FormRequest
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
            "name" => "required|string|min:5|unique:products,name,".$this->product,
            "description" => "required|string|min:10",
            "price_old" => "required|numeric|min:0|not_in:0",
            "price_new" => "sometimes|numeric|min:0",
            "system" => "required|string|min:5",
            "display" => "required|string|min:10",
            "processor" => "required|string|min:5",
            "graphics" => "required|string|min:5",
            "memory" => "required|string|min:4",
            "hard_drive" => "required|string|min:5",
            "wireless" => "required|string|min:5",
            "battery" => "required|string|min:5",
            "image_display" => Rule::requiredIf(!$this->product)."|image",
            "images" => Rule::requiredIf(!$this->product)."|array|min:1|max:5",
            "images.*" => Rule::requiredIf(!$this->product)."|image",
            "id_country" => "required|integer|exists:countries,id",
            "id_category" => "required|integer|exists:categories,id",
            "id_supplier" => "required|integer|exists:suppliers,id",
        ];
    }

    public function messages()
    {
        return [
            "name.*" => ":attribute chỉ từ 5 ký tự và chưa được khởi tạo",
            "description.*" => ":attribute chỉ từ 10 ký tự",
            "price_old.*" => ":attribute phải là số và lớn hơn 0", 
            "price_new.*" => ":attribute phải là số và nhỏ nhất là 0", 
            "system.*" => ":attribute chỉ từ 5 ký tự",
            "display.*" => ":attribute chỉ từ 10 ký tự",
            "processor.*" => ":attribute chỉ từ 5 ký tự",
            "graphics.*" => ":attribute chỉ từ 5 ký tự",
            "memory.*" => ":attribute chỉ từ 4 ký tự",
            "hard_drive.*" => ":attribute chỉ từ 5 ký tự",
            "wireless.*" => ":attribute chỉ từ 5 ký tự",
            "battery.*" => ":attribute chỉ từ 5 ký tự",
            "image_display.*" => ":attribute có đuôi (jpg, jpeg, png, bmp, gif, svg, or webp)",
            "images.*" => ":attribute là một mảng có đuôi (jpg, jpeg, png, bmp, gif, svg, or webp) và tối đa 5 file",
            "images.*.*" => ":attribute là một mảng có đuôi (jpg, jpeg, png, bmp, gif, svg, or webp) và tối đa 5 file",
            "id_country.*" => ":attribute phải là số nguyên và đã tồn tại",
            "id_category.*" => ":attribute phải là số nguyên và đã tồn tại",
            "id_supplier.*" => ":attribute phải là số nguyên và đã tồn tại",
        ];
    }

    public function attributes()
    {
        return [
            "name" => "Tên sản phẩm",
            "description" => "Mô tả",
            "price_old" => "Giá",
            "price_new" => "Giá mới",
            "system" => "Hệ điều hành",
            "display" => "Chi tiết",
            "processor" => "Bộ xử lý",
            "graphics" => "Đồ hoạ",
            "memory" => "Bộ nhớ",
            "hard_drive" => "Ổ cứng",
            "wireless" => "Kết nối không dây",
            "battery" => "Pin",
            "image_display" => "Hình ảnh hiển thị",
            "images" => "Hình ảnh chi tiết",
            "images.*" => "Hình ảnh chi tiết",
            "id_country" => "Tên xuất xứ",
            "id_category" => "Tên danh mục",
            "id_supplier" => "Tên thương hiệu"
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->toArray();
        $errorImage = $validator->errors()->get('images.*');
        $count = 0;
        foreach($errorImage as $key => $val)
        {
            if(!Arr::has($error,'images') && $count == 0)
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
