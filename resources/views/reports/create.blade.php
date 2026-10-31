@extends('layouts.app')
@section('title', 'Buat Laporan Keluhan')

@section('content')
<div class="card narrow">
    <h2>Buat Laporan Keluhan MBG</h2>
    
    @if ($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 16px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div style="margin-bottom: 14px;">
            <label style="display: block; font-weight: bold; margin-bottom: 4px;">Pilih Sekolah</label>
            <select id="school_id" name="school_id" required style="width: 100%; padding: 8px;">
                <option value="">-- Pilih Sekolah --</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                        {{ $school->name }} ({{ $school->district }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 14px;">
            <label style="display: block; font-weight: bold; margin-bottom: 4px;">Tanggal & Menu Distribusi Makanan</label>
            <select id="meal_distribution_id" name="meal_distribution_id" required disabled style="width: 100%; padding: 8px;">
                <option value="">-- Pilih Sekolah Terlebih Dahulu --</option>
            </select>
        </div>

        <div style="margin-bottom: 14px;">
            <label style="display: block; font-weight: bold; margin-bottom: 4px;">Waktu Kejadian Keluhan</label>
            <input type="datetime-local" name="incident_at" value="{{ old('incident_at') }}" required style="width: 100%; padding: 8px;">
        </div>

        <div style="margin-bottom: 14px;">
            <label style="display: block; font-weight: bold; margin-bottom: 4px;">Jumlah Korban Terdampak</label>
            <input type="number" name="affected_count" value="{{ old('affected_count', 1) }}" min="1" required style="width: 100%; padding: 8px;">
        </div>

        <div style="margin-bottom: 14px;">
            <label style="display: block; font-weight: bold; margin-bottom: 4px;">Deskripsi Keluhan / Gejala</label>
            <textarea name="description" rows="4" required style="width: 100%; padding: 8px;" placeholder="Jelaskan detail keluhan kesehatan atau kondisi makanan yang bermasalah...">{{ old('description') }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 4px;">Unggah Bukti Pendukung (Opsional, Max 2MB: JPG, PNG, PDF)</label>
            <input type="file" name="evidence" accept=".jpg,.jpeg,.png,.pdf">
        </div>

        <button type="submit" class="btn" style="width: 100%;">Kirim Laporan Keluhan</button>
    </form>
</div>

<!-- AJAX Fetch API untuk memuat jadwal distribusi makanan berdasarkan sekolah yang dipilih -->
<script>
document.getElementById('school_id').addEventListener('change', function() {
    let schoolId = this.value;
    let distSelect = document.getElementById('meal_distribution_id');
    
    distSelect.innerHTML = '<option value="">Sedang memuat jadwal distribusi...</option>';
    distSelect.disabled = true;

    if (schoolId) {
        fetch(`/api/schools/${schoolId}/distributions`)
            .then(response => response.json())
            .then(data => {
                distSelect.innerHTML = '<option value="">-- Pilih Jadwal Distribusi --</option>';
                if (data.length === 0) {
                    distSelect.innerHTML = '<option value="">Tidak ada riwayat distribusi untuk sekolah ini</option>';
                } else {
                    data.forEach(item => {
                        let option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = `${item.distribution_date} - ${item.menu_description}`;
                        distSelect.appendChild(option);
                    });
                    distSelect.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error fetching distributions:', error);
                distSelect.innerHTML = '<option value="">Gagal mengambil data distribusi</option>';
            });
    } else {
        distSelect.innerHTML = '<option value="">-- Pilih Sekolah Terlebih Dahulu --</option>';
    }
});
</script>
@endsection