<form id="{{ $form_id }}" action="#" method="POST" enctype="multipart/form-data">
    @csrf
    @if (isset($method) && $method == 'PUT')
        @method('PUT')
    @endif
    <div class="row g-4">
        <div class="col-sm-12 col-lg">
            @component('dashboard.components.formElements.select', [
                'label' => 'Nama Kepala Keluarga / Nomor KK',
                'id' => 'nama-kepala-keluarga',
                'name' => 'nama_kepala_keluarga',
                'class' => 'select2',
                'wajib' => '<sup class="text-danger">*</sup>',
                ])
                @slot('options')
                    @foreach ($kartuKeluarga as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_kepala_keluarga }} / {{ $item->nomor_kk }}
                        </option>
                    @endforeach
                @endslot

            @endcomponent
        </div>
        <div class="col-sm-12 col-lg">
            @component('dashboard.components.formElements.select', [
                'label' => 'Nama Ibu (Tanggal Lahir)',
                'id' => 'nama-ibu',
                'name' => 'nama_ibu',
                'class' => 'select2',
                'attribute' => 'disabled',
                'wajib' => '<sup class="text-danger">*</sup>',
                ])
            @endcomponent
        </div>
        @if (Auth::user()->role == 'admin' && $method == 'POST')
            <div class="col-sm-12 col-lg">
                @component('dashboard.components.formElements.select', [
                    'label' => 'Bidan sesuai lokasi anak',
                    'id' => 'nama-bidan',
                    'name' => 'nama_bidan',
                    'class' => 'select2',
                    'attribute' => 'disabled',
                    'wajib' => '<sup class="text-danger">*</sup>',
                    ])
                @endcomponent
            </div>
        @endif
        <div class="col-sm-12 col-lg-12">
            <h6 class="card-title mb-0">Pertanyaan</h6>
            @foreach ($daftarSoal as $soal)
                @php
                    $checkedYa = '';
                    $checkedTidak = '';
                @endphp
                @if (isset($method) && $method == 'PUT')
                    @php
                        $jawabanSoal = \App\Models\JawabanDeteksiDini::where('deteksi_dini_id', $dataEdit->id)
                            ->where('soal_id', $soal->id)
                            ->first();
                        if ($jawabanSoal) {
                            if ($jawabanSoal->jawaban == 'Ya') {
                                $checkedYa = 'checked';
                            } else {
                                $checkedTidak = 'checked';
                            }
                        }
                    @endphp
                @endif
                <div class="card p-0 my-3">
                    <div class="card-body">
                        <p>{{ $loop->iteration }}. {{ $soal->soal }}</p>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">Ya</label>
                            <input class="form-check-input" type="radio" id="jawaban-{{ $loop->iteration }}"
                                name="jawaban-{{ $loop->iteration }}[]" value="Ya" {{ $checkedYa }}>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">Tidak</label>
                            <input class="form-check-input" type="radio" id="jawaban-{{ $loop->iteration }}"
                                name="jawaban-{{ $loop->iteration }}[]" value="Tidak" {{ $checkedTidak }}>
                        </div>
                        <p class="text-danger jawaban-{{ $loop->iteration }}-error my-0"></p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-12 text-end">
            @component('dashboard.components.buttons.process', [
                'id' => 'proses-pertumbuhan-anak',
                'type' => 'submit',
                ])
            @endcomponent
        </div>
    </div>
    <div class="modal fade" id="modal-hasil" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-body custom_scroll p-lg-4 pb-3">
                    <div class="d-flex w-100 justify-content-between mb-1">
                        <div>
                            <h5>Hasil Deteksi Dini</h5>
                            <p class="text-muted" id="tanggal-proses"> - </p>
                        </div>
                        <button type="button" class="btn-close float-end" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="alert kategori-alert rounded-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar rounded no-thumbnail kategori-bg text-light"><i id="kategori-emot"
                                    class=""></i></div>
                            <div class="w-100 align-items-center">
                                <div class="h6 mb-0" id="modal-kategori" style="margin-left: 5px"> - </div>
                                <div class="h6 mb-0" id="modal-total-skor" style="margin-left: 5px"> - </div>
                            </div>
                        </div>
                    </div>
                    <div class="card fieldset border border-dark my-4">
                        <span class="fieldset-tile text-dark ml-5 bg-white">Info Ibu:</span>
                        <div class="card-body p-0 py-1 px-1">
                            <ul class="list-unstyled mb-0">
                                <li class="justify-content-between mb-2">
                                    <label><i class="fa-solid fa-child"></i> Nama Ibu:</label>
                                    <span class="badge bg-info float-end text-uppercase" id="modal-nama-ibu"> - </span>
                                </li>
                                <li class="justify-content-between mb-2">
                                    <label><i class="bi bi-calendar2-event-fill"></i> Tanggal Lahir</label>
                                    <span class="badge bg-info float-end text-uppercase" id="modal-tanggal-lahir"> -
                                    </span>
                                </li>
                                <li class="justify-content-between mb-2">
                                    <label><i class="fa-solid fa-cake-candles"></i> Usia</label>
                                    <span class="badge bg-info float-end text-uppercase" id="modal-usia"> - </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-sm-6 col-lg-4">
                            <button class="btn btn-outline-dark text-uppercase w-100" data-bs-dismiss="modal"
                                aria-label="Close"><i class="bi bi-x-circle"></i> Batal</button>
                        </div>
                        <div class="col-sm-6 col-lg-8">
                            {{-- <a href="#" class="btn btn-info text-white text-uppercase w-100" id="simpan-pertumbuhan-anak"><i class="fa-solid fa-floppy-disk"></i> Simpan</a> --}}
                            @component('dashboard.components.buttons.submit', [
                                'id' => 'proses-pertumbuhan-anak',
                                'type' => 'submit',
                                'class' => 'text-white text-uppercase w-100',
                                'label' => 'Simpan',
                                ])
                            @endcomponent
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>


