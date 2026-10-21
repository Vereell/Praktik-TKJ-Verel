<?php

return [
    'name'      => 'Yoghurt-Q',
    'address'   => 'Jl. Sangkuriang Barat 1',
    'city'      => 'Bandung',
    // Nomor WhatsApp admin, format internasional tanpa "+" (contoh: 6281234567890)
    'wa_number' => env('ADMIN_WA', '6285722281849'),
    'wa_display' => env('ADMIN_WA_DISPLAY', '+62 857-2228-1849'),
    'email'     => env('SHOP_EMAIL', 'kikin.farrell@gmail.com'),

    // GANTI sesuai jam operasional sebenarnya
    'hours'     => 'Senin – Sabtu, 08.00 – 17.00',

    // Teks & klaim di bawah ini dibawa dari desain Figma — pastikan sesuai kenyataan sebelum go-live.
    'badge'     => 'Bersertifikat Halal',
    'hero_title_1' => 'Yoghurt',
    'hero_title_2' => 'Premium,',
    'hero_title_3' => 'Murni & Halal',
    'hero_text' => 'Dibuat dari susu segar berkualitas, difermentasi secara alami tanpa bahan pengawet. Pesan sekarang, diantar langsung ke rumahmu.',
    'about_title_1' => 'Dirintis dengan Cinta,',
    'about_title_2' => 'Tumbuh Bersama Pelanggan',
    'about' => [
        'Yoghurt-Q lahir dari kegelisahan: yoghurt premium di pasaran seringkali mahal dan tidak jelas kehalalannya. Kami hadir sebagai solusi — yoghurt berkualitas tinggi, halal bersertifikat, dengan harga yang bersahabat.',
        'Setiap batch diproduksi dari susu terbaik dan difermentasi dalam kondisi terkontrol. Tanpa shortcut, tanpa kompromi.',
    ],
    'values' => [
        ['🌿', 'Bahan 100% Alami', 'Susu segar berkualitas, tanpa pengawet, tanpa pewarna buatan.'],
        ['🏅', 'Halal Bersertifikat', 'Aman dikonsumsi seluruh keluarga dengan tenang.'],
        ['🚚', 'Antar ke Rumah', 'Layanan antar langsung ke lokasi kamu. Fresh, aman, tepat waktu.'],
        ['📦', 'Pre-Order Tersedia', 'Pesan lebih awal, kami siapkan sesuai jadwal dan kuantitas kebutuhanmu.'],
    ],
    // CONTOH testimoni dari desain — ganti dengan ulasan asli pelanggan.
    'testimonials' => [
        ['Aisyah R.', 'Ibu Rumah Tangga', 'Yoghurt Q jadi langganan keluarga saya. Anak-anak suka banget varian Strawberry-nya, sehat dan enak!'],
        ['Budi S.', 'Pemilik Kafe', 'Saya pakai Yoghurt Q untuk parfait menu kafe saya. Pelanggan selalu nanya merk yoghurtnya apa, kualitasnya memang beda.'],
        ['Nadia K.', 'Content Creator', 'Kemasan cantik, rasa autentik. Honey & Mint-nya juara! Sering saya rekomendasi di konten healthy living.'],
    ],
];
