<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreHazardRequest extends FormRequest
{
    
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     * Dalam kasus ini, hanya pengguna yang terautentikasi (Auth::check()) yang diizinkan.
     *
     * @return bool
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
            'area_gedung' => 'required|string|max:100',
            // Nama field ini harus sinkron dengan field di Blade.
            'aktivitas_kerja' => 'required|string|max:100',
            'deskripsi_bahaya' => 'required|string',
            'foto_bukti' => ['nullable','image','mimes:jpg,jpeg,png','max:5120'],
            'tingkat_keparahan' => 'required|integer|min:1|max:5',
            'kemungkinan_terjadi' => 'required|integer|min:1|max:5',
            'kategori_stop6' => 'required|string|max:50',
            // ATURAN VALIDASI UNTUK SKOR DAN KATEGORI RISIKO (Wajib diisi dan divalidasi)
            'risk_score' => 'nullable|integer|min:1|max:25', // Hasil kali Severity * Probability (1-25)
            'kategori_resiko' => 'required|string|in:Low,Medium,High,Low (Rendah),Medium (Sedang),High (Tinggi)', // Harus salah satu kategori ini
            'ide_penanggulangan' => 'nullable|string',
        ];
    }
    
    /**
     * Dapatkan pesan kesalahan yang disesuaikan untuk aturan validasi tertentu.
     *
     * @return array
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
            'in' => 'Kolom :attribute memiliki nilai yang tidak valid.',
        ];
    }
    
    /**
     * Dapatkan nama atribut yang disesuaikan untuk memberikan pesan yang lebih mudah dibaca.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'NPK' => 'Nomor Pokok Karyawan (NPK)',
            'dept' => 'Departemen',
            'tgl_observasi' => 'Tanggal Observasi',
            'area_gedung' => 'Area/Gedung',
            'aktivitas_kerja' => 'Aktivitas Kerja',
            'deskripsi_bahaya' => 'Deskripsi Bahaya',
            'foto_bukti' => 'Foto Bukti',
            'tingkat_keparahan' => 'Tingkat Keparahan (Severity)',
            'kemungkinan_terjadi' => 'Kemungkinan Terjadi (Probability)',
            'kategori_stop6' => 'Kategori STOP6',
            'risk_score' => 'Skor Resiko',
            'kategori_resiko' => 'Kategori Resiko',
            'ide_penanggulangan' => 'Ide Penanggulangan',
        ];
    }
}