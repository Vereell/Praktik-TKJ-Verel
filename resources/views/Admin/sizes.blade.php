@extends('layouts.admin')
@section('heading', 'Ukuran Botol')
@section('content')
@php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp
<div class="flex flex-col gap-5">
  <form method="POST" action="{{ route('admin.sizes.store') }}" class="p-5 rounded-2xl" style="background:#fff;border:1px solid rgba(196,152,74,0.15)">
    @csrf
    <h3 class="font-bold text-base mb-4" style="font-family:var(--font-display);color:#1B4D4D">Tambah Ukuran Baru</h3>
    <div class="grid sm:grid-cols-3 gap-4">
      <div><label class="block text-xs font-semibold mb-1.5" style="color:#1B4D4D">Label *</label><input name="label" value="{{ old('label') }}" required class="input-field" placeholder="cth. 250 ml"></div>
      <div><label class="block text-xs font-semibold mb-1.5" style="color:#1B4D4D">Volume (ml)</label><input type="number" name="ml" value="{{ old('ml', 250) }}" required class="input-field"></div>
      <div><label class="block text-xs font-semibold mb-1.5" style="color:#1B4D4D">Harga Dasar (Rp)</label><input type="number" name="base_price" value="{{ old('base_price', 25000) }}" required class="input-field"></div>
    </div>
    @if ($errors->any())<p class="text-xs mt-2" style="color:#EF4444">⚠ {{ $errors->first() }}</p>@endif
    <button class="btn-primary mt-4 text-sm">+ Tambah Ukuran</button>
  </form>

  <div class="p-5 rounded-2xl" style="background:#fff;border:1px solid rgba(196,152,74,0.15)">
    <h3 class="font-bold text-base mb-4" style="font-family:var(--font-display);color:#1B4D4D">Daftar Ukuran</h3>
    <div class="flex flex-col gap-2">
      @foreach ($sizes as $s)
        <div class="flex flex-wrap items-center justify-between gap-3 p-3 rounded-xl" style="background:#F8F4EE;border:1px solid rgba(196,152,74,0.12)">
          <div class="flex items-center gap-4">
            <span class="text-sm font-bold" style="color:#1B4D4D">{{ $s->label }}</span>
            <span class="text-xs" style="color:#7A7060">{{ $s->ml }} ml</span>
            <span class="text-xs font-semibold" style="color:#C4984A">{{ $rp($s->base_price) }}</span>
          </div>
          <div class="flex items-center gap-3">
            <form method="POST" action="{{ route('admin.sizes.update', $s) }}" class="flex items-center gap-2">@csrf @method('PATCH')
              <input type="number" name="base_price" value="{{ $s->base_price }}" min="0" class="text-xs rounded-lg px-2 py-1 outline-none w-28" style="background:#fff;border:1px solid rgba(196,152,74,0.25)">
              <button class="text-xs font-semibold px-3 py-1 rounded-lg" style="background:#1B4D4D;color:#F4EFE4">Ubah harga</button>
            </form>
            <form method="POST" action="{{ route('admin.sizes.destroy', $s) }}" onsubmit="return confirm('Hapus ukuran &quot;{{ $s->label }}&quot;?')">@csrf @method('DELETE')
              <button class="text-xs hover:underline" style="color:#EF4444">Hapus</button>
            </form>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