@push('script')
    @if (isset($method) && $method == 'PUT')
        <script>
            $(document).ready(function() {
                $('#nama-kepala-keluarga').val(
                    '{{ $dataEdit->anggotaKeluarga->kartuKeluarga->id }}').change();
                setTimeout(function() {
                    $('#nama-ibu').val(
                        '{{ $dataEdit->anggotaKeluarga->id }}').change();
                }, 500);
            });
        </script>
    @endif
    <script>
        $(function() {
            $('.modal').modal({
                backdrop: 'static',
                keyboard: false
            })
            if ($('#nama-kepala-keluarga').val() != '') {
                changeKepalaKeluarga()
            }

            $('#nama-kepala-keluarga').change(function() {
                changeKepalaKeluarga()
            })

            $('#nama-ibu').change(function() {
                changeIbu()
            })

            $('#{{ $form_id }}').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                if ($('#modal-hasil').hasClass('show')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Apakah data sudah sesuai?',
                        text: 'Jika sudah sesuai, maka data akan disimpan dan dilihat oleh Penyuluh BKKBN dan Dinas P2KB',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Simpan',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "POST",
                                url: "{{ $action }}",
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: formData,
                                cache: false,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    if (response.status == 'success') {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Data berhasil disimpan',
                                            text: 'Data akan dilihat oleh Penyuluh BKKBN dan Dinas P2KB',
                                            showConfirmButton: false,
                                            timer: 2000,
                                        }).then((result) => {
                                            // set location
                                            window.location.href =
                                                "{{ $back_url }}";
                                        })
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Terjadi kesalahan',
                                            text: 'Data gagal disimpan',
                                            showConfirmButton: false,
                                            timer: 1500
                                        })
                                    }

                                },
                                error: function(response) {
                                    alert(response.responseJSON.message)
                                },

                            });
                        }
                    })

                } else {
                    $("#overlay").fadeIn(100);
                    $('.error-text').text('');
                    $.ajax({
                        type: "POST",
                        url: "{{ $proses }}",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            $("#overlay").fadeOut(100);
                            if ($.isEmptyObject(data.error)) {
                                $('#modal-hasil').modal('show');
                                $('#tanggal-proses').text('Tanggal : ' + moment().format('LL'))
                                $('#modal-nama-ibu').text(data.nama_ibu);
                                $('#modal-tanggal-lahir').text(moment(data.tanggal_lahir)
                                    .format('LL'));
                                $('#modal-usia').text(data.usia_tahun);
                                $('#modal-kategori').text(data.kategori);
                                $('#modal-total-skor').text("Skor : " + data.total_skor);
                                var kategoriBg = ['bg-danger', 'bg-warning', 'bg-info',
                                    'bg-success', 'bg-primary'
                                ];
                                var kategoriAlert = ['alert-danger', 'alert-warning',
                                    'alert-info', 'alert-success', 'alert-primary'
                                ];
                                var kategoriEmot = ['fa-solid fa-face-frown',
                                    'fa-solid fa-face-meh', 'fa-solid fa-face-smile',
                                    'fa-solid fa-face-surprise'
                                ];
                                $.each(kategoriBg, function(i, v) {
                                    $('.kategori-bg').removeClass(v);
                                });
                                $.each(kategoriAlert, function(i, v) {
                                    $('.kategori-alert').removeClass(v);
                                });
                                $.each(kategoriEmot, function(i, v) {
                                    $('.kategori-emot').removeClass(v);
                                });

                                if (data.kategori ==
                                    'Kehamilan : KRST (Beresiko SANGAT TINGGI)') {
                                    $('.kategori-bg').addClass('bg-danger');
                                    $('.kategori-alert').addClass('alert-danger');
                                    $('#kategori-emot').addClass('fa-solid fa-face-frown');
                                } else if (data.kategori ==
                                    'Kehamilan : KRT (Beresiko TINGGI)') {
                                    $('.kategori-bg').addClass('bg-warning');
                                    $('.kategori-alert').addClass('alert-warning');
                                    $('#kategori-emot').addClass('fa-solid fa-face-meh');
                                } else if (data.kategori ==
                                    'Kehamilan : KRR (Beresiko Rendah)') {
                                    $('.kategori-bg').addClass('bg-primary');
                                    $('.kategori-alert').addClass('alert-primary');
                                    $('#kategori-emot').addClass('fa-solid fa-face-surprise');
                                }
                            } else {
                                Swal.fire(
                                    'Terjadi Kesalahan!',
                                    'Periksa Kembali Inputan Anda!',
                                    'error'
                                )
                                printErrorMsg(data.error);
                            }
                        }
                    });

                }

            });

            const printErrorMsg = (msg) => {
                $.each(msg, function(key, value) {
                    $('.' + key + '-error').text(value);
                });
            }
        });

        function changeKepalaKeluarga() {
            var id = $('#nama-kepala-keluarga').val();
            var id_edit = "{{ isset($dataEdit) ? $dataEdit->anggota_keluarga_id : '' }}";
            var selected = '';
            $('#nama-ibu').html('');
            $('#nama-ibu').append('<option value="" selected hidden>- Pilih Salah Satu -</option>')
            $.get("{{ url('get-ibu') }}", {
                id: id,
                method: "{{ $method }}",
                id_edit: id_edit
            }, function(result) {
                $.each(result.anggota_keluarga, function(key, val) {
                    var tanggal_lahir = moment(val.tanggal_lahir).format('LL');
                    selected = '';
                    if (val.id == "{{ isset($dataEdit) ? $dataEdit->anggota_keluarga_id : '' }}") {
                        selected = 'selected';
                    }
                    $('#nama-ibu').append(
                        `<option value="${val.id}" ${selected}>${val.nama_lengkap} (${tanggal_lahir})</option>`
                    );
                })

                if ("{{ $method }}" == 'PUT') {
                    selected = '';

                    if (result.anggota_keluarga_hapus) {
                        if (result.anggota_keluarga_hapus.id ==
                            "{{ isset($dataEdit) ? $dataEdit->anggota_keluarga_id : '' }}") {
                            selected = 'selected';
                        }

                        $('#nama-ibu').append(
                            `<option value="${result.anggota_keluarga_hapus.id}" ${selected}>${result.anggota_keluarga_hapus.nama_lengkap} (${result.anggota_keluarga_hapus.tanggal_lahir})</option>`
                        );

                    }
                }
                $('#nama-ibu').removeAttr('disabled');
            });
        }

        function changeIbu() {
            var id = $('#nama-ibu').val();

            $('#nama-bidan').html('');
            $('#nama-bidan').append('<option value="" selected hidden>- Pilih Salah Satu -</option>')
            $.get("{{ route('getBidan') }}", {
                id: id,
            }, function(result) {
                $.each(result, function(key, val) {
                    $('#nama-bidan').append(`<option value="${val.id}">${val.nama_lengkap}</option>`);
                })
                $('#nama-bidan').removeAttr('disabled');
            });
        }
    </script>
@endpush