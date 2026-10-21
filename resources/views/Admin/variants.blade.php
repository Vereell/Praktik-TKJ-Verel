@extends('layouts.admin')
@section('heading', 'Varian Produk')
@section('content')
<div class="flex flex-col gap-5">
  <form method="POST" action="{{ route('admin.variants.store') }}" enctype="multipart/form-data" class="p-5 rounded-2xl" style="background:#fff;border:1px solid rgba(196,152,74,0.15)">
    @csrf
    <h3 class="font-bold text-base mb-4" style="font-family:var(--font-display);color:#1B4D4D">Tambah Varian Baru</h3>
    <div class="grid sm:grid-cols-2 gap-4">
      <div><label class="block text-xs font-semibold mb-1.5" style="color:#1B4D4D">Nama Varian *</label><input name="name" value="{{ old('name') }}" required class="input-field" placeholder="cth. Passion Fruit"></div>
      <div><label class="block text-xs font-semibold mb-1.5" style="color:#1B4D4D">Emoji</label><input name="emoji" value="{{ old('emoji', '🥛') }}" class="input-field" placeholder="🥭"></div>
      <div class="sm:col-span-2"><label class="block text-xs font-semibold mb-1.5" style="color:#1B4D4D">Deskripsi</label><input name="description" value="{{ old('description') }}" class="input-field" placeholder="Deskripsi rasa..."></div>
      <div><label class="block text-xs font-semibold mb-1.5" style="color:#1B4D4D">URL Gambar</label><input name="img" value="{{ old('img') }}" class="input-field" placeholder="https://..."></div>
      <div><label class="block text-xs font-semibold mb-1.5" style="color:#1B4D4D">…atau unggah gambar</label><input type="file" name="image" accept="image/*" class="text-sm"></div>
    </div>
    @if ($errors->any())<p class="text-xs mt-2" style="color:#EF4444">⚠ {{ $errors->first() }}</p>@endif
    <button class="btn-primary mt-4 text-sm">+ Tambah Varian</button>
  </form>

  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach ($variants as $v)
      @php $t = $v->products->sum('stock'); @endphp
      <div class="rounded-2xl overflow-hidden card-hover" style="background:#fff;border:1px solid rgba(196,152,74,0.15)">
        <div class="h-32 overflow-hidden"><img src="{{ $v->img }}" alt="{{ $v->name }}" class="w-full h-full object-cover"></div>
        <div class="p-4">
          <div class="flex items-center justify-between mb-1">
            <h4 class="font-bold" style="font-family:var(--font-display);color:#1B4D4D">{{ $v->emoji }} {{ $v->name }}</h4>
            <span class="text-xs" style="color:{{ $t < 100 ? '#EF4444' : '#16A34A' }}">{{ $t }} unit</span>
          </div>
          <p class="text-xs mb-3" style="color:#7A7060">{{ $v->description }}</p>
          <form method="POST" action="{{ route('admin.variants.destroy', $v) }}" onsubmit="return confirm('Hapus varian &quot;{{ $v->name }}&quot;?')">@csrf @method('DELETE')
            <button class="text-xs font-medium hover:underline" style="color:#EF4444">🗑 Hapus Varian</button>
          </form>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection
