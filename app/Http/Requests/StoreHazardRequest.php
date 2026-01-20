<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreHazardRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     * Dalam kasus ini, hanya pengguna yang terautentikasi (Auth::check()) yang diizinkan.
     */
    public function authorize(): bool
    {
        // Pastikan pengguna yang sedang login adalah Karyawan dan terautentikasi.
        return Auth::check();
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'NPK' => 'required|string|max:20',
            'dept' => 'required|string|max:100',
            'tgl_observasi' => 'required|date|before_or_equal:today',
            'location_id' => 'required|integer|exists:locations,id',
            'lokasi_detail_manual' => 'nullable|string',
            'deskripsi_bahaya' => 'required|string',
            'foto_bukti' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'tingkat_keparahan' => 'required|integer|in:1,3,5',
            'kemungkinan_terjadi' => 'required|integer|in:1,2,3,4,5',
            'kategori_stop6' => 'required|string|max:50',
            'ide_penanggulangan' => 'nullable|string',
            'cell_id' => 'nullable|integer|exists:cells,id',
        ];
    }

    /**
     * Dapatkan pesan kesalahan yang disesuaikan untuk aturan validasi tertentu.
     */
    public function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'string' => 'Kolom :attribute harus berupa teks.',
            'max' => 'Kolom :attribute tidak boleh melebihi :max karakter.',
            'integer' => 'Kolom :attribute harus berupa angka.',
            'min' => 'Kolom :attribute minimal harus :min.',
            'date' => 'Kolom :attribute harus berupa format tanggal yang valid.',
            'before_or_equal' => 'Tanggal Observasi tidak boleh melebihi hari ini.',
            'image' => 'File :attribute harus berupa gambar.',
            'mimes' => 'Format file :attribute yang diizinkan adalah :values.',
            'tingkat_keparahan.in' => 'Pilihan untuk Tingkat Keparahan tidak valid. Harap pilih salah satu dari opsi yang tersedia.',
            'kemungkinan_terjadi.in' => 'Pilihan untuk Kemungkinan Terjadi tidak valid. Harap pilih salah satu dari opsi yang tersedia.',
            'in' => 'Kolom :attribute memiliki nilai yang tidak valid.',
            'location_id.exists' => 'Lokasi yang dipilih tidak valid atau tidak ada dalam sistem.',
        ];
    }

    /**
     * Dapatkan nama atribut yang disesuaikan untuk memberikan pesan yang lebih mudah dibaca.
     */
    public function attributes(): array
    {
        return [
            'NPK' => 'Nomor Pokok Karyawan (NPK)',
            'dept' => 'Departemen',
            'tgl_observasi' => 'Tanggal Observasi',
            'location_id' => 'Lokasi Kejadian',
            'lokasi_detail_manual' => 'Detail Tambahan Lokasi',
            'deskripsi_bahaya' => 'Deskripsi Bahaya',
            'foto_bukti' => 'Foto Bukti',
            'tingkat_keparahan' => 'Tingkat Keparahan (Severity)',
            'kemungkinan_terjadi' => 'Kemungkinan Terjadi (Probability)',
            'kategori_stop6' => 'Kategori STOP6',
            'ide_penanggulangan' => 'Ide Penanggulangan',
        ];
    }
}
