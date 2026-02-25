<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CompanyDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apiUrl = env('EMPLOYEE_API_URL');
        $apiKey = env('EMPLOYEE_API_KEY');

        try {
            // Uncomment this when API key is valid
            // $response = Http::withoutVerifying()->withToken($apiKey)->timeout(10)->get($apiUrl);

            // For now, use mock data or handle error gracefully
            // if ($response->successful()) {
            //     $data = $response->json();
            // } else {
            //     Log::error('Company Data API Error: ' . $response->status() . ' - ' . $response->body());
            //     $data = []; // Or Mock Data
            // }

            // MOCK DATA FOR DEVELOPMENT
            $data = [
                'status' => 'success',
                'data' => [
                    [
                        'id' => 1,
                        'company_code' => 'DPI',
                        'company_name' => 'PT Dharma Polimetal Tbk',
                        'address' => 'Jl. Ray Serang Km. 24, Balaraja, Tangerang',
                        'phone' => '021-5951624',
                        'email' => 'contact@dharmap.com',
                        'website' => 'www.dharmap.com',
                    ],
                    [
                        'id' => 2,
                        'company_code' => 'DPA',
                        'company_name' => 'PT Dharma Poliemtal Tbk (Automotive)',
                        'address' => 'Kawasan Industri GIIC Cikarang',
                        'phone' => '021-89901234',
                        'email' => 'info@dpa.co.id',
                        'website' => 'www.dpa.co.id',
                    ],
                    [
                        'id' => 3,
                        'company_code' => 'DPE',
                        'company_name' => 'PT Dharma Poliemtal Tbk (Energy)',
                        'address' => 'Cikarang Pusat',
                        'phone' => '021-89905678',
                        'email' => 'support@dpe.co.id',
                        'website' => 'www.dpe.co.id',
                    ],
                ],
            ];

            // Check if real API call would have worked (for logic structure)
            if (empty($apiKey)) {
                session()->flash('warning', 'API Key belum dikonfigurasi di file .env');
            }

        } catch (\Exception $e) {
            Log::error('Company Data API Exception: '.$e->getMessage());
            $data = ['data' => []];
            session()->flash('error', 'Terjadi kesalahan saat menghubungkan ke API Data Perusahaan.');
        }

        return view('she.company_data.index', [
            'companies' => $data['data'] ?? [],
            'debug_mode' => true, // To show we are using mock data
        ]);
    }
}
