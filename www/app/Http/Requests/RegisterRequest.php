<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'nim' => ['required', 'string', 'regex:/^([0-9]{12}|[0-9]{16})$/'],
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'konfirmasi_password' => 'required|string|same:password',
            'program_studi' => 'required|string|max:255',
            'no_whatsapp' => 'nullable|numeric|digits_between:10,13',
            'jenis_notifikasi' => 'nullable|in:Email,Whatsapp',
        ];
    }

    public function messages(): array
    {
        return [
            'password.min' => 'Password minimal 8 karakter.',
            'konfirmasi_password.same' => 'Password dan konfirmasi password tidak cocok.',
            'nim.regex' => 'Harus 12 digit (NIM) atau 16 digit (NUPTK) angka.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.email' => 'Format email tidak valid.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nim.required' => 'NIM/NUPTK wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'konfirmasi_password.required' => 'Konfirmasi password wajib diisi.',
            'program_studi.required' => 'Program studi wajib diisi.',
            'no_whatsapp.numeric' => 'Nomor WhatsApp harus berupa angka.',
            'no_whatsapp.digits_between' => 'Nomor WhatsApp harus antara 10 hingga 13 digit.',
        ];
    }
}
