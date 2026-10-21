@extends('layouts.base')
@section('title', (config('yoghurtq.name') ?? 'Yoghurt-Q') . ' — Yoghurt Premium, Murni & Halal')
@section('body')
@php
 

  $cfg = config('yoghurtq') ?? [];
  $cfg = array_merge([
    'name' => 'Yoghurt-Q',
    'address' => 'Jl. Sangkuriang Barat 1',
    'city' => 'Cimahi
    'wa_number' => '6285722281849',
    'wa_display' => '+62 857-2228-1849',
    'email' => 'kikin.farrell@gmail.com',
    'hours' => 'Senin – Minggu, 08.00 – 17.00',
    'badge' => 'Bersertifikat Halal',
    'hero_title_1' => 'Yoghurt',
    'hero_title_2' => 'Premium,',
    'hero_title_3' => 'Murni & Halal',
    'hero_text' => 'Dibuat dari susu segar berkualitas langsung dari lembang, difermentasi secara alami tanpa bahan pengawet. Pesan sekarang, diantar langsung ke rumahmu.',
    'about_title_1' => 'Dirintis dengan Cinta,'
    'about_title_2' => 'Tumbuh Bersama Pelanggan',
    'about' => ['Yoghurt-Q lahir dari kegelisahan: yoghurt premium di pasaran seringkali mahal dan tidak jelas kehalalannya. Kami hadir sebagai solusi — yoghurt berkualitas tinggi, halal bersertifikat, dengan harga yang bersahabat.', 'Setiap batch diproduksi dari susu terbaik dan difermentasi dalam kondisi terkontrol. Tanpa shortcut, tanpa kompromi.'],
    'values' => [['🌿', 'Bahan 100% Alami', 'Susu segar berkualitas, tanpa pengawet, tanpa pewarna buatan.'], ['🏅', 'Halal Bersertifikat', 'Aman dikonsumsi seluruh keluarga dengan tenang.'], ['🚚', 'Antar ke Rumah', 'Layanan antar langsung ke lokasi kamu. Fresh, aman, tepat waktu.'], ['📦', 'Pre-Order Tersedia', 'Pesan lebih awal, kami siapkan sesuai jadwal dan kuantitas kebutuhanmu.']],
    'testimonials' => [['Aisyah R.', 'Ibu Rumah Tangga', 'Yoghurt Q jadi langganan keluarga saya. Anak-anak suka banget varian Strawberry-nya, sehat dan enak!'], ['Budi S.', 'Pemilik Kafe', 'Saya pakai Yoghurt Q untuk parfait menu kafe saya. Pelanggan selalu nanya merk yoghurtnya apa, kualitasnya memang beda.'], ['Nadia K.', 'Content Creator', 'Kemasan cantik, rasa autentik. Honey & Mint-nya juara! Sering saya rekomendasi di konten healthy living.']],
  ], $cfg);
  $catalog = $catalog ?? [];
  $stats = $stats ?? ['variants' => 0, 'sizes' => 0, 'orders' => 0];
  $logo = asset('images/logo.png');
  $rawWa = preg_replace('/\D+/', '', (string) ($cfg['wa_number'] ?? '6285722281849'));
  if (str_starts_with($rawWa, '0')) {
    $rawWa = '62' . substr($rawWa, 1);
  }
  $waHello = 'https://wa.me/' . $rawWa . '?text=' . rawurlencode('Halo ' . ($cfg['name'] ?? 'Yoghurt-Q') . '! Saya ingin bertanya tentang produk dan cara pemesanan 🙏');
@endphp



<div x-data="shop(@js($catalog), @js(route('order.store')))" class="min-h-screen bg-cream text-charcoal" style="font-family: var(--font-body)">

  {{-- NAVBAR --}}
  <header class="fixed top-0 inset-x-0 z-50 transition-all duration-300"
          :class="scrolled
            ? 'bg-[#F4EFE4]/95 backdrop-blur-md shadow-md border-b border-[#C4984A]/30 py-3'
            : 'bg-[#0E2B2B]/75 backdrop-blur-md border-b border-[#C4984A]/20 py-4 shadow-sm'">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="#" class="flex items-center gap-2 group">
          <img src="{{ $logo }}" alt="{{ $cfg['name'] ?? 'Yoghurt-Q' }}" class="h-10 sm:h-11 w-auto drop-shadow-md">
          <span class="font-bold text-xl tracking-tight transition-colors"
                :style="scrolled ? 'color:#1B4D4D' : 'color:#F4EFE4'"
                style="font-family: var(--font-display)">
            {{ $cfg['name'] ?? 'Yoghurt-Q' }}
          </span>
        </a>
      </div>

      <nav class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-full transition-all"
           :style="scrolled ? 'background:rgba(27,77,77,0.06)' : 'background:rgba(244,239,228,0.08);border:1px solid rgba(196,152,74,0.2)'">
        @foreach ([['#tentang', 'Tentang'], ['#katalog', 'Katalog'], ['#nilai', 'Keunggulan'], ['#ulasan', 'Ulasan']] as [$href, $label])
          <a href="{{ $href }}"
             class="px-4 py-1.5 rounded-full text-sm font-semibold transition-all hover:scale-105"
             :style="scrolled
               ? 'color:#1B4D4D;hover:background:rgba(27,77,77,0.1)'
               : 'color:#F4EFE4;hover:background:rgba(244,239,228,0.15)'">
            {{ $label }}
          </a>
        @endforeach
      </nav>

      <div class="flex items-center gap-3">
        <button x-on:click="openCart()"
                :aria-label="'Buka keranjang, ' + cartCount + ' item'"
                class="relative flex items-center gap-2.5 px-4 py-2 rounded-full text-sm font-semibold transition-all hover:-translate-y-0.5 shadow-md active:scale-95"
                :style="scrolled
                  ? 'background:#1B4D4D;color:#F4EFE4'
                  : 'background:#C4984A;color:#1B4D4D;box-shadow:0 4px 16px rgba(196,152,74,0.35)'">
          <svg aria-hidden="true" class="size-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l1.6 9.1a2 2 0 0 0 2 1.7h7.8a2 2 0 0 0 2-1.6L20 6H6M9 19h.01M17 19h.01"/></svg>
          <span class="hidden sm:inline font-bold">Keranjang</span>
          <span x-show="cartCount > 0" x-text="cartCount"
                class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-black badge-pop shadow-sm"
                :style="scrolled ? 'background:#C4984A;color:#1B4D4D' : 'background:#1B4D4D;color:#F4EFE4'"></span>
        </button>

        @auth
          <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex text-xs font-semibold px-3.5 py-1.5 rounded-full bg-gold text-white font-bold hover:opacity-90 transition-opacity">
            Dashboard
          </a>
          <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="hidden sm:inline-flex text-xs opacity-60 hover:opacity-100 transition-opacity" :style="scrolled ? 'color:#1B4D4D' : 'color:#F4EFE4'">
              Keluar
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="hidden lg:inline-flex text-xs font-semibold px-3 py-1.5 rounded-full transition-all border"
             :style="scrolled ? 'color:#1B4D4D;border-color:rgba(27,77,77,0.25)' : 'color:#D4AE6A;border-color:rgba(212,174,106,0.35)'">
            Admin Portal
          </a>
        @endauth

        <button class="md:hidden size-10 rounded-full flex items-center justify-center transition-colors"
                :aria-label="menuOpen ? 'Tutup menu' : 'Buka menu'"
                x-on:click="menuOpen = !menuOpen"
                :style="scrolled ? 'color:#1B4D4D;background:rgba(27,77,77,0.08)' : 'color:#F4EFE4;background:rgba(244,239,228,0.1)'">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="menuOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'"/></svg>
        </button>
      </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="menuOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="md:hidden px-5 py-4 flex flex-col gap-2.5 shadow-xl"
         :style="scrolled ? 'background:#F4EFE4;border-top:1px solid rgba(196,152,74,0.25)' : 'background:#103434;border-top:1px solid rgba(196,152,74,0.3)'">
      @foreach ([['#tentang', 'Tentang'], ['#katalog', 'Katalog'], ['#nilai', 'Keunggulan'], ['#ulasan', 'Ulasan']] as [$href, $label])
        <a href="{{ $href }}"
           class="py-2.5 px-3 rounded-xl text-sm font-semibold transition-colors"
           :style="scrolled ? 'color:#1B4D4D;background:rgba(27,77,77,0.05)' : 'color:#F4EFE4;background:rgba(244,239,228,0.06)'"
           x-on:click="menuOpen = false">{{ $label }}</a>
      @endforeach

      @auth
        <div class="py-2.5 px-3 rounded-xl flex items-center justify-between"
             :style="scrolled ? 'background:rgba(27,77,77,0.05)' : 'background:rgba(244,239,228,0.06)'">
          <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold" :style="scrolled ? 'color:#1B4D4D' : 'color:#F4EFE4'">
            Dashboard Admin
          </a>
          <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-xs font-semibold text-red-500">Keluar</button>
          </form>
        </div>
      @else
        <a href="{{ route('login') }}" class="py-2.5 px-3 rounded-xl text-sm font-bold flex items-center justify-between"
           style="background:rgba(196,152,74,0.15);color:#D4AE6A">
          <span>Admin Portal</span> <span>→</span>
        </a>
      @endauth
    </div>
  </header>

  {{-- HERO --}}
  <section class="relative min-h-[760px] flex items-center overflow-hidden">
    <div class="absolute inset-0" style="background: linear-gradient(135deg, #0E2B2B 0%, #1B4D4D 55%, #2A6B6B 100%)"></div>
    <div class="absolute top-20 right-0 w-[500px] h-[500px] rounded-full opacity-[0.07]" style="background: radial-gradient(circle, #C4984A, transparent); transform: translate(25%,-10%)"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full opacity-[0.04]" style="background: radial-gradient(circle, #F4EFE4, transparent); transform: translate(-30%,30%)"></div>
    <div class="absolute inset-0 opacity-[0.04]" style="background-image: repeating-linear-gradient(0deg,transparent,transparent 48px,rgba(196,152,74,1) 48px,rgba(196,152,74,1) 49px),repeating-linear-gradient(90deg,transparent,transparent 48px,rgba(196,152,74,1) 48px,rgba(196,152,74,1) 49px)"></div>

    <div class="relative max-w-7xl mx-auto px-5 sm:px-8 pt-32 pb-24 grid md:grid-cols-[1.08fr_0.92fr] gap-14 lg:gap-20 items-center w-full">
      <div class="animate-fade-up">
        <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full text-xs font-semibold mb-7" style="background: rgba(196,152,74,0.12); border: 1px solid rgba(196,152,74,0.35); color: #D4AE6A">
          <span class="size-1.5 rounded-full bg-gold-light"></span> {{ $cfg['badge'] }}
        </div>
        <h1 class="text-5xl md:text-7xl font-bold leading-none mb-5" style="font-family: var(--font-display); color: #F4EFE4">
          {{ $cfg['hero_title_1'] }}<br>
          <span class="gold-shimmer italic">{{ $cfg['hero_title_2'] }}</span><br>
          <span style="color: rgba(244,239,228,0.75); font-size: 0.75em">{{ $cfg['hero_title_3'] }}</span>
        </h1>
        <p class="text-base md:text-lg mb-9 leading-relaxed" style="color: rgba(244,239,228,0.68); max-width: 500px">{{ $cfg['hero_text'] }}</p>
        <div class="flex flex-wrap gap-3 mb-12">
          <a href="#katalog" class="btn-gold text-sm">Lihat Katalog <span aria-hidden="true">→</span></a>
          <button x-on:click="showPO = true; lockBody()" class="btn-outline text-sm">Buat Pre-Order</button>
        </div>
        <div class="grid grid-cols-3 max-w-lg">
          @foreach ([['Varian Rasa', $stats['variants'], '+'], ['Ukuran Botol', $stats['sizes'], ''], ['Pesanan Diproses', $stats['orders'], '']] as [$label, $val, $suf])
            <div class="pr-5 mr-5 border-r border-cream/10 last:border-r-0 last:mr-0 last:pr-0">
              <div class="text-2xl font-bold" style="font-family: var(--font-display); color: #D4AE6A"><span data-count="{{ $val }}">0</span>{{ $suf }}</div>
              <div class="text-xs mt-1" style="color: rgba(244,239,228,0.4); letter-spacing: 0.05em">{{ $label }}</div>
            </div>
          @endforeach
        </div>
      </div>
      <div class="hidden md:flex justify-center items-center">
        <div class="relative w-full max-w-md animate-float">
          <div class="aspect-[4/5] rounded-[2.5rem] p-8 flex flex-col justify-between overflow-hidden" style="background: linear-gradient(145deg, rgba(244,239,228,0.12), rgba(244,239,228,0.04)); border: 1px solid rgba(196,152,74,0.28); box-shadow: 0 32px 80px rgba(5,20,20,0.28); backdrop-filter: blur(12px)">
            <div class="flex items-center justify-between text-[11px] font-semibold tracking-[0.16em] uppercase" style="color: #D4AE6A"><span>Small batch</span><span>Fresh daily</span></div>
            <img src="{{ $logo }}" alt="{{ $cfg['name'] }}" class="w-full max-h-64 object-contain drop-shadow-2xl">
            <div class="grid grid-cols-3 gap-2">
              @foreach (['Alami', 'Halal', 'Segar'] as $item)
                <div class="py-2.5 rounded-xl text-center text-xs font-medium" style="background: rgba(244,239,228,0.08); color: rgba(244,239,228,0.76)">{{ $item }}</div>
              @endforeach
            </div>
          </div>
          <div class="absolute -right-5 top-12 size-20 rounded-full flex items-center justify-center text-center shadow-xl" style="background: #C4984A; color: #F4EFE4">
            <span class="text-[11px] font-bold leading-tight tracking-wide">100%<br>HALAL</span>
          </div>
        </div>
      </div>
    </div>
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
      <svg class="w-5 h-5 opacity-30" fill="none" stroke="#F4EFE4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
  </section>

  {{-- ABOUT --}}
  <section id="tentang" class="py-24 lg:py-28 px-5 sm:px-8" style="background: #EDE5D4">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center">
      <div class="reveal">
        <div class="relative">
          <div class="aspect-square rounded-3xl overflow-hidden" style="border: 2px solid rgba(196,152,74,0.25)">
            <img src="https://images.unsplash.com/photo-1607706189992-eae578626c86?w=800&h=800&fit=crop&auto=format" alt="Produksi {{ $cfg['name'] }}" class="w-full h-full object-cover">
          </div>
          <div class="absolute -bottom-5 -right-5 p-5 rounded-2xl shadow-xl" style="background: #1B4D4D">
            <div class="text-2xl font-bold" style="font-family: var(--font-display); color: #D4AE6A">{{ $cfg['city'] }}</div>
            <div class="text-xs mt-1" style="color: rgba(244,239,228,0.6)">{{ $cfg['address'] }}</div>
          </div>
        </div>
      </div>
      <div class="reveal" style="transition-delay: 150ms">
        <p class="section-kicker mb-4">Cerita Kami</p>
        <h2 class="text-4xl md:text-5xl font-bold mb-5 leading-tight" style="font-family: var(--font-display); color: #1B4D4D">{{ $cfg['about_title_1'] }}<br><em>{{ $cfg['about_title_2'] }}</em></h2>
        @foreach ($cfg['about'] as $i => $para)
          <p class="text-sm leading-relaxed {{ $loop->last ? 'mb-7' : 'mb-3' }}" style="color: #7A7060">{{ $para }}</p>
        @endforeach
        <div class="grid grid-cols-3 gap-4">
          @foreach ([['Varian Rasa', $stats['variants'], '+'], ['Ukuran Botol', $stats['sizes'], ''], ['Pesanan Diproses', $stats['orders'], '']] as [$label, $val, $suf])
            <div class="text-center p-4 rounded-2xl" style="background: rgba(27,77,77,0.07)">
              <div class="text-2xl font-bold" style="font-family: var(--font-display); color: #1B4D4D"><span data-count="{{ $val }}">0</span>{{ $suf }}</div>
              <div class="text-xs mt-1" style="color: #7A7060">{{ $label }}</div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  {{-- CATALOG --}}
  <section id="katalog" class="py-24 lg:py-28 px-5 sm:px-8" style="background: #F4EFE4">
    <div class="max-w-7xl mx-auto">
      <div class="reveal text-center mb-14">
        <p class="section-kicker mb-3">Katalog Produk</p>
        <h2 class="text-4xl md:text-5xl font-bold" style="font-family: var(--font-display); color: #1B4D4D">Pilih Favoritmu</h2>
        <p class="mt-3 text-sm" style="color: #7A7060; max-width: 480px; margin: 12px auto 0">Semua varian tersedia dalam berbagai ukuran botol. Klik varian, pilih ukuran, dan tambah ke keranjang.</p>
      </div>

      <template x-if="variants.length === 0"><p class="text-center text-sm" style="color:#7A7060">Belum ada produk.</p></template>

      {{-- Variant selector --}}
      <div class="flex overflow-x-auto md:flex-wrap gap-3 mb-10 md:justify-center pb-2">
        <template x-for="v in variants" :key="v.id">
          <button x-on:click="activeVariant = v.id" class="flex shrink-0 items-center gap-2 px-4 py-2.5 rounded-full text-sm font-semibold transition-all"
                  :style="activeVariant === v.id
                    ? 'background:#1B4D4D;color:#F4EFE4;border:2px solid #1B4D4D;transform:scale(1.05);box-shadow:0 4px 16px rgba(27,77,77,0.2)'
                    : 'background:#fff;color:#1B4D4D;border:2px solid rgba(196,152,74,0.2)'">
            <span x-text="v.emoji"></span> <span x-text="v.name"></span>
          </button>
        </template>
      </div>

      {{-- Detail card --}}
      <template x-if="selectedVariant">
        <div class="surface-card grid md:grid-cols-2 gap-8 lg:gap-12 mb-16 animate-scale-in rounded-3xl p-4 sm:p-6 lg:p-8">
          <div class="aspect-square rounded-2xl overflow-hidden" style="max-height: 420px">
            <img :key="selectedVariant.id" :src="selectedVariant.img" :alt="selectedVariant.name" class="w-full h-full object-cover animate-scale-in">
          </div>
          <div class="flex flex-col justify-center">
            <div class="inline-flex w-fit px-3 py-1.5 rounded-full text-xs font-semibold mb-4" style="background: rgba(196,152,74,0.1); color: #A07A2E"><span x-text="selectedVariant.emoji"></span>&nbsp;Varian pilihan</div>
            <h3 class="text-3xl font-bold mb-2" style="font-family: var(--font-display); color: #1B4D4D" x-text="selectedVariant.name"></h3>
            <p class="text-sm leading-relaxed mb-6" style="color: #7A7060" x-text="selectedVariant.description"></p>

            <p class="text-xs font-semibold mb-3 tracking-widest uppercase" style="color: #C4984A">Pilih Ukuran</p>
            <div class="flex flex-wrap gap-2 mb-6">
              <template x-for="s in sizes" :key="s.id">
                <button x-on:click="inStock(activeVariant, s.id) && (activeSize = s.id)" :disabled="!inStock(activeVariant, s.id)"
                        class="flex flex-col items-center px-4 py-2.5 rounded-xl text-sm transition-all"
                        :style="'background:' + (activeSize === s.id ? '#1B4D4D' : inStock(activeVariant, s.id) ? '#fff' : '#f0f0f0')
                          + ';color:' + (activeSize === s.id ? '#F4EFE4' : inStock(activeVariant, s.id) ? '#1B4D4D' : '#999')
                          + ';border:2px solid ' + (activeSize === s.id ? '#1B4D4D' : 'rgba(196,152,74,0.2)')
                          + ';opacity:' + (inStock(activeVariant, s.id) ? 1 : 0.5)
                          + ';cursor:' + (inStock(activeVariant, s.id) ? 'pointer' : 'not-allowed')">
                  <span class="font-semibold" x-text="s.label"></span>
                  <span class="text-xs mt-0.5" :style="'color:' + (activeSize === s.id ? 'rgba(244,239,228,0.7)' : '#C4984A')" x-text="fmt(product(activeVariant, s.id)?.price ?? 0)"></span>
                  <span x-show="!inStock(activeVariant, s.id)" class="text-xs mt-0.5" style="color:#DC2626">Habis</span>
                  <span x-show="product(activeVariant, s.id)?.discount > 0" class="text-xs mt-0.5 font-bold" :style="'color:' + (activeSize === s.id ? '#D4AE6A' : '#C4984A')" x-text="'-' + product(activeVariant, s.id)?.discount + '%'"></span>
                </button>
              </template>
            </div>

            <div class="flex items-center justify-between mb-5 p-4 rounded-2xl" style="background: rgba(196,152,74,0.08); border: 1px solid rgba(196,152,74,0.2)">
              <div>
                <div class="text-xs" style="color: #7A7060">Harga</div>
                <div class="text-2xl font-bold" style="font-family: var(--font-display); color: #1B4D4D" x-text="fmt(selected?.price ?? 0)"></div>
              </div>
              <div class="text-right">
                <div class="text-xs" style="color: #7A7060">Stok Tersedia</div>
                <div class="font-bold" :style="'color:' + ((selected?.stock ?? 0) > 20 ? '#16A34A' : (selected?.stock ?? 0) > 0 ? '#D97706' : '#DC2626')" x-text="(selected?.stock ?? 0) + ' pcs'"></div>
              </div>
            </div>

            <div class="flex gap-3">
              <button x-on:click="selected && selected.stock > 0 && add(selected.id)" :disabled="!selected || selected.stock === 0"
                      class="flex-1 py-3.5 rounded-2xl font-semibold text-sm transition-all flex items-center justify-center gap-2"
                      :style="'background:' + (addedId === selected?.id ? '#16A34A' : '#1B4D4D') + ';color:#F4EFE4;opacity:' + ((!selected || selected.stock === 0) ? 0.5 : 1)"
                      x-text="addedId === selected?.id ? '✓ Ditambahkan' : 'Tambah ke Keranjang'"></button>
              <a href="{{ $waHello }}" target="_blank" rel="noreferrer" class="flex items-center gap-2 px-5 py-3.5 rounded-2xl font-semibold text-sm transition-all hover:shadow-lg" style="background:#25D366;color:#fff">Tanya via WA</a>
            </div>
          </div>
        </div>
      </template>

      {{-- All variants --}}
      <div class="reveal text-center mb-8"><h3 class="text-2xl font-bold" style="font-family: var(--font-display); color: #1B4D4D">Semua Varian</h3></div>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <template x-for="v in variants" :key="v.id">
          <div class="surface-card card-hover rounded-2xl overflow-hidden cursor-pointer animate-fade-up" x-on:click="activeVariant = v.id">
            <div class="relative h-48 overflow-hidden">
              <img :src="v.img" :alt="v.name" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
              <div x-show="defaultProduct(v.id)?.discount > 0" class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-bold" style="background:#C4984A;color:#F4EFE4" x-text="'DISC ' + defaultProduct(v.id)?.discount + '%'"></div>
            </div>
            <div class="p-5">
              <div class="flex justify-between items-start mb-2">
                <h4 class="font-bold text-lg" style="font-family: var(--font-display); color:#1B4D4D"><span x-text="v.emoji"></span> <span x-text="v.name"></span></h4>
                <span class="text-sm font-semibold" style="color:#C4984A" x-text="fmt(defaultProduct(v.id)?.price ?? 0)"></span>
              </div>
              <p class="text-xs leading-relaxed mb-4" style="color:#7A7060" x-text="v.description"></p>
              <div class="flex gap-2">
                <button x-on:click.stop="defaultProduct(v.id) && add(defaultProduct(v.id).id)" class="flex-1 py-2 rounded-xl text-xs font-semibold transition-all hover:shadow" style="background:#1B4D4D;color:#F4EFE4">Tambah</button>
                <button x-on:click.stop="activeVariant = v.id; document.getElementById('katalog').scrollIntoView({ behavior: 'smooth' })" class="px-3 py-2 rounded-xl text-xs font-semibold transition-all border" style="border:1px solid rgba(27,77,77,0.2);color:#1B4D4D">Pilih Ukuran</button>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
  </section>

  {{-- VALUES --}}
  <section id="nilai" class="py-24 lg:py-28 px-5 sm:px-8" style="background: linear-gradient(135deg, #1B4D4D 0%, #133838 100%)">
    <div class="max-w-7xl mx-auto">
      <div class="reveal text-center mb-14">
        <p class="text-xs font-semibold mb-3 tracking-widest uppercase" style="color:#D4AE6A">Keunggulan Kami</p>
        <h2 class="text-4xl md:text-5xl font-bold" style="font-family: var(--font-display); color:#F4EFE4">Kenapa {{ $cfg['name'] }}?</h2>
      </div>
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach ($cfg['values'] as $i => [$icon, $title, $desc])
          <div class="reveal p-6 rounded-2xl card-hover" style="background: rgba(244,239,228,0.06); border: 1px solid rgba(196,152,74,0.2); transition-delay: {{ $i * 80 }}ms">
            <div class="text-3xl mb-4">{{ $icon }}</div>
            <h3 class="font-bold text-base mb-2" style="font-family: var(--font-display); color:#F4EFE4">{{ $title }}</h3>
            <p class="text-sm leading-relaxed" style="color: rgba(244,239,228,0.6)">{{ $desc }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- TESTIMONIALS --}}
  <section id="ulasan" class="py-24 lg:py-28 px-5 sm:px-8" style="background: #EDE5D4">
    <div class="max-w-4xl mx-auto text-center">
      <div class="reveal">
        <p class="text-xs font-semibold mb-3 tracking-widest uppercase" style="color:#C4984A">Apa Kata Mereka</p>
        <h2 class="text-4xl font-bold mb-12" style="font-family: var(--font-display); color:#1B4D4D">Testimoni Pelanggan</h2>
      </div>
      <div class="relative" style="min-height: 240px">
        @foreach ($cfg['testimonials'] as $i => [$name, $role, $quote])
          <div class="absolute inset-0 transition-all duration-700" :style="'opacity:' + (testIdx === {{ $i }} ? 1 : 0) + ';transform:translateY(' + (testIdx === {{ $i }} ? 0 : 12) + 'px);pointer-events:' + (testIdx === {{ $i }} ? 'all' : 'none')">
            <div class="surface-card p-8 sm:p-10 rounded-3xl mx-auto max-w-xl">
              <div class="flex justify-center mb-4">@for ($j = 0; $j < 5; $j++)<span style="color:#C4984A;font-size:1.1rem">★</span>@endfor</div>
              <p class="text-base italic leading-relaxed mb-5" style="color:#7A7060">"{{ $quote }}"</p>
              <div class="font-semibold" style="color:#1B4D4D">{{ $name }}</div>
              <div class="text-xs mt-0.5" style="color:#C4984A">{{ $role }}</div>
            </div>
          </div>
        @endforeach
      </div>
      <div class="flex justify-center gap-2 mt-8">
        @foreach ($cfg['testimonials'] as $i => $t)
          <button x-on:click="testIdx = {{ $i }}" aria-label="Testimoni {{ $i + 1 }}" class="rounded-full transition-all" :style="'width:' + (testIdx === {{ $i }} ? 24 : 8) + 'px;height:8px;background:' + (testIdx === {{ $i }} ? '#C4984A' : 'rgba(196,152,74,0.3)')"></button>
        @endforeach
      </div>
    </div>
  </section>

  {{-- PRE-ORDER CTA --}}
  <section class="py-20 px-5" style="background:#F4EFE4">
    <div class="reveal max-w-3xl mx-auto text-center">
      <div class="p-12 rounded-3xl" style="background: linear-gradient(135deg, #1B4D4D, #2A6B6B); position: relative; overflow: hidden">
        <div class="absolute top-0 right-0 w-48 h-48 rounded-full opacity-10" style="background: radial-gradient(circle, #C4984A, transparent); transform: translate(30%, -30%)"></div>
        <p class="text-xs font-semibold mb-3 tracking-widest uppercase" style="color:#D4AE6A">Pesan Dalam Jumlah Besar?</p>
        <h2 class="text-3xl md:text-4xl font-bold mb-4" style="font-family: var(--font-display); color:#F4EFE4">Pre-Order Sekarang</h2>
        <p class="text-sm mb-7" style="color: rgba(244,239,228,0.7); max-width: 420px; margin: 0 auto 1.75rem">Cocok untuk acara, kafe, atau kebutuhan bulanan. Pesan lebih awal dan kami pastikan stok tersedia.</p>
        <div class="flex flex-wrap justify-center gap-3">
          <button x-on:click="showPO = true; lockBody()" class="btn-gold text-sm">Isi Form Pre-Order</button>
          <a href="{{ $waHello }}" target="_blank" rel="noreferrer" class="btn-outline text-sm">Chat WhatsApp</a>
        </div>
      </div>
    </div>
  </section>

  {{-- CONTACT --}}
  <section class="py-16 px-5" style="background:#EDE5D4">
    <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-8">
      @foreach ([
        ['📍', 'Lokasi', $cfg['address'] . "\n" . $cfg['city']],
        ['📞', 'WhatsApp', $cfg['wa_display'] . "\n" . $cfg['hours']],
        ['📧', 'Email', $cfg['email'] . "\nBalas dalam 1×24 jam"],
      ] as [$icon, $label, $val])
        <div class="reveal flex items-start gap-4 p-5 rounded-2xl" style="background:#fff;border:1px solid rgba(196,152,74,0.15)">
          <div class="text-2xl">{{ $icon }}</div>
          <div>
            <div class="font-semibold text-sm mb-1" style="color:#1B4D4D">{{ $label }}</div>
            <div class="text-xs leading-relaxed" style="color:#7A7060;white-space:pre-line">{{ $val }}</div>
          </div>
        </div>
      @endforeach
    </div>
  </section>

  {{-- FOOTER --}}
  <footer class="py-8 px-5" style="background:#1B4D4D">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
      <img src="{{ $logo }}" alt="{{ $cfg['name'] }}" class="h-10">
      <p class="text-xs" style="color: rgba(244,239,228,0.4)">© {{ date('Y') }} {{ $cfg['name'] }}. Halal · Natural · Premium</p>
      <a href="{{ route('login') }}" class="text-xs hover:underline" style="color: rgba(244,239,228,0.3)">Admin Portal</a>
    </div>
  </footer>

  {{-- FLOATING WA --}}
  <a href="{{ $waHello }}" target="_blank" rel="noreferrer" aria-label="Chat WhatsApp"
     class="fixed bottom-6 right-6 z-40 w-14 h-14 rounded-full flex items-center justify-center shadow-2xl text-2xl wa-pulse" style="background:#25D366;transition:transform .2s">💬</a>

  {{-- CART DRAWER --}}
  <template x-if="cartOpen">
    <div>
      <div class="drawer-overlay" x-on:click="closeCart()"></div>
      <div class="drawer-panel shadow-2xl">
        <div class="flex items-center justify-between px-6 py-5" style="border-bottom:1px solid rgba(196,152,74,0.18);background:#1B4D4D">
          <div class="flex items-center gap-3">
            <button x-show="step === 'checkout' && !done" x-on:click="step = 'cart'" class="mr-1 opacity-70 hover:opacity-100 transition-opacity" style="color:#F4EFE4">←</button>
            <h2 class="font-bold text-lg" style="font-family: var(--font-display);color:#F4EFE4" x-text="done ? '✅ Pesanan Diterima' : step === 'cart' ? 'Keranjang (' + cart.length + ')' : 'Detail Pengiriman'"></h2>
          </div>
          <button x-on:click="closeCart()" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-white/10 transition-all" style="color:#F4EFE4;font-size:1.25rem" aria-label="Tutup">×</button>
        </div>

        {{-- Done --}}
        <div x-show="done" class="flex-1 flex flex-col items-center justify-center p-8 text-center animate-scale-in">
          <div class="text-6xl mb-4">🎉</div>
          <h3 class="text-2xl font-bold mb-2" style="font-family: var(--font-display);color:#1B4D4D">Pesanan Masuk!</h3>
          <p class="text-sm mb-1" style="color:#7A7060">Kode pesanan: <strong x-text="orderCode"></strong></p>
          <p class="text-sm mb-6" style="color:#7A7060">Lanjutkan ke WhatsApp untuk menanyakan ketersediaan stok. Admin akan mengecek dan mengonfirmasi pesanan kamu.</p>
          <a :href="waUrl" target="_blank" rel="noreferrer" class="btn-primary" x-on:click="closeCart()">💬 Konfirmasi via WhatsApp</a>
          <button class="mt-3 text-xs underline" style="color:#7A7060" x-on:click="closeCart()">Tutup</button>
        </div>

        {{-- Cart step --}}
        <template x-if="!done && step === 'cart'">
          <div class="flex-1 flex flex-col min-h-0">
            <div class="flex-1 overflow-y-auto px-6 py-4 flex flex-col gap-3">
              <template x-if="enriched.length === 0">
                <div class="flex-1 flex flex-col items-center justify-center py-16 text-center">
                  <div class="text-5xl mb-3">🛒</div>
                  <p class="font-semibold" style="color:#1B4D4D">Keranjang kosong</p>
                  <p class="text-sm mt-1" style="color:#7A7060">Yuk pilih produk favoritmu!</p>
                  <button class="btn-primary mt-5 text-sm" x-on:click="closeCart()">Lihat Katalog</button>
                </div>
              </template>
              <template x-for="c in enriched" :key="c.product.id">
                <div class="surface-card flex gap-3 p-3.5 rounded-2xl">
                  <div class="w-14 h-14 rounded-lg overflow-hidden flex-shrink-0 bg-cream-dark"><img :src="c.variant.img" :alt="c.variant.name" class="w-full h-full object-cover"></div>
                  <div class="flex-1 min-w-0">
                    <div class="font-semibold text-sm truncate" style="color:#1B4D4D" x-text="c.variant.name"></div>
                    <div class="text-xs" style="color:#7A7060" x-text="c.size.label + ' · ' + fmt(c.product.price)"></div>
                    <div class="flex items-center gap-2 mt-2">
                      <button x-on:click="changeQty(c.product.id, -1)" class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-sm" style="border:1px solid rgba(27,77,77,0.2);color:#1B4D4D" aria-label="Kurangi">−</button>
                      <span class="text-sm font-semibold w-6 text-center" style="color:#1B4D4D" x-text="c.qty"></span>
                      <button x-on:click="changeQty(c.product.id, 1)" class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-sm" style="border:1px solid rgba(27,77,77,0.2);color:#1B4D4D" aria-label="Tambah">+</button>
                    </div>
                  </div>
                  <div class="flex flex-col items-end justify-between">
                    <button x-on:click="remove(c.product.id)" class="text-xs opacity-40 hover:opacity-80 transition-opacity" style="color:#DC2626" aria-label="Hapus">🗑</button>
                    <span class="text-sm font-bold" style="color:#C4984A" x-text="fmt(c.subtotal)"></span>
                  </div>
                </div>
              </template>
            </div>
            <div x-show="enriched.length > 0" class="px-6 py-5" style="border-top:1px solid rgba(196,152,74,0.15)">
              <div class="flex justify-between text-sm mb-1" style="color:#7A7060"><span x-text="cartCount + ' item'"></span><span>Subtotal</span></div>
              <div class="flex justify-between font-bold text-xl mb-4" style="font-family: var(--font-display);color:#1B4D4D"><span>Total</span><span style="color:#C4984A" x-text="fmt(total)"></span></div>
              <button class="btn-primary w-full justify-center text-sm" x-on:click="step = 'checkout'">Lanjut ke Checkout →</button>
              <button class="mt-2 text-xs w-full text-center py-2 hover:underline" style="color:#7A7060" x-on:click="clear()">Hapus semua</button>
            </div>
          </div>
        </template>

        {{-- Checkout step --}}
        <template x-if="!done && step === 'checkout'">
          <form x-on:submit.prevent="checkout()" class="flex-1 flex flex-col min-h-0">
            <div class="flex-1 overflow-y-auto px-6 py-4 flex flex-col gap-4">
              <div class="p-4 rounded-2xl text-sm" style="background:rgba(27,77,77,0.06);border:1px solid rgba(27,77,77,0.1)">
                <div class="font-semibold mb-2" style="color:#1B4D4D">Ringkasan Pesanan</div>
                <template x-for="c in enriched" :key="c.product.id">
                  <div class="flex justify-between py-0.5 text-xs" style="color:#7A7060"><span x-text="c.variant.name + ' ' + c.size.label + ' × ' + c.qty"></span><span x-text="fmt(c.subtotal)"></span></div>
                </template>
                <div class="flex justify-between font-bold mt-2 pt-2 text-sm" style="border-top:1px solid rgba(196,152,74,0.2);color:#1B4D4D"><span>Total</span><span style="color:#C4984A" x-text="fmt(total)"></span></div>
              </div>
              <div><label class="block text-xs font-semibold mb-1.5 tracking-wide" style="color:#1B4D4D">Nama Lengkap</label><input type="text" required placeholder="Aisyah Ramadhani" x-model="form.name" class="input-field"></div>
              <div><label class="block text-xs font-semibold mb-1.5 tracking-wide" style="color:#1B4D4D">No. WhatsApp</label><input type="tel" required placeholder="08xxxxxxxxxx" x-model="form.phone" class="input-field"></div>
              <div><label class="block text-xs font-semibold mb-1.5 tracking-wide" style="color:#1B4D4D">Alamat Pengiriman</label><textarea required rows="2" placeholder="Jl. Contoh No.1, Kota" x-model="form.address" class="input-field resize-none"></textarea></div>
              <div><label class="block text-xs font-semibold mb-1.5 tracking-wide" style="color:#7A7060">Catatan (opsional)</label><textarea rows="2" placeholder="Misal: titip satpam, bubble wrap, dll." x-model="form.note" class="input-field resize-none"></textarea></div>
              <p x-show="error" x-text="error" class="text-xs" style="color:#DC2626"></p>
            </div>
            <div class="px-6 py-5" style="border-top:1px solid rgba(196,152,74,0.15)">
              <button type="submit" :disabled="loading" class="btn-primary w-full justify-center" :style="loading ? 'opacity:.7' : ''" x-text="loading ? 'Memproses…' : '📦 Konfirmasi Pesanan'"></button>
              <p class="text-xs text-center mt-2" style="color:#7A7060">Setelah pesanan masuk, kamu diarahkan ke WhatsApp admin untuk cek stok.</p>
            </div>
          </form>
        </template>
      </div>
    </div>
  </template>

  {{-- PRE-ORDER MODAL --}}
  <template x-if="showPO">
    <div class="drawer-overlay flex items-center justify-center p-4" style="z-index:300" x-on:click="closePO()">
      <div class="w-full max-w-lg rounded-3xl overflow-hidden animate-scale-in" x-on:click.stop style="background:#F4EFE4;max-height:90vh;display:flex;flex-direction:column;box-shadow:0 32px 90px rgba(19,56,56,0.28)">
        <div class="px-6 py-5 flex items-center justify-between" style="background:#1B4D4D">
          <div>
            <h2 class="font-bold text-xl" style="font-family: var(--font-display);color:#F4EFE4">Pre-Order</h2>
            <p class="text-xs mt-0.5" style="color:rgba(244,239,228,0.6)">Pesan sekarang, terima sesuai jadwal</p>
          </div>
          <button x-on:click="closePO()" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-white/10 text-xl" style="color:#F4EFE4" aria-label="Tutup">×</button>
        </div>

        <div x-show="poDone" class="flex-1 flex flex-col items-center justify-center p-8 text-center">
          <div class="text-5xl mb-4">✅</div>
          <h3 class="text-xl font-bold mb-2" style="font-family: var(--font-display);color:#1B4D4D">Pre-Order Masuk!</h3>
          <p class="text-sm mb-1" style="color:#7A7060">Kode pesanan: <strong x-text="orderCode"></strong></p>
          <p class="text-sm mb-6" style="color:#7A7060">Lanjutkan ke WhatsApp agar admin bisa mengecek stok dan mengonfirmasi jadwalnya.</p>
          <a :href="waUrl" target="_blank" rel="noreferrer" class="btn-gold" x-on:click="closePO()">💬 Chat via WhatsApp</a>
          <button class="mt-3 text-xs underline" style="color:#7A7060" x-on:click="closePO()">Tutup</button>
        </div>

        <form x-show="!poDone" x-on:submit.prevent="submitPO()" class="flex-1 overflow-y-auto px-6 py-5 flex flex-col gap-4">
          <div class="flex items-center justify-between pb-2" style="border-bottom:1px solid rgba(196,152,74,0.18)">
            <div>
              <span class="text-xs font-bold uppercase tracking-wider" style="color:#1B4D4D">Daftar Produk Pre-Order</span>
              <p class="text-[11px]" style="color:#7A7060">Bisa pesan lebih dari satu varian dan ukuran</p>
            </div>
            <button type="button" x-on:click="addPoItem()" class="px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1 transition-all" style="background:rgba(27,77,77,0.1);color:#1B4D4D;border:1px solid rgba(27,77,77,0.2)">
              <span>+</span> Tambah Varian
            </button>
          </div>

          <div class="flex flex-col gap-3">
            <template x-for="(item, idx) in po.items" :key="idx">
              <div class="p-3.5 rounded-2xl relative" style="background:#fff;border:1px solid rgba(196,152,74,0.22);box-shadow:0 2px 8px rgba(27,77,77,0.04)">
                <div class="flex items-center justify-between mb-2.5">
                  <span class="text-xs font-bold" style="color:#1B4D4D" x-text="'Item #' + (idx + 1)"></span>
                  <button type="button" x-show="po.items.length > 1" x-on:click="removePoItem(idx)" class="text-xs font-medium px-2 py-0.5 rounded-md hover:bg-red-50 transition-colors" style="color:#DC2626">Hapus</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mb-2.5">
                  <div>
                    <label class="block text-[11px] font-semibold mb-1" style="color:#1B4D4D">Varian *</label>
                    <select required x-model="item.variant_id" class="input-field text-xs py-2">
                      <option value="">Pilih varian</option>
                      <template x-for="v in variants" :key="v.id">
                        <option :value="v.id" x-text="v.emoji + ' ' + v.name"></option>
                      </template>
                    </select>
                  </div>
                  <div>
                    <label class="block text-[11px] font-semibold mb-1" style="color:#1B4D4D">Ukuran *</label>
                    <select required x-model="item.size_id" class="input-field text-xs py-2">
                      <option value="">Pilih ukuran</option>
                      <template x-for="s in sizes" :key="s.id">
                        <option :value="s.id" x-text="s.label"></option>
                      </template>
                    </select>
                  </div>
                </div>
                <div class="flex items-center justify-between gap-3 pt-2" style="border-top:1px dashed rgba(196,152,74,0.2)">
                  <div class="flex items-center gap-2">
                    <label class="text-[11px] font-semibold" style="color:#1B4D4D">Jumlah:</label>
                    <input type="number" min="1" required x-model.number="item.qty" class="input-field text-xs py-1.5 px-2.5 w-20 text-center">
                  </div>
                  <div class="text-right">
                    <span class="text-[11px]" style="color:#7A7060">Subtotal: </span>
                    <span class="font-bold text-xs" style="color:#C4984A" x-text="fmt((product(item.variant_id, item.size_id)?.price ?? 0) * (item.qty || 0))"></span>
                  </div>
                </div>
              </div>
            </template>
          </div>

          <div class="p-3.5 rounded-2xl flex justify-between items-center text-sm" style="background:rgba(196,152,74,0.12);border:1px solid rgba(196,152,74,0.28)">
            <div>
              <span class="text-xs font-semibold block" style="color:#1B4D4D">Estimasi Total Pre-Order</span>
              <span class="text-[11px]" style="color:#7A7060" x-text="poTotalQty + ' botol pesanan'"></span>
            </div>
            <span class="font-bold text-xl" style="font-family: var(--font-display);color:#1B4D4D" x-text="fmt(poTotalEstimated)"></span>
          </div>

          <div>
            <label class="block text-xs font-semibold mb-1.5" style="color:#1B4D4D">Tanggal Pengambilan / Kirim *</label>
            <input type="date" required :min="today" x-model="po.date" class="input-field">
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold mb-1.5" style="color:#1B4D4D">Nama Lengkap *</label>
              <input type="text" required x-model="po.name" placeholder="Nama pemesan" class="input-field">
            </div>
            <div>
              <label class="block text-xs font-semibold mb-1.5" style="color:#1B4D4D">No. WhatsApp *</label>
              <input type="tel" required x-model="po.phone" placeholder="08xxxxxxxxxx" class="input-field">
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold mb-1.5" style="color:#1B4D4D">Alamat Pengiriman *</label>
            <textarea required rows="2" x-model="po.address" placeholder="Jl. Contoh No.1, Kota" class="input-field resize-none"></textarea>
          </div>

          <div>
            <label class="block text-xs font-semibold mb-1.5" style="color:#7A7060">Catatan (opsional)</label>
            <textarea rows="2" x-model="po.note" placeholder="Permintaan khusus / waktu pengambilan..." class="input-field resize-none"></textarea>
          </div>

          <p x-show="error" x-text="error" class="text-xs" style="color:#DC2626"></p>
          <button type="submit" :disabled="loading" class="btn-gold justify-center w-full mt-1" style="border-radius:0.75rem" x-text="loading ? 'Memproses…' : '📋 Kirim Pre-Order'"></button>
        </form>
      </div>
    </div>
  </template>

  {{-- TOAST NOTIFICATION --}}
  <div x-show="toastVisible" x-cloak
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0 translate-y-4"
       x-transition:enter-end="opacity-100 translate-y-0"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100 translate-y-0"
       x-transition:leave-end="opacity-0 translate-y-4"
       class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[999] px-6 py-3 rounded-2xl shadow-2xl text-sm font-semibold max-w-sm text-center"
       style="background:#1B4D4D;color:#F4EFE4;border:1px solid rgba(196,152,74,0.3)"
       x-text="toast">
  </div>

  @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="fixed top-20 left-1/2 -translate-x-1/2 z-[999] px-6 py-3 rounded-2xl shadow-2xl text-sm font-semibold flex items-center gap-2"
         style="background:#1B4D4D;color:#F4EFE4;border:1px solid rgba(196,152,74,0.3)">
      <span>✨ {{ session('success') }}</span>
      <button @click="show = false" class="text-xs opacity-75 hover:opacity-100 ml-2">✕</button>
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
function shop(catalog, orderUrl) {
  return {
    variants: catalog.variants, sizes: catalog.sizes, products: catalog.products,
    activeVariant: catalog.variants[0]?.id ?? null,
    activeSize: (catalog.sizes[1] ?? catalog.sizes[0])?.id ?? null,
    cart: [], cartOpen: false, step: 'cart', done: false,
    showPO: false, poDone: false, menuOpen: false, scrolled: false,
    addedId: null, testIdx: 0, loading: false, error: '',
    orderCode: '', waUrl: '#', today: new window.Date().toISOString().slice(0, 10),
    toast: '', toastVisible: false, toastTimer: null,
    form: { name: '', phone: '', address: '', note: '' },
    po: {
      items: [{ variant_id: '', size_id: '', qty: 1 }],
      date: '', name: '', phone: '', address: '', note: ''
    },

    addPoItem() {
      this.po.items.push({ variant_id: '', size_id: '', qty: 1 });
    },
    removePoItem(idx) {
      if (this.po.items.length > 1) {
        this.po.items.splice(idx, 1);
      }
    },
    get poTotalQty() {
      return this.po.items.reduce((sum, item) => sum + (Number(item.qty) || 0), 0);
    },
    get poTotalEstimated() {
      return this.po.items.reduce((sum, item) => {
        const prod = this.product(item.variant_id, item.size_id);
        return sum + ((prod?.price ?? 0) * (Number(item.qty) || 0));
      }, 0);
    },

    init() {
      try { this.cart = JSON.parse(localStorage.getItem('yq_cart') || '[]'); } catch (e) { this.cart = []; }
      // buang item yang produknya sudah tidak ada
      this.cart = this.cart.filter(c => this.products.some(p => p.id === c.product_id));
      const onScroll = () => { this.scrolled = window.scrollY > 60; };
      window.addEventListener('scroll', onScroll, { passive: true }); onScroll();
      setInterval(() => { this.testIdx = (this.testIdx + 1) % {{ count($cfg['testimonials'] ?? []) }}; }, 4000);
    },

    fmt(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); },
    product(v, s) { return this.products.find(p => p.variant_id == v && p.size_id == s); },
    inStock(v, s) { return (this.product(v, s)?.stock ?? 0) > 0; },
    defaultProduct(v) { return this.product(v, (this.sizes[1] ?? this.sizes[0])?.id); },
    get selected() { return this.product(this.activeVariant, this.activeSize); },
    get selectedVariant() { return this.variants.find(v => v.id == this.activeVariant); },
    get poProduct() { return this.po.variant_id && this.po.size_id ? this.product(this.po.variant_id, this.po.size_id) : null; },

    get cartCount() { return this.cart.reduce((s, c) => s + c.qty, 0); },
    get enriched() {
      return this.cart.flatMap(c => {
        const product = this.products.find(p => p.id === c.product_id); if (!product) return [];
        const variant = this.variants.find(v => v.id === product.variant_id);
        const size = this.sizes.find(s => s.id === product.size_id);
        return [{ product, variant, size, qty: c.qty, subtotal: product.price * c.qty }];
      });
    },
    get total() { return this.enriched.reduce((s, c) => s + c.subtotal, 0); },

    save() { localStorage.setItem('yq_cart', JSON.stringify(this.cart)); },
    showToast(msg) {
      clearTimeout(this.toastTimer);
      this.toast = msg; this.toastVisible = true;
      this.toastTimer = setTimeout(() => { this.toastVisible = false; }, 2500);
    },
    add(pid, qty = 1) {
      const p = this.products.find(p => p.id === pid);
      const v = p ? this.variants.find(vr => vr.id === p.variant_id) : null;
      const s = p ? this.sizes.find(sz => sz.id === p.size_id) : null;
      const ex = this.cart.find(c => c.product_id === pid);
      this.cart = ex ? this.cart.map(c => c.product_id === pid ? { ...c, qty: c.qty + qty } : c) : [...this.cart, { product_id: pid, qty }];
      this.save(); this.addedId = pid; setTimeout(() => { this.addedId = null; }, 1500);
      if (v && s) this.showToast(`✅ ${v.name} ${s.label} ditambahkan ke keranjang!`);
    },
    changeQty(pid, d) {
      const ex = this.cart.find(c => c.product_id === pid); if (!ex) return;
      if (ex.qty + d < 1) return this.remove(pid);
      this.cart = this.cart.map(c => c.product_id === pid ? { ...c, qty: c.qty + d } : c); this.save();
    },
    remove(pid) {
      const p = this.products.find(p => p.id === pid);
      const v = p ? this.variants.find(vr => vr.id === p.variant_id) : null;
      this.cart = this.cart.filter(c => c.product_id !== pid); this.save();
      if (v) this.showToast(`🗑 ${v.name} dihapus dari keranjang`);
    },
    clear() { this.cart = []; this.save(); this.showToast('🗑 Keranjang dikosongkan'); },
    lockBody() { document.body.style.overflow = 'hidden'; },
    unlockBody() { document.body.style.overflow = ''; },
    openCart() { this.cartOpen = true; this.step = 'cart'; this.error = ''; this.lockBody(); },
    closeCart() { this.cartOpen = false; this.step = 'cart'; this.done = false; this.error = ''; this.unlockBody(); },
    closePO() { this.showPO = false; this.poDone = false; this.error = ''; this.unlockBody(); },

    async send(payload) {
      let waTab = null;
      try {
        waTab = window.open('', '_blank');
      } catch (err) {}

      this.loading = true; this.error = '';
      try {
        const res = await fetch(orderUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json',
                     'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
          body: JSON.stringify(payload),
        });
        const data = await res.json();
        if (!res.ok) {
          if (waTab) waTab.close();
          this.error = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Terjadi kesalahan, coba lagi.');
          return null;
        }
        this.orderCode = data.code; this.waUrl = data.wa_url;
        if (waTab) {
          waTab.location.href = data.wa_url;
        } else {
          window.location.href = data.wa_url;
        }
        return data;
      } catch (e) {
        if (waTab) waTab.close();
        this.error = 'Koneksi bermasalah, coba lagi.'; return null;
      } finally { this.loading = false; }
    },
    async checkout() {
      const data = await this.send({
        type: 'regular', customer_name: this.form.name, phone: this.form.phone, address: this.form.address, note: this.form.note,
        items: this.cart.map(c => ({ product_id: c.product_id, qty: c.qty })),
      });
      if (data) { this.done = true; this.clear(); }
    },
    async submitPO() {
      const validItems = [];
      for (const item of this.po.items) {
        if (!item.variant_id || !item.size_id) {
          this.error = 'Pastikan semua item sudah dipilih varian dan ukurannya.';
          return;
        }
        const prod = this.product(item.variant_id, item.size_id);
        if (!prod) {
          this.error = 'Ada kombinasi varian & ukuran yang tidak valid.';
          return;
        }
        validItems.push({ product_id: prod.id, qty: Number(item.qty) || 1 });
      }

      if (validItems.length === 0) {
        this.error = 'Pilih minimal satu produk untuk pre-order.';
        return;
      }

      const data = await this.send({
        type: 'preorder',
        pickup_date: this.po.date,
        customer_name: this.po.name,
        phone: this.po.phone,
        address: this.po.address,
        note: this.po.note,
        items: validItems,
      });

      if (data) {
        this.poDone = true;
      }
    },
  };
}

// Animasi reveal & counter
document.addEventListener('DOMContentLoaded', () => {
  if (!('IntersectionObserver' in window)) {
    document.querySelectorAll('.reveal').forEach(el => el.classList.add('visible'));
    document.querySelectorAll('[data-count]').forEach(el => {
      const target = Number(el.dataset.count);
      el.textContent = target.toLocaleString('id-ID');
    });
    return;
  }

  const io = new window.IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('visible');
        io.unobserve(e.target);
      }
    });
  }, { threshold: 0.12 });

  document.querySelectorAll('.reveal').forEach(el => io.observe(el));

  const co = new window.IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      co.unobserve(e.target);

      const target = Number(e.target.dataset.count);
      const start = performance.now();
      const dur = 1400;

      const tick = (now) => {
        const t = Math.min((now - start) / dur, 1);
        e.target.textContent = Math.round((1 - Math.pow(1 - t, 3)) * target).toLocaleString('id-ID');
        if (t < 1) requestAnimationFrame(tick);
      };

      requestAnimationFrame(tick);
    });
  }, { threshold: 0.3 });

  document.querySelectorAll('[data-count]').forEach(el => co.observe(el));
});
</script>
@endpush
