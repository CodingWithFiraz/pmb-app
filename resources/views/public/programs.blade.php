@extends('layouts.app')
@section('title', 'Program Studi')

@section('content')
<h1 class="text-2xl sm:text-3xl font-bold text-black mb-6">Program Studi</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    @forelse($programs as $program)
        <div class="bg-white rounded-2xl p-6 border border-[#E5EAFC] shadow-xs hover:shadow-md transition">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <span class="text-xs font-mono font-bold text-[#606ADF] bg-[#F2F5FF] border border-[#606ADF]/20 px-2.5 py-1 rounded-lg">
                        {{ $program->code }}
                    </span>
                    <h3 class="font-bold text-lg text-black mt-3">{{ $program->name }}</h3>
                    <div class="text-[#818CA2] text-sm mt-0.5">{{ $program->faculty }}</div>
                    @if($program->description)
                        <p class="text-gray-600 text-sm mt-3 leading-relaxed">{{ $program->description }}</p>
                    @endif
                </div>
                @if($program->quota)
                    <div class="text-right text-sm shrink-0">
                        <div class="text-[#818CA2] text-xs uppercase font-medium">Kuota</div>
                        <div class="font-bold text-xl text-[#606ADF] mt-0.5">{{ $program->quota }}</div>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl p-8 border border-[#E5EAFC] text-center text-[#818CA2] col-span-2">
            Belum ada program studi yang tersedia.
        </div>
    @endforelse
</div>
@endsection
