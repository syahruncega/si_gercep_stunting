@extends('dashboard.layouts.main')

@section('title')
    Edit Perkiraan Melahirkan
@endsection

@push('style')
@endpush

@section('breadcrumb')
    <div class="col">
        <ol class="breadcrumb bg-transparent mb-0">
            <li class="breadcrumb-item"><a class="text-secondary" href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Moms Care</li>
            <li class="breadcrumb-item active" aria-current="page">Perkiraan Melahirkan</li>
            <li class="breadcrumb-item active" aria-current="page">Edit Perkiraan Melahirkan</li>
        </ol>
    </div>
@endsection

@section('content')
    <section>
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12">
                @if (isset($perkiraanMelahirkan) && $perkiraanMelahirkan->is_valid == 2)
                    <div role="alert" class="alert alert-danger mt-2">
                        <h6>Alasan data anda ditolak:</h6>
                        <p class="mb-0">{{ $perkiraanMelahirkan->alasan_ditolak }}</p>
                    </div>
                @endif
                <div class="card p-0">
                    {{-- <div class="card-header py-3 bg-transparent border-bottom-0">
                        <h6 class="card-title mb-0">Basic example</h6>
                    </div> --}}
                    <div class="card-body">
                        @component('dashboard.components.forms.utama.perkiraan_melahirkan')
                            @slot('kartuKeluarga', $kartuKeluarga)
                            @slot('form_id', 'form_perkiraan_melahirkan')
                            @slot('proses', url('proses-perkiraan-melahirkan'))
                            @slot('dataEdit', $perkiraanMelahirkan)
                            @slot('action', url('perkiraan-melahirkan/' . $perkiraanMelahirkan->id))
                            @slot('method', 'PUT')
                            @slot('back_url', url('perkiraan-melahirkan'))
                        @endcomponent

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        $('#m-link-moms-care').addClass('active');
        $('#menu-moms-care').addClass('collapse show')
        $('#ms-link-perkiraan-melahirkan').addClass('active')
    </script>
@endpush
