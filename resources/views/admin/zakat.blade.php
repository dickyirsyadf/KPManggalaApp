@extends('admin.layouts.admin-master')
@section('admin-master')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a>Transaksi</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{$menu}}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn icon icon-left btn-light block" data-bs-toggle="modal"
                    data-bs-target="#modal-form">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-edit">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Tambah {{$menu}}
                </button>
                <button type="button" class="btn icon icon-left btn-light block " data-bs-toggle="modal"
                    data-bs-target="#modal-form-hitungzakat">
                    <i class="bi bi-calculator-fill"></i>
                    Hitung Zakat
                </button>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Muzakki</th>
                            <th>No Zakat</th>
                            <th>Nominal</th>
                            <th>Kategori Zakat</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $actualIteration = 0;
                        @endphp
                        @foreach ($transaksi->sortByDesc('tanggal_transaksi') as $data)
                        @if ($data->keterangan === null)
                        @php
                        $actualIteration++;
                        @endphp
                        <tr>
                            <td>{{$actualIteration}}</td>
                            <td>{{$data->muzakki->nama}}</td>
                            <td>{{$data->no_transaksi}}</td>
                            <td>Rp. {{ str_replace(',','.', number_format($data->nominal_transaksi, 0)) }}</td>
                            <td>{{ $data->jenis_transaksi}}</td>
                            <td>{{ $data->tanggal_transaksi }}</td>
                            @if($data->status === 'Bayar')
                            <td><span class="badge bg-success">{{$data->status}}</span></td>
                            @else
                            <td><span class="badge bg-warning">{{$data->status}}</span></td>
                            @endif
                            <td>
                                <button id="btn-keterangan" type="button" class="btn-keterangan btn icon btn-danger "
                                    data-bs-toggle="modal" data-bs-target="#modal-form-keterangan"
                                    data-nama="{{ $data->muzakki->nama }}" data-nominal="{{ $data->nominal_transaksi }}"
                                    data-id="{{ $data->no_transaksi }}" data-zakat="{{ $data->jenis_transaksi }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah -->
<div class="modal fade text-left modal-borderless" id="modal-form" tabindex="-1" role="dialog"
    aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah {{$menu}}</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="/admin/tambahtransaksi" method="POST">
                    @csrf
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input id="nama" name="nama" type="text" placeholder="Nama Lengkap" class="form-control"
                                autocomplete="off" />
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input id="alamat" name="alamat" type="text" placeholder="Alamat" class="form-control"
                                autocomplete="off" />
                            <div class="form-control-icon">
                                <i class="bi bi-card-list"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input id="no_hp" name="no_hp" type="text" placeholder="No.HP" class="form-control"
                                autocomplete="off" />
                            <div class="form-control-icon">
                                <i class="bi bi-phone"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <select id="kategori-select-tambah" name="kategori" class="form-control"
                                onfocus="focused(this)" onfocusout="defocused(this)" required autocomplete="off">
                                <option selected disabled>Pilih kategori zakat</option>
                                @foreach ($jenis_transaksi as $data)
                                <option value="{{$data->jenis_transaksi}}" name="kategori">{{$data->jenis_transaksi}}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-control-icon">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                        </div>
                    </div>
                    <label for="">Nominal Zakat Yang Harus Dibayar : </label>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input id="nominal-zakat-tambah" name="nominal_transaksi" type="text" class="form-control"
                                autocomplete="off" />
                            <div class="form-control-icon">
                                {{-- <i class="bi bi-currency-dollar"></i> --}}
                                <span>Rp</span>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="submit" id="submit-btn" class="btn btn-primary ms-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Zakat Sekarang</span>
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Hitung --}}
<div class="modal fade text-left modal-borderless" id="modal-form-hitungzakat" tabindex="-1" role="dialog"
    aria-labelledby="modal-form-hitungzakat" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hitung Zakat</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <select id="kategori-select-hitung" onchange="showInput(this)" name=" kategori"
                                class="form-control" required autocomplete="off">
                                <option selected disabled>Pilih kategori zakat</option>
                                @foreach ($jenis_transaksi as $data)
                                <option value="{{$data->jenis_transaksi}}" name="kategori">
                                    {{$data->jenis_transaksi}}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-control-icon">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                        </div>
                    </div>
                    <div id="div-container-zakatmaal" class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" id="input-container-zakatmaal"
                                placeholder="Nominal Harta" name="zakat-maal-harta" required autocomplete="off">
                            <div class="form-control-icon">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>

                    <div id="div-container-zakatfitrah" class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" id="input-container-zakatfitrah"
                                placeholder="Nominal Zakat Fitrah" name="zakat-fitrah" required autocomplete="off">
                            <div class="form-control-icon">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>

                    <div id="div-container-zakatpenghasilan" class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" id="input-container-zakatpenghasilan"
                                placeholder="Total Penghasilan Per Bulan/Tahun" required autocomplete="off">
                            <div class="form-control-icon">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label id="label-zakat-maal" style="display:none;">Zakat Maal Yang Harus Dibayar :
                        </label>
                        <p type="text" id="nominal-zakat-maal-hitung" style="display:none;"></p>

                        <label id="label-zakat-penghasilan" style="display:none;">Zakat Penghasilan Yang
                            Harus
                            Dibayar :
                        </label>
                        <p type="text" id="nominal-zakat-penghasilan-hitung" style="display:none;"></p>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" class="btn btn-primary ms-1" onclick="openTambahZakatModal()">
                        Zakat Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Keterangan --}}
<div class="modal fade text-left modal-borderless modal-md" id="modal-form-keterangan" tabindex="-1" role="dialog"
    aria-labelledby="modal-form-keterangan" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h3>Tambah Keterangan</h3>
                    <p class="text-subtitle text-muted">
                        Tambah keterangan dimaksudkan apabila melakukan kesalahan input data, maka
                        tambahkan saja
                        keterangan
                        kesalahannya ( contoh: Salah Input Nominal )
                    </p>
                </div>
            </div>
            <form method="POST" action="/admin/tambahketerangan">
                @csrf
                <div class="modal-body">
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="No Transaksi" id="notrans"
                                name="no_transaksi" required autocomplete="off" readonly>
                            <div class="form-control-icon">
                                <i class="bi bi-file-earmark-binary"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Nama Muzakki" id="namas" required
                                autocomplete="off" readonly>
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Jenis Transaksi" id="jentrans" required
                                autocomplete="off" readonly>
                            <div class="form-control-icon">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Nominal" id="nomtrans" required
                                autocomplete="off" readonly>
                            <div class="form-control-icon">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Keterangan" name="keterangan" required
                                autocomplete="off">
                            <div class="form-control-icon">
                                <i class="bi bi-chat-right-text"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="submit" class="btn btn-primary ms-1">
                        Tambah Keterangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- passing data keterangan dari btn ke modal  -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnKeteranganList = document.querySelectorAll(".btn-keterangan");
        const noTrans = document.getElementById("notrans");
        const nomTrans = document.getElementById("nomtrans");
        const namas = document.getElementById("namas");
        const jentTrans = document.getElementById("jentrans");

        btnKeteranganList.forEach(function(button) {
            button.addEventListener("click", function() {
                const dataId = button.getAttribute("data-id");
                const dataNama = button.getAttribute("data-nama");
                const dataNominal = button.getAttribute("data-nominal");
                const dataZakat = button.getAttribute("data-zakat");
                const formattedNominal = formatRupiah(dataNominal);

                noTrans.value = dataId;
                namas.value = dataNama;
                nomTrans.value = "Rp. " + formattedNominal;
                jentTrans.value = dataZakat;
            });
        });
    });
    // Fungsi untuk mengubah nilai menjadi format rupiah
    function formatRupiah(nominal) {
    var reverse = nominal.toString().split('').reverse().join('');
    var thousands = reverse.match(/\d{1,3}/g);
    var formatted = thousands.join('.').split('').reverse().join('');
    return formatted;
    }
</script>

<!-- scrip untuk modal hitung zakat = menampilkan inputan sesuai dengan kategori -->
<script>
    function showInput(select) {
    var selectedValue = select.value;
    var inputElement;
    var hasilElement;
    var maxInputValue = 1000000000; // Nilai maksimum yang diizinkan

    var inputContainerZakatMaal = document.getElementById('input-container-zakatmaal');
    var divContainerZakatMaal = document.getElementById('div-container-zakatmaal');
    var labelNominalZakatMaal = document.getElementById('label-zakat-maal');
    var nominalZakatMaalHitung = document.getElementById('nominal-zakat-maal-hitung');

    var inputContainerZakatFitrah = document.getElementById('input-container-zakatfitrah');
    var divContainerZakatFitrah = document.getElementById('div-container-zakatfitrah');

    var inputContainerZakatPenghasilan = document.getElementById('input-container-zakatpenghasilan');
    var divContainerZakatPenghasilan = document.getElementById('div-container-zakatpenghasilan');
    var labelNominalZakatPenghasilan = document.getElementById('label-zakat-penghasilan');
    var nominalZakatPenghasilanHitung = document.getElementById('nominal-zakat-penghasilan-hitung');

    if (selectedValue === 'Zakat Maal (Harta)') {
      inputContainerZakatMaal.style.display = 'block';
      divContainerZakatMaal.style.display = 'block';
      labelNominalZakatMaal.style.display = 'block';
      nominalZakatMaalHitung.style.display = 'block';
      inputElement = document.getElementById("input-container-zakatmaal");
      hasilElement = document.getElementById("nominal-zakat-maal-hitung");
      // Menambahkan event listener untuk memantau perubahan input
      inputElement.addEventListener("input", function() {
        var angkaInput = inputElement.value;

        // Menghilangkan karakter selain angka
        var angkaBersih = angkaInput.replace(/\D/g, "");

        // Memeriksa apakah nilai input melebihi batas maksimum
        if (parseInt(angkaBersih) > maxInputValue) {
          angkaBersih = maxInputValue.toString(); // Mengatur nilai input ke nilai maksimum yang diizinkan
        }

        // Mengatur format input menjadi ribuan dengan tanda titik
        var angkaFormatted = formatRibuan(angkaBersih);

        // Memperbarui nilai input dengan format yang telah diformat
        inputElement.value = angkaFormatted;

        hitung(); // Memperbarui hasil perhitungan
      });

      // Fungsi untuk mengatur format ribuan dengan tanda titik
      function formatRibuan(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      }

      // Fungsi untuk menghitung dan memperbarui hasil perhitungan
      function hitung() {
        // var angkaInput = inputElement.value.replace(/\D/g, "");
        var angkaInput = inputElement.value.replace(/\D/g, "");

        // Memeriksa apakah nilai input melebihi batas maksimum setelah pemformatan
        if (parseInt(angkaInput) > maxInputValue) {
          angkaInput = maxInputValue.toString();
        }

        // Mengalikan angka dengan 0.025
        var hasilKali = angkaInput * 0.025;

        // Memformat hasil perhitungan dengan tanda titik ribuan dan desimal dua angka
        var hasilFormatted = formatRibuan(hasilKali.toFixed());

        // Menampilkan hasil di bawahnya dengan format mata uang Rupiah
        hasilElement.innerHTML = "Rp. " + hasilFormatted;
      }

      inputContainerZakatFitrah.style.display = 'none';
      divContainerZakatFitrah.style.display = 'none';

      inputContainerZakatPenghasilan.style.display = 'none';
      divContainerZakatPenghasilan.style.display = 'none';
      labelNominalZakatPenghasilan.style.display = 'none';
      nominalZakatPenghasilanHitung.style.display = 'none';

    } else if (selectedValue === 'Zakat Fitrah') {
      inputContainerZakatMaal.style.display = 'none';
      divContainerZakatMaal.style.display = 'none';
      labelNominalZakatMaal.style.display = 'none';
      nominalZakatMaalHitung.style.display = 'none';

      inputContainerZakatFitrah.style.display = 'block';
      divContainerZakatFitrah.style.display = 'block';

      inputContainerZakatPenghasilan.style.display = 'none';
      divContainerZakatPenghasilan.style.display = 'none';
      labelNominalZakatPenghasilan.style.display = 'none';
      nominalZakatPenghasilanHitung.style.display = 'none';

    } else if (selectedValue === 'Zakat Penghasilan') {
      inputContainerZakatMaal.style.display = 'none';
      divContainerZakatMaal.style.display = 'none';
      labelNominalZakatMaal.style.display = 'none';
      nominalZakatMaalHitung.style.display = 'none';

      inputContainerZakatFitrah.style.display = 'none';
      divContainerZakatFitrah.style.display = 'none';

      inputContainerZakatPenghasilan.style.display = 'block';
      divContainerZakatPenghasilan.style.display = 'block';
      labelNominalZakatPenghasilan.style.display = 'block';
      nominalZakatPenghasilanHitung.style.display = 'block';
      inputElement = document.getElementById("input-container-zakatpenghasilan");
      hasilElement = document.getElementById("nominal-zakat-penghasilan-hitung");
      // Menambahkan event listener untuk memantau perubahan input
      inputElement.addEventListener("input", function() {
        var angkaInput = inputElement.value;

        // Menghilangkan karakter selain angka
        var angkaBersih = angkaInput.replace(/\D/g, "");

        // Memeriksa apakah nilai input melebihi batas maksimum
        if (parseInt(angkaBersih) > maxInputValue) {
          angkaBersih = maxInputValue.toString(); // Mengatur nilai input ke nilai maksimum yang diizinkan
        }

        // Mengatur format input menjadi ribuan dengan tanda titik
        var angkaFormatted = formatRibuan(angkaBersih);

        // Memperbarui nilai input dengan format yang telah diformat
        inputElement.value = angkaFormatted;

        hitung(); // Memperbarui hasil perhitungan
      });

      // Fungsi untuk mengatur format ribuan dengan tanda titik
      function formatRibuan(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      }

      // Fungsi untuk menghitung dan memperbarui hasil perhitungan
      function hitung() {
        var angkaInput = inputElement.value.replace(/\D/g, "");

        // Memeriksa apakah nilai input melebihi batas maksimum setelah pemformatan
        if (parseInt(angkaInput) > maxInputValue) {
          angkaInput = maxInputValue.toString();
        }

        // Mengalikan angka dengan 0.025
        var hasilKali = angkaInput * 0.025;

        // Memformat hasil perhitungan dengan tanda titik ribuan dan desimal dua angka
        var hasilFormatted = formatRibuan(hasilKali.toFixed());

        // Menampilkan hasil di bawahnya dengan format mata uang Rupiah
        hasilElement.innerHTML = "Rp. " + hasilFormatted;
      }

    } else {
      inputContainer.style.display = 'none';
      divContainerZakatPenghasilan.style.display = 'none';
    }
  }
</script>

<!-- Script dismissabled kategori ketika onclik  -->
<script>
    var selectElement = document.getElementById("kategori-select-tambah");
  var submitButton = document.getElementById("submit-btn");

  submitButton.addEventListener("click", function() {
    selectElement.disabled = false; // Hapus atribut disabled sebelum mengirimkan permintaan

  });
</script>

<!-- Passing data from "Hitung Zakat" modal to "Tambah Zakat" modal -->
<script>
    function openTambahZakatModal() {
    var kategori = document.getElementById('kategori-select-hitung').value; // Get the value of kategori
    var nominalZakatMaal = document.getElementById('nominal-zakat-maal-hitung').innerHTML; // Get the value of nominal zakat
    var nominalZakatFitrah = document.getElementById('input-container-zakatfitrah').value;
    var nominalZakatPenghasilan = document.getElementById('nominal-zakat-penghasilan-hitung').innerHTML; // Get the value of nominal zakat


    $('#modal-form-hitungzakat').modal('hide');
    $('#modal-form').modal('show');


    if (kategori === 'Zakat Maal (Harta)') {
      // Zakat Maal
      document.getElementById('kategori-select-tambah').value = kategori;
      document.getElementById('kategori-select-tambah').disabled = true;
      document.getElementById('nominal-zakat-tambah').value = nominalZakatMaal;
      document.getElementById('nominal-zakat-tambah').readOnly = true;
      // console.log(kategori);
      // console.log(nominalZakatMaal);
    } else if (kategori === 'Zakat Fitrah') {
      //Zakat Fitrah
      document.getElementById('kategori-select-tambah').value = kategori;
      document.getElementById('kategori-select-tambah').disabled = true;
      document.getElementById('nominal-zakat-tambah').value = nominalZakatFitrah;
      document.getElementById('nominal-zakat-tambah').readOnly = true;
      // console.log(kategori);
      // console.log(nominalZakatFitrah);
    } else if (kategori === 'Zakat Penghasilan') {
      document.getElementById('kategori-select-tambah').value = kategori;
      document.getElementById('kategori-select-tambah').disabled = true;
      document.getElementById('nominal-zakat-tambah').value = nominalZakatPenghasilan;
      document.getElementById('nominal-zakat-tambah').readOnly = true;
      // console.log(kategori);
      // console.log(nominalZakatPenghasilan);x
    }
  }
</script>

<!-- Mengubah inputan modal #nominal-zakat-tambah menjadi format mata uang rupiah  -->
<script>
    var inputElem = document.getElementById('nominal-zakat-tambah');

  inputElem.addEventListener('input', function() {
    // Mengambil nilai input
    var inputVal = this.value;

    // Mengubah format menjadi mata uang rupiah
    var formattedVal = parseInt(inputVal.replace(/\D/g, '')).toLocaleString('id-ID');

    // Mengatur nilai input dengan format mata uang rupiah
    this.value = formattedVal;
  });
</script>

<!-- Mengubah inputan modal #input-container-zakatfitrah menjadi format mata uang rupiah  -->
<script>
    var inputElem = document.getElementById('input-container-zakatfitrah');

  inputElem.addEventListener('input', function() {
    // Mengambil nilai input
    var inputVal = this.value;

    // Mengubah format menjadi mata uang rupiah
    var formattedVal = parseInt(inputVal.replace(/\D/g, '')).toLocaleString('id-ID');

    // Mengatur nilai input dengan format mata uang rupiah
    this.value = formattedVal;
  });
</script>

<script>
    // If you want to use tooltips in your project, we suggest initializing them globally
      // instead of a "per-page" level.
      document.addEventListener(
        "DOMContentLoaded",
        function () {
          var tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
          );
          var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
          });
        },
        false
      );
</script>
@endsection