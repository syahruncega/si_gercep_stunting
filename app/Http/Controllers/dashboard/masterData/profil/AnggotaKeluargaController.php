<?php

namespace App\Http\Controllers\dashboard\masterData\profil;

use Carbon\Carbon;
use App\Models\Agama;
use App\Models\Bidan;
use App\Models\Provinsi;
use App\Models\Kecamatan;
use App\Models\Pekerjaan;
use App\Models\Pendidikan;
use Illuminate\Http\Request;
use App\Models\DesaKelurahan;
use App\Models\GolonganDarah;
use App\Models\KabupatenKota;
use App\Models\KartuKeluarga;
use App\Models\Pemberitahuan;
use App\Models\StatusHubungan;
use App\Models\AnggotaKeluarga;
use App\Models\WilayahDomisili;
use App\Models\StatusPerkawinan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreAnggotaKeluargaRequest;
use App\Http\Requests\UpdateAnggotaKeluargaRequest;

class AnggotaKeluargaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->role == 'keluarga'){
            $idKeluarga = \Request::segment(2);
            if($idKeluarga != Auth::user()->profil->kartu_keluarga_id){
                abort(404);
            }
        }

        if(Auth::user()->role != 'keluarga'){
            $idKeluarga = \Request::segment(2);
            $keluarga = KartuKeluarga::findOrFail($idKeluarga);
            if($keluarga->is_valid == 0){
                abort(404);
            }
            
            if ($request->ajax()) {
                $data = AnggotaKeluarga::with('statusHubunganDalamKeluarga')->where('kartu_keluarga_id', $request->keluarga)->orderBy('status_hubungan_dalam_keluarga_id', 'asc');
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('status_hubungan_dalam_keluarga', function ($row) {     
                            return $row->statusHubunganDalamKeluarga->status_hubungan;
                    })
                    ->addColumn('status', function ($row) {   
                        if($row->is_valid == 1){
                            return '<span class="badge rounded bg-success">Tervalidasi</span>';
                        } else if ($row->is_valid == 0){
                            return '<span class="badge rounded bg-primary">Belum Divalidasi</span>';
                        } else{
                            return '<span class="badge rounded bg-danger">Ditolak</span>';
                        }
                    })
    
                    ->addColumn('action', function ($data) {     
                        $actionBtn = '
                        <div class="text-center justify-content-center text-white">';
                        if($data->is_valid == 0){
                            $actionBtn .= '<button class="btn btn-primary btn-sm me-1 shadow" data-toggle="tooltip" data-placement="top" title="Konfirmasi" onclick=modalValidasi('.$data->id.','.$data->kartu_keluarga_id.')><i class="fa-solid fa-lg fa-clipboard-check"></i></button> ';
                        } else{
                            $actionBtn .= '<button class="btn btn-info btn-sm mr-1 my-1 text-white shadow" data-toggle="tooltip" data-placement="top" title="Lihat" onclick=modalLihat('.$data->id.','.$data->kartu_keluarga_id.')><i class="fas fa-eye"></i></button>';
                            $actionBtn .= '
                                <a href="'.url('anggota-keluarga/' . $data->kartu_keluarga_id .'/'.$data->id).'/edit" id="btn-edit" class="btn btn-warning btn-sm mr-1 my-1 text-white shadow" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fas fa-edit"></i></a>';
                            if($data->status_hubungan_dalam_keluarga_id != 1){
                                $actionBtn .= '
                                    <button id="btn-delete" onclick="hapus(' . $data->id . ', '. $data->kartuKeluarga->id .')" class="btn btn-danger btn-sm mr-1 my-1 shadow" value="' . $data->id . '" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fas fa-trash"></i></button>
                                </div>';
                            }
                        }
                        return $actionBtn;
                    })
                    ->rawColumns(['action', 'status', 'status_hubungan_dalam_keluarga'])
                    ->make(true);
            }
    
            $kartuKeluarga = KartuKeluarga::find($request->keluarga);
            return view('dashboard.pages.masterData.profil.keluarga.anggotaKeluarga.index', compact('kartuKeluarga'));
        } else{
            $data = [
                'keluarga' => AnggotaKeluarga::with('statusHubunganDalamKeluarga')
                                ->where('kartu_keluarga_id', Auth::user()->profil->kartu_keluarga_id)
                                ->orderBy('status_hubungan_dalam_keluarga_id', 'ASC')
                                ->get(),
            ];
            return view('dashboard.pages.utama.anggotaKeluarga.index', $data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role == 'keluarga'){
            $idKeluarga = \Request::segment(2);
            if($idKeluarga != Auth::user()->profil->kartu_keluarga_id){
                abort(404);
            }
        }

        $idKeluarga = \Request::segment(2);
        $keluarga = KartuKeluarga::find($idKeluarga);
        $data = [
            'kartu_keluarga_id' => $idKeluarga,
            'agama' => Agama::all(),
            'pendidikan' => Pendidikan::all(),
            'pekerjaan' => Pekerjaan::all(),
            'golonganDarah' => GolonganDarah::all(),
            'statusPerkawinan' => StatusPerkawinan::all(),
            'provinsi' => Provinsi::all(),
            'statusHubungan' => StatusHubungan::all()->skip(1),
            'provinsiKK' => $keluarga->provinsi_id,
            'kabupatenKotaKK' => $keluarga->kabupaten_kota_id,
            'kecamatanKK' => $keluarga->kecamatan_id,
            'desaKelurahanKK' => $keluarga->desa_kelurahan_id,
            'alamatKK' => $keluarga->alamat,

            
           
        ];
        return view('dashboard.pages.masterData.profil.keluarga.anggotaKeluarga.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAnggotaKeluargaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::user()->role != 'keluarga'){
            $idKeluarga = \Request::segment(2);
        } else{
            $idKeluarga = Auth::user()->profil->kartu_keluarga_id;
        }

        if($request->status_perkawinan != 1){
            $tanggal_perkawinan_req = 'required';
        } else{
            $tanggal_perkawinan_req = '';
        }

        $validator = Validator::make(
            $request->all(),
            [
                'nama_lengkap' => 'required',
                'nik' => 'required|unique:anggota_keluarga,nik,NULL,id,deleted_at,NULL|digits:16',
                'jenis_kelamin' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'agama' => 'required',
                'pendidikan' => 'required',
                'pekerjaan' => 'required',
                'golongan_darah' => 'required',
                'status_perkawinan' => 'required',
                'tanggal_perkawinan' => $tanggal_perkawinan_req,
                'status_hubungan' => 'required',
                'kewarganegaraan' => 'required',
                'nomor_paspor' => 'required',
                'nomor_kitap' => 'required',
                'ayah' => 'required',
                'ibu' => 'required',
                'foto_profil' => 'mimes:jpeg,jpg,png|max:3072',

                'alamat_domisili' => 'required',
                'provinsi_domisili' => 'required',
                'kabupaten_kota_domisili' => 'required',
                'kecamatan_domisili' => 'required',
                'desa_kelurahan_domisili' => 'required',
                'file_domisili' => 'mimes:jpeg,jpg,png,pdf|max:3072',
            ],
            [
                'nama_lengkap.required' => 'Nama Lengkap tidak boleh kosong',
                'nik.required' => 'NIK tidak boleh kosong',
                'nik.unique' => 'NIK sudah terdaftar',
                'nik.digits' => 'NIK harus 16 digit',
                'jenis_kelamin.required' => 'Jenis Kelamin tidak boleh kosong',
                'tempat_lahir.required' => 'Tempat Lahir tidak boleh kosong',
                'tanggal_lahir.required' => 'Tanggal Lahir tidak boleh kosong',
                'agama.required' => 'Agama tidak boleh kosong',
                'pendidikan.required' => 'Pendidikan tidak boleh kosong',
                'pekerjaan.required' => 'Pekerjaan tidak boleh kosong',
                'golongan_darah.required' => 'Golongan Darah tidak boleh kosong',
                'status_perkawinan.required' => 'Status Perkawinan tidak boleh kosong',
                'tanggal_perkawinan.required' => 'Tanggal Perkawinan tidak boleh kosong',
                'status_hubungan.required' => 'Status Hubungan tidak boleh kosong',
                'kewarganegaraan.required' => 'Kewarganegaraan tidak boleh kosong',
                'nomor_paspor.required' => 'Nomor Paspor tidak boleh kosong',
                'nomor_kitap.required' => 'Nomor KITAP tidak boleh kosong',
                'ayah.required' => 'Nama Ayah tidak boleh kosong',
                'ibu.required' => 'Nama Ibu tidak boleh kosong',
                'foto_profil.mimes' => 'Foto Profil harus berupa file jpeg, jpg, png',
                'foto_profil.max' => 'Foto Profil tidak boleh lebih dari 3 MB',

                'alamat_domisili.required' => 'Alamat Domisili tidak boleh kosong',
                'provinsi_domisili.required' => 'Provinsi Domisili tidak boleh kosong',
                'kabupaten_kota_domisili.required' => 'Kabupaten/Kota Domisili tidak boleh kosong',
                'kecamatan_domisili.required' => 'Kecamatan Domisili tidak boleh kosong',
                'desa_kelurahan_domisili.required' => 'Desa/Kelurahan Domisili tidak boleh kosong',
                'file_domisili.mimes' => 'File Surat Keterangan Domisili harus berupa file jpeg, jpg, png, pdf',
                'file_domisili.max' => 'File Surat Keterangan Domisili tidak boleh lebih dari 3 MB',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        
        if(Auth::user()->role == 'admin'){
            $bidan_id = $request->nama_bidan;
        } else if (Auth::user()->role == 'bidan') {
            $bidan_id = Auth::user()->profil->id;  
        } else if (Auth::user()->role == 'keluarga'){
            $bidan_id = null;
        }

        $dataAnggotaKeluarga = [
            'kartu_keluarga_id' => $idKeluarga,
            'bidan_id' => $bidan_id,
            'nama_lengkap' => strtoupper($request->nama_lengkap),
            'nik' => $request->nik,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => strtoupper($request->tempat_lahir),
            'tanggal_lahir' => date("Y-m-d", strtotime($request->tanggal_lahir)),
            'agama_id' => $request->agama,
            'pendidikan_id' => $request->pendidikan,
            'jenis_pekerjaan_id' => $request->pekerjaan,
            'golongan_darah_id' => $request->golongan_darah,
            'status_perkawinan_id' => $request->status_perkawinan,
            'status_hubungan_dalam_keluarga_id' => $request->status_hubungan,
            'kewarganegaraan' => $request->kewarganegaraan,
            'no_paspor' => $request->nomor_paspor,
            'no_kitap' => $request->nomor_kitap,
            'nama_ayah' => strtoupper($request->ayah),
            'nama_ibu' => strtoupper($request->ibu),
        ];

        

        if(Auth::user()->role != 'keluarga'){
            $dataAnggotaKeluarga['is_valid'] = 1;
            $dataAnggotaKeluarga['tanggal_validasi'] = Carbon::now();
        } else{
            $dataAnggotaKeluarga['is_valid'] = 0;
            $dataAnggotaKeluarga['tanggal_validasi'] = null;
        }

        if($request->status_perkawinan != 1){
            $dataAnggotaKeluarga['tanggal_perkawinan'] = date("Y-m-d", strtotime($request->tanggal_perkawinan));
        } 

        if($request->file('foto_profil')) {
            $request->file('foto_profil')->storeAs(
                'upload/foto_profil/keluarga/', $request->nik. '.'. $request->file('foto_profil')->extension()
            );
            $dataAnggotaKeluarga['foto_profil'] = $request->nik. '.'. $request->file('foto_profil')->extension();
        }

        AnggotaKeluarga::create($dataAnggotaKeluarga);

        $dataWilayahDomisili = [
            'anggota_keluarga_id' => AnggotaKeluarga::max('id'),
            'alamat' => strtoupper($request->alamat_domisili),
            'desa_kelurahan_id' => $request->desa_kelurahan_domisili,
            'kecamatan_id' => $request->kecamatan_domisili,
            'kabupaten_kota_id' => $request->kabupaten_kota_domisili,
            'provinsi_id' => $request->provinsi_domisili,

        ];
        
        if($request->file('file_domisili')) {
            $request->file('file_domisili')->storeAs(
                'upload/surat_keterangan_domisili/', $request->nik. '.'. $request->file('file_domisili')->extension()
            );
            $dataWilayahDomisili['file_ket_domisili'] = $request->nik. '.'. $request->file('file_domisili')->extension();
        }

        WilayahDomisili::create($dataWilayahDomisili);

        return response()->json(['success' => 'Berhasil', 'mes' => 'Anggota Keluarga berhasil ditambahkan.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AnggotaKeluarga  $anggotaKeluarga
     * @return \Illuminate\Http\Response
     */
    public function show(KartuKeluarga $keluarga, AnggotaKeluarga $anggotaKeluarga)
    {
        $anggotaKeluarga['kartu_keluarga_id'] = $keluarga->id;
        $anggotaKeluarga['desa_kelurahan_nama'] = $anggotaKeluarga->kartuKeluarga->desaKelurahan->nama;

        $anggotaKeluarga['agama_'] = $anggotaKeluarga->agama->agama;
        $anggotaKeluarga['pendidikan_'] = $anggotaKeluarga->pendidikan->pendidikan;
        $anggotaKeluarga['pekerjaan_'] = $anggotaKeluarga->pekerjaan->pekerjaan;
        $anggotaKeluarga['golongan_darah_'] = $anggotaKeluarga->golonganDarah->golongan_darah;
        $anggotaKeluarga['status_perkawinan_'] = $anggotaKeluarga->statusPerkawinan->status_perkawinan;
        $anggotaKeluarga['tanggal_perkawinan'] = $anggotaKeluarga->tanggal_perkawinan;
       
        $anggotaKeluarga['alamat_domisili'] = $anggotaKeluarga->wilayahDomisili->alamat;
        $anggotaKeluarga['desa_kelurahan_domisili'] = $anggotaKeluarga->wilayahDomisili->desaKelurahan->nama;
        $anggotaKeluarga['kecamatan_domisili'] = $anggotaKeluarga->wilayahDomisili->kecamatan->nama;
        $anggotaKeluarga['kabupaten_kota_domisili'] = $anggotaKeluarga->wilayahDomisili->kabupatenKota->nama;
        $anggotaKeluarga['provinsi_domisili'] = $anggotaKeluarga->wilayahDomisili->provinsi->nama;
        // $anggotaKeluarga['nomor_hp'] = $anggotaKeluarga->kartuKeluarga->kepalaKeluarga->user->nomor_hp;

        if($anggotaKeluarga->user){
            $anggotaKeluarga['nomor_hp'] = $anggotaKeluarga->user->nomor_hp;
        } else{
            $anggotaKeluarga['nomor_hp'] = '-';
        } 
        $anggotaKeluarga['surat_keterangan_domisili'] = $anggotaKeluarga->wilayahDomisili->file_ket_domisili;
        if($anggotaKeluarga->bidan){
            $anggotaKeluarga['nama_bidan'] = $anggotaKeluarga->bidan->nama_lengkap;
        } else {
            $anggotaKeluarga['nama_bidan'] = '-';
        }
        $anggotaKeluarga['foto_profil'] = $anggotaKeluarga->foto_profil;
        $anggotaKeluarga['bidan_konfirmasi'] = $anggotaKeluarga->getBidan($anggotaKeluarga->id);

        return $anggotaKeluarga;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AnggotaKeluarga  $anggotaKeluarga
     * @return \Illuminate\Http\Response
     */
    public function edit(KartuKeluarga $keluarga, AnggotaKeluarga $anggotaKeluarga)
    {
        if(Auth::user()->role == 'keluarga'){
            $idKeluarga = \Request::segment(2);
            if($idKeluarga != Auth::user()->profil->kartu_keluarga_id){
                abort(404);
            }
        }

        $data = [
            'keluarga' => $keluarga,
            'anggotaKeluarga' => $anggotaKeluarga,
            'agama' => Agama::all(),
            'pendidikan' => Pendidikan::all(),
            'pekerjaan' => Pekerjaan::all(),
            'golonganDarah' => GolonganDarah::all(),
            'statusPerkawinan' => StatusPerkawinan::all(),
            'provinsi' => Provinsi::all(),
            'kabupatenKotaDomisili' => KabupatenKota::where('provinsi_id', $anggotaKeluarga->wilayahDomisili->provinsi_id)->get(),
            'kecamatanDomisili' => Kecamatan::where('kabupaten_kota_id', $anggotaKeluarga->wilayahDomisili->kabupaten_kota_id)->get(),
            'desaKelurahanDomisili' => DesaKelurahan::where('kecamatan_id', $anggotaKeluarga->wilayahDomisili->kecamatan_id)->get(),
            'provinsiKK' => $keluarga->provinsi_id,
            'kabupatenKotaKK' => $keluarga->kabupaten_kota_id,
            'kecamatanKK' => $keluarga->kecamatan_id,
            'desaKelurahanKK' => $keluarga->desa_kelurahan_id,
            'alamatKK' => $keluarga->alamat,
        ];
        if($anggotaKeluarga->status_hubungan_dalam_keluarga_id == 1){
            $data['statusHubungan'] = StatusHubungan::all();
        } else {
            $data['statusHubungan'] = StatusHubungan::all()->skip(1);
        }
        // dd($keluarga->desa_kelurahan_id);
        return view('dashboard.pages.masterData.profil.keluarga.anggotaKeluarga.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAnggotaKeluargaRequest  $request
     * @param  \App\Models\AnggotaKeluarga  $anggotaKeluarga
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KartuKeluarga $keluarga, AnggotaKeluarga $anggotaKeluarga)
    {
        if($request->status_perkawinan != 1){
            $tanggal_perkawinan_req = 'required';
        } else{
            $tanggal_perkawinan_req = '';
        }

        if($anggotaKeluarga->status_hubungan_dalam_keluarga_id != 1){
            $status_hubungan_dalam_keluarga_req = 'required';
            $status_hubungan_dalam_keluarga_val = $request->status_hubungan;
        } else{
            $status_hubungan_dalam_keluarga_req = '';
            $status_hubungan_dalam_keluarga_val = 1;
        }

        
        $validator = Validator::make(
            $request->all(),
            [
                'nama_lengkap' => 'required',
                'nik' => 'required|unique:anggota_keluarga,nik,'. $anggotaKeluarga->nik .',nik,deleted_at,NULL|digits:16',
                'jenis_kelamin' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'agama' => 'required',
                'pendidikan' => 'required',
                'pekerjaan' => 'required',
                'golongan_darah' => 'required',
                'status_perkawinan' => 'required',
                'tanggal_perkawinan' => $tanggal_perkawinan_req,
                'status_hubungan' => $status_hubungan_dalam_keluarga_req,
                'kewarganegaraan' => 'required',
                'nomor_paspor' => 'required',
                'nomor_kitap' => 'required',
                'ayah' => 'required',
                'ibu' => 'required',
                'foto_profil' => 'mimes:jpeg,jpg,png|max:3072',
                'alamat_domisili' => 'required',
                'provinsi_domisili' => 'required',
                'kabupaten_kota_domisili' => 'required',
                'kecamatan_domisili' => 'required',
                'desa_kelurahan_domisili' => 'required',
                'file_domisili' => 'mimes:jpeg,jpg,png,pdf|max:3072',
            ],
            [
                'nama_lengkap.required' => 'Nama Lengkap tidak boleh kosong',
                'nik.required' => 'NIK tidak boleh kosong',
                'nik.unique' => 'NIK sudah terdaftar',
                'nik.digits' => 'NIK harus 16 digit',
                'jenis_kelamin.required' => 'Jenis Kelamin tidak boleh kosong',
                'tempat_lahir.required' => 'Tempat Lahir tidak boleh kosong',
                'tanggal_lahir.required' => 'Tanggal Lahir tidak boleh kosong',
                'agama.required' => 'Agama tidak boleh kosong',
                'pendidikan.required' => 'Pendidikan tidak boleh kosong',
                'pekerjaan.required' => 'Pekerjaan tidak boleh kosong',
                'golongan_darah.required' => 'Golongan Darah tidak boleh kosong',
                'status_perkawinan.required' => 'Status Perkawinan tidak boleh kosong',
                'tanggal_perkawinan.required' => 'Tanggal Perkawinan tidak boleh kosong',
                'status_hubungan.required' => 'Status Hubungan tidak boleh kosong',
                'kewarganegaraan.required' => 'Kewarganegaraan tidak boleh kosong',
                'nomor_paspor.required' => 'Nomor Paspor tidak boleh kosong',
                'nomor_kitap.required' => 'Nomor KITAP tidak boleh kosong',
                'ayah.required' => 'Nama Ayah tidak boleh kosong',
                'ibu.required' => 'Nama Ibu tidak boleh kosong',
                'foto_profil.mimes' => 'Foto Profil harus berupa file jpeg, jpg, png',
                'foto_profil.max' => 'Foto Profil tidak boleh lebih dari 3 MB',
                'alamat_domisili.required' => 'Alamat Domisili tidak boleh kosong',
                'provinsi_domisili.required' => 'Provinsi Domisili tidak boleh kosong',
                'kabupaten_kota_domisili.required' => 'Kabupaten/Kota Domisili tidak boleh kosong',
                'kecamatan_domisili.required' => 'Kecamatan Domisili tidak boleh kosong',
                'desa_kelurahan_domisili.required' => 'Desa/Kelurahan Domisili tidak boleh kosong',
                'file_domisili.mimes' => 'File Surat Keterangan Domisili harus berupa file jpeg, jpg, png, pdf',
                'file_domisili.max' => 'File Surat Keterangan Domisili tidak boleh lebih dari 3 MB',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $data = [
            'nama_lengkap' => strtoupper($request->nama_lengkap),
            'nik' => $request->nik,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => strtoupper($request->tempat_lahir),
            'tanggal_lahir' => date("Y-m-d", strtotime($request->tanggal_lahir)),
            'agama_id' => $request->agama,
            'pendidikan_id' => $request->pendidikan,
            'jenis_pekerjaan_id' => $request->pekerjaan,
            'golongan_darah_id' => $request->golongan_darah,
            'status_perkawinan_id' => $request->status_perkawinan,
            'kewarganegaraan' => $request->kewarganegaraan,
            'no_paspor' => $request->nomor_paspor,
            'no_kitap' => $request->nomor_kitap,
            'nama_ayah' => strtoupper($request->ayah),
            'nama_ibu' => strtoupper($request->ibu),

            'alamat_domisili' => $request->alamat_domisili,
            'provinsi_domisili' => $request->provinsi_domisili,
            'kabupaten_kota_domisili' => $request->kabupaten_kota_domisili,
            'kecamatan_domisili' => $request->kecamatan_domisili,
            'desa_kelurahan_domisili' => $request->desa_kelurahan_domisili,
            'file_domisili' => $request->file_domisili,
        
        ];
        $data['status_hubungan_dalam_keluarga_id'] = $status_hubungan_dalam_keluarga_val;

        if($request->status_perkawinan != 1){
            $data['tanggal_perkawinan'] = date("Y-m-d", strtotime($request->tanggal_perkawinan));
        } else{
            $data['tanggal_perkawinan'] = null;
        }

        if($request->file('foto_profil')) {
            if (Storage::exists('upload/foto_profil/keluarga/' . $anggotaKeluarga->foto_profil)) {
                Storage::delete('upload/foto_profil/keluarga/' . $anggotaKeluarga->foto_profil);
            }
            $request->file('foto_profil')->storeAs(
                'upload/foto_profil/keluarga/', $request->nik. '.'. $request->file('foto_profil')->extension()
            );
            $data['foto_profil'] = $request->nik. '.'. $request->file('foto_profil')->extension();
        }

        if((Auth::user()->role == 'keluarga') && ($anggotaKeluarga->is_valid == 2)){
            $data['bidan_id'] = null;
            $data['is_valid'] = 0;
            $data['tanggal_validasi'] = null;
            $data['alasan_ditolak'] = null;
        } 

        $anggotaKeluarga->update($data);

        if($anggotaKeluarga->user){
            $anggotaKeluarga->user->update(['nik' => $request->nik]);
        }

        $dataWilayahDomisili = [
            'alamat' => strtoupper($request->alamat_domisili),
            'desa_kelurahan_id' => $request->desa_kelurahan_domisili,
            'kecamatan_id' => $request->kecamatan_domisili,
            'kabupaten_kota_id' => $request->kabupaten_kota_domisili,
            'provinsi_id' => $request->provinsi_domisili,
        ];
        
        if($request->file('file_domisili')) {
            if (Storage::exists('upload/surat_keterangan_domisili/' . $anggotaKeluarga->wilayahDomisili->file_ket_domisili)) {
                Storage::delete('upload/surat_keterangan_domisili/' . $anggotaKeluarga->wilayahDomisili->file_ket_domisili);
            }
            $request->file('file_domisili')->storeAs(
                'upload/surat_keterangan_domisili/', $request->nik. '.'. $request->file('file_domisili')->extension()
            );
            $dataWilayahDomisili['file_ket_domisili'] = $request->nik. '.'. $request->file('file_domisili')->extension();
        }

        $anggotaKeluarga->wilayahDomisili->update($dataWilayahDomisili);

        $pemberitahuan = Pemberitahuan::where('anggota_keluarga_id', $anggotaKeluarga->id)
            ->where('tentang', 'validasi_anggota_keluarga')
            ->where('is_valid', 2)
            ->first();
        
        if($pemberitahuan){
            $pemberitahuan->delete();
        }

        return response()->json(['success' => 'Berhasil', 'mes' => 'Data Anggota Keluarga berhasil perbarui.']);
    }

    public function validasi(Request $request)
    {
        $id = $request->id;

        if($request->konfirmasi == 1){
            $alasan_req = '';
            $alasan = null;
        } else{
            $alasan_req = 'required';
            $alasan = $request->alasan;
        }

        if(Auth::user()->role == 'admin'){
            $bidan_id_req = 'required';
            $bidan_id = $request->bidan_id;
        } else{
            $bidan_id_req = '';
            $bidan_id = Auth::user()->profil->id;
        }

        $validator = Validator::make(
            $request->all(),
            [
                'bidan_id' => $bidan_id_req,
                'konfirmasi' => 'required',
                'alasan' => $alasan_req,
            ],
            [
                'bidan_id.required' => 'Bidan harus diisi',
                'konfirmasi.required' => 'Konfirmasi harus diisi',
                'alasan.required' => 'Alasan harus diisi',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $anggotaKeluarga = AnggotaKeluarga::find($id);

        $updateAnggotaKeluarga = $anggotaKeluarga
        ->update(['is_valid' => $request->konfirmasi, 'bidan_id' => $bidan_id, 'tanggal_validasi' => Carbon::now(), 'alasan_ditolak' => $alasan]);

        $namaBidan = Bidan::where('id', $bidan_id)->first();
        if($request->konfirmasi == 1){
            $pemberitahuan = Pemberitahuan::create([
                'user_id' => $anggotaKeluarga->kartuKeluarga->kepalaKeluarga->user_id,
                'anggota_keluarga_id' => $anggotaKeluarga->id,
                'judul' => 'Selamat, data '. ucwords(strtolower($anggotaKeluarga->statusHubunganDalamKeluarga->status_hubungan)) . ' anda telah divalidasi',
                'isi' => 'Data '. ucwords(strtolower($anggotaKeluarga->statusHubunganDalamKeluarga->status_hubungan)) . ' anda ('. ucwords(strtolower($anggotaKeluarga->nama_lengkap)) .') divalidasi oleh bidan '. $namaBidan->nama_lengkap,
                'tentang' => 'validasi_anggota_keluarga',
                'is_valid' => 1,
            ]);
        } else{
            $pemberitahuan = Pemberitahuan::create([
                'user_id' => $anggotaKeluarga->kartuKeluarga->kepalaKeluarga->user_id,
                'anggota_keluarga_id' => $anggotaKeluarga->id,
                'judul' => 'Maaf, data '. ucwords(strtolower($anggotaKeluarga->statusHubunganDalamKeluarga->status_hubungan)) . ' anda ('. ucwords(strtolower($anggotaKeluarga->nama_lengkap)) .') ditolak',
                'isi' => 'Silahkan perbarui data untuk melihat alasan data anda ditolak dan mengirim ulang data. Terima Kasih.',
                'tentang' => 'validasi_anggota_keluarga',
                'is_valid' => 2,
            ]);
        }
           
        if($updateAnggotaKeluarga){
            $pemberitahuan;
            return response()->json(['res' => 'success', 'konfirmasi' => $request->konfirmasi]);
        } else{
            return response()->json(['res' => 'error']);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AnggotaKeluarga  $anggotaKeluarga
     * @return \Illuminate\Http\Response
     */
    public function destroy(KartuKeluarga $keluarga, AnggotaKeluarga $anggotaKeluarga)
    {
        if (Storage::exists('upload/foto_profil/keluarga/' . $anggotaKeluarga->foto_profil)) {
            Storage::delete('upload/foto_profil/keluarga/' . $anggotaKeluarga->foto_profil);
        }
      
        if (Storage::exists('upload/surat_keterangan_domisili/' . $anggotaKeluarga->wilayahDomisili->file_ket_domisili)) {
            Storage::delete('upload/surat_keterangan_domisili/' . $anggotaKeluarga->wilayahDomisili->file_ket_domisili);
        }

        $anggotaKeluarga->wilayahDomisili->delete();
        $anggotaKeluarga->delete();
        return response()->json(['res' => 'success']);

    }
}