<div class="form-group col-12 col-md-6">
    @if ($bulkEdit)
    <div class="bulk-check">
        <input 
        type="checkbox" 
        class="check-input" 
        name="fields_modifiables[]" 
        value="type" 
        id="bulk_field_type" 
        title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
    </div>
    @endif
    <label for="type">
        {{ ucfirst(__('PkgQcm::question.type')) }}
        <span class="text-danger">*</span>
    </label>
    <select name="type" id="type" class="form-control select2" required>
        <option value="">Sélectionnez un type</option>
        @foreach(\Modules\PkgQcm\Models\Enums\QuestionTypeEnum::cases() as $enumCase)
            <option value="{{ $enumCase->value }}"
                {{ old('type', $itemQuestion?->type?->value ?? '') == $enumCase->value ? 'selected' : '' }}>
                {{ $enumCase->label() }}
            </option>
        @endforeach
    </select>
    @error('type')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
