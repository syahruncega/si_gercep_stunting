<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\LokasiTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiLokasiTugasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 20;
        $filterBy = $request->filter_by;

        if ($filterBy) {
            return LokasiTugas::with('desaKelurahan')
                ->where('jenis_profil', $filterBy)
                ->groupBy('desa_kelurahan_id')
                ->get();
        }

        return LokasiTugas::paginate($pageSize);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reqBody = json_decode($request->getContent());
        if (is_array($reqBody) && sizeof($reqBody) > 0) {
            // $rules = [
            //     'required', function ($attribute, $value, $fail) {
            //         if (!DB::table('bidan')->where('id', $value)->exists() && !DB::table('penyuluh')->where('id', $value)->exists()) {
            //             return $fail("The provided $attribute is not valid.");
            //         }
            //     }
            // ];
            error_log("sini");
            $request->validate([
                "*.jenis_profil" => "required|in:bidan,penyuluh",
                "*.profil_id" => "required",
                "*.desa_kelurahan_id" => "required|exists:desa_kelurahan,id",
                "*.kecamatan_id" => "required|exists:kecamatan,id",
                "*.kabupaten_kota_id" => "required|exists:kabupaten_kota,id",
                "*.provinsi_id" => "required|exists:provinsi,id",
            ]);


            return LokasiTugas::insert($request->all());
        } else {
            error_log("situ");
            $request->validate([
                "jenis_profil" => "required|in:bidan,penyuluh",
                "profil_id" => "required",
                "desa_kelurahan_id" => "required|exists:desa_kelurahan,id",
                "kecamatan_id" => "required|exists:kecamatan,id",
                "kabupaten_kota_id" => "required|exists:kabupaten_kota,id",
                "provinsi_id" => "required|exists:provinsi,id",
            ]);

            return LokasiTugas::create($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return LokasiTugas::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "jenis_profil" => "in:bidan,penyuluh",
            "profil_id" => "exists:bidan,id",
            "desa_kelurahan_id" => "exists:desa_kelurahan,id",
            "kecamatan_id" => "exists:kecamatan,id",
            "kabupaten_kota_id" => "exists:kabupaten_kota,id",
            "provinsi_id" => "exists:provinsi,id",
        ]);
        $lokasiTugas = LokasiTugas::find($id);
        $lokasiTugas->update($request->all());
        return $lokasiTugas;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $profilId = $request->profil_id;
        $deleteLokasiTugas = false;

        if ($id) {
            $deleteLokasiTugas = LokasiTugas::destroy($id);
        } else if ($profilId) {
            $deleteLokasiTugas = LokasiTugas::where('profil_id', $profilId)->delete();
        }

        if ($deleteLokasiTugas) {
            return response([
                'message' => 'Data deleted.'
            ], 200);
        }

        return response([
            'message' => 'failed to delete data.'
        ], 500);
    }
}