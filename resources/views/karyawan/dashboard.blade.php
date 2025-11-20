@extends('layouts.main-layout')

@section('main-content')
<div class="max-w-4xl mx-auto">
    <h1 class="mb-3">Dashboard Karyawan</h1>

    @if(session('success'))
        <div class="p-2 bg-green-100 text-green-700 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('karyawan.hazards.create') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + Laporkan Duga Bahaya
        </a>
    </div>

    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-200 text-gray-700">
            <tr>
                <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Tanggal Observasi</th>
                <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Area / Line</th>
                <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Jenis Bahaya</th>
                <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Skor Risiko</th>
                <th class="py-3 px-4 uppercase font-semibold text-sm text-left">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hazards as $hazard)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-4">{{ $hazard->tgl_observasi }}</td>
                    <td class="py-3 px-4">{{ $hazard->area_gedung }} / {{ $hazard->line }}</td>
                    <td class="py-3 px-4">{{ $hazard->jenis_bahaya }}</td>
                    <td class="py-3 px-4">{{ $hazard->skor_resiko }}</td>
                    <td class="py-3 px-4">{{ ucfirst($hazard->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-3 px-4 text-center text-gray-500">Belum ada laporan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
