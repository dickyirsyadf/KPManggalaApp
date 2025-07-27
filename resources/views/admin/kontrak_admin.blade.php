@extends('../layouts.admin-master')
@section('admin-master')

<style>
    .card { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: none; }
    .card-header { background-color: transparent; border-bottom: 1px solid #eee; }
    .card-title-custom { font-size: 1.25rem; font-weight: 600; color: #333; }
    .table thead th { background-color: #f8f9fa; font-weight: 600; }
    .btn { border-radius: 8px; font-weight: 500; }
    .badge-status { padding: 0.5em 0.75em; font-size: 0.8rem; font-weight: 500; }
</style>

<div class="page-heading">
    <div class="page-title">
        <h3>{{ $menu }}</h3>
        <p class="text-subtitle text-muted">Setujui atau tolak pengajuan kontrak iklan dari staff.</p>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header"><h4 class="card-title-custom">Daftar Pengajuan Kontrak Iklan</h4></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="kontrakAdminTable">
                        <thead>
                            <tr>
                                <th>ID Kontrak</th>
                                <th>Klien</th>
                                <th>Biaya</th>
                                <th>Diajukan Oleh</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($semuaKontrak as $kontrak)
                            <tr>
                                <td>{{ $kontrak->id_kontrak }}</td>
                                <td>{{ $kontrak->nama_client }}</td>
                                <td>Rp {{ number_format($kontrak->biaya_iklan, 0, ',', '.') }}</td>
                                <td>{{ $kontrak->diajukan_oleh }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'Dalam Pengajuan' => 'bg-light-warning',
                                            'Diterima' => 'bg-light-success',
                                            'Ditolak' => 'bg-light-danger',
                                            'Sedang Tayang' => 'bg-light-info',
                                            'Kontrak Selesai' => 'bg-light-secondary',
                                        ][$kontrak->status] ?? 'bg-light-primary';
                                    @endphp
                                    <span class="badge badge-status {{ $statusClass }}">{{ $kontrak->status }}</span>
                                </td>
                                <td>
                                    @if($kontrak->status == 'Dalam Pengajuan')
                                        <form action="{{ route('kontrak.updateStatus', $kontrak->id_kontrak) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="Diterima">
                                            <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                        </form>
                                        <form action="{{ route('kontrak.updateStatus', $kontrak->id_kontrak) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                        </form>
                                    @else
                                        Dikonfirmasi oleh {{ $kontrak->dikonfirmasi_oleh ?? '-' }}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">Tidak ada pengajuan kontrak.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#kontrakAdminTable').DataTable({
            "order": [[ 0, "desc" ]],
            "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" }
        });
    });
</script>
@endsection
