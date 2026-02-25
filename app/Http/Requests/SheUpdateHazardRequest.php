<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SheUpdateHazardRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     * Hanya pengguna dengan peran 'she' yang diizinkan.
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
        // Status yang diperbolehkan dalam update
        $allowedStatus = ['diproses', 'ditolak', 'selesai'];

        // Tentukan apakah ini aksi "revisi" dari status 'menunggu verifikasi'
        // (yaitu SHE mengembalikan ke PIC setelah PIC sudah kirim bukti)
        $isFromVerification = $this->boolean('_from_verification');

        return [
            // Status wajib diubah oleh SHE.
            'status' => 'required|string|in:'.implode(',', $allowedStatus),

            // --- VALIDASI PENOLAKAN (Status = ditolak) ---
            'alasan_penolakan' => 'required_if:status,ditolak|nullable|string|max:1000',

            // --- VALIDASI PENERIMAAN/PROSES (Status = diproses) ---
            // Hanya wajib jika bukan aksi revisi dari menunggu verifikasi

            // Wajib jika diproses DAN bukan dari verifikasi
            'final_tingkat_keparahan' => ($isFromVerification ? 'nullable' : 'required_if:status,diproses').'|nullable|integer|in:1,3,5',
            'final_kemungkinan_terjadi' => ($isFromVerification ? 'nullable' : 'required_if:status,diproses').'|nullable|integer|in:1,2,3,4,5',
            'final_kategori_stop6' => ($isFromVerification ? 'nullable' : 'required_if:status,diproses').'|nullable|string|max:50',

            'rencana_perbaikan' => ($isFromVerification ? 'nullable' : 'required_if:status,diproses').'|nullable|string',
            'feedback_verifikasi' => 'nullable|string|max:1000',
            'target_penyelesaian' => 'nullable|date|after_or_equal:today',
            'faktor_penyebab' => ($isFromVerification ? 'nullable' : 'required_if:status,diproses').'|nullable|string|max:100',

            // Upaya Penanggulangan (Array dari Checkbox yang dipilih)
            'upaya_penanggulangan' => 'nullable|array',
            'upaya_penanggulangan.*' => 'nullable|string|max:100',

            // --- FIELD UMUM (TIDAK BERGANTUNG STATUS) ---
            'kategori_stop6' => 'nullable|string|max:50',
            'pic_id' => 'nullable|exists:users,id',

            // --- FIELD SELESAI (Status = selesai) ---
            'foto_bukti_penyelesaian' => [
                Rule::requiredIf(function () {
                    // Get the Hazard model from the route
                    $hazard = $this->route('hazard');

                    // If hazard already has a completion photo (uploaded by PIC), don't require it
                    if ($hazard && $hazard->foto_bukti_penyelesaian) {
                        return false;
                    }

                    $tindakan = $this->input('tindakan_perbaikan');
                    $isDirectCompletion =
                        str_contains($tindakan ?? '', 'Validasi tanpa tindak lanjut') ||
                        str_contains($tindakan ?? '', 'SHE akan tetap pantau area yg terlapor secara berkala');

                    return $this->input('status') === 'selesai' && ! $isDirectCompletion;
                }),
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf,doc,docx',
                'max:10240',
            ],
        ];
    }

    /**
     * Dapatkan pesan kesalahan yang disesuaikan untuk aturan validasi tertentu.
     */
    public function messages(): array
    {
        return [
            'final_tingkat_keparahan.in' => 'Pilihan untuk Final Tingkat Keparahan tidak valid. Harap pilih salah satu dari opsi yang tersedia.',
            'final_kemungkinan_terjadi.in' => 'Pilihan untuk Final Kemungkinan Terjadi tidak valid. Harap pilih salah satu dari opsi yang tersedia.',
            'foto_bukti_penyelesaian.required' => 'File bukti selesai belom di inputkan',
            'foto_bukti_penyelesaian.max' => 'Ukuran file bukti terlalu besar (Maksimal 10MB)',
        ];
    }

    /**
     * Dapatkan nama atribut yang disesuaikan.
     */
    public function attributes(): array
    {
        return [
            'status' => 'Status Laporan',
            'kategori_stop6' => 'Kategori STOP6',
            'faktor_penyebab' => 'Faktor Penyebab Kecelakaan',
            'upaya_penanggulangan' => 'Upaya Penanggulangan',
            'upaya_penanggulangan.*' => 'Detail Upaya Penanggulangan',
            'tindakan_perbaikan' => 'Tindakan Perbaikan (PIC)',
            'rencana_perbaikan' => 'Instruksi Rencana Perbaikan (SHE)',
            'feedback_verifikasi' => 'Feedback Verifikasi (SHE)',
            'target_penyelesaian' => 'Target Penyelesaian',
            'alasan_penolakan' => 'Alasan Penolakan',
            'aktivitas_kerja' => 'Aktivitas',
            'area_gedung' => 'Area Gedung',
            'final_tingkat_keparahan' => 'Final Tingkat Keparahan',
            'final_kemungkinan_terjadi' => 'Final Kemungkinan Terjadi',
            'final_kategori_stop6' => 'Final Kategori STOP6',
            'foto_bukti_penyelesaian' => 'Foto Bukti Penyelesaian',
        ];
    }
}
