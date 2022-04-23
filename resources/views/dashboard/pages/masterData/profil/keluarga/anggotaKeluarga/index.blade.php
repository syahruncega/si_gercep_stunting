@extends('dashboard.layouts.main')

@section('title')
    Anggota Keluarga Bapak {{$kartuKeluarga->nama_kepala_keluarga}}
@endsection

@section('breadcrumb')
    <div class="col">
        <ol class="breadcrumb bg-transparent mb-0">
            <li class="breadcrumb-item"><a class="text-secondary" href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profil</li>
            <li class="breadcrumb-item active" aria-current="page">Keluarga</li>
            <li class="breadcrumb-item active" aria-current="page">Anggota Keluarga</li>
        </ol>
    </div>
@endsection

@section('content')
<section>      
    <div class="row mb-4">
        <div class="col">
            <div class="card ">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom-0 pt-3 pb-0">
                    <h5 class="card-title mb-0">Data Anggota Keluarga Bapak {{$kartuKeluarga->nama_kepala_keluarga}}</h5>
                    @component('dashboard.components.buttons.add',[
                        'id' => 'catatan-pertumbuhan-anak',
                        'class' => '',
                        'url' => url('anggota-keluarga' . '/' . $kartuKeluarga->id .'/create'),
                    ])        
                    @endcomponent
                </div>
                <div class="card-body pt-2">
                    <div class="row mb-0">
                        <div class="col">
                            <div class="card fieldset border border-secondary mb-4">
                                <span class="fieldset-tile text-secondary bg-white">Filter Data</span>
                                <div class="row">
                                    <div class="col-lg">
                                        @component('dashboard.components.formElements.select', [
                                            'label' => 'Status',
                                            'id' => 'status',
                                            'name' => 'status',
                                            'class' => 'filter',
                                            ])         
                                            @slot('options')
                                                <option value="1">Aktif</option>
                                                <option value="0">Tidak Aktif</option>
                                            @endslot
                                        @endcomponent
                                    </div>
                                    <div class="col-lg">
                                        @component('dashboard.components.formElements.select', [
                                            'label' => 'Kategori Gizi',
                                            'id' => 'kategori-gizi',
                                            'name' => 'kategori_gizi',
                                            'class' => 'filter',
                                            ])         
                                            @slot('options')
                                                <option>Mustard</option>
                                                <option>Ketchup</option>
                                                <option>Relish</option>
                                            @endslot
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card fieldset border border-secondary">
                                @component('dashboard.components.dataTables.index', [
                                    'id' => 'table-bidan',
                                    'th' => [
                                        'No',
                                        'Status',
                                        'NIK',
                                        'Nama Lengkap',
                                        'Status Dalam Keluarga',
                                        'Aksi',
                                    ],
                                ])
                                @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-validasi-ubah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body custom_scroll p-lg-4 pb-3">
                <div class="d-flex w-100 justify-content-between mb-1">
                    <div class="w-100">
                        <h5>Detail Keluarga</h5>
                    </div>
                    
                    <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="row">
                    <div class="col-6"> 
                        <p class="text-muted mb-0" id="">Dibuat: </p>
                        <p class="text-muted" id="modal-created-at">-</p>
                    </div>

                    <div class="col-6 float-end text-end" id="terakhir-diperbarui">
                        <p class="text-muted mb-0" id="">Terakhir Diperbarui: </p>
                        <p class="text-muted" id="modal-updated-at">-</p>
                    </div>
                </div>

                <div class="alert kategori-alert alert-primary rounded-4 mb-0">
                    <div class="d-flex align-items-center">
                        <div class="avatar rounded no-thumbnail kategori-bg bg-primary text-light mx-1"><i class="fa-solid fa-map-location-dot"></i></div>
                        <div class="d-flex w-100 justify-content-between align-items-center" style="font-size: 0.8em">
                            <div class="" id="">DOMISILI: </div>
                            <div class="float-end text-end" id="modal-domisili"></div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col mb-2">
                        <div class="card fieldset border border-dark">
                            <span class="fieldset-tile text-dark ml-5 bg-white">Info Kepala Keluarga:</span>
                            <div class="card-body p-0 py-1 px-1">
                                <ul class="list-unstyled mb-0">
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-person fa-lg"></i> Nama Lengkap</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-nama-lengkap"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-id-card"></i> NIK</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-nik"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-venus-mars"></i> Jenis Kelamin</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-jenis-kelamin"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-map-location-dot"></i> Tempat Lahir</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-tempat-lahir"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="bi bi-calendar2-event-fill"></i> Tanggal Lahir</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-tanggal-lahir"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-hands-praying"></i> Agama</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-agama"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-graduation-cap"></i> Pendidikan</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-pendidikan"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-briefcase"></i> Jenis Pekerjaan</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-jenis-pekerjaan"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-droplet"></i> Golongan Darah</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-golongan-darah"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-ring"></i> Status Perkawinan</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-status-perkawinan"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-calendar-day"></i> Tanggal Perkawinan</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-tanggal-perkawinan"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-flag"></i> Kewarganegaraan</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-kewarganegaraan"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2" id="li-nomor-paspor">
                                        <label><i class="fa-solid fa-passport"></i> Nomor Paspor</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-nomor-paspor"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2" id="li-nomor-kitap">
                                        <label><i class="fa-solid fa-passport"></i> Nomor KITAP</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-nomor-kitap"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-person fa-lg"></i> Nama Ayah</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-nama-ayah"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-person-dress fa-lg"></i> Nama Ibu</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-nama-ibu"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-road"></i> Alamat Domisili</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-alamat-domisili"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-location-dot"></i> Desa/Kelurahan Domisili</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-desa-kelurahan-domisili"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-location-dot"></i> Kecamatan Domisili</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-kecamatan-domisili"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-location-dot"></i> Kabupaten/Kota Domisili</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-kabupaten-kota-domisili"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-location-dot"></i> Provinsi Domisili</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-provinsi-domisili"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2 d-none" id="col-sk-domisili">
                                        <label><i class="fa-solid fa-file"></i> Surat Keterangan Domisili</label>
                                        <a href="#" id="file-surat-keterangan-domisili" target="_blank"><span class="badge bg-success shadow float-end text-uppercase">Lihat</span></a>
                                        <span class="badge bg-info shadow float-end text-uppercase" id="modal-file-surat-keterangan-domisili">Tidak Ada</span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-phone"></i> Nomor HP</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-nomor-hp"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-clipboard-question"></i> Status</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-status-konfirmasi"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="bi bi-calendar2-event-fill"></i> Tanggal Konfirmasi</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-tanggal-konfirmasi"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-stethoscope"></i> Oleh Bidan</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-oleh-bidan"> - </span>
                                    </li>
                                    <li class="justify-content-between">
                                        <label><i class="fa-solid fa-image"></i> Foto Profil</label>
                                        <span class="float-end" id="modal-foto-profil">
                                        </span>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-none align-items-end" id="form-konfirmasi">
                    <div class="col" id="pilih-konfirmasi">
                        @component('dashboard.components.formElements.select', [
                            'label' => 'Konfirmasi',
                            'id' => 'konfirmasi',
                            'name' => 'konfirmasi',
                            'class' => 'kosong',
                            'wajib' => '<sup class="text-danger">*</sup>',
                            ])
                            @slot('options')
                                <option value="1">Setujui</option>
                                <option value="2">Tolak</option>
                            @endslot
                        @endcomponent
                    </div>
                    @if (Auth::user()->role == 'admin')
                    <div class="col" id="pilih-bidan">
                        @component('dashboard.components.formElements.select', [
                            'label' => 'Bidan sesuai lokasi domisili kepala keluarga',
                            'id' => 'nama-bidan',
                            'name' => 'bidan_id',
                            'class' => 'bidan_id filter',
                            'wajib' => '<sup class="text-danger">*</sup>',
                            ])
                        @endcomponent
                    </div>
                    @endif
                    <div class="col-12 mt-3 d-none" id="col-alasan">
                        <label for="textareaInput" class="form-label">Alasan <sup class="text-danger">*</sup></label>
                        <textarea name="alasan" id="alasan" cols="30" rows="5" class="form-control alasan"></textarea>
                        <span class="text-danger error-text alasan-error"></span>

                    </div>
                    
                </div>
                <div class="row g-2 mt-3">
                    <div class="col">
                        <button class="btn btn-outline-dark text-uppercase w-100" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i>  Tutup</button>
                    </div>
                    <div class="col-sm-6 col-lg-8 d-none" id="col-btn-validasi">
                        @component('dashboard.components.buttons.konfirmasi', [
                            'id' => 'modal-btn-validasi',
                        ])      
                        @endcomponent
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-lihat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body custom_scroll p-lg-4 pb-3">
                <div class="d-flex w-100 justify-content-between mb-1">
                    <div class="w-100">
                        <h5>Detail Keluarga</h5>
                    </div>
                    
                    <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="row">
                    <div class="col-6"> 
                        <p class="text-muted mb-0" id="">Dibuat: </p>
                        <p class="text-muted" id="modal-created-at2">-</p>
                    </div>

                    <div class="col-6 float-end text-end" id="terakhir-diperbarui">
                        <p class="text-muted mb-0" id="">Terakhir Diperbarui: </p>
                        <p class="text-muted" id="modal-updated-at2">-</p>
                    </div>
                </div>

                <div class="alert kategori-alert alert-primary rounded-4 mb-0">
                    <div class="d-flex align-items-center">
                        <div class="avatar rounded no-thumbnail kategori-bg bg-primary text-light mx-1"><i class="fa-solid fa-map-location-dot"></i></div>
                        <div class="d-flex w-100 justify-content-between align-items-center" style="font-size: 0.8em">
                            <div class="" id="">DOMISILI: </div>
                            <div class="float-end text-end" id="modal-domisili2"></div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col mb-2">
                        <div class="card fieldset border border-dark">
                            <span class="fieldset-tile text-dark ml-5 bg-white">Info Kepala Keluarga:</span>
                            <div class="card-body p-0 py-1 px-1">
                                <ul class="list-unstyled mb-0">
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-person fa-lg"></i> Nama Lengkap</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-nama-lengkap2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-id-card"></i> NIK</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-nik2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-venus-mars"></i> Jenis Kelamin</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-jenis-kelamin2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-map-location-dot"></i> Tempat Lahir</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-tempat-lahir2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="bi bi-calendar2-event-fill"></i> Tanggal Lahir</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-tanggal-lahir2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-hands-praying"></i> Agama</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-agama2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-graduation-cap"></i> Pendidikan</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-pendidikan2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-briefcase"></i> Jenis Pekerjaan</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-jenis-pekerjaan2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-droplet"></i> Golongan Darah</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-golongan-darah2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-ring"></i> Status Perkawinan</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-status-perkawinan2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-calendar-day"></i> Tanggal Perkawinan</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-tanggal-perkawinan2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-flag"></i> Kewarganegaraan</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-kewarganegaraan2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2" id="li-nomor-paspor">
                                        <label><i class="fa-solid fa-passport"></i> Nomor Paspor</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-nomor-paspor2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2" id="li-nomor-kitap">
                                        <label><i class="fa-solid fa-passport"></i> Nomor KITAP</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-nomor-kitap2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-person fa-lg"></i> Nama Ayah</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-nama-ayah2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-person-dress fa-lg"></i> Nama Ibu</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-nama-ibu2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-road"></i> Alamat Domisili</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-alamat-domisili2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-location-dot"></i> Desa/Kelurahan Domisili</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-desa-kelurahan-domisili2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-location-dot"></i> Kecamatan Domisili</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-kecamatan-domisili2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-location-dot"></i> Kabupaten/Kota Domisili</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-kabupaten-kota-domisili2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-location-dot"></i> Provinsi Domisili</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-provinsi-domisili2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2 d-none" id="col-sk-domisili">
                                        <label><i class="fa-solid fa-file"></i> Surat Keterangan Domisili</label>
                                        <a href="#" id="file-surat-keterangan-domisili2" target="_blank"><span class="badge bg-success shadow float-end text-uppercase">Lihat</span></a>
                                        <span class="badge bg-info shadow float-end text-uppercase" id="modal-file-surat-keterangan-domisili2">Tidak Ada</span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-phone"></i> Nomor HP</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-nomor-hp2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-clipboard-question"></i> Status</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-status-konfirmasi2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="bi bi-calendar2-event-fill"></i> Tanggal Konfirmasi</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-tanggal-konfirmasi2"> - </span>
                                    </li>
                                    <li class="justify-content-between mb-2">
                                        <label><i class="fa-solid fa-stethoscope"></i> Oleh Bidan</label>
                                        <span class="badge bg-info float-end text-uppercase" id="modal-oleh-bidan2"> - </span>
                                    </li>
                                    <li class="justify-content-between">
                                        <label><i class="fa-solid fa-image"></i> Foto Profil</label>
                                        <span class="float-end" id="modal-foto-profil2">
                                        </span>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-2 mt-3">
                    <div class="col">
                        <button class="btn btn-outline-dark text-uppercase w-100" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i>  Tutup</button>
                    </div>
                    <div class="col-sm-6 col-lg-8 d-none" id="col-modal-btn-ubah">
                        @component('dashboard.components.buttons.edit', [
                            'id' => 'modal-btn-ubah',
                        ])      
                        @endcomponent
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        $('#m-link-profil').addClass('active');
        $('#menu-profil').addClass('collapse show')
        $('#ms-link-master-data-profil-keluarga').addClass('active') 
        
        var table = $('#table-bidan').removeAttr('width').DataTable({
            processing: true,
            serverSide: true,
            dom: 'lBfrtip',
            buttons : [
                {
                    extend: 'excel',
                    className: 'btn btn-sm btn-light-success px-2 btn-export-table d-inline ml-3 font-weight',                        
                    text: '<i class="bi bi-file-earmark-arrow-down"></i> Ekspor Data',
                    exportOptions: {
                        modifier: {
                            order: 'index', // 'current', 'applied', 'index',  'original'
                            page: 'all', // 'all',     'current'
                            search: 'applied' // 'none',    'applied', 'removed'
                        },
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    className: 'btn btn-sm btn-light-success px-2 btn-export-table d-inline ml-3 font-weight',                        
                    text: '<i class="bi bi-eye-fill"></i> Tampil/Sembunyi Kolom',
                }                   
            ],  
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],  
            ajax: {
                url: "{{ url('anggota-keluarga' . '/' . $kartuKeluarga->id) }}",
                data: function(d){
                    // d.role = $('#role-filter').val();                    
                    // d.search = $('input[type="search"]').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'status',
                    className: 'text-center',
                },
                {
                    data: 'nik',
                    name: 'nik',
                    className: 'text-center',
                },
                {
                    data: 'nama_lengkap',
                    name: 'nama_lengkap',
                },
                {
                    data: 'status_hubungan_dalam_keluarga',
                    name: 'status_hubungan_dalam_keluarga',
                    className: 'text-center',
                },
                {
                    data: 'action',
                    name: 'action',
                    className: 'text-center',
                    orderable: true,
                    searchable: true
                },
                
            ],  
            // columnDefs: [
            //     {
            //         targets: [1,4,5,6,7,9,10,12,13,14,15],
            //         visible: false,
            //     },
            //     {
            //         targets: [5,15],
            //         visible: false,
            //         render: function(data) {
            //             return moment(data).format('LL');
            //         }
            //     },
            //     {
            //         targets: 16,
            //         className: 'text-center',
            //         render: function (data, type, full, meta) {
            //             return "<div style='white-space: normal;width: 180px;'>" + data + "</div>";
            //         }
            //     },
            // ],        
        });

        function hapus(idAnggota, idKeluarga) {
            var _token = "{{csrf_token()}}";
            Swal.fire({
                title : 'Apakah anda yakin?',
                text : "Data bidan yang dipilih akan dihapus!",
                icon : 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{url('anggota-keluarga')}}" + '/' + idKeluarga + '/' + idAnggota,
                        data: {
                            _token: _token
                        },
                        success: function(response) {
                            if (response.res == 'success') {
                                Swal.fire(
                                    'Terhapus!',
                                    'Data berhasil dihapus.',
                                    'success'
                                ).then(function() {
                                    table.draw();
                                })
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    'Data gagal dihapus.',
                                    'error'
                                )
                            }
                        }
                    })
                } 
            })
        }

        function modalValidasi(anggotaKeluarga, keluarga){
            $.ajax({
                type: "GET",
                url: "{{url('anggota-keluarga')}}" + '/' + keluarga + '/' + anggotaKeluarga,
                success: function (data) {
                    $('#col-alasan').addClass('d-none');
                    $('#modal-validasi-ubah').modal('show');
                    $('#modal-created-at').html(moment(data.created_at).format('LL'));
                    if(data.updated_at != null){
                        $('#modal-updated-at').html(moment(data.updated_at).format('LL'));
                    } else{
                        $('#modal-updated-at').html(moment(data.created_at).format('LL'));
                    }
                    $('#modal-domisili').html(data.desa_kelurahan_domisili+', '+data.kecamatan_domisili+', '+data.kabupaten_kota_domisili+', '+data.provinsi_domisili)
                    $('#modal-nama-lengkap').html(data.nama_lengkap);
                    $('#modal-jenis-kelamin').html(data.jenis_kelamin);
                    $('#modal-nik').html(data.nik);
                    $('#modal-tempat-lahir').html(data.tempat_lahir);
                    $('#modal-tanggal-lahir').html(moment(data.tanggal_lahir).format('LL'));
                    $('#modal-agama').html(data.agama_);
                    $('#modal-pendidikan').html(data.pendidikan_);
                    $('#modal-jenis-pekerjaan').html(data.pekerjaan_);
                    $('#modal-golongan-darah').html(data.golongan_darah_);
                    $('#modal-status-perkawinan').html(data.status_perkawinan_);
                    if(data.tanggal_perkawinan != null){
                        $('#modal-tanggal-perkawinan').html(moment(data.tanggal_perkawinan).format('LL'));
                    } else{
                        $('#modal-tanggal-perkawinan').html('-');
                    }
                    $('#modal-kewarganegaraan').html(data.kewarganegaraan);
                    $('#modal-nomor-paspor').html(data.no_paspor);
                    $('#modal-nomor-kitap').html(data.no_kitap);
                    $('#modal-nama-ayah').html(data.nama_ayah);
                    $('#modal-nama-ibu').html(data.nama_ibu);

                    $('#modal-alamat-domisili').html(data.alamat_domisili);
                    $('#modal-desa-kelurahan-domisili').html(data.desa_kelurahan_domisili);
                    $('#modal-kecamatan-domisili').html(data.kecamatan_domisili);
                    $('#modal-kabupaten-kota-domisili').html(data.kabupaten_kota_domisili);
                    $('#modal-provinsi-domisili').html(data.provinsi_domisili);

                    if(data.is_valid == 1){
                        $('#modal-status-konfirmasi').html('Valid');
                    } else{
                        $('#modal-status-konfirmasi').html('Tidak Valid');
                    }

                    $('#modal-tanggal-konfirmasi').html(moment(data.tanggal_validasi).format('LL'));
                    $('#modal-oleh-bidan').html(data.nama_bidan)
                    
                    if(data.desa_kelurahan_nama != data.desa_kelurahan_domisili){
                        $('#col-sk-domisili').removeClass('d-none');
                        if(data.surat_keterangan_domisili){
                            $('#modal-file-surat-keterangan-domisili').addClass('d-none')
                            $('#file-surat-keterangan-domisili').removeClass('d-none');
                            $('#file-surat-keterangan-domisili').attr('href', '{{ asset("upload/surat_keterangan_domisili") }}/'+data.surat_keterangan_domisili);
                        } else{
                            $('#modal-file-surat-keterangan-domisili').removeClass('d-none')
                            $('#file-surat-keterangan-domisili').addClass('d-none')
                        }
                    } else{
                        $('#col-sk-domisili').addClass('d-none');
                    }

                    if(data.foto_profil != null){
                        $('#modal-foto-profil').html('<div class="image-input shadow avatar xxl rounded-4" style="background-image: url(../upload/foto_profil/keluarga/'+data.foto_profil+')">')
                    } else{
                        $('#modal-foto-profil').html('<span class="badge bg-info text-uppercase shadow">Tidak Ada</span>')
                    }

                    $('#modal-nomor-hp2').html(data.nomor_hp);

                    if(data.is_valid == 1){
                        $('#modal-status-konfirmasi').html('Valid');
                    } else{
                        $('#modal-status-konfirmasi').html('Tidak Valid');
                    }

                    $('#modal-tanggal-konfirmasi').html(moment(data.tanggal_validasi).format('LL'));

                    if(data.nama_bidan != null){
                        $('#modal-oleh-bidan').html(data.nama_bidan);
                    } else{
                        $('#modal-oleh-bidan').html('-');
                    }

                    if(data.is_valid == 1){
                        $('#col-modal-btn-ubah').removeClass('d-none');
                        $('#modal-btn-ubah').attr('href', '{{url('anggota-keluarga')}}' + '/' + data.kartu_keluarga_id + '/' + data.id + '/edit');
                    }

                    if(data.is_valid == 0){
                        $('#form-konfirmasi').removeClass('d-none');
                        $('#col-btn-validasi').removeClass('d-none');
                        $('#status-konfirmasi').addClass('d-none');

                        $('#nama-bidan').html('');
                        $('#nama-bidan').append('<option value="" selected hidden>- Pilih Salah Satu -</option>')
                        $('#konfirmasi').append('<option value="" selected hidden>- Pilih Salah Satu -</option>')

                        if('{{Auth::user()->role}}' == 'admin'){
                            $.each(data.bidan_konfirmasi, function(key, val) {
                                $('#nama-bidan').append(`<option value="${val.id}">${val.nama_lengkap}</option>`);
                            })
                        } else if('{{Auth::user()->role}}' == 'bidan'){
                            $('#pilih-bidan').addClass('d-none');
                        }
                        $('#col-modal-btn-ubah').addClass('d-none');
                       
                        $('#modal-btn-validasi').click(function(e){
                            e.preventDefault();
                            $('.error-text').text('');
                            if('{{Auth::user()->role}}' == 'admin'){
                                var bidan_id = $('#nama-bidan').val();
                                var konfirmasi = $('#konfirmasi').val();
                                var alasan = $('#alasan').val();
                            } else if ('{{Auth::user()->role}}' == 'bidan'){
                                var bidan_id = '{{Auth::user()->profil->id}}';
                                var konfirmasi = $('#konfirmasi').val();
                                var alasan = $('#alasan').val();
                            }
                            Swal.fire({
                                title : 'Apakah anda yakin?',
                                text : "Konfirmasi data keluarga ini?",
                                icon : 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'Batal',
                                confirmButtonText: 'Ya, Konfirmasi'
                            }).then((result) => {
                                if (result.value) {
                                    $.ajax({
                                        type: "PUT",
                                        url: "{{url('anggota-keluarga/validasi')}}" + '/' + keluarga + '/' + anggotaKeluarga,
                                        data: {
                                            _token: "{{csrf_token()}}",
                                            id: data.id,
                                            bidan_id: bidan_id,
                                            konfirmasi: konfirmasi,
                                            alasan: alasan
                                        },
                                        success: function(response) {
                                            if ($.isEmptyObject(response.error)) {
                                                console.log(response);
                                                if (response.res == 'success') {
                                                    $('#modal-validasi-ubah').modal('hide');
                                                    if(response.konfirmasi == 1){
                                                        Swal.fire(
                                                            'Berhasil!',
                                                            'Data divalidasi.',
                                                            'success'
                                                        ).then(function() {
                                                            table.draw();
                                                        })
                                                    } else{
                                                        Swal.fire(
                                                            'Berhasil!',
                                                            'Data ditolak.',
                                                            'success'
                                                        ).then(function() {
                                                            table.draw();
                                                        })
                                                    }
                                                } 
                                            } else {
                                                $('#overlay').hide();
                                                printErrorMsg(response.error);
                                                
                                                Swal.fire(
                                                    'Terjadi Kesalahan!',
                                                    'Periksa kembali data yang anda masukkan',
                                                    'error'
                                                )
                                                // console.log(response.error)
                                            }
                                        }
                                    })
                                } 
                            })
                        })

                        const printErrorMsg = (msg) => {
                            $.each(msg, function(key, value) {
                                $('.' + key + '-error').text(value);
                            });
                        }
                    } else{
                        $('#form-konfirmasi').addClass('d-none');
                        $('#col-btn-validasi').addClass('d-none');
                        $('#status-konfirmasi').removeClass('d-none');
                    }

                    $('#konfirmasi').change(function(){
                        if($('#konfirmasi').val() == 1){
                            $('#col-alasan').addClass('d-none');
                        } else{
                            $('#col-alasan').removeClass('d-none');
                            $('#alasan').val('');
                        }
                    })
                    
                    
                },
            })
            // console.log(keluarga + ' ' + anggotaKeluarga);
        }

        function modalLihat(anggotaKeluarga, keluarga){
            $.ajax({
                type: "GET",
                url: "{{url('anggota-keluarga')}}" + '/' + keluarga + '/' + anggotaKeluarga,
                success: function (data) {
                    $('#modal-lihat').modal('show');
                    $('#modal-created-at2').html(moment(data.created_at).format('LL'));
                    if(data.updated_at != null){
                        $('#modal-updated-at2').html(moment(data.updated_at).format('LL'));
                    } else{
                        $('#modal-updated-at2').html(moment(data.created_at).format('LL'));
                    }
                    $('#modal-domisili2').html(data.desa_kelurahan_domisili+', '+data.kecamatan_domisili+', '+data.kabupaten_kota_domisili+', '+data.provinsi_domisili)
                    $('#modal-nama-lengkap2').html(data.nama_lengkap);
                    $('#modal-jenis-kelamin2').html(data.jenis_kelamin);
                    $('#modal-nik2').html(data.nik);
                    $('#modal-tempat-lahir2').html(data.tempat_lahir);
                    $('#modal-tanggal-lahir2').html(moment(data.tanggal_lahir).format('LL'));
                    $('#modal-agama2').html(data.agama_);
                    $('#modal-pendidikan2').html(data.pendidikan_);
                    $('#modal-jenis-pekerjaan2').html(data.pekerjaan_);
                    $('#modal-golongan-darah2').html(data.golongan_darah_);
                    $('#modal-status-perkawinan2').html(data.status_perkawinan_);
                    if(data.tanggal_perkawinan != null){
                        $('#modal-tanggal-perkawinan2').html(moment(data.tanggal_perkawinan).format('LL'));
                    } else{
                        $('#modal-tanggal-perkawinan2').html('-');
                    }
                    $('#modal-kewarganegaraan2').html(data.kewarganegaraan);
                    $('#modal-nomor-paspor2').html(data.no_paspor);
                    $('#modal-nomor-kitap2').html(data.no_kitap);
                    $('#modal-nama-ayah2').html(data.nama_ayah);
                    $('#modal-nama-ibu2').html(data.nama_ibu);

                    $('#modal-alamat-domisili2').html(data.alamat_domisili);
                    $('#modal-desa-kelurahan-domisili2').html(data.desa_kelurahan_domisili);
                    $('#modal-kecamatan-domisili2').html(data.kecamatan_domisili);
                    $('#modal-kabupaten-kota-domisili2').html(data.kabupaten_kota_domisili);
                    $('#modal-provinsi-domisili2').html(data.provinsi_domisili);

                    if(data.is_valid == 1){
                        $('#modal-status-konfirmasi2').html('Valid');
                    } else{
                        $('#modal-status-konfirmasi2').html('Tidak Valid');
                    }

                    $('#modal-tanggal-konfirmasi2').html(moment(data.tanggal_validasi).format('LL'));
                    $('#modal-oleh-bidan2').html(data.nama_bidan)
                    
                    if(data.desa_kelurahan_nama != data.desa_kelurahan_domisili){
                        $('#col-sk-domisili').removeClass('d-none');
                        if(data.surat_keterangan_domisili){
                            $('#modal-file-surat-keterangan-domisili2').addClass('d-none')
                            $('#file-surat-keterangan-domisili2').removeClass('d-none');
                            $('#file-surat-keterangan-domisili2').attr('href', '{{ asset("upload/surat_keterangan_domisili") }}/'+data.surat_keterangan_domisili);
                        } else{
                            $('#modal-file-surat-keterangan-domisili2').removeClass('d-none')
                            $('#file-surat-keterangan-domisili2').addClass('d-none')
                        }
                    } else{
                        $('#col-sk-domisili').addClass('d-none');
                    }

                    if(data.foto_profil != null){
                        $('#modal-foto-profil2').html('<div class="image-input shadow avatar xxl rounded-4" style="background-image: url(../upload/foto_profil/keluarga/'+data.foto_profil+')">')
                    } else{
                        $('#modal-foto-profil2').html('<span class="badge bg-info text-uppercase shadow">Tidak Ada</span>')
                    }

                    $('#modal-nomor-hp2').html(data.nomor_hp);

                    if(data.is_valid == 1){
                        $('#modal-status-konfirmasi2').html('Valid');
                    } else{
                        $('#modal-status-konfirmasi2').html('Tidak Valid');
                    }

                    $('#modal-tanggal-konfirmasi2').html(moment(data.tanggal_validasi).format('LL'));

                    if(data.nama_bidan != null){
                        $('#modal-oleh-bidan2').html(data.nama_bidan);
                    } else{
                        $('#modal-oleh-bidan2').html('-');
                    }

                    if(data.is_valid == 1){
                        $('#col-modal-btn-ubah').removeClass('d-none');
                        $('#modal-btn-ubah').attr('href', '{{url('anggota-keluarga')}}' + '/' + data.kartu_keluarga_id + '/' + data.id + '/edit');
                    }
                    
                    
                },
            })
            // console.log(keluarga + ' ' + anggotaKeluarga);
        }
    </script>
@endpush