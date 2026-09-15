<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            
            // Kolom dinamis (berlaku untuk Admin maupun Klien):
            'no_wa' => ['nullable', 'string', 'max:20'],
            'npwp' => ['nullable', 'string', 'max:50'],
            'nama_perusahaan' => ['nullable', 'string', 'max:255'],
            
            // Tambahan untuk fitur unggah foto profil BOMA Sys:
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }
}