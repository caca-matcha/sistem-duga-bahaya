<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SheUpdateHazardRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     * Hanya pengguna dengan peran 'she' yang diizinkan.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->hasRole('she');
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan update/review SHE.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Status yang diperbolehkan dalam update (Status 'baru' hanya ada di awal, tidak dikirim di request update)
        $allowedStatus = ['diproses', 'ditolak', 'selesai'];
        
        // Tentukan status saat ini untuk validasi bersyarat
        $status = $this->input('status');

        return [
            // Status wajib diubah oleh SHE.
            'status' => 'required|string|in:' . implode(',', $allowedStatus),
            
            // --- VALIDASI PENOLAKAN (Status = ditolak) ---
            'alasan_penolakan' => 'required_if:status,ditolak|nullable|string|max:1000',
            
            // --- VALIDASI PENERIMAAN/PROSES (Status = diproses) ---

            // Wajib jika diproses (Verifikasi Final Risk Matrix)
            'final_tingkat_keparahan' => 'required_if:status,diproses|nullable|integer|min:1|max:5',
            'final_kemungkinan_terjadi' => 'required_if:status,diproses|nullable|integer|min:1|max:5',

            // Data Penanganan Lanjutan (Wajib jika status = diproses)
            'tindakan_perbaikan' => 'required_if:status,diproses|nullable|string',
            'target_penyelesaian' => 'required_if:status,diproses|nullable|date|after_or_equal:today',
            'faktor_penyebab' => 'required_if:status,diproses|nullable|string|max:100',
            
            // Upaya Penanggulangan (Array dari Checkbox yang dipilih)
            'upaya_penanggulangan' => 'nullable|array',
            'upaya_penanggulangan.*' => 'nullable|string|max:100', 
            
            // --- FIELD UMUM (TIDAK BERGANTUNG STATUS) ---
            'resiko_residual' => 'nullable|integer|min:1|max:25', 
            'kategori_stop6' => 'nullable|string|max:50',

            // --- FIELD SELESAI (Status = selesai) ---
            'foto_bukti_penyelesaian' => 'required_if:status,selesai|nullable|image|mimes:jpg,jpeg,png|max:5120',
            
            // Kolom di bawah ini dihapus karena nilainya dihitung di Controller atau diisi otomatis oleh Auth::id()
            // risk_score, kategori_resiko (dihitung)
            // ditangani_oleh (otomatis di Controller)
            // pic_penanggung_jawab (dihapus/diganti ditangani_oleh)
            // tingkat_keparahan / kemungkinan_terjadi (data awal karyawan, tidak boleh diedit)
        ];
    }
    
    /**
     * Dapatkan nama atribut yang disesuaikan.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'status' => 'Status Laporan',
            'kategori_stop6' => 'Kategori STOP6',
            'faktor_penyebab' => 'Faktor Penyebab Kecelakaan',
            'upaya_penanggulangan' => 'Upaya Penanggulangan',
            'upaya_penanggulangan.*' => 'Detail Upaya Penanggulangan',
            'tindakan_perbaikan' => 'Tindakan Perbaikan',
            'resiko_residual' => 'Resiko Residual',
            'target_penyelesaian' => 'Target Penyelesaian',
            'alasan_penolakan' => 'Alasan Penolakan',
            'final_tingkat_keparahan' => 'Final Tingkat Keparahan',
            'final_kemungkinan_terjadi' => 'Final Kemungkinan Terjadi',
            'foto_bukti_penyelesaian' => 'Foto Bukti Penyelesaian',
        ];
    }
}