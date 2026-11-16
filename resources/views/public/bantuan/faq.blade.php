@extends('layouts.public')
@section('title', 'Pertanyaan Umum')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Pertanyaan Umum</h1>
    <p class="text-gray-600 mt-1">Jawaban atas pertanyaan yang sering diajukan calon mahasiswa.</p>
</div>

@forelse($faq as $kategori => $items)
    <div class="mb-6 last:mb-0">
        <h2 class="text-lg font-bold text-gray-700 mb-3">{{ $kategori }}</h2>

        <div class="space-y-2">
            @foreach($items as $item)
                <details class="group bg-white shadow rounded-xl overflow-hidden">
                    <summary class="flex items-center justify-between gap-4 px-5 py-4 cursor-pointer font-medium text-gray-800 hover:bg-blue-50/50 list-none">
                        <span>{{ $item->pertanyaan }}</span>
                        <span class="shrink-0 text-blue-600 transition-transform group-open:rotate-45 text-xl leading-none">+</span>
                    </summary>
                    <div class="px-5 pb-5 text-gray-600 leading-relaxed border-t border-gray-100 pt-4">
                        {{ $item->jawaban }}
                    </div>
                </details>
            @endforeach
        </div>
    </div>
@empty
    <p class="text-gray-500">Belum ada pertanyaan yang tersedia.</p>
@endforelse

<div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
    <p class="text-blue-900">Belum menemukan jawabannya?</p>
    <a href="{{ route('public.bantuan.kontak') }}" class="text-blue-600 hover:underline font-medium">Hubungi panitia PMB →</a>
</div>
@endsection
