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
        {{ ucfirst(__('PkgQcm::questionLib.type')) }}
        <span class="text-danger">*</span>
    </label>
    <select
        name="type"
        class="form-control"
        required
        id="type">
        <option value="">{{ __('Core::msg.select') }}</option>
        @foreach(\Modules\PkgQcm\Models\Enums\QuestionTypeEnum::cases() as $enum)
            <option value="{{ $enum->value }}" {{ old('type', isset($itemQuestionLib) && $itemQuestionLib->type ? $itemQuestionLib->type->value : '') == $enum->value ? 'selected' : '' }}>
                {{ $enum->label() }}
            </option>
        @endforeach
    </select>
    @error('type')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
