@extends('layouts.admin')
@section('heading', 'Kelola Stok')
@section('content')
<div class="flex flex-col gap-4">
  <p class="text-sm" style="color:#7A7060">Perbarui jumlah stok untuk setiap kombinasi varian & ukuran. Stok otomatis berkurang saat pesanan diubah ke status Diproses/Dikirim/Selesai.</p>
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
          <form method="POST" action="{{ route('admin.stock.update', $p) }}" x-data="{ v: {{ $p->stock }} }" class="p-3 rounded-xl" style="background:#F8F4EE;border:1px solid {{ $p->stock < 20 ? 'rgba(239,68,68,0.3)' : 'rgba(196,152,74,0.15)' }}">
            @csrf @method('PATCH')
            <div class="text-xs font-semibold mb-2" style="color:#1B4D4D">{{ $s->label }}</div>
            <div class="flex items-center gap-1 mb-2">
              <button type="button" x-on:click="v = Math.max(0, Number(v) - 10)" class="w-7 h-7 rounded-lg text-sm font-bold flex items-center justify-center" style="background:#EDE5D4;color:#1B4D4D">−</button>
              <input type="number" min="0" name="stock" x-model.number="v" class="flex-1 text-center text-sm font-bold rounded-lg py-1 outline-none" style="background:#fff;border:1px solid rgba(196,152,74,0.2);color:#1B4D4D;min-width:0">
              <button type="button" x-on:click="v = Number(v) + 10" class="w-7 h-7 rounded-lg text-sm font-bold flex items-center justify-center" style="background:#EDE5D4;color:#1B4D4D">+</button>
            </div>
            <button class="w-full py-1.5 rounded-lg text-xs font-semibold hover:opacity-90" style="background:#1B4D4D;color:#F4EFE4">Simpan</button>
            @if ($p->stock > 0 && $p->stock < 20)<div class="text-xs mt-1 text-center" style="color:#EF4444">⚠ Stok kritis!</div>@endif
            @if ($p->stock === 0)<div class="text-xs mt-1 text-center" style="color:#EF4444">● Habis</div>@endif
          </form>
        @endforeach
      </div>
    </div>
  @empty
    <p class="text-sm" style="color:#7A7060">Belum ada varian. Tambahkan di menu Varian Produk.</p>
  @endforelse
</div>
@endsection
