@extends('layouts.public')
@section('title', 'Program Studi')

@section('content')
<h1 class="text-2xl font-bold mb-6">Program Studi</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @forelse($programs as $program)
        <div class="bg-white shadow rounded-xl p-5">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ $program->kode }}</span>
                    <h3 class="font-bold text-lg mt-1">{{ $program->nama }}</h3>
                    <div class="text-gray-500 text-sm">
                        {{ $program->jenjang }} · {{ $program->fakultas->nama }}
                    </div>
                    @if($program->deskripsi)
                        <p class="text-gray-600 text-sm mt-2">{{ $program->deskripsi }}</p>
                    @endif
                </div>
                @if($program->kuota)
                    <div class="text-right text-sm">
                        <div class="text-gray-400">Kuota</div>
                        <div class="font-bold text-blue-700">{{ $program->kuota }}</div>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <p class="text-gray-500 col-span-2">Belum ada program studi.</p>
    @endforelse
</div>
@endsection
