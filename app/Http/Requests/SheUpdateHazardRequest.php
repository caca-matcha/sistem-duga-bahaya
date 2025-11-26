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
        // Ganti 'she' dengan nama peran yang benar di aplikasi Anda.
        // Asumsi: user memiliki method hasRole() atau serupa.
        // Untuk tujuan testing, Anda bisa menggunakan: return Auth::check();
        return Auth::check() && Auth::user()->hasRole('she'); 
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan update/review SHE.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Aturan untuk data yang diubah atau ditambahkan oleh SHE
        return [
            // Status wajib diubah oleh SHE.
            'status' => 'required|string|in:baru,diproses,disetujui,ditolak,selesai',
            
            // --- FIELD TAMBAHAN DARI SHE ---
            'jenis_bahaya' => 'nullable|string|max:100', // Jenis bahaya (Kimia, Fisik, Ergonomi, dsb.)
            'faktor_penyebab' => 'nullable|string|max:100', // Faktor penyebab (Unsafe Act / Condition)

            //--FILE INPUT dari Karyawan dan bisa di edit SHE
            'tingkat_keparahan' => 'nullable|integer|min:1|max:5',
            'kemungkinan_terjadi' => 'nullable|integer|min:1|max:5',
            'nilai_risk' => 'nullable|integer|min:1|max:25',
            'kategori_resiko' => 'nullable|string|max:50',
            'alasan_penolakan' => 'nullabel|string|max:100',

            // Upaya Penanggulangan (Array dari Checkbox yang dipilih)
            // Asumsi: nilai yang dipilih adalah array string (misal: ['Eliminasi', 'Substitusi']).
            'upaya_penanggulangan' => 'nullable|array',
            'upaya_penanggulangan.*' => 'string|max:100', // Validasi setiap item dalam array
            
            // Catatan Textarea untuk setiap upaya (Dikirim sebagai array/objek dari frontend)
            // Validasi ini fleksibel, Controller harus meng-encode ini ke JSON.
            'catatan_penanggulangan' => 'nullable|array',
            'catatan_penanggulangan.*' => 'string', // Validasi setiap catatan adalah string
            
            // --- FIELD TINDAK LANJUT STANDAR ---
            'tindakan_perbaikan' => 'nullable|string',
            'resiko_residual' => 'nullable|integer|min:1|max:25', 
            'pic_penanggung_jawab' => 'nullable|string|max:150',
            'target_penyelesaian' => 'nullable|date|after_or_equal:today',

            // FIELD BARU: FOTO BUKTI PENYELESAIAN
            'foto_bukti_penyelesaian' => ['nullable','image','mimes:jpg,jpeg,png','max:5120'],
            'ditangani_pada' => 'required|date|today',
        ];
    }

    
    /**
     * Tentukan kapan kolom tertentu menjadi 'wajib' (conditional validation).
     * Jika SHE menyetujui atau menyelesaikan, beberapa field harus diisi.
     */

    public function quickReject(Hazard $hazard)
    {
    // Update status langsung jadi 'ditolak'
    $hazard->update([
        'status' => 'ditolak',
        'pic_penanggung_jawab' => Auth::id(),
        'ditangani_pada' => now(),
        'alasan_penolakan',
        ]);
             return redirect()->route('she.hazards.show', $hazard)
        ->with('success', 'Laporan berhasil ditolak.');
    }


    public function withValidator($validator)
    {
        $validator->sometimes([
            'tindakan_perbaikan', 
            'pic_penanggung_jawab', 
            'target_penyelesaian',
            'jenis_bahaya',
            'faktor_penyebab',
            'upaya_penanggulangan',
            'resiko_residual',
            'risk_score',
            'kategori_resiko',
        ], 'required', function ($input) {
            return in_array($input->status, ['disetujui', 'selesai']);
        });

        // FOTO BUKTI PENYELESAIAN WAJIB HANYA KETIKA STATUS = 'selesai'
        $validator->sometimes(['foto_bukti_penyelesaian'], ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'], function ($input) {
            return $input->status === 'selesai';
        });
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
            'jenis_bahaya' => 'Jenis Bahaya',
            'faktor_penyebab' => 'Faktor Penyebab Kecelakaan',
            'upaya_penanggulangan' => 'Upaya Penanggulangan',
            'upaya_penanggulangan.*' => 'Detail Upaya Penanggulangan',
            'tingkat_keparahan' => 'Tingkat Keparahan (Severity)',
            'catatan_penanggulangan.*' => 'Catatan Penanggulangan',
            'tindakan_perbaikan' => 'Tindakan Perbaikan',
            'resiko_residual' => 'Resiko Residual',
            'pic_penanggung_jawab' => 'PIC Penanggung Jawab',
            'target_penyelesaian' => 'Target Penyelesaian',
            'foto_bukti_penyelesaian' => 'Foto Bukti Penyelesaian', // ATRIBUT BARU
            'kategori_resiko' => 'kategori_resiko'
        ];
    }
}
