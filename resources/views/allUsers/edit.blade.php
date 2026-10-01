@extends('layouts.user-layout')
@section('content')

@push('styles')
<style>
.modern-form-container {
    background: #f7f9fc;
    min-height: 100vh;
    padding: 2rem 1.25rem;
}

.form-card {
    background: rgba(255, 255, 255, 0.98);
    border-radius: 1.5rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    max-width: 900px;
    margin: 0 auto;
    backdrop-filter: blur(10px);
}

.form-header {
    background: linear-gradient(135deg, var(--primary) 0%, #818cf8 100%);
    color: white;
    padding: 2.5rem;
    text-align: center;
}

.form-header.header-warning {
    background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
}

.form-header h2 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
}

.form-header p {
    margin: 0.625rem 0 0;
    opacity: 0.9;
    font-size: 1rem;
}

.form-body {
    padding: 3rem 2.5rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.875rem;
}

.form-group-modern {
    position: relative;
    margin-bottom: 0.9375rem;
}

.form-group-modern.full-width {
    grid-column: 1 / -1;
}

.form-label-modern {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
}

.required-field::after {
    content: " *";
    color: var(--danger);
    font-weight: bold;
}

.form-control-modern {
    width: 100%;
    padding: 0.875rem 1.125rem;
    font-size: 0.9375rem;
    border: 2px solid var(--gray-200);
    border-radius: 0.75rem;
    background: #f8fafc;
    transition: var(--transition);
}

.form-control-modern:focus {
    outline: none;
    border-color: var(--primary);
    background: white;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    transform: translateY(-2px);
}

.form-control-modern.has-value {
    border-color: var(--primary);
}

.select-modern {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%234a5568' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1.125rem center;
    padding-right: 2.8125rem;
}

[dir="rtl"] .select-modern {
    background-position: left 1.125rem center;
    padding-right: 1.125rem;
    padding-left: 2.8125rem;
}

.input-group-modern {
    position: relative;
}

.phone-prefix {
    position: absolute;
    left: 1.125rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-600);
    font-weight: 600;
    font-size: 0.9375rem;
    pointer-events: none;
}

[dir="rtl"] .phone-prefix {
    left: auto;
    right: 1.125rem;
}

.has-prefix .form-control-modern {
    padding-left: 4rem;
}

[dir="rtl"] .has-prefix .form-control-modern {
    padding-left: 1.125rem;
    padding-right: 4rem;
}

.has-icon .form-control-modern {
    padding-left: 3.125rem;
}

[dir="rtl"] .has-icon .form-control-modern {
    padding-left: 1.125rem;
    padding-right: 3.125rem;
}

.input-icon {
    position: absolute;
    left: 1.125rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    font-size: 1.125rem;
    pointer-events: none;
    transition: var(--transition);
}

[dir="rtl"] .input-icon {
    left: auto;
    right: 1.125rem;
}

.has-icon .form-control-modern:focus ~ .input-icon {
    color: var(--primary);
}

.section-divider {
    grid-column: 1 / -1;
    border-top: 2px solid var(--gray-200);
    margin: 1.25rem 0;
    position: relative;
}

.section-title {
    position: absolute;
    top: -0.75rem;
    left: 1.25rem;
    background: white;
    padding: 0 0.9375rem;
    color: var(--primary);
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

[dir="rtl"] .section-title {
    left: auto;
    right: 1.25rem;
}

.gender-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.9375rem;
}

.gender-option {
    position: relative;
}

.gender-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.gender-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
    border: 2px solid var(--gray-200);
    border-radius: 0.75rem;
    background: #f8fafc;
    cursor: pointer;
    transition: var(--transition);
    min-height: 6.25rem;
}

.gender-label:hover {
    border-color: var(--primary);
    background: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.gender-option input[type="radio"]:checked + .gender-label {
    border-color: var(--primary);
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(129, 140, 248, 0.05));
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.1);
}

.gender-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    transition: var(--transition);
}

.gender-option input[type="radio"]:checked + .gender-label .gender-icon {
    transform: scale(1.1);
}

.gender-text {
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.9375rem;
}

.gender-option input[type="radio"]:checked + .gender-label .gender-text {
    color: var(--primary);
}

