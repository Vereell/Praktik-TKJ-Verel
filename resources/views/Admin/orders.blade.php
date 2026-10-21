@extends('layouts.admin')
@section('heading', 'Pesanan Masuk')
@section('content')
@php
  $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');
  $cur = request('status');
@endphp
<div class="flex flex-col gap-4">
  <div class="flex flex-wrap gap-3 items-center">
    <form method="GET" class="flex-1" style="max-width:300px">
      @if ($cur)<input type="hidden" name="status" value="{{ $cur }}">@endif
      <input name="q" value="{{ request('q') }}" class="input-field" placeholder="🔍 Cari nama / ID pesanan...">
    </form>
    <div class="flex gap-2 flex-wrap">
      <a href="{{ route('admin.orders', array_filter(['q' => request('q')])) }}" class="px-3 py-1.5 rounded-full text-xs font-semibold" style="background:{{ !$cur ? '#1B4D4D' : '#fff' }};color:{{ !$cur ? '#F4EFE4' : '#7A7060' }};border:1px solid rgba(196,152,74,0.2)">Semua</a>
      @foreach (\App\Models\Order::STATUSES as $k => $l)
        <a href="{{ route('admin.orders', array_filter(['status' => $k, 'q' => request('q')])) }}" class="px-3 py-1.5 rounded-full text-xs font-semibold" style="background:{{ $cur === $k ? '#1B4D4D' : '#fff' }};color:{{ $cur === $k ? '#F4EFE4' : '#7A7060' }};border:1px solid rgba(196,152,74,0.2)">{{ $l }}@if ($k === 'pending' && $pending > 0) ({{ $pending }})@endif</a>
      @endforeach
    </div>
  </div>

  <div class="flex flex-col gap-3">
    @forelse ($orders as $o)
      <div class="p-5 rounded-2xl" style="background:#fff;border:2px solid {{ $o->status === 'pending' ? 'rgba(239,68,68,0.3)' : 'rgba(196,152,74,0.15)' }}">
        <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="text-sm font-bold" style="font-family:var(--font-mono);color:#C4984A">#{{ $o->code }}</span>
              <span class="px-2 py-0.5 rounded-full text-xs font-semibold" style="{{ $o->status_style }}">{{ $o->status_label }}</span>
              @if ($o->is_preorder)<span class="px-2 py-0.5 rounded-full text-xs font-semibold" style="background:rgba(139,92,246,0.12);color:#7C3AED">Pre-Order{{ $o->pickup_date ? ' · ambil ' . $o->pickup_date->format('d/m/Y') : '' }}</span>@endif
            </div>
            <div class="font-semibold" style="color:#1B4D4D">{{ $o->customer_name }}</div>
            <div class="text-xs" style="color:#7A7060">📞 {{ $o->phone }} · {{ $o->created_at->format('d M Y H:i') }}</div>
            <div class="text-xs mt-0.5" style="color:#7A7060">📍 {{ $o->address }}</div>
            @if ($o->note)<div class="text-xs mt-0.5 italic" style="color:#B0A890">📝 {{ $o->note }}</div>@endif
          </div>
          <div class="text-right">
            <div class="text-xl font-bold" style="font-family:var(--font-display);color:#1B4D4D">{{ $rp($o->total) }}</div>
            <div class="text-xs mt-0.5" style="color:#7A7060">{{ $o->items->sum('qty') }} item</div>
          </div>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-3 pt-3" style="border-top:1px solid rgba(196,152,74,0.12)">
          <div class="flex flex-wrap gap-2">
            @foreach ($o->items as $i)<span class="px-2 py-1 rounded-lg text-xs" style="background:#F8F4EE;color:#1B4D4D">{{ $i->name }} × {{ $i->qty }}</span>@endforeach
          </div>
          <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('admin.orders.status', $o) }}">@csrf @method('PATCH')
              <select name="status" onchange="this.form.submit()" class="text-xs rounded-xl px-3 py-2 border outline-none font-medium" style="border:1px solid rgba(196,152,74,0.25);color:#1B4D4D;background:#F8F4EE">
                @foreach (\App\Models\Order::STATUSES as $k => $l)<option value="{{ $k }}" @selected($o->status === $k)>{{ $l }}</option>@endforeach
              </select>
            </form>
            <a href="{{ $o->waLink() }}" target="_blank" rel="noreferrer" class="flex items-center gap-1 px-3 py-2 rounded-xl text-xs font-semibold hover:shadow" style="background:#25D366;color:#fff">💬 WA</a>
          </div>
        </div>
      </div>
    @empty
      <div class="text-center py-12" style="color:#7A7060">Tidak ada pesanan ditemukan.</div>
    @endforelse
  </div>
  <div>{{ $orders->links() }}</div>
</div>
@endsection
