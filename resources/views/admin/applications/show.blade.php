@extends('layouts.app')
@section('title', 'Detail Pendaftar')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Detail Pendaftar</h1>
        <a href="{{ route('admin.applications.index') }}" class="text-blue-600 hover:underline text-sm">← Kembali</a>
    </div>

    {{-- Header --}}
    <div class="bg-white shadow rounded-xl p-6 mb-4">
        <div class="flex justify-between">
            <div>
                <div class="text-2xl font-bold">{{ $application->full_name }}</div>
                <div class="text-gray-600">{{ $application->studyProgram->name }} · {{ $application->admissionPeriod->name }}</div>
                @if($application->registration_number)
                    <div class="font-mono text-blue-700 text-sm mt-1">{{ $application->registration_number }}</div>
                @endif
            </div>
            <div>
                <span class="px-4 py-1 rounded-full font-semibold bg-gray-100 text-gray-700">{{ $application->status_label }}</span>
            </div>
        </div>
    </div>

    {{-- Data Pribadi --}}
    <div class="bg-white shadow rounded-xl p-6 mb-4">
        <h2 class="font-bold text-blue-700 mb-3">Data Pribadi</h2>
        <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
            <dt class="text-gray-500">Email</dt><dd>{{ $application->user->email }}</dd>
            <dt class="text-gray-500">NIK</dt><dd>{{ $application->nik ?? '-' }}</dd>
            <dt class="text-gray-500">Jenis Kelamin</dt><dd>{{ $application->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</dd>
            <dt class="text-gray-500">Tgl Lahir</dt><dd>{{ $application->birth_date->format('d M Y') }} · {{ $application->birth_place }}</dd>
            <dt class="text-gray-500">No. HP</dt><dd>{{ $application->phone }}</dd>
            <dt class="text-gray-500">Agama</dt><dd>{{ $application->religion ?? '-' }}</dd>
            <dt class="text-gray-500">Alamat</dt><dd>{{ $application->address }}, {{ $application->city }}, {{ $application->province }}</dd>
        </dl>
    </div>

    {{-- Data Pendidikan --}}
    <div class="bg-white shadow rounded-xl p-6 mb-4">
        <h2 class="font-bold text-blue-700 mb-3">Data Pendidikan</h2>
        <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
            <dt class="text-gray-500">Sekolah</dt><dd>{{ $application->school_name }}</dd>
            <dt class="text-gray-500">Kota Sekolah</dt><dd>{{ $application->school_city }}</dd>
            <dt class="text-gray-500">Jurusan</dt><dd>{{ $application->major ?? '-' }}</dd>
            <dt class="text-gray-500">Tahun Lulus</dt><dd>{{ $application->graduation_year }}</dd>
            <dt class="text-gray-500">Nilai Rata-rata</dt><dd>{{ $application->gpa ?? '-' }}</dd>
        </dl>
    </div>

    {{-- Dokumen --}}
    <div class="bg-white shadow rounded-xl p-6 mb-4">
        <h2 class="font-bold text-blue-700 mb-3">Dokumen</h2>
        @if($application->documents->isEmpty())
            <p class="text-gray-500 text-sm">Belum ada dokumen.</p>
        @else
            <div class="space-y-2">
                @foreach($application->documents as $doc)
                    <div class="flex items-center justify-between border rounded-lg px-3 py-2">
                        <div>
                            <div class="font-medium text-sm">{{ $doc->label }}</div>
                            <div class="text-xs text-gray-500">{{ $doc->original_name }} · {{ round($doc->file_size/1024,1) }} KB</div>
                            @if($doc->admin_note)<div class="text-xs text-orange-600">{{ $doc->admin_note }}</div>@endif
                        </div>
                        <a href="{{ route('admin.applications.document.download', [$application, $doc]) }}"
                            class="text-sm bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                            Download
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Admin Actions --}}
    @if(in_array($application->status, ['submitted', 'under_review']))
        <div class="bg-white shadow rounded-xl p-6 mb-4">
            <h2 class="font-bold text-blue-700 mb-3">Tindakan Pemeriksaan</h2>
            <form method="POST" action="{{ route('admin.applications.review', $application) }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Aksi</label>
                    <select name="action" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        @if($application->status === 'submitted')
                            <option value="under_review">Mulai Periksa (Under Review)</option>
                        @endif
                        <option value="revision_required">Minta Revisi</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Catatan (wajib jika minta revisi)</label>
                    <textarea name="note" rows="3"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Jelaskan apa yang perlu diperbaiki..."></textarea>
                </div>
                <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-yellow-700">
                    Simpan Tindakan
                </button>
            </form>
        </div>
    @endif

    @if(in_array($application->status, ['submitted', 'under_review']))
        <div class="bg-white shadow rounded-xl p-6 mb-4">
            <h2 class="font-bold text-blue-700 mb-3">Keputusan Seleksi</h2>
            <form method="POST" action="{{ route('admin.applications.decide', $application) }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Keputusan</label>
                    <select name="decision" id="decision"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="passed">✓ Lulus</option>
                        <option value="not_passed">✗ Tidak Lulus</option>
                    </select>
                </div>
                <div class="mb-3" id="ukt-golongan-field">
                    <label class="block text-sm font-medium mb-1">Golongan UKT</label>
                    <select name="ukt_golongan" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih golongan 1–5</option>
                        @foreach(config('ukt.golongan') as $golongan => $nominal)
                            <option value="{{ $golongan }}">
                                {{ config('ukt.labels.'.$golongan) }} — Rp {{ number_format($nominal, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Wajib diisi jika keputusan lulus. Nominal mengikuti golongan.</p>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Catatan (opsional)</label>
                    <textarea name="note" rows="2"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Catatan untuk pendaftar..."></textarea>
                </div>
                <button type="submit"
                    onclick="return confirm('Yakin ingin menetapkan keputusan ini?')"
                    class="bg-blue-700 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-800">
                    Tetapkan Keputusan
                </button>
            </form>
        </div>
    @endif

    @if($application->status === 'passed')
        <div class="bg-emerald-50 border border-emerald-200 shadow rounded-xl p-6 mb-4">
            <h2 class="font-bold text-emerald-800 mb-2">UKT Ditetapkan</h2>
            @if($application->ukt_golongan)
                <p class="text-sm text-emerald-900 mb-3">
                    {{ $application->ukt_golongan_label }}
                    — Rp {{ number_format($application->uktAmount() ?? 0, 0, ',', '.') }}
                </p>
                <p class="text-xs text-emerald-700 mb-3">Status pembayaran: {{ $application->payment_status_label }}</p>
            @else
                <p class="text-sm text-amber-800 mb-3">Golongan UKT belum ditetapkan.</p>
            @endif
            <form method="POST" action="{{ route('admin.applications.ukt', $application) }}" class="flex flex-wrap gap-2 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-medium mb-1">Ubah golongan</label>
                    <select name="ukt_golongan" class="border rounded-lg px-3 py-2 text-sm">
                        @foreach(config('ukt.golongan') as $golongan => $nominal)
                            <option value="{{ $golongan }}" @selected($application->ukt_golongan == $golongan)>
                                {{ config('ukt.labels.'.$golongan) }} — Rp {{ number_format($nominal, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-800">
                    Simpan Golongan
                </button>
            </form>
        </div>
    @endif

    {{-- Riwayat Status --}}
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="font-bold text-blue-700 mb-4">Riwayat Status</h2>
        @if($application->statusHistories->isEmpty())
            <p class="text-gray-500 text-sm">Belum ada riwayat.</p>
        @else
            <div class="space-y-2 text-sm">
                @foreach($application->statusHistories as $h)
                    <div class="flex gap-3">
                        <div class="text-gray-400 text-xs w-36 shrink-0 pt-0.5">{{ $h->created_at->format('d M Y H:i') }}</div>
                        <div>
                            <span class="text-blue-700 font-medium">{{ $h->to_status }}</span>
                            <span class="text-gray-500 text-xs ml-1">oleh {{ $h->changedBy->name }}</span>
                            @if($h->note)<div class="text-gray-600">{{ $h->note }}</div>@endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    const decisionSelect = document.getElementById('decision');
    const golonganField = document.getElementById('ukt-golongan-field');

    function toggleGolongan() {
        if (!decisionSelect || !golonganField) return;
        golonganField.style.display = decisionSelect.value === 'passed' ? '' : 'none';
    }

    if (decisionSelect) {
        decisionSelect.addEventListener('change', toggleGolongan);
        toggleGolongan();
    }
</script>
@endsection