@extends('tenant.admin.admin-master')
@section('title')
    {{__('Import Product')}}
@endsection
@section('site-title')
    {{__('Import Product')}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/tenant/backend/css/bootstrap-taginput.css')}}">
    <x-media-upload.css/>
    <x-summernote.css/>
    <style>
        .required-field {
            background-color: #fff3cd;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>CSV Field Mapping</h3>
                        <p class="text-muted mb-0">Map your CSV columns to database fields. <span class="text-danger">*Required fields</span></p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tenant.products.import.post') }}" method="POST">
                            @csrf
                            <input type="hidden" name="csv_file" value="{{ $filePath }}">

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                    <tr>
                                        <th width="40%">Database Field</th>
                                        <th width="60%">CSV Column</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($dbFields as $dbField => $label)
                                        <tr class="{{ $dbField == 'name' ? 'required-field' : '' }}">
                                            <td>
                                                <strong>{{ $label }}</strong>
                                                @if($dbField == 'name')
                                                    <span class="text-danger">*</span>
                                                @endif
                                                <br>
                                                <small class="text-muted">{{ $dbField }}</small>
                                            </td>
                                            <td>
                                                <select name="mapping[{{ $dbField }}]" class="form-select">
                                                    <option value="">-- Skip this field --</option>
                                                    @foreach($header as $col)
                                                        <option value="{{ $col }}"
                                                            {{ strtolower(str_replace(' ', '_', $col)) == $dbField ? 'selected' : '' }}>
                                                            {{ $col }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

{{--                            <hr class="my-4">--}}

{{--                            <h4 class="mb-3">CSV Preview (First 5 Rows)</h4>--}}
{{--                            <div class="table-responsive">--}}
{{--                                <table class="table table-striped table-sm table-bordered">--}}
{{--                                    <thead class="table-dark">--}}
{{--                                    <tr>--}}
{{--                                        <th>#</th>--}}
{{--                                        @foreach($header as $col)--}}
{{--                                            <th>{{ $col }}</th>--}}
{{--                                        @endforeach--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    @foreach($rows as $index => $row)--}}
{{--                                        <tr>--}}
{{--                                            <td>{{ $index + 1 }}</td>--}}
{{--                                            @foreach($row as $col)--}}
{{--                                                <td>{{ Str::limit($col, 50) }}</td>--}}
{{--                                            @endforeach--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-upload"></i> Import Products Now
                                </button>
                                <a href="{{ route('tenant.products.import') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