.file-upload-container {
    position: relative;
    border: 3px dashed var(--gray-300);
    border-radius: 0.75rem;
    padding: 1.875rem;
    text-align: center;
    background: #f8fafc;
    transition: var(--transition);
    cursor: pointer;
}

.file-upload-container:hover {
    border-color: var(--primary);
    background: #f1f5f9;
}

.file-upload-container input[type="file"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    cursor: pointer;
}

.upload-icon {
    font-size: 3rem;
    color: var(--primary);
    margin-bottom: 0.625rem;
}

.upload-text {
    color: var(--gray-700);
    font-weight: 500;
}

.upload-hint {
    color: var(--gray-400);
    font-size: 0.8125rem;
    margin-top: 0.3125rem;
}

.error-alert {
    background: #fee2e2;
    border: 2px solid #fecaca;
    border-radius: 0.75rem;
    padding: 1.25rem;
    margin-bottom: 1.875rem;
}

.error-alert ul {
    margin: 0;
    padding-left: 1.25rem;
    color: #991b1b;
}

[dir="rtl"] .error-alert ul {
    padding-left: 0;
    padding-right: 1.25rem;
}

.error-alert li {
    margin: 0.3125rem 0;
}

.custom-switch .form-check-input {
    width: 3rem;
    height: 1.5rem;
    cursor: pointer;
}

.custom-switch .form-check-input:checked {
    background-color: var(--success);
    border-color: var(--success);
}
</style>
@endpush


