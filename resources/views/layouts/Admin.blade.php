@extends('layouts.base')
@section('title', trim($__env->yieldContent('heading', 'Admin')) . ' — ' . config('yoghurtq.name'))
@section('body')
@php
  $pendingCount = \App\Models\Order::where('status', 'pending')->count();
  $nav = [
    ['admin.dashboard', '⊞', 'Dashboard'],
    ['admin.stock', '📦', 'Kelola Stok'],
    ['admin.variants', '🧴', 'Varian Produk'],
    ['admin.sizes', '📏', 'Ukuran Botol'],
    ['admin.discounts', '🏷', 'Diskon'],
    ['admin.orders', '🛒', 'Pesanan Masuk'],
  ];
@endphp
<div class="min-h-screen flex" x-data="{ open: false }" style="font-family: var(--font-body); background: linear-gradient(135deg, #F4EFE4 0%, #EDE5D4 100%)">

  @if (session('ok'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2500)" x-show="show" x-transition
         class="fixed top-5 right-5 z-[999] px-5 py-3 rounded-xl text-sm font-semibold shadow-xl" style="background: #1B4D4D; color: #F4EFE4">{{ session('ok') }}</div>
  @endif

  {{-- Sidebar --}}
  <aside class="fixed inset-y-0 left-0 z-40 flex flex-col transition-transform duration-300 lg:relative lg:translate-x-0"
         :class="open ? 'translate-x-0' : '-translate-x-full'" style="width: 240px; flex-shrink: 0; background: #1B4D4D">
    <div class="p-5 flex flex-col" style="border-bottom: 1px solid rgba(196,152,74,0.2)">
      <img src="{{ asset('images/logo.png') }}" alt="Yoghurt Q" class="h-11 w-auto mb-2">
      <div class="text-xs" style="font-family: var(--font-mono); color: rgba(244,239,228,0.35); letter-spacing: 0.1em">ADMIN PORTAL</div>
    </div>
    <nav class="flex-1 p-3 flex flex-col gap-0.5 overflow-y-auto">
      @foreach ($nav as [$route, $icon, $label])
        @php $active = request()->routeIs($route); @endphp
        <a href="{{ route($route) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-left transition-all"
           style="background: {{ $active ? 'rgba(196,152,74,0.18)' : 'transparent' }}; color: {{ $active ? '#D4AE6A' : 'rgba(244,239,228,0.65)' }}; border-left: 3px solid {{ $active ? '#C4984A' : 'transparent' }}">
          <span>{{ $icon }}</span> {{ $label }}
          @if ($route === 'admin.orders' && $pendingCount > 0)
            <span class="ml-auto w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold badge-pop" style="background: #EF4444; color: #fff">{{ $pendingCount }}</span>
          @endif
        </a>
      @endforeach
    </nav>
    <div class="p-3" style="border-top: 1px solid rgba(196,152,74,0.15)">
      <div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-2" style="background: rgba(244,239,228,0.06)">
        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0" style="background: #C4984A; color: #F4EFE4">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div class="min-w-0">
          <div class="text-xs font-semibold truncate" style="color: #F4EFE4">{{ auth()->user()->name }}</div>
          <div class="text-xs truncate" style="color: rgba(244,239,228,0.4)">{{ auth()->user()->email }}</div>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}">@csrf
        <button class="flex items-center gap-2 w-full px-4 py-2 rounded-xl text-xs hover:bg-white/5 transition-all text-left" style="color: rgba(244,239,228,0.45)">⬅ Keluar</button>
      </form>
    </div>
  </aside>
  <div x-show="open" x-cloak class="fixed inset-0 bg-black/40 z-30 lg:hidden" x-on:click="open = false"></div>

  {{-- Main --}}
  <div class="flex-1 flex flex-col min-w-0">
    <header class="sticky top-0 z-20 flex items-center justify-between px-5 lg:px-8 py-4" style="background: rgba(244,239,228,0.9); backdrop-filter: blur(18px); border-bottom: 1px solid rgba(196,152,74,0.15)">
      <div class="flex items-center gap-4">
        <button class="lg:hidden" x-on:click="open = true" style="color: #1B4D4D" aria-label="Buka menu">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h1 class="font-bold text-xl" style="font-family: var(--font-display); color: #1B4D4D">@yield('heading', 'Dashboard')</h1>
      </div>
      <div class="flex items-center gap-2">
        @if ($pendingCount > 0)
          <a href="{{ route('admin.orders', ['status' => 'pending']) }}" class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold animate-pulse" style="background: rgba(239,68,68,0.12); color: #DC2626">🔔 {{ $pendingCount }} pesanan baru</a>
        @endif
        <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium hover:shadow transition-all" style="background: #1B4D4D; color: #F4EFE4">↗ Lihat Toko</a>
      </div>
    </header>
    <main class="flex-1 p-5 overflow-auto">
      @yield('content')
    </main>
  </div>
</div>
@endsection
