@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 900px">
    <h1 class="mb-3">Form Laporan Duga Bahaya</h1>

    {{-- tampilkan error validasi --}}
    @if ($errors->any())
        <div style="padding: 8px; background: #fee2e2; margin-bottom: 12px;">
            <strong>Terjadi kesalahan:</strong>
            <ul style="margin-left: 16px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('karyawan.hazards.store') }}">
        @csrf

        {{-- Data karyawan --}}
        <h3>Data Karyawan</h3>

        <div style="margin-bottom: 10px;">
            <label>Nama</label><br>
            <input type="text" value="{{ auth()->user()->name }}" disabled style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>NPK</label><br>
            <input type="text" name="NPK" value="{{ old('NPK') }}" style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Departemen</label><br>
            <select name ="dept" style="width: 100%;">
            <option value="Produksi"{{ old('dept')=='Produksi'?'selected': '' }}>Produksi</option>
            <option value="'Quality"{{ old ('dept')=='Quality'?'selected':'' }}>Quality</option>
            <option value="Engineering"{{ old('dept')=='Engineering'?'selected':'' }}>Engineering</option>
            <option value="Finance"{{ old('dept')=='Finance'?'selected':'' }}>Finance</option>
            <option value="Human Resource"{{ old('dept')=='Human Resource'?'selected':'' }}>HumanResource</option>
            </select>
        </div>

        <hr style="margin: 16px 0;">

        {{-- Data observasi --}}
        <h3>Data Observasi</h3>

        <div style="margin-bottom: 10px;">
            <label>Tanggal Observasi</label><br>
            <input type="date" name="tgl_observasi" value="{{ old('tgl_observasi') }}" style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Area Gedung</label><br>
            <select name="area_gedung" id="area_gedung" style="'width: 100%; ">
                <option value="">--Pilih Gedung --</option>
                <option value="Gedung A" {{ old('area_gedung')== 'Gedung A' ? 'selected'
                <option value="Gedung B" {{ old('area_gedung')== 'Gedung B' ? 'selected'
                <option value="Gedung C" {{ old('area_gedung')== 'Gedung C' ? 'selected'
                <option value="Gedung D" {{ old('area_gedung')== 'Gedung D' ? 'selected'
                <option value="Gedung E" {{ old('area_gedung')== 'Gedung E' ? 'selected'
                <option value="Gedung F" {{ old('area_gedung')== 'Gedung F' ? 'selected'
                <option value="Gedung G" {{ old('area_gedung')== 'Gedung G' ? 'selected'
            </select>
        </div>

        <div style="margin-bottom: 10px;">
            <label>Line</label><br>
            <input type="text" name="line" value="{{ old('line') }}" style="width: 100%;">
        </div>

        <hr style="margin: 16px 0;">

        {{-- Detail Bahaya --}}
        <h3>Detail Bahaya</h3>

        <div style="margin-bottom: 10px;">
            <label>Deskripsi Bahaya</label><br>
            <textarea name="deskripsi_bahaya" rows="4" style="width: 100%;">{{ old('deskripsi_bahaya') }}</textarea>
        </div>

        <div style="margin-bottom: 10px;">
            <label>Jenis Bahaya</label><br>
            <select name ="jenis_bahaya" style="width: 100%;">
            <option value="A-Aparatus (terjepit, terpotong, tersayat)"{{ old('jenis_bahaya')=='Aparatus'?'selected': '' }}>Aparatus</option>
            <option value="B-Big Heavy (Tertimpa benda berat/terbentur)"{{ old('jenis_bahaya')=='BigHeavy'?'selected':''}}>BigHeavy</option>
            <option value="C-Car (Tertabrak kendaraan berat/alat angkut)"{{ old('jenis_bahaya')=='Car'?'selected':''}}>Car</option>
            <option value="D-Drop(Terjatuh dari ketinggian/tersandung)"{{old('jenis-bahaya')=='Drop'?'selected':''}}>Drop</option>
            <option value= "E-Electrical (Tersengat arus listrik)" {{ old('jenis_bahaya')=='Electrical'?' 'selected':''}}>Electrical</option>
            <option value="O-Others (Kimia, lingkungan, gigitan hewan"{{ old('jenis_bahaya' ) == 'Others'?'selected':''}}>Others</option>
            <option

        </div>

        <div style="margin-bottom: 10px;">
            <label>Faktor Penyebab</label><br>
            <input type="text" name="faktor_penyebab" value="{{ old('faktor_penyebab') }}" style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Tingkat Keparahan (misal 1-5)</label><br>
            <input type="number" name="tingkat_keparahan" value="{{ old('tingkat_keparahan') }}" style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Kemungkinan Terjadi (misal 1-5)</label><br>
            <input type="number" name="kemungkinan_terjadi" value="{{ old('kemungkinan_terjadi') }}" style="width: 100%;">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Ide Penanggulangan</label><br>
            <textarea name="ide_penanggulangan" rows="3" style="width: 100%;">{{ old('ide_penanggulangan') }}</textarea>
        </div>
        @extends('layouts.app')

@section('content')
<div class="container" style="max-width: 900px">
    <h1 class="mb-3">Form Laporan Duga Bahaya</h1>

    {{-- error validasi --}}
    @if ($errors->any())
        <div style="padding: 8px; background: #fee2e2; margin-bottom: 12px;">
            <strong>Terjadi kesalahan:</strong>
            <ul style="margin-left: 16px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
         @endif
        <button type="submit">
            Kirim Laporan
        </button>
    </form>
</div>

<script>
    //mapping : setiap gedung punya daftar line masing-masing
    const linesByGedung ={
        'Gedung A' : ['Line A1', 'Line A2', 'Line A3'],
        'Gedung B' : ['Line B1', 'Line B2', 'Line B3'],
        'Gedung C' : ['Line C1', 'Line C2', 'Line C3'],
        'Gedung D' : ['Line D1', 'Line D2', 'Line D3', 'Line D4'],
        'Gedung E' : ['Line E1', 'Line E2', 'Line E3', 'Line E4'],
        'Gedung F' : ['Line F1', 'Line F2', 'Line F3', 'Line F4'],
        'Gedung G' : ['Line G1', 'Line G2', 'Line G3', 'Line G4'],
    };

    const gedungSelect = document.getElementById('area_gedung');
    const lineSelect = document.getElementById('line');

    function populateLines(selectedGedung) {
    // kosongkan dropdown line dulu
    lineSelect.innerHTML = '<option value="">-- Pilih Line --</option>';

    if (!selectedGedung || !linesByGedung[selectedGedung]) {
        return;
    }

    lines.forEach(function(line) {
            const option = document.createElement('option');
            option.value = line;
            option.textContent = line;
            // supaya old('line') keisi lagi waktu validasi gagal
            @if(old('line'))
                if ("{{ old('line') }}" === line) {
                    option.selected = true;
                }
            @endif
            lineSelect.appendChild(option);
        });
    }

    //saat dropdown gedung berubah
    gedungSelect.addEventListener('change', function(){
        populateLines(this.value);
    });

    //kalau form reload karena error validasi, isi ulang line berdasar old(area)
     document.addEventListener('DOMContentLoaded', function() {
        const oldGedung = "{{ old('area_gedung') }}";
        if (oldGedung) {
            populateLines(oldGedung);
        }
    });
</script>

@endsection