<div class="modern-form-container {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="form-card">
        <div class="form-header">
            <h2>
                {{ __('messages.edit_user') }} ✏️
            </h2>
        </div>
        
        <div class="form-body">
            @if ($errors->any())
                <div class="error-alert">
                    <strong>
                        {{ __('messages.validation_errors') }}
                    </strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('allUsers.update', [$singleUser->id]) }}" method="post" enctype="multipart/form-data" id="userForm">
                @method('PUT')
                @csrf
                
                <div class="form-grid">
                    <!-- Name Field -->
                    <div class="form-group-modern">
                        <label for="name" class="form-label-modern required-field">
                            {{ __('messages.full_name') }}
                        </label>
                        <div class="input-group-modern has-icon">
                            <i class="fas fa-user input-icon"></i>
                            <input 
                                type="text" 
                                id="name" 
                                class="form-control-modern" 
                                name="name" 
                                required 
                                value="{{ old('name', $singleUser->name) }}"
                            >
                        </div>
                    </div>
                    
                    <!-- Phone Field (Required) -->
                    <div class="form-group-modern">
                        <label for="phone" class="form-label-modern required-field">
                            {{ __('messages.phone') }}
                        </label>
                        <div class="input-group-modern has-prefix">
                            <span class="phone-prefix">📞</span>
                            <input 
                                type="tel" 
                                id="phone" 
                                class="form-control-modern" 
                                name="phone" 
                                required 
                                value="{{ old('phone', $singleUser->phone) }}"
                                pattern="[0-9+\-\s()]+"
                            >
                        </div>
                    </div>
                    
                    <!-- Email Field (Optional) -->
                    <div class="form-group-modern">
                        <label for="email" class="form-label-modern">
                            {{ __('messages.email') }}
                        </label>
                        <div class="input-group-modern has-icon">
                            <i class="fas fa-envelope input-icon"></i>
                            <input 
                                type="email" 
                                id="email" 
                                class="form-control-modern" 
                                name="email" 
                                placeholder="{{ __('messages.optional') }}"
                                value="{{ old('email', $singleUser->email) }}"
                            >
                        </div>
                    </div>
                    
                    
                    <!-- National ID -->
                    <div class="form-group-modern">
                        <label for="national_id" class="form-label-modern">
                            {{ __('messages.national_id') }}
                        </label>
                        <div class="input-group-modern has-icon">
                            <i class="fas fa-id-card input-icon"></i>
                            <input 
                                type="text" 
                                id="national_id" 
                                class="form-control-modern" 
                                name="national_id" 
                                value="{{ old('national_id', $singleUser->national_id) }}"
                            >
                        </div>
                    </div>

                    @hasanyrole('admin|cityManager|gymManager')
                    <!-- Num Dep -->
                    <div class="form-group-modern">
                        <label for="num_dep" class="form-label-modern">
                            {{ __('messages.num_dep') }}
                        </label>
                        <div class="input-group-modern has-icon">
                            <i class="fas fa-map-marker-alt input-icon"></i>
                            <input 
                                type="text" 
                                id="num_dep" 
                                class="form-control-modern" 
                                name="num_dep" 
                                value="{{ old('num_dep', $singleUser->num_dep) }}"
                            >
                        </div>
                    </div>

                    <!-- Insurance -->
                    <div class="form-group-modern full-width">
                        <div class="d-flex align-items-center gap-3">
                            <label class="form-label-modern mb-0">
                                {{ __('messages.has_insurance') }}
                            </label>
                            <div class="form-check form-switch custom-switch">
                                <input class="form-check-input" type="checkbox" id="has_insurance" name="has_insurance" value="1" {{ old('has_insurance', $singleUser->has_insurance) ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_insurance">
                                    <span class="badge rounded-pill bg-light text-muted px-3 py-2 border">
                                        {{ __('messages.no') }} / لا
                                    </span>
                                    <span class="badge rounded-pill bg-success-light text-success px-3 py-2 border d-none">
                                        {{ __('messages.yes') }} / نعم
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Stage -->
                    <div class="form-group-modern full-width">
                        <div class="d-flex align-items-center gap-3">
                            <label class="form-label-modern mb-0">
                                {{ __('messages.has_stage') }}
                            </label>
                            <div class="form-check form-switch custom-switch">
                                <input class="form-check-input" type="checkbox" id="has_stage" name="has_stage" value="1" {{ old('has_stage', $singleUser->has_stage) ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_stage">
                                    <span class="badge rounded-pill bg-light text-muted px-3 py-2 border">
                                        {{ __('messages.no') }} / لا
                                    </span>
                                    <span class="badge rounded-pill bg-success-light text-success px-3 py-2 border d-none">
                                        {{ __('messages.yes') }} / نعم
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @endhasanyrole
                    
                    <div class="section-divider">
                        <span class="section-title">
                            {{ __('messages.personal_info') }}
                        </span>
                    </div>
                    
                    <!-- Gender Field -->
                    <div class="form-group-modern full-width">
                        <label class="form-label-modern">
                            {{ __('messages.gender') }}
                        </label>
                        <div class="gender-container">
                            <div class="gender-option">
                                <input type="radio" id="male" name="gender" value="male" {{ old('gender', $singleUser->gender) == 'male' ? 'checked' : '' }}>
                                <label for="male" class="gender-label">
                                    <div class="gender-icon">👨</div>
                                    <div class="gender-text">
                                        {{ __('messages.male') }}
                                    </div>
                                </label>
                            </div>
                            <div class="gender-option">
                                <input type="radio" id="female" name="gender" value="female" {{ old('gender', $singleUser->gender) == 'female' ? 'checked' : '' }}>
                                <label for="female" class="gender-label">
                                    <div class="gender-icon">👩</div>
                                    <div class="gender-text">
                                        {{ __('messages.female') }}
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Birth Date -->
                    <div class="form-group-modern">
                        <label for="birth_date" class="form-label-modern">
                            {{ __('messages.birth_date') }}
                        </label>
                        <div class="input-group-modern has-icon">
                            <i class="fas fa-calendar input-icon"></i>
                            <input 
                                type="date" 
                                id="birth_date" 
                                class="form-control-modern" 
                                name="birth_date"
                                value="{{ old('birth_date', $singleUser->birth_date ? $singleUser->birth_date->format('Y-m-d') : '') }}"
                            >
                        </div>
                    </div>
                    
                    <!-- Weight -->
                    <div class="form-group-modern">
                        <label for="weight_kg" class="form-label-modern">
                            {{ __('messages.weight_kg') }}
                        </label>
                        <div class="input-group-modern has-icon">
                            <i class="fas fa-weight input-icon"></i>
                            <input 
                                type="number" 
                                step="0.01" 
                                min="10" 
                                max="300" 
                                id="weight_kg" 
                                class="form-control-modern" 
                                name="weight_kg" 
                                value="{{ old('weight_kg', $singleUser->weight_kg) }}"
                            >
                        </div>
                    </div>
                    
                    <!-- Category -->
                    <div class="form-group-modern">
                        <label for="category_id" class="form-label-modern">
                            {{ __('messages.category') }}
                        </label>
                        <select id="category_id" name="category_id" class="form-control-modern select-modern">
                            <option value="">
                                {{ __('messages.select_category') }}
                            </option>
                            @foreach(($categories ?? []) as $cat)
                                <option value="{{ $cat->id }}" {{ (string)old('category_id', $singleUser->category_id) === (string)$cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Course -->
                    <div class="form-group-modern">
                        <label for="course_id" class="form-label-modern">
                            {{ __('messages.course') }}
                        </label>
                        <select id="course_id" name="course_id" class="form-control-modern select-modern">
                            <option value="">
                                {{ __('messages.none') }}
                            </option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ isset($currentCourseId) && $currentCourseId == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Profile Image -->
                    <div class="form-group-modern full-width">
                        <label class="form-label-modern">
                            {{ __('messages.profile_photo') }}
                        </label>
                        @if($singleUser->profile_image)
                            <div style="margin-bottom: 15px; text-align: center;">
                                <img src="{{ asset($singleUser->profile_image) }}" alt="{{ __('messages.current_photo') }}" style="max-width: 150px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                <p style="margin-top: 10px; color: #4a5568;">
                                    {{ __('messages.current_photo') }}
                                </p>
                            </div>
                        @endif
                        <div class="file-upload-container" id="fileUpload">
                            <input type="file" id="profile_image" name="profile_image" accept="image/png, image/jpeg, image/jpg">
                            <div class="upload-icon">📸</div>
                            <div class="upload-text">
                                {{ __('messages.click_to_change_photo') }}
                            </div>
                            <div class="upload-hint">{{ __('messages.upload_format_hint') }}</div>
                            <div id="fileName" style="margin-top: 15px; color: #f6ad55; font-weight: 600;"></div>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="{{ route('allUsers.list') }}" class="btn-modern btn-secondary-modern">
                        <i class="fas fa-times"></i>
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="submit" class="btn-modern btn-primary-modern">
                        <i class="fas fa-save"></i>
                        {{ __('messages.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload preview
    const fileInput = document.getElementById('profile_image');
    const fileNameDiv = document.getElementById('fileName');
    const fileUploadContainer = document.getElementById('fileUpload');
    
    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const fileName = this.files[0].name;
            const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
            fileNameDiv.innerHTML = `✅ ${fileName} (${fileSize} MB)`;
            fileUploadContainer.style.borderColor = '#48bb78';
            fileUploadContainer.style.background = '#f0fff4';
        }
    });
    
    // Phone number formatting
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9+\-\s()]/g, '');
    });
    
    // Form validation feedback
    const form = document.getElementById('userForm');
    const inputs = form.querySelectorAll('.form-control-modern');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.style.borderColor = '#f56565';
            } else {
                this.style.borderColor = '#e2e8f0';
            }
        });
        
        input.addEventListener('input', function() {
            if (this.style.borderColor === 'rgb(245, 101, 101)') {
                this.style.borderColor = '#e2e8f0';
            }
        });
    });

    // Insurance switch toggle display
    const insuranceSwitch = document.getElementById('has_insurance');
    if (insuranceSwitch) {
        insuranceSwitch.addEventListener('change', function() {
            const label = this.nextElementSibling;
            const noBadge = label.querySelector('.bg-light');
            const yesBadge = label.querySelector('.bg-success-light');
            
            if (this.checked) {
                noBadge.classList.add('d-none');
                yesBadge.classList.remove('d-none');
            } else {
                noBadge.classList.remove('d-none');
                yesBadge.classList.add('d-none');
            }
        });
        
        // Initial state
        if (insuranceSwitch.checked) {
            const label = insuranceSwitch.nextElementSibling;
            label.querySelector('.bg-light').classList.add('d-none');
            label.querySelector('.bg-success-light').classList.remove('d-none');
        }
    }
});
</script>

@endsection
