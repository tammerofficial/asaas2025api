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
        .sample-download-card {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .sample-download-card h4 {
            color: #1976d2;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .sample-download-card p {
            color: #424242;
            margin-bottom: 15px;
        }
        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .required-fields-card {
            background: #fff9e6;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .required-fields-card h5 {
            color: #f57c00;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .required-fields-card ul {
            margin: 0;
            padding-left: 20px;
        }
        .required-fields-card li {
            color: #616161;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .upload-area {
            border: 2px dashed #bdbdbd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            background: #f9f9f9;
        }
        .upload-area:hover, .upload-area.dragover {
            border-color: #2196f3;
            background: #e3f2fd;
        }
        .upload-area i {
            font-size: 48px;
            color: #bdbdbd;
            margin-bottom: 10px;
        }
        .file-selected {
            background: #e8f5e9;
            border: 1px solid #4caf50;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .file-error {
            background: #ffebee;
            border: 1px solid #ef5350;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }
    </style>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="mb-4">{{__('Import Products from CSV')}}</h2>

                        @if(isset($importResult))
                            @if($importResult['status'] == 'success')
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ $importResult['message'] }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @elseif($importResult['status'] == 'failed')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $importResult['message'] }}
                                    @if(!empty($importResult['reasons']))
                                        <ul class="mt-2 mb-0">
                                            @foreach($importResult['reasons'] as $reason)
                                                <li>{{ $reason }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @elseif($importResult['status'] == 'error')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $importResult['message'] }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Sample Download Section -->
                        <div class="sample-download-card">
                            <div class="d-flex align-items-start">
                                <i class="las la-file-csv" style="font-size: 32px; color: #1976d2; margin-right: 15px;"></i>
                                <div class="flex-grow-1">
                                    <h4>{{__('Need help with CSV format?')}}</h4>
                                    <p class="mb-3">
                                        {{__('Download a sample CSV with example data from your existing products, or download an empty template to get started.')}}
                                    </p>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ route('tenant.products.download.sample') }}"
                                           class="btn btn-primary btn-download">
                                            <i class="las la-download"></i>
                                            {{__('Download Sample CSV')}}
                                        </a>
                                        <a href="{{ route('tenant.products.download.empty') }}"
                                           class="btn btn-outline-primary btn-download">
                                            <i class="las la-download"></i>
                                            {{__('Download Empty Template')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Required Fields Info -->
                        <div class="required-fields-card">
                            <h5><i class="las la-exclamation-triangle"></i> {{__('Required Fields')}}</h5>
                            <ul>
                                <li><strong>name</strong> - {{__('Product name (required)')}}</li>
                                <li><strong>slug</strong> - {{__('URL friendly name (required, must be unique)')}}</li>
                                <li><strong>summary</strong> - {{__('Short product summary (optional)')}}</li>
                                <li><strong>description</strong> - {{__('Detailed product description (required, min 10 characters)')}}</li>
                                <li><strong>status_id</strong> - {{__('Product status ID (1 = active, 2 = inactive)')}}</li>
                                <li><strong>sale_price</strong> - {{__('Sale price (required)')}}</li>
                                <li><strong>sku</strong> - {{__('Stock keeping unit (required, must be unique)')}}</li>
                                <li><strong>stock_count</strong> - {{__('Available stock quantity (required)')}}</li>
                                <li><strong>uom</strong> - {{__('Unit of measurement (required)')}}</li>
                            </ul>
                            <small class="text-muted mt-2 d-block">
                                <strong>{{__('Note:')}}</strong> {{__('The sample CSV includes all fields. Optional fields can be left empty.')}}
                            </small>
                        </div>

                        <!-- Upload Form -->
                        <form action="{{ route('tenant.products.import.preview') }}" method="POST" enctype="multipart/form-data" id="csvUploadForm">
                            @csrf
                            <div class="form-group">
                                <label for="csv_file" class="font-weight-bold">{{__('Upload CSV')}}</label>
                                <div class="" id="uploadArea">
{{--                                    <i class="las la-cloud-upload-alt"></i>--}}
{{--                                    <p class="mb-1"><strong>{{__('Click to upload')}}</strong> {{__('or drag and drop')}}</p>--}}
{{--                                    <small class="text-muted">{{__('CSV files only (.csv, .txt)')}}</small>--}}
                                    <input type="file" name="csv_file" id="csv_file" class="upload-area form--control"  required accept=".csv,.txt">
                                </div>
                                <!-- File selected indicator -->
                                <div id="fileSelected" class="file-selected" style="display: none;">
                                    <i class="las la-check-circle" style="color: #4caf50; font-size: 20px;"></i>
                                    <strong>{{__('File selected:')}}</strong> <span id="fileName"></span>
                                </div>
                                <!-- Error message for invalid file types -->
                                <div id="fileError" class="file-error" style="display: none;">
                                    {{__('Please upload a valid CSV file (.csv or .txt).')}}
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg mt-3" id="submitBtn">
                                <i class="las la-eye"></i>
                                {{__('Preview & Map Fields')}}
                                <span class="spinner-border spinner-border-sm d-none" id="submitSpinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('csv_file');
            const fileSelected = document.getElementById('fileSelected');
            const fileNameDisplay = document.getElementById('fileName');
            const fileError = document.getElementById('fileError');
            const submitBtn = document.getElementById('submitBtn');
            const submitSpinner = document.getElementById('submitSpinner');

            // Show selected file name
            function showFileName(input) {
                fileError.style.display = 'none';
                const file = input.files[0];
                if (file) {
                    const validTypes = ['text/csv', 'text/plain', 'application/csv'];
                    if (!validTypes.includes(file.type) && !file.name.match(/\.(csv|txt)$/i)) {
                        fileError.style.display = 'block';
                        fileInput.value = '';
                        fileSelected.style.display = 'none';
                        return;
                    }
                    fileNameDisplay.textContent = file.name;
                    fileSelected.style.display = 'flex';
                } else {
                    fileSelected.style.display = 'none';
                }
            }

            // Handle click to trigger file input
            uploadArea.addEventListener('click', () => {
                fileInput.click();
            });

            // Handle file input change
            fileInput.addEventListener('change', () => {
                showFileName(fileInput);
            });

            // Drag-and-drop functionality
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, () => {
                    uploadArea.classList.add('dragover');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, () => {
                    uploadArea.classList.remove('dragover');
                }, false);
            });

            uploadArea.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;
                showFileName(fileInput);
            }, false);

            // Show spinner on form submit
            document.getElementById('csvUploadForm').addEventListener('submit', () => {
                submitBtn.disabled = true;
                submitSpinner.classList.remove('d-none');
            });
        });
    </script>
@endsection
