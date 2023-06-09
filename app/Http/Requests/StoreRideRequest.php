<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRideRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'resident_id'   => 'required|integer|exists:residents,id',
            'pickup_moment' => 'required|date_format:Y-m-d H:i|after:today',
            'to'            => 'required|string|min:3|max:255',
            'from'          => 'required|string|min:3|max:255',
            'distance'      => 'required|integer|min:1|max:3000',
        ];
    }

    public function messages()
    {
        return [
            'resident_id.exists' => 'The provided resident does not exists.',
        ];
    }
}
