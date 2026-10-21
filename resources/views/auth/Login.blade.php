@extends('layouts.base')
@section('title', 'Login Admin — ' . config('yoghurtq.name'))
@section('body')
<div class="min-h-screen flex" style="font-family: var(--font-body)">
  {{-- Panel brand --}}
  <div class="hidden lg:flex flex-col justify-between w-[45%] p-12 relative overflow-hidden" style="background: linear-gradient(145deg, #0E2B2B 0%, #1B4D4D 65%, #2A6B6B 100%)">
    <div class="absolute top-0 right-0 w-80 h-80 rounded-full" style="background: radial-gradient(circle, rgba(196,152,74,0.12), transparent); transform: translate(30%,-30%)"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full" style="background: radial-gradient(circle, rgba(244,239,228,0.05), transparent); transform: translate(-35%,35%)"></div>
    <a href="{{ route('home') }}" class="relative"><img src="{{ asset('images/logo.png') }}" alt="Yoghurt Q" class="h-16 w-auto hover:opacity-80 transition-opacity"></a>
    <div class="relative">
      <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs mb-6" style="background: rgba(196,152,74,0.15); border: 1px solid rgba(196,152,74,0.3); color: #D4AE6A">🔐 Portal Manajemen {{ config('yoghurtq.name') }}</div>
      <h1 class="text-5xl font-bold leading-tight mb-5" style="font-family: var(--font-display); color: #F4EFE4">Kelola Usaha<br><span class="gold-shimmer italic">Lebih Mudah</span></h1>
      <p class="text-sm leading-relaxed mb-8" style="color: rgba(244,239,228,0.6); max-width: 360px">Pantau stok secara real-time, atur harga & diskon, terima pesanan masuk, dan lihat statistik penjualan — semua dalam satu dashboard.</p>
      <div class="grid grid-cols-3 gap-3">
        @foreach ([['📦', 'Stok Real-Time', 'Live'], ['📊', 'Statistik', 'Visual'], ['🛒', 'Pesanan', 'Langsung']] as [$icon, $label, $val])
          <div class="p-4 rounded-2xl text-center" style="background: rgba(244,239,228,0.07); border: 1px solid rgba(196,152,74,0.18)">
            <div class="text-xl mb-1.5">{{ $icon }}</div>
            <div class="text-xs font-bold mb-0.5" style="color: #D4AE6A">{{ $val }}</div>
            <div class="text-xs" style="color: rgba(244,239,228,0.4)">{{ $label }}</div>
          </div>
        @endforeach
      </div>
    </div>
    <div class="relative text-xs" style="color: rgba(244,239,228,0.35)">{{ config('yoghurtq.address') }}</div>
  </div>

  {{-- Form --}}
  <div class="flex-1 flex items-center justify-center p-6" style="background: #F4EFE4">
    <div class="w-full max-w-md" x-data="{ show: false }">
      <div class="lg:hidden flex justify-center mb-8"><a href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" alt="Yoghurt Q" class="h-14"></a></div>

      <h2 class="text-2xl font-bold mb-1" style="font-family: var(--font-display); color: #1B4D4D">Selamat datang kembali!</h2>
      <p class="text-xs mb-6" style="color: #7A7060">Masuk sebagai administrator {{ config('yoghurtq.name') }}.</p>

      <form method="POST" action="{{ route('login.attempt') }}" class="flex flex-col gap-4">
        @csrf
        <div>
          <label class="block text-xs font-semibold mb-1.5 tracking-wide" style="color: #1B4D4D">EMAIL</label>
          <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@@domain.com" class="input-field">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1.5 tracking-wide" style="color: #1B4D4D">PASSWORD</label>
          <div class="relative">
            <input :type="show ? 'text' : 'password'" name="password" required placeholder="••••••••" class="input-field pr-10">
            <button type="button" x-on:click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-sm" style="color: #7A7060" x-text="show ? '🙈' : '👁'"></button>
          </div>
        </div>
        <label class="flex items-center gap-2 text-xs" style="color: #7A7060"><input type="checkbox" name="remember"> Ingat saya</label>
        @error('email')
          <div class="px-4 py-3 rounded-xl text-xs flex items-center gap-2" style="background: #FEE2E2; color: #991B1B">⚠️ {{ $message }}</div>
        @enderror
        <button type="submit" class="btn-primary justify-center w-full py-3.5 text-sm mt-1" style="border-radius: 0.875rem">Masuk sebagai Admin</button>
      </form>

      <div class="mt-6 pt-5 text-center" style="border-top: 1px solid rgba(196,152,74,0.18)">
        <a href="{{ route('home') }}" class="text-xs hover:underline" style="color: #7A7060">← Kembali ke halaman utama</a>
      </div>
    </div>
  </div>
</div>
@endsection
