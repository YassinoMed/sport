@extends('layouts.user-layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('messages.appearance_settings') }}</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card card-primary card-outline shadow-sm settings-card">
                    <div class="card-header bg-white">
                        <h3 class="card-title fw-bold text-primary">
                            <i class="fas fa-font me-2"></i> {{ __('messages.font_style_size') }}
                        </h3>
                    </div>
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        <div class="card-body p-4">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="font_family" class="form-label fw-bold text-muted small text-uppercase">
                                            {{ __('messages.font_family') }}
                                        </label>
                                        <select name="font_family" id="font_family" class="form-select form-control-lg shadow-sm">
                                            <option value="Source Sans Pro" {{ $fontFamily == 'Source Sans Pro' ? 'selected' : '' }}>{{ __('messages.font_default') }}</option>
                                            <option value="'Roboto', sans-serif" {{ $fontFamily == "'Roboto', sans-serif" ? 'selected' : '' }}>Roboto</option>
                                            <option value="'Open Sans', sans-serif" {{ $fontFamily == "'Open Sans', sans-serif" ? 'selected' : '' }}>Open Sans</option>
                                            <option value="'Lato', sans-serif" {{ $fontFamily == "'Lato', sans-serif" ? 'selected' : '' }}>Lato</option>
                                            <option value="'Montserrat', sans-serif" {{ $fontFamily == "'Montserrat', sans-serif" ? 'selected' : '' }}>Montserrat</option>
                                            <option value="'Cairo', sans-serif" {{ $fontFamily == "'Cairo', sans-serif" ? 'selected' : '' }}>{{ __('messages.font_cairo') }}</option>
                                            <option value="'Tajawal', sans-serif" {{ $fontFamily == "'Tajawal', sans-serif" ? 'selected' : '' }}>{{ __('messages.font_tajawal') }}</option>
                                        </select>
                                        <small class="text-muted mt-2 d-block">{{ __('messages.font_instruction') }}</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="font_size" class="form-label fw-bold text-muted small text-uppercase">
                                            {{ __('messages.font_size') }}
                                        </label>
                                        <div class="input-group shadow-sm border rounded-3 overflow-hidden">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-text-height"></i></span>
                                            <input type="number" name="font_size" id="font_size" class="form-control form-control-lg border-0" 
                                                   value="{{ $fontSize }}" min="10" max="30" required>
                                        </div>
                                        <small class="text-muted mt-2 d-block">{{ __('messages.font_size_instruction') }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="preview-box mt-4 p-4 bg-light rounded-3 shadow-sm border">
                                <h6 class="fw-bold text-uppercase small text-primary mb-3">{{ __('messages.preview') }}</h6>
                                <p id="font_preview" style="font-family: {{ $fontFamily }}; font-size: {{ $fontSize }}px;">
                                    {{ __('messages.preview_text') }}
                                </p>
                            </div>
                        </div>
                        <div class="card-footer bg-white p-4 text-end">
                            <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm">
                                <i class="fas fa-save me-2"></i> {{ __('messages.save_changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fontFamilySelect = document.getElementById('font_family');
        const fontSizeInput = document.getElementById('font_size');
        const previewText = document.getElementById('font_preview');

        function updatePreview() {
            previewText.style.fontFamily = fontFamilySelect.value;
            previewText.style.fontSize = fontSizeInput.value + 'px';
        }

        fontFamilySelect.addEventListener('change', updatePreview);
        fontSizeInput.addEventListener('input', updatePreview);
    });
</script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600;700&family=Roboto:wght@400;700&family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
@endpush

@endsection
