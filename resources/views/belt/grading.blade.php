@extends('layouts.user-layout')
@section('content')

<div class="content-wrapper pb-4">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('messages.belt_grading') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">{{ __('messages.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.belt_grading') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="card card-primary w-75 m-auto">
            <div class="card-header">
                <h3 class="card-title">{{ __('messages.new_grading') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">{{ __('messages.belt_grading_desc') }}</div>
                
                <form action="{{ route('belt.storeGrading') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="user_ids">{{ __('messages.athletes') }} <span class="text-danger">*</span></label>
                        <select name="user_ids[]" id="user_ids" class="form-control select2 @error('user_ids') is-invalid @enderror" multiple required style="height: 200px;">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" data-gym-id="{{ $user->gym_id }}" {{ (collect(old('user_ids'))->contains($user->id)) ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ optional($user->belts->first())->belt_name ?? __('messages.none') }})
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">{{ __('messages.multi_select_hint') }}</small>
                        @error('user_ids')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="belt_name">{{ __('messages.new_belt_label') }} <span class="text-danger">*</span></label>
                        <select name="belt_name" id="belt_name" class="form-control @error('belt_name') is-invalid @enderror" required>
                            <option value="">{{ __('messages.select_belt') }}</option>
                            @foreach(($beltOptions ?? []) as $value => $label)
                                <option value="{{ $value }}" {{ old('belt_name') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('belt_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="achieved_at">{{ __('messages.achieved_at') }} <span class="text-danger">*</span></label>
                        <input type="date" name="achieved_at" id="achieved_at" 
                               class="form-control @error('achieved_at') is-invalid @enderror" 
                               value="{{ old('achieved_at', date('Y-m-d')) }}" 
                               max="{{ date('Y-m-d') }}" 
                               required>
                        @error('achieved_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="exam_at">{{ __('messages.exam_at_label') }}</label>
                        <input type="date" name="exam_at" id="exam_at"
                               class="form-control @error('exam_at') is-invalid @enderror"
                               value="{{ old('exam_at', date('Y-m-d')) }}"
                               max="{{ date('Y-m-d') }}">
                        @error('exam_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="price">{{ __('messages.grading_price_per_athlete') }}</label>
                        <input type="number" step="0.01" min="0" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="{{ __('messages.amount_tnd') }}">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="cash_register_id">{{ __('messages.cash_register') ?? 'Caisse' }}</label>
                        <select name="cash_register_id" id="cash_register_id" class="form-control @error('cash_register_id') is-invalid @enderror">
                            <option value="">{{ __('messages.select_cash_register') ?? 'Choisir une caisse...' }}</option>
                            @foreach($cashRegisters as $register)
                                <option value="{{ $register->id }}" data-gym-id="{{ $register->gym_id }}" {{ old('cash_register_id') == $register->id ? 'selected' : '' }}>
                                    {{ $register->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('cash_register_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('belt.list') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> {{ __('messages.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
@section('dataTableScript')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var userSelect = $('#user_ids');
    var cashRegisterSelect = $('#cash_register_id');

    userSelect.on('change', function() {
        var selectedOptions = userSelect.find('option:selected');
        var gymIds = [];
        
        selectedOptions.each(function() {
            var gymId = $(this).data('gym-id');
            if (gymId && !gymIds.includes(gymId)) {
                gymIds.push(gymId);
            }
        });

        // Filter cash registers based on gymIds
        cashRegisterSelect.find('option').each(function() {
            var option = $(this);
            if (option.val() === "") {
                option.show();
                return;
            }
            
            var optionGymId = option.data('gym-id');
            // If all selected users belong to the same gym, show only that gym's registers
            // If users from different gyms are selected, this is complex. 
            // For now, if any gym matches or if no gym is selected, show it.
            if (gymIds.length === 1) {
                if (optionGymId && optionGymId == gymIds[0]) {
                    option.show();
                } else {
                    option.hide();
                    if (cashRegisterSelect.val() == option.val()) {
                        cashRegisterSelect.val('').trigger('change');
                    }
                }
            } else {
                option.show();
            }
        });
    });
});
</script>
@endsection
