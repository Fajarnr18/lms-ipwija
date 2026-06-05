<?php

namespace App\Http\Requests\Tool;

use Illuminate\Foundation\Http\FormRequest;

class StoreToolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'kode_alat' => 'required|string|max:20|unique:tools,kode_alat',
            'nama_alat' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'stok_total' => 'required|integer|min:0',
            'stok_tersedia' => 'required|integer|min:0|lte:stok_total',
            'status_alat' => 'required|in:Tersedia,Dipinjam,Rusak,Dalam Perbaikan',
            'lokasi' => 'required|string|max:50',
            'foto_alat' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_alat.unique' => 'Kode alat sudah digunakan.',
            'stok_tersedia.lte' => 'Stok tersedia tidak boleh melebihi stok total.',
            'status_alat.in' => 'Status alat harus: Tersedia, Dipinjam, Rusak, atau Dalam Perbaikan.',
        ];
    }
}
