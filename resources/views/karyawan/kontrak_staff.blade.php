@extends('../layouts.admin-master')
@section('admin-master')

<style>
    .card { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: none; }
    .card-header { background-color: transparent; border-bottom: 1px solid #eee; }
    .card-title-custom { font-size: 1.25rem; font-weight: 600; color: #333; }
    .table thead th { background-color: #f8f9fa; font-weight: 600; }
    .btn { border-radius: 8px; font-weight: 500; }
    .btn-primary { background: linear-gradient(45deg, #435ebe, #5e72e4); border: none; }
    .badge-status { padding: 0.5em 0.75em; font-size: 0.8rem; font-weight: 500; }
</style>

<div class="page-heading">
    <div class="page-title">
        <h3>{{ $menu }}</h3>
        <p class="text-subtitle text-muted">Ajukan dan kelola kontrak iklan dengan klien.</p>
    </div>

    <div class="row">
        <!-- Form Pengajuan Kontrak -->
        <div class="col-md-5">
            <section class="section">
                <div class="card">
                    <div class="card-header"><h4 class="card-title-custom">Form Pengajuan Kontrak</h4></div>
                    <div class="card-body">
                        <form action="{{ route('kontrak.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nama_client" class="form-label">Nama Klien</label>
                                <input type="text" class="form-control" id="nama_client" name="nama_client" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_media" class="form-label">Nama Media</label>
                                <input type="text" class="form-control" id="nama_media" name="nama_media" required>
                            </div>
                            <div class="form-group">
                                <label for="biaya_iklan" class="form-label">Biaya Iklan (Rp)</label>
                                <input type="number" class="form-control" id="biaya_iklan" name="biaya_iklan" required>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_mulai_kontrak" class="form-label">Tanggal Mulai Tayang</label>
                                <input type="date" class="form-control" id="tanggal_mulai_kontrak" name="tanggal_mulai_kontrak" required>
                            </div>
                            <div class="form-group">
                                <label for="durasi" class="form-label">Durasi (Hari)</label>
                                <input type="number" class="form-control" id="durasi" name="durasi" placeholder="Default: 30 hari">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-3">Ajukan Kontrak</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>

        <!-- Riwayat Pengajuan -->
        <div class="col-md-7">
            <section class="section">
                <div class="card">
                    <div class="card-header"><h4 class="card-title-custom">Riwayat Pengajuan Anda</h4></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Klien</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($riwayatKontrak as $kontrak)
                                    <tr>
                                        <td>{{ $kontrak->nama_client }}</td>
                                        <td>{{ \Carbon\Carbon::parse($kontrak->tanggal_mulai_kontrak)->format('d M Y') }}</td>
                                        <td>
                                            @php
                                                $statusClass = [
                                                    'Dalam Pengajuan' => 'bg-light-warning',
                                                    'Diterima' => 'bg-light-info',
                                                    'Ditolak' => 'bg-light-danger',
                                                    'Sedang Tayang' => 'bg-light-success',
                                                    'Kontrak Selesai' => 'bg-light-secondary',
                                                ][$kontrak->status] ?? 'bg-light-primary';
                                            @endphp
                                            <span class="badge badge-status {{ $statusClass }}">{{ $kontrak->status }}</span>
                                        </td>
                                        <td>
                                            @if($kontrak->status == 'Diterima')
                                                <form action="{{ route('kontrak.updateTayang', $kontrak->id_kontrak) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-info">Mulai Tayang</button>
                                                </form>
                                            @elseif($kontrak->status == 'Sedang Tayang')
                                                <form action="{{ route('kontrak.selesaikan', $kontrak->id_kontrak) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-secondary">Selesaikan</button>
                                                </form>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center text-muted">Belum ada riwayat pengajuan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
