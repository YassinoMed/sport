@extends('layouts.user-layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Custom folders') }}</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-5">
                <div class="card card-primary card-outline shadow-sm settings-card">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-primary">
                            <i class="fas fa-upload me-2"></i> {{ __('Scan files') }}
                        </h3>
                    </div>
                    <form action="{{ route('settings.custom-folders.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body p-4">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <ul class="mb-0 ps-3">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group mb-4">
                                <label for="folder_name" class="form-label fw-bold text-muted small text-uppercase">
                                    {{ __('Folder name') }}
                                </label>
                                <input
                                    type="text"
                                    name="folder_name"
                                    id="folder_name"
                                    class="form-control form-control-lg shadow-sm"
                                    value="{{ old('folder_name') }}"
                                    placeholder="{{ __('ex: invoices, certificates') }}"
                                    required
                                >
                            </div>

                            <div class="form-group mb-3">
                                <label for="files" class="form-label fw-bold text-muted small text-uppercase">
                                    {{ __('Files') }}
                                </label>
                                <input
                                    type="file"
                                    name="files[]"
                                    id="files"
                                    class="form-control shadow-sm"
                                    multiple
                                    accept=".pdf,.png,.jpg,.jpeg"
                                    required
                                >
                            </div>

                            <div class="mt-3">
                                <button type="button" id="scanBtn" class="btn btn-outline-primary shadow-sm">
                                    <i class="fas fa-search me-2"></i> {{ __('Scan') }}
                                </button>
                            </div>

                            <div class="mt-4 p-3 bg-light rounded-3 shadow-sm border">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h6 class="fw-bold text-uppercase small text-primary mb-0">{{ __('Scan result') }}</h6>
                                    <span class="badge bg-primary" id="scanCount">0</span>
                                </div>
                                <div id="scanEmpty" class="text-muted small">{{ __('Choose files then click Scan') }}</div>
                                <ul id="scanList" class="mb-0 ps-3 d-none"></ul>
                            </div>
                        </div>
                        <div class="card-footer bg-white p-4 text-end">
                            <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm">
                                <i class="fas fa-save me-2"></i> {{ __('messages.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card card-primary card-outline shadow-sm settings-card">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-primary">
                            <i class="fas fa-folder-open me-2"></i> {{ __('Saved folders') }}
                        </h3>
                    </div>

                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-4 border-end">
                                <div class="p-3">
                                    <div class="list-group rounded-3 overflow-hidden">
                                        <a href="{{ route('settings.custom-folders.index') }}" class="list-group-item list-group-item-action {{ $folder === '' ? 'active' : '' }}">
                                            {{ __('messages.all') }}
                                        </a>
                                        @foreach($folders as $f)
                                            <a href="{{ route('settings.custom-folders.index', ['folder' => $f->folder_slug]) }}" class="list-group-item list-group-item-action {{ $folder === $f->folder_slug ? 'active' : '' }}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-truncate" style="max-width: 140px;">{{ $f->folder_name }}</span>
                                                    <span class="badge bg-secondary">{{ $f->files_count }}</span>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="p-3">{{ __('Folder') }}</th>
                                                <th class="p-3">{{ __('File') }}</th>
                                                <th class="p-3 text-end">{{ __('Size') }}</th>
                                                <th class="p-3 text-end">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($files as $file)
                                                <tr>
                                                    <td class="p-3 align-middle">
                                                        <span class="text-truncate d-inline-block" style="max-width: 160px;">{{ $file->folder_name }}</span>
                                                    </td>
                                                    <td class="p-3 align-middle">
                                                        <div class="text-truncate" style="max-width: 220px;">{{ $file->original_name }}</div>
                                                        <div class="text-muted small">{{ $file->created_at?->format('Y-m-d H:i') }}</div>
                                                    </td>
                                                    <td class="p-3 align-middle text-end">
                                                        @php($size = $file->size_bytes ? round($file->size_bytes / 1024, 1) . ' KB' : '-')
                                                        <span class="text-muted">{{ $size }}</span>
                                                    </td>
                                                    <td class="p-3 align-middle text-end">
                                                        <a href="{{ route('settings.custom-folders.files.download', ['file' => $file->id]) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="p-4 text-center text-muted">
                                                        {{ __('messages.no_data') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @if(method_exists($files, 'links'))
                                    <div class="p-3 border-top bg-white">
                                        {{ $files->links('pagination::bootstrap-5') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filesInput = document.getElementById('files');
        const scanBtn = document.getElementById('scanBtn');
        const scanList = document.getElementById('scanList');
        const scanEmpty = document.getElementById('scanEmpty');
        const scanCount = document.getElementById('scanCount');

        function humanSize(bytes) {
            if (!bytes && bytes !== 0) return '-';
            const units = ['B', 'KB', 'MB', 'GB'];
            let v = bytes;
            let i = 0;
            while (v >= 1024 && i < units.length - 1) {
                v = v / 1024;
                i++;
            }
            return (i === 0 ? v : v.toFixed(1)) + ' ' + units[i];
        }

        function renderScan() {
            const files = filesInput.files ? Array.from(filesInput.files) : [];
            scanCount.textContent = String(files.length);
            scanList.innerHTML = '';

            if (files.length === 0) {
                scanEmpty.classList.remove('d-none');
                scanList.classList.add('d-none');
                return;
            }

            scanEmpty.classList.add('d-none');
            scanList.classList.remove('d-none');
            for (const f of files) {
                const li = document.createElement('li');
                li.textContent = `${f.name} (${humanSize(f.size)})`;
                scanList.appendChild(li);
            }
        }

        scanBtn.addEventListener('click', renderScan);
        filesInput.addEventListener('change', renderScan);
    });
</script>
@endpush
@endsection
