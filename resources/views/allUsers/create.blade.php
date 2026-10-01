@extends('layouts.user-layout')
@section('content')

@push('styles')
<style>
/* Page specific tweaks if any */
</style>
@endpush


<div class="breadcrumb-container">
    <a href="{{ route('welcome') }}" class="breadcrumb-link">
        <i class="fas fa-home"></i>
    </a>
    <i class="fas fa-chevron-right breadcrumb-separator"></i>
    <a href="{{ route('allUsers.list') }}" class="breadcrumb-link">
        <span class="label-fr">Utilisateurs</span>
        <span class="label-ar">المستخدمين</span>
    </a>
    <i class="fas fa-chevron-right breadcrumb-separator"></i>
    <span class="breadcrumb-current">
        <span class="label-fr">Nouvel Utilisateur</span>
        <span class="label-ar">مستخدم جديد</span>
    </span>
</div>

<div class="modern-form-container {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="card-modern">
        <div class="card-header-modern">
            <div class="header-icon-container">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="header-text">
                <h2 class="header-title">
                    <span class="label-fr">Nouvel Utilisateur</span>
                    <span class="label-ar">مستخدم جديد</span>
                </h2>
                <p class="header-subtitle">
                    <span class="label-fr">Ajouter un nouveau membre au système</span>
                    <span class="label-ar">إضافة عضو جديد إلى النظام</span>
                </p>
            </div>
        </div>
        
        <div class="form-body">
            <!-- Stepper Progress Bar -->
            <div class="stepper-wrapper">
                <div class="stepper-item active" id="step-item-1">
                    <div class="step-counter">1</div>
                    <div class="step-name">
                        <span class="label-fr">Infos Personnelles</span>
                        <span class="label-ar">معلومات شخصية</span>
                    </div>
                </div>
                <div class="stepper-item" id="step-item-2">
                    <div class="step-counter">2</div>
                    <div class="step-name">
                        <span class="label-fr">Docs & Assurance</span>
                        <span class="label-ar">الوثائق والتأمين</span>
                    </div>
                </div>
                <div class="stepper-item" id="step-item-3">
                    <div class="step-counter">3</div>
                    <div class="step-name">
                        <span class="label-fr">Gym & Groupe</span>
                        <span class="label-ar">القاعة والمجموعة</span>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="modern-alert alert-danger mb-4">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h4 class="alert-title">
                            <span class="label-fr">Erreurs de validation</span>
                            <span class="label-ar">أخطاء في التحقق</span>
                        </h4>
                        <ul class="alert-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            
            <form action="{{ route('allUsers.store') }}" method="post" enctype="multipart/form-data" id="userForm">
                @csrf
                
                <!-- Step 1: Personal Info -->
                <div class="form-step active" id="step-1">
                    <div class="form-grid">
                        <!-- Name Field -->
                        <div class="form-group-modern">
                            <label for="name" class="form-label-modern">
                                <span class="label-fr">Nom Complet</span>
                                <span class="label-ar">الاسم الكامل</span>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group-modern has-icon">
                                <i class="fas fa-user input-icon"></i>
                                <input 
                                    type="text" 
                                    id="name" 
                                    class="form-control-modern @error('name') is-invalid @enderror" 
                                    name="name" 
                                    required 
                                    placeholder="Ex: Ahmed Benali"
                                    value="{{ old('name') }}"
                                >
                            </div>
                            @error('name')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Phone Field (Required) -->
                        <div class="form-group-modern">
                            <label for="phone" class="form-label-modern">
                                <span class="label-fr">Téléphone</span>
                                <span class="label-ar">الهاتف</span>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group-modern has-prefix">
                                <span class="phone-prefix">📞</span>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    class="form-control-modern @error('phone') is-invalid @enderror" 
                                    name="phone" 
                                    required 
                                    placeholder="0612345678"
                                    value="{{ old('phone') }}"
                                    pattern="[0-9+\-\s()]+"
                                >
                            </div>
                            @error('phone')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Email Field (Optional) -->
                        <div class="form-group-modern">
                            <label for="email" class="form-label-modern">
                                <span class="label-fr">Email</span>
                                <span class="label-ar">البريد الإلكتروني</span>
                            </label>
                            <div class="input-group-modern has-icon">
                                <i class="fas fa-envelope input-icon"></i>
                                <input 
                                    type="email" 
                                    id="email" 
                                    class="form-control-modern @error('email') is-invalid @enderror" 
                                    name="email" 
                                    placeholder="exemple@email.com"
                                    value="{{ old('email') }}"
                                >
                            </div>
                            @error('email')
                                <div class="invalid-feedback-modern">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gender Field -->
                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <span class="label-fr">Sexe</span>
                                <span class="label-ar">الجنس</span>
                            </label>
                            <div class="gender-container">
                                <div class="gender-option">
                                    <input type="radio" id="male" name="gender" value="male" {{ old('gender', 'male') == 'male' ? 'checked' : '' }}>
                                    <label for="male" class="gender-label">
                                        <div class="gender-icon">👨</div>
                                        <div class="gender-text">
                                            <span class="label-fr">Homme</span>
                                            <span class="label-ar">ذكر</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="gender-option">
                                    <input type="radio" id="female" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                    <label for="female" class="gender-label">
                                        <div class="gender-icon">👩</div>
                                        <div class="gender-text">
                                            <span class="label-fr">Femme</span>
                                            <span class="label-ar">أنثى</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Birth Date -->
                        <div class="form-group-modern">
                            <label for="birth_date" class="form-label-modern">
                                <span class="label-fr">Date de naissance</span>
                                <span class="label-ar">تاريخ الميلاد</span>
                            </label>
                            <div class="input-group-modern has-icon">
                                <i class="fas fa-calendar input-icon"></i>
                                <input 
                                    type="date" 
                                    id="birth_date" 
                                    class="form-control-modern" 
                                    name="birth_date"
                                    value="{{ old('birth_date') }}"
                                >
                            </div>
                        </div>
                        
                        <!-- Weight -->
                        <div class="form-group-modern">
                            <label for="weight_kg" class="form-label-modern">
                                <span class="label-fr">Poids (kg)</span>
                                <span class="label-ar">الوزن (كلغ)</span>
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
                                    placeholder="Ex: 75"
                                    value="{{ old('weight_kg') }}"
                                >
                            </div>
                        </div>

                        <!-- Profile Image -->
                        <div class="form-group-modern full-width">
                            <label class="form-label-modern">
                                <span class="label-fr">Photo de profil</span>
                                <span class="label-ar">الصورة الشخصية</span>
                            </label>
                            <div class="file-upload-container" id="fileUpload">
                                <input type="file" id="profile_image" name="profile_image" accept="image/png, image/jpeg, image/jpg">
                                <div class="upload-icon">📸</div>
                                <div class="upload-text">
                                    <span class="label-fr">Cliquez pour télécharger</span>
                                    <span class="label-ar">انقر للتحميل</span>
                                </div>
                                <div class="upload-hint">
                                    <span class="label-fr">PNG, JPG ou JPEG (Max. 2MB)</span>
                                    <span class="label-ar">PNG, JPG أو JPEG (كحد أقصى 2 ميجابايت)</span>
                                </div>
                                <div id="fileName" style="margin-top: 15px; color: #667eea; font-weight: 600;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Documents & Assurance -->
                <div class="form-step" id="step-2">
                    <div class="form-grid">
                        <!-- National ID -->
                        <div class="form-group-modern">
                            <label for="national_id" class="form-label-modern">
                                <span class="label-fr">CIN</span>
                                <span class="label-ar">بطاقة التعريف الوطنية</span>
                            </label>
                            <div class="input-group-modern has-icon">
                                <i class="fas fa-id-card input-icon"></i>
                                <input 
                                    type="text" 
                                    id="national_id" 
                                    class="form-control-modern" 
                                    name="national_id" 
                                    placeholder="Ex: AB123456"
                                    value="{{ old('national_id') }}"
                                >
                            </div>
                        </div>

                        @hasanyrole('admin|cityManager|gymManager')
                        <!-- Num Dep -->
                        <div class="form-group-modern">
                            <label for="num_dep" class="form-label-modern">
                                <span class="label-fr">N° Dossier</span>
                                <span class="label-ar">رقم الملف</span>
                            </label>
                            <div class="input-group-modern has-icon">
                                <i class="fas fa-map-marker-alt input-icon"></i>
                                <input 
                                    type="text" 
                                    id="num_dep" 
                                    class="form-control-modern" 
                                    name="num_dep" 
                                    placeholder="N° de dépôt"
                                    value="{{ old('num_dep') }}"
                                >
                            </div>
                        </div>

                        <!-- Insurance -->
                        <div class="form-group-modern full-width">
                            <div class="d-flex align-items-center gap-3">
                                <label class="form-label-modern mb-0">
                                    <span class="label-fr">Assurance</span>
                                    <span class="label-ar">التأمين</span>
                                </label>
                                <div class="form-check form-switch custom-switch">
                                    <input class="form-check-input" type="checkbox" id="has_insurance" name="has_insurance" value="1" {{ old('has_insurance') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="has_insurance">
                                        <span class="badge rounded-pill bg-light text-muted px-3 py-2 border">
                                            <span class="label-fr">Non</span><span class="label-ar">لا</span>
                                        </span>
                                        <span class="badge rounded-pill bg-success-light text-success px-3 py-2 border d-none">
                                            <span class="label-fr">Oui</span><span class="label-ar">نعم</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Stage -->
                        <div class="form-group-modern full-width">
                            <div class="d-flex align-items-center gap-3">
                                <label class="form-label-modern mb-0">
                                    <span class="label-fr">Stage</span>
                                    <span class="label-ar">تدريب</span>
                                </label>
                                <div class="form-check form-switch custom-switch">
                                    <input class="form-check-input" type="checkbox" id="has_stage" name="has_stage" value="1" {{ old('has_stage') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="has_stage">
                                        <span class="badge rounded-pill bg-light text-muted px-3 py-2 border">
                                            <span class="label-fr">Non</span><span class="label-ar">لا</span>
                                        </span>
                                        <span class="badge rounded-pill bg-success-light text-success px-3 py-2 border d-none">
                                            <span class="label-fr">Oui</span><span class="label-ar">نعم</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endhasanyrole
                    </div>
                </div>

                <!-- Step 3: Attribution Gym/Groupe -->
                <div class="form-step" id="step-3">
                    <div class="form-grid">
                        <!-- Category -->
                        <div class="form-group-modern">
                            <label for="category_id" class="form-label-modern">
                                <span class="label-fr">Catégorie</span>
                                <span class="label-ar">الصنف</span>
                            </label>
                            <select id="category_id" name="category_id" class="form-control-modern select-modern">
                                <option value="">
                                    <span class="label-fr">Sélectionner une catégorie</span>
                                    <span class="label-ar">اختر الصنف</span>
                                </option>
                                @foreach(($categories ?? []) as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Belt -->
                        <div class="form-group-modern">
                            <label for="belt_name" class="form-label-modern">
                                <span class="label-fr">Ceinture</span>
                                <span class="label-ar">الحزام</span>
                            </label>
                            <select id="belt_name" name="belt_name" class="form-control-modern select-modern">
                                <option value="">
                                    <span class="label-fr">Sélectionner une ceinture</span>
                                    <span class="label-ar">اختر الحزام</span>
                                </option>
                                @foreach(($beltOptions ?? []) as $value => $label)
                                    <option value="{{ $value }}" {{ old('belt_name') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Belt Achievement Date -->
                        <div class="form-group-modern">
                            <label for="belt_achieved_at" class="form-label-modern">
                                <span class="label-fr">Date d'obtention</span>
                                <span class="label-ar">تاريخ الحصول عليه</span>
                            </label>
                            <div class="input-group-modern has-icon">
                                <i class="fas fa-medal input-icon"></i>
                                <input 
                                    type="date" 
                                    id="belt_achieved_at" 
                                    name="belt_achieved_at" 
                                    class="form-control-modern"
                                    value="{{ old('belt_achieved_at') }}"
                                >
                            </div>
                        </div>

                        <!-- Groups -->
                        <div class="form-group-modern">
                            <label for="group_id" class="form-label-modern">
                                <span class="label-fr">Groupe</span>
                                <span class="label-ar">المجموعة</span>
                                <span class="text-danger">*</span>
                            </label>
                            <select id="group_id" name="group_id" class="form-control-modern select-modern" required>
                                <option value="">
                                    <span class="label-fr">Sélectionner un groupe</span>
                                    <span class="label-ar">اختر المجموعة</span>
                                </option>
                                @foreach(($groups ?? []) as $g)
                                    <option value="{{ $g->id }}" {{ old('group_id') == $g->id ? 'selected' : '' }}>
                                        {{ $g->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback-modern">Ce champ est obligatoire</div>
                            <div class="upload-hint">
                                <span class="label-fr">Un utilisateur peut appartenir à un seul groupe</span>
                                <span class="label-ar">يمكن للمستخدم الانتماء إلى مجموعة واحدة فقط</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-modern btn-secondary-modern d-none" id="prevBtn">
                        <i class="fas fa-arrow-left"></i>
                        <span class="label-fr">Précédent</span>
                        <span class="label-ar">السابق</span>
                    </button>
                    
                    <a href="{{ route('allUsers.list') }}" class="btn-modern btn-secondary-modern" id="cancelBtn">
                        <i class="fas fa-times"></i>
                        <span class="label-fr">Annuler</span>
                        <span class="label-ar">إلغاء</span>
                    </a>

                    <button type="button" class="btn-modern btn-primary-modern" id="nextBtn">
                        <span class="label-fr">Suivant</span>
                        <span class="label-ar">التالي</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>

                    <button type="submit" class="btn-modern btn-primary-modern d-none" id="saveBtn">
                        <i class="fas fa-check"></i>
                        <span class="label-fr">Enregistrer</span>
                        <span class="label-ar">حفظ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('userForm');
    const steps = form.querySelectorAll('.form-step');
    const stepItems = document.querySelectorAll('.stepper-item');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    let currentStep = 0;

    function updateStepper() {
        steps.forEach((step, index) => {
            step.classList.toggle('active', index === currentStep);
        });

        stepItems.forEach((item, index) => {
            item.classList.toggle('active', index === currentStep);
            item.classList.toggle('completed', index < currentStep);
        });

        // Update buttons
        prevBtn.classList.toggle('d-none', currentStep === 0);
        cancelBtn.classList.toggle('d-none', currentStep !== 0);
        
        if (currentStep === steps.length - 1) {
            nextBtn.classList.add('d-none');
            saveBtn.classList.remove('d-none');
        } else {
            nextBtn.classList.remove('d-none');
            saveBtn.classList.add('d-none');
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(stepIndex) {
        const currentStepEl = steps[stepIndex];
        const inputs = currentStepEl.querySelectorAll('input[required], select[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!validateInput(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    function validateInput(input) {
        let isValid = true;
        const value = input.value.trim();
        const type = input.getAttribute('type');

        if (input.hasAttribute('required') && !value) {
            isValid = false;
        } else if (type === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            isValid = false;
        } else if (type === 'tel' && value && !/^[0-9+\-\s()]{8,}$/.test(value)) {
            isValid = false;
        }

        if (!isValid) {
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }

        return isValid;
    }

    nextBtn.addEventListener('click', () => {
        if (validateStep(currentStep)) {
            currentStep++;
            updateStepper();
        }
    });

    prevBtn.addEventListener('click', () => {
        currentStep--;
        updateStepper();
    });

    // Real-time validation
    form.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('blur', () => validateInput(input));
        input.addEventListener('input', () => {
            if (input.classList.contains('is-invalid')) {
                validateInput(input);
            }
        });
    });

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
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9+\-\s()]/g, '');
        });
    }
    
    // Insurance switch toggle display
    const switches = ['has_insurance', 'has_stage'];
    switches.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            const updateLabel = (checkbox) => {
                const label = checkbox.nextElementSibling;
                const noBadge = label.querySelector('.bg-light');
                const yesBadge = label.querySelector('.bg-success-light');
                
                if (checkbox.checked) {
                    noBadge.classList.add('d-none');
                    yesBadge.classList.remove('d-none');
                } else {
                    noBadge.classList.remove('d-none');
                    yesBadge.classList.add('d-none');
                }
            };

            el.addEventListener('change', () => updateLabel(el));
            updateLabel(el); // Initial state
        }
    });
});
</script>

@endsection
