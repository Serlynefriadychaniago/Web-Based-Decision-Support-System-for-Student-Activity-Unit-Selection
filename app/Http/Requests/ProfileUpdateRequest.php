<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
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
        $userId = $this->user() ? $this->user()->id : null;
        $mahasiswaRec = $userId ? DB::table('mahasiswa')->where('user_id', $userId)->first() : null;
        $mahasiswaId = $mahasiswaRec ? $mahasiswaRec->id : null;

        return [
            'name'          => ['nullable', 'string', 'max:255'],
            'nama'          => ['required', 'string', 'max:255'],
            'nim'           => [
                'required',
                'string',
                'max:50',
                Rule::unique('mahasiswa', 'nim')->ignore($mahasiswaId),
            ],
            'email'         => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'no_hp'         => ['required', 'string', 'max:20'],
            'prodi'         => ['required', 'string', 'max:255'],
            'fakultas'      => ['required', 'string', 'max:255'],
            'angkatan'      => ['required', 'numeric', 'min:2000', 'max:2100'],
            'jenis_kelamin' => ['required', 'in:L,P,Laki-laki,Perempuan'],
        ];
    }
}
