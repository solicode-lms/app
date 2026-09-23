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
    <label>
        {{ ucfirst(__('PkgQcm::questionLib.type')) }}
        <span class="text-danger">*</span>
    </label>
    <div class="pt-2">
        @foreach(\Modules\PkgQcm\Models\Enums\QuestionTypeEnum::cases() as $enum)
            <div class="icheck-primary d-inline mr-3">
                <input type="radio" 
                       id="type_{{ $enum->value }}" 
                       name="type" 
                       value="{{ $enum->value }}" 
                       {{ old('type', isset($itemQuestionLib) && $itemQuestionLib->type ? $itemQuestionLib->type->value : '') == $enum->value ? 'checked' : '' }}
                       required>
                <label for="type_{{ $enum->value }}">
                    {{ $enum->label() }}
                </label>
            </div>
        @endforeach
    </div>
    @error('type')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
