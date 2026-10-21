@extends('layouts.admin')
@section('heading', 'Diskon')
@section('content')
@php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp
<div class="flex flex-col gap-4">
  <p class="text-sm" style="color:#7A7060">Atur persentase diskon untuk setiap kombinasi varian & ukuran. Masukkan 0 untuk menonaktifkan.</p>
  @forelse ($variants as $v)
    <div class="rounded-2xl overflow-hidden" style="background:#fff;border:1px solid rgba(196,152,74,0.15)">
      <div class="flex items-center gap-3 px-5 py-4" style="border-bottom:1px solid rgba(196,152,74,0.1);background:#F8F4EE">
        <span class="text-xl">{{ $v->emoji }}</span>
        <span class="font-bold" style="font-family:var(--font-display);color:#1B4D4D">{{ $v->name }}</span>
      </div>
      <div class="p-4 grid sm:grid-cols-2 lg:grid-cols-5 gap-3">
        @foreach ($sizes as $s)
          @php $p = $v->products->firstWhere('size_id', $s->id); @endphp
          @continue(!$p)
          <form method="POST" action="{{ route('admin.discounts.update', $p) }}" x-data="{ d: {{ $p->discount }}, base: {{ $s->base_price }} }" class="p-3 rounded-xl" style="background:#F8F4EE;border:1px solid {{ $p->discount > 0 ? 'rgba(196,152,74,0.4)' : 'rgba(196,152,74,0.15)' }}">
            @csrf @method('PATCH')
            <div class="text-xs font-semibold mb-1" style="color:#1B4D4D">{{ $s->label }}</div>
            <div class="text-xs mb-2" style="color:#C4984A" x-text="'Rp ' + Math.round(base * (100 - (d || 0)) / 100).toLocaleString('id-ID')">{{ $rp($p->final_price) }}</div>
            <div class="flex items-center gap-1 mb-2">
              <input type="number" min="0" max="80" name="discount" x-model.number="d" class="flex-1 text-center text-sm font-bold rounded-lg py-1 outline-none" style="background:#fff;border:1px solid rgba(196,152,74,0.2);color:#1B4D4D;min-width:0">
              <span class="text-sm" style="color:#7A7060">%</span>
            </div>
            <div x-show="d > 0" class="text-xs mb-2 text-center font-semibold" style="color:#C4984A" x-text="'DISC ' + d + '%'"></div>
            <button class="w-full py-1.5 rounded-lg text-xs font-semibold" style="background:#C4984A;color:#F4EFE4">Simpan</button>
          </form>
        @endforeach
      </div>
    </div>
  @empty
    <p class="text-sm" style="color:#7A7060">Belum ada varian.</p>
  @endforelse
</div>
@endsection
