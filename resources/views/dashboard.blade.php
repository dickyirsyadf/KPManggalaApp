@extends('../layouts.admin-master')
@section('admin-master')

{{-- CSS Kustom untuk Dasbor --}}
<style>
    .summary-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 12px;
    }
    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    .summary-card .card-body {
        display: flex;
        align-items: center;
    }
    .summary-card .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #fff;
    }
    .stats-icon.purple { background-color: #435ebe; }
    .stats-icon.green { background-color: #198754; }
    .stats-icon.red { background-color: #dc3545; }
    .stats-icon.blue { background-color: #0d6efd; }
    .stats-icon.orange { background-color: #fd7e14; }
    .list-item {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .list-item:last-child {
        border-bottom: none;
    }
    .list-item .list-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 1.2rem;
        color: #435ebe;
    }
</style>

<div class="page-heading">
    <h3>Dashboard Keuangan</h3>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12">
            {{-- Kartu Ringkasan Keuangan --}}
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card summary-card">
                        <div class="card-body px-4 py-4-5">
                            <div class="stats-icon purple"><i class="iconly-boldWallet"></i></div>
                            <div class="ms-3">
                                <h6 class="text-muted font-semibold">Pemasukan</h6>
                                <h6 class="font-extrabold mb-0">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card summary-card">
                        <div class="card-body px-4 py-4-5">
                            <div class="stats-icon green"><i class="iconly-boldChart"></i></div>
                            <div class="ms-3">
                                <h6 class="text-muted font-semibold">Keuntungan Penjualan</h6>
                                <h6 class="font-extrabold mb-0">Rp {{ number_format($keuntunganBulanIni, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card summary-card">
                        <div class="card-body px-4 py-4-5">
                            <div class="stats-icon red"><i class="iconly-boldDownload"></i></div>
                            <div class="ms-3">
                                <h6 class="text-muted font-semibold">Pengeluaran</h6>
                                <h6 class="font-extrabold mb-0">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card summary-card">
                        <div class="card-body px-4 py-4-5">
                            <div class="stats-icon orange"><i class="iconly-boldWork"></i></div>
                            <div class="ms-3">
                                <h6 class="text-muted font-semibold">Kontrak Aktif</h6>
                                <h6 class="font-extrabold mb-0">{{ $kontrakAktifCount }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Grafik & Kontrak Akan Berakhir --}}
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header"><h4>Grafik Penjualan (7 Hari Terakhir)</h4></div>
                        <div class="card-body"><div id="sales-chart"></div></div>
                    </div>
                    <div class="card">
                        <div class="card-header"><h4>Kontrak Akan Berakhir (30 Hari)</h4></div>
                        <div class="card-body">
                            @forelse ($kontrakAkanBerakhir as $kontrak)
                                <div class="list-item">
                                    <div class="list-icon"><i class="bi bi-megaphone-fill"></i></div>
                                    <div>
                                        <h6 class="mb-0 font-bold">{{ $kontrak->nama_client }}</h6>
                                        <p class="mb-0 text-sm text-muted">Berakhir pada: {{ \Carbon\Carbon::parse($kontrak->tanggal_selesai_kontrak)->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center">Tidak ada kontrak yang akan berakhir dalam waktu dekat.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Produk Terlaris & Absensi --}}
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header"><h4>Produk Terlaris Bulan Ini</h4></div>
                        <div class="card-body">
                            @forelse ($produkTerlaris as $produk)
                                <div class="list-item">
                                    <div class="list-icon"><i class="bi bi-box-seam"></i></div>
                                    <div>
                                        <h6 class="mb-0 font-bold">{{ $produk->barang->nama ?? 'Produk Dihapus' }}</h6>
                                        <p class="mb-0 text-sm text-muted">Terjual {{ $produk->total_qty }} unit</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center">Belum ada data penjualan bulan ini.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon blue mb-2 me-3"><i class="iconly-boldUser"></i></div>
                                @if(auth()->user()->id_hakakses == 1)
                                    <div>
                                        <h6 class="text-muted font-semibold">Status Absensi Hari Ini</h6>
                                        @if ($absensiStatus == 'belum_absen')
                                            <h6 class="font-extrabold text-danger mb-0">Belum Absen Masuk</h6>
                                            <a href="{{ route('absensi.index') }}" class="btn btn-sm btn-danger mt-2">Absen Sekarang</a>
                                        @elseif ($absensiStatus == 'sudah_masuk')
                                            <h6 class="font-extrabold text-info mb-0">Anda Sedang Bekerja</h6>
                                            <p class="text-muted mb-0">Masuk: {{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') }}</p>
                                            <a href="{{ route('absensi.index') }}" class="btn btn-sm btn-info mt-2">Absen Pulang</a>
                                        @elseif ($absensiStatus == 'sudah_pulang')
                                            <h6 class="font-extrabold text-success mb-0">Absensi Selesai</h6>
                                            <p class="text-muted mb-0">Pulang: {{ \Carbon\Carbon::parse($absensiHariIni->jam_keluar)->format('H:i') }}</p>
                                        @endif
                                    </div>
                                @else
                                    <div>
                                        <h6 class="text-muted font-semibold">Status Absensi Hari Ini</h6>
                                        @if ($absensiStatus == 'belum_absen')
                                            <h6 class="font-extrabold text-danger mb-0">Belum Absen Masuk</h6>
                                            <a href="{{ route('karyawan.absensi.index') }}" class="btn btn-sm btn-danger mt-2">Absen Sekarang</a>
                                        @elseif ($absensiStatus == 'sudah_masuk')
                                            <h6 class="font-extrabold text-info mb-0">Anda Sedang Bekerja</h6>
                                            <p class="text-muted mb-0">Masuk: {{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') }}</p>
                                            <a href="{{ route('karyawan.absensi.index') }}" class="btn btn-sm btn-info mt-2">Absen Pulang</a>
                                        @elseif ($absensiStatus == 'sudah_pulang')
                                            <h6 class="font-extrabold text-success mb-0">Absensi Selesai</h6>
                                            <p class="text-muted mb-0">Pulang: {{ \Carbon\Carbon::parse($absensiHariIni->jam_keluar)->format('H:i') }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Script untuk Grafik --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var options = {
        chart: { type: 'bar', height: 350, toolbar: { show: false } },
        series: [{ name: 'Penjualan', data: @json($salesData['data']) }],
        xaxis: { categories: @json($salesData['labels']) },
        yaxis: { labels: { formatter: function (value) { return "Rp " + new Intl.NumberFormat('id-ID').format(value); } } },
        plotOptions: { bar: { horizontal: false, borderRadius: 4, columnWidth: '50%', } },
        dataLabels: { enabled: false },
        colors: ['#435ebe'],
        tooltip: { y: { formatter: function (val) { return "Rp " + new Intl.NumberFormat('id-ID').format(val) } } }
    };
    var chart = new ApexCharts(document.querySelector("#sales-chart"), options);
    chart.render();
});
</script>
@endsection
