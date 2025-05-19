<?php

namespace App\Http\Requests;

use App\Enum\ResponseCode;
use App\Models\Products\Subtype;
use App\Models\Products\Type;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:50',
            'original_price' => 'required|numeric',
            'description' => 'nullable|max:255',

            'supplier_name' => 'nullable|string|max:65',
            'company_name' => 'required|string|max:65',
            'address' => 'required|string|max:255',
            'contact' => 'required|string|digits:11',

            'type' => ['required', Rule::in([...Type::pluck('type_name')->toArray(), 'new_type'])],
            'subtype' => ['required', Rule::in([...Subtype::pluck('subtype_name')->toArray(), 'new_subtype'])],
        ];

        // Conditional rules
        if ($this->input('type') === 'new_type') {
            $rules['new_type'] = 'required|string|max:100|unique:types,type_name';
        }

        if ($this->input('subtype') === 'new_subtype') {
            $rules['new_subtype'] = 'required|string|max:200';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'product_name.required' => 'The product name is required',
            'product_name.max' => 'Product name cannot exceed 255 characters',
            'original_price.required' => 'The original price is required',
            'original_price.numeric' => 'The original price must be a number',
            'description.max' => 'Description cannot exceed 255 characters',
            'company_name.required' => 'The Company name is required',
            'supplier_name.max' => 'Supplier name cannot exceed 65 characters',
            'company_name.max' => 'Company name cannot exceed 65 characters',
            'address.max' => 'Address cannot exceed 255 characters',
            'address.required' => 'Address is required',
            'contact.required' => 'The contact number is required',
            'contact.digits' => 'The contact number must be 11 digits',
            'type.in' => 'Please select a valid type',
            'new_type.required_if' => 'New type name is required',
            'new_type.unique' => 'This type already exists',
            'subtypes.required' => 'At least one subtype is required',
            'subtypes.*.in' => 'Invalid subtype selected',
            'new_subtypes.required_if' => 'New subtype(s) are required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('subtype') === 'new_subtype') {
                $subtypes = $this->parseNewSubtypes();

                if (empty($subtypes)) {
                    $validator->errors()->add('new_subtype', 'Please enter at least one valid subtype');
                }

                foreach ($subtypes as $subtype) {
                    if (Subtype::where('id', $this->getTypeId())->where('subtype_name', $subtype)->exists()) {
                        $validator->errors()->add('new_subtype', "Subtype '{$subtype}' already exists for this type");
                    }
                }
            }
        });
    }

    protected function parseNewSubtypes()
    {
        return array_filter(array_map('trim', explode(',', $this->input('new_subtype', ''))));
    }

    protected function getTypeId()
    {
        if ($this->input('type') === 'new_type') {
            $type = Type::firstOrCreate([
                'type_name' => $this->input('new_type'),
            ]);
            return $type->id;
        }

        return Type::where('type_name', $this->input('type'))->value('id');
    }
}
