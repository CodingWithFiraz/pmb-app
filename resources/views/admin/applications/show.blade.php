@extends('layouts.app')
@section('title', 'Detail Pendaftar')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Top Bar & Back Link --}}
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#818CA2] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-black transition">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.applications.index') }}" class="hover:text-black transition">Pendaftar</a>
                <span>/</span>
                <span class="text-[#606ADF]">Detail</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">Detail Pendaftar</h1>
        </div>
        <a href="{{ route('admin.applications.index') }}" 
           class="bg-white border border-[#E5EAFC] text-gray-700 hover:text-black hover:bg-[#F2F5FF] px-4 py-2 rounded-xl text-sm font-semibold transition inline-flex items-center gap-2">
            <svg class="w-4 h-4 text-[#818CA2]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    {{-- Applicant Summary Header Card --}}
    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#E5EAFC] shadow-xs">
        <div class="flex flex-wrap justify-between items-start gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">{{ $application->full_name }}</h2>
                @if($application->registration_number)
                    <div class="inline-block font-mono text-xs font-bold text-[#606ADF] bg-[#F2F5FF] border border-[#606ADF]/20 px-3 py-1 rounded-lg mt-3">
                        {{ $application->registration_number }}
                    </div>
                @endif
            </div>
            <div>
                @php
                    $statusStyles = [
                        'draft' => 'bg-gray-100 text-gray-600',
                        'submitted' => 'bg-blue-50 text-blue-700 border border-blue-200',
                        'under_review' => 'bg-amber-50 text-amber-700 border border-amber-200',
                        'revision_required' => 'bg-orange-50 text-orange-700 border border-orange-200',
                        'passed' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                        'not_passed' => 'bg-red-50 text-red-700 border border-red-200',
                    ];
                    $badgeStyle = $statusStyles[$application->status] ?? 'bg-gray-100 text-gray-700';
                @endphp
                <span class="px-4 py-1.5 rounded-full text-xs font-semibold inline-block {{ $badgeStyle }}">
                    {{ $application->status_label }}
                </span>
            </div>
        </div>
    </div>

    {{-- Data Pribadi --}}
    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#E5EAFC] shadow-xs">
        <h2 class="font-bold text-lg text-black mb-4 pb-2 border-b border-[#E5EAFC]">Data Pribadi</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3.5 text-sm">
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Email</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->user->email }}</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">NIK</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->nik ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Jenis Kelamin</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Tempat / Tanggal Lahir</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->birth_place }}, {{ $application->birth_date->format('d M Y') }}</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Nomor WhatsApp / HP</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->phone }}</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Agama</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->religion ?? '-' }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Alamat Lengkap</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->address }}, {{ $application->city }}, {{ $application->province }} ({{ $application->postal_code ?? '-' }})</dd>
            </div>
        </dl>
    </div>

    {{-- Data Pendidikan --}}
    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#E5EAFC] shadow-xs">
        <h2 class="font-bold text-lg text-black mb-4 pb-2 border-b border-[#E5EAFC]">Data Pendidikan</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3.5 text-sm">
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Nama Sekolah Asal</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->school_name }}</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Kota Sekolah</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->school_city }}</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Jurusan Asal</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->major ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Tahun Kelulusan</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->graduation_year }}</dd>
            </div>
            <div>
                <dt class="text-[#818CA2] text-xs uppercase font-medium">Nilai Rata-rata / GPA</dt>
                <dd class="font-semibold text-black mt-0.5">{{ $application->gpa ?? '-' }}</dd>
            </div>
        </dl>
    </div>

    {{-- Dokumen --}}
    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#E5EAFC] shadow-xs">
        <h2 class="font-bold text-lg text-black mb-4 pb-2 border-b border-[#E5EAFC]">Berkas & Dokumen</h2>
        @if($application->documents->isEmpty())
            <p class="text-[#818CA2] text-sm">Belum ada dokumen yang diunggah.</p>
        @else
            <div class="space-y-3">
                @foreach($application->documents as $doc)
                    <div class="flex flex-wrap items-center justify-between gap-3 p-4 rounded-xl border border-[#E5EAFC] bg-[#F2F5FF]/40 hover:bg-[#F2F5FF] transition">
                        <div>
                            <div class="font-semibold text-sm text-black">{{ $doc->label }}</div>
                            <div class="text-xs text-[#818CA2] mt-0.5">{{ $doc->original_name }} · {{ round($doc->file_size/1024, 1) }} KB</div>
                            @if($doc->admin_note)
                                <div class="text-xs text-orange-600 mt-1 font-medium">{{ $doc->admin_note }}</div>
                            @endif
                        </div>
                        <a href="{{ route('admin.applications.document.download', [$application, $doc]) }}"
                            class="bg-[#606ADF] hover:bg-[#4F58CC] text-white px-4 py-2 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5 shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            <span>Download</span>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Tindakan Pemeriksaan --}}
    @if(in_array($application->status, ['submitted', 'under_review']))
        <div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#E5EAFC] shadow-xs">
            <h2 class="font-bold text-lg text-black mb-4 pb-2 border-b border-[#E5EAFC]">Tindakan Pemeriksaan</h2>
            <form method="POST" action="{{ route('admin.applications.review', $application) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Aksi Pemeriksaan</label>
                    <select name="action" class="w-full bg-[#F2F5FF]/60 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF]">
                        @if($application->status === 'submitted')
                            <option value="under_review">Mulai Periksa (Under Review)</option>
                        @endif
                        <option value="revision_required">Minta Revisi Dokumen/Data</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Catatan Pemeriksa (wajib diisi jika minta revisi)</label>
                    <textarea name="note" rows="3"
                        class="w-full bg-[#F2F5FF]/60 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF]"
                        placeholder="Jelaskan apa yang perlu diperbaiki pendaftar..."></textarea>
                </div>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2.5 rounded-xl font-semibold text-sm transition shadow-xs">
                    Simpan Tindakan
                </button>
            </form>
        </div>
    @endif

    {{-- Keputusan Seleksi --}}
    @if(in_array($application->status, ['submitted', 'under_review']))
        <div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#E5EAFC] shadow-xs">
            <h2 class="font-bold text-lg text-black mb-4 pb-2 border-b border-[#E5EAFC]">Keputusan Akhir Seleksi</h2>
            <form method="POST" action="{{ route('admin.applications.decide', $application) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Keputusan Kelulusan</label>
                    <select name="decision" class="w-full bg-[#F2F5FF]/60 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF]">
                        <option value="passed">✓ Dinyatakan Lulus</option>
                        <option value="not_passed">✗ Dinyatakan Tidak Lulus</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Catatan Keputusan (opsional)</label>
                    <textarea name="note" rows="2"
                        class="w-full bg-[#F2F5FF]/60 border border-[#E5EAFC] rounded-xl px-4 py-2.5 text-sm text-black focus:outline-none focus:ring-2 focus:ring-[#606ADF] focus:border-[#606ADF]"
                        placeholder="Berikan pesan kelulusan / alasan..."></textarea>
                </div>
                <button type="submit"
                    onclick="return confirm('Yakin ingin menetapkan keputusan seleksi ini? Keputusan ini akan diteruskan ke pendaftar.')"
                    class="bg-[#606ADF] hover:bg-[#4F58CC] text-white px-6 py-2.5 rounded-xl font-semibold text-sm transition shadow-xs">
                    Tetapkan Keputusan Seleksi
                </button>
            </form>
        </div>
    @endif

    {{-- Riwayat Status --}}
    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-[#E5EAFC] shadow-xs">
        <h2 class="font-bold text-lg text-black mb-4 pb-2 border-b border-[#E5EAFC]">Riwayat Status</h2>
        @if($application->statusHistories->isEmpty())
            <p class="text-[#818CA2] text-sm">Belum ada catatan riwayat status.</p>
        @else
            <div class="space-y-3">
                @foreach($application->statusHistories as $h)
                    <div class="flex items-start gap-4 p-3.5 rounded-xl bg-[#F2F5FF]/50 border border-[#E5EAFC]">
                        <div class="text-xs text-[#818CA2] w-36 shrink-0 pt-0.5 font-medium">
                            {{ $h->created_at->format('d M Y, H:i') }}
                        </div>
                        <div class="flex-1 text-sm">
                            <span class="font-semibold text-[#606ADF]">{{ $h->to_status }}</span>
                            <span class="text-xs text-[#818CA2] ml-1.5">oleh {{ $h->changedBy->name }}</span>
                            @if($h->note)
                                <div class="text-gray-700 text-xs mt-1 bg-white p-2.5 rounded-lg border border-[#E5EAFC]">{{ $h->note }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
