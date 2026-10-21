@extends('layouts.admin')
@section('heading', 'Dashboard')
@section('content')
@php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp
<div class="flex flex-col gap-5">
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach ([
      ['Total Pendapatan', $rp($stats['revenue']), 'Pesanan dibayar, 30 hari terakhir', '💰', '#22C55E'],
      ['Pesanan Menunggu', $stats['pending'], 'Perlu dicek stok & diproses', '⏳', '#F59E0B'],
      ['Total Stok', number_format($stats['stock'], 0, ',', '.'), 'Semua varian & ukuran', '📦', '#3B82F6'],
      ['Stok Hampir Habis', $stats['low_stock'], 'Di bawah 30 unit', '⚠️', '#EF4444'],
    ] as [$label, $val, $sub, $icon, $color])
      <div class="p-5 rounded-2xl card-hover" style="background:#fff;border:1px solid rgba(196,152,74,0.15)">
        <div class="flex items-center justify-between mb-3"><span class="text-2xl">{{ $icon }}</span><div class="w-2 h-2 rounded-full" style="background:{{ $color }}"></div></div>
        <div class="text-xl font-bold mb-0.5" style="font-family:var(--font-display);color:#1B4D4D">{{ $val }}</div>
        <div class="text-xs" style="color:#7A7060">{{ $label }}</div>
        <div class="text-xs mt-0.5" style="color:{{ $color }}">{{ $sub }}</div>
      </div>
    @endforeach
  </div>

  <div class="grid lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 p-5 rounded-2xl" style="background:#fff;border:1px solid rgba(196,152,74,0.15)">
      <h3 class="font-bold text-base mb-4" style="font-family:var(--font-display);color:#1B4D4D">Statistik Penjualan (30 Hari Terakhir)</h3>
      <div style="height:220px"><canvas id="revChart"></canvas></div>
    </div>
    <div class="p-5 rounded-2xl" style="background:#fff;border:1px solid rgba(196,152,74,0.15)">
      <h3 class="font-bold text-base mb-4" style="font-family:var(--font-display);color:#1B4D4D">Pesanan / Hari</h3>
      <div style="height:220px"><canvas id="ordChart"></canvas></div>
    </div>
  </div>

  <div class="p-5 rounded-2xl" style="background:#fff;border:1px solid rgba(196,152,74,0.15)">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-base" style="font-family:var(--font-display);color:#1B4D4D">Ringkasan Stok Varian</h3>
      <a href="{{ route('admin.stock') }}" class="text-xs font-medium hover:underline" style="color:#C4984A">Kelola →</a>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
      @foreach ($variantStock as $v)
        @php $t = $v->products->sum('stock'); $pct = min($t / 1200 * 100, 100); $c = $t < 100 ? '#EF4444' : ($t < 300 ? '#F59E0B' : '#1B4D4D'); @endphp
        <div class="p-3 rounded-xl" style="background:#F8F4EE">
          <div class="flex justify-between text-xs mb-1.5">
            <span class="font-semibold" style="color:#1B4D4D">{{ $v->emoji }} {{ $v->name }}</span>
            <span style="color:{{ $c === '#1B4D4D' ? '#16A34A' : $c }};font-weight:600">{{ $t }} unit</span>
          </div>
          <div class="h-2 rounded-full" style="background:#EDE5D4"><div class="h-2 rounded-full" style="width:{{ $pct }}%;background:{{ $c }}"></div></div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="p-5 rounded-2xl" style="background:#fff;border:1px solid rgba(196,152,74,0.15)">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-base" style="font-family:var(--font-display);color:#1B4D4D">Pesanan Terbaru</h3>
      <a href="{{ route('admin.orders') }}" class="text-xs font-medium hover:underline" style="color:#C4984A">Lihat Semua →</a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead><tr style="border-bottom:1px solid rgba(196,152,74,0.12)">
          @foreach (['ID', 'Pelanggan', 'Total', 'Status', 'Aksi'] as $h)<th class="text-left pb-2 pr-4 text-xs font-semibold" style="color:#7A7060">{{ $h }}</th>@endforeach
        </tr></thead>
        <tbody>
        @forelse ($recent as $o)
          <tr style="border-bottom:1px solid rgba(196,152,74,0.07)">
            <td class="py-3 pr-4 text-xs font-medium" style="font-family:var(--font-mono);color:#C4984A">#{{ $o->code }}</td>
            <td class="py-3 pr-4 text-sm font-medium" style="color:#1B4D4D">{{ $o->customer_name }}</td>
            <td class="py-3 pr-4 text-sm font-semibold" style="color:#1B4D4D">{{ $rp($o->total) }}</td>
            <td class="py-3 pr-4"><span class="px-2.5 py-1 rounded-full text-xs font-semibold" style="{{ $o->status_style }}">{{ $o->status_label }}</span></td>
            <td class="py-3">
              <form method="POST" action="{{ route('admin.orders.status', $o) }}">@csrf @method('PATCH')
                <select name="status" onchange="this.form.submit()" class="text-xs rounded-lg px-2 py-1 border outline-none" style="border:1px solid rgba(196,152,74,0.25);color:#1B4D4D;background:#fff">
                  @foreach (\App\Models\Order::STATUSES as $k => $l)<option value="{{ $k }}" @selected($o->status === $k)>{{ $l }}</option>@endforeach
                </select>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="py-6 text-center text-sm" style="color:#7A7060">Belum ada pesanan.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
const chart = @json($chart);
const labels = chart.map(c => c.label);
const grid = { color: 'rgba(196,152,74,0.1)' };
const ticks = { color: '#B0A890', font: { size: 10 } };
new Chart(document.getElementById('revChart'), {
  type: 'line',
  data: { labels, datasets: [{ data: chart.map(c => c.revenue), borderColor: '#1B4D4D', borderWidth: 2, tension: .35, fill: true, pointRadius: 0, pointHoverRadius: 4, pointHoverBackgroundColor: '#C4984A',
    backgroundColor: (ctx) => { const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 220); g.addColorStop(0, 'rgba(27,77,77,.35)'); g.addColorStop(1, 'rgba(27,77,77,.02)'); return g; } }] },
  options: { maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => 'Rp ' + c.parsed.y.toLocaleString('id-ID') } } },
    scales: { x: { grid: { display: false }, ticks: { ...ticks, maxTicksLimit: 8 } }, y: { grid, ticks: { ...ticks, callback: v => v >= 1e6 ? (v / 1e6) + 'jt' : v >= 1e3 ? (v / 1e3) + 'rb' : v } } } }
});
new Chart(document.getElementById('ordChart'), {
  type: 'bar',
  data: { labels, datasets: [{ data: chart.map(c => c.orders), backgroundColor: '#C4984A', borderRadius: 4 }] },
  options: { maintainAspectRatio: false, plugins: { legend: { display: false } },
    scales: { x: { grid: { display: false }, ticks: { ...ticks, maxTicksLimit: 6 } }, y: { grid, ticks: { ...ticks, precision: 0 } } } }
});
</script>
@endpush
