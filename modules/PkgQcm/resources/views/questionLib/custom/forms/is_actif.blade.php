<div class="form-group col-12 col-md-6">
    @if ($bulkEdit)
    <div class="bulk-check">
        <input 
        type="checkbox" 
        class="check-input" 
        name="fields_modifiables[]" 
        value="is_actif" 
        id="bulk_field_is_actif" 
        title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
    </div>
    @endif
    <label for="is_actif">
        {{ ucfirst(__('PkgQcm::questionLib.is_actif')) }}
    </label>
    <input type="hidden" name="is_actif" value="0">
    <input
        name="is_actif"
        type="checkbox"
        class="form-control d-block"
        id="is_actif"
        value="1"
        {{ old('is_actif', isset($itemQuestionLib) && $itemQuestionLib->id ? $itemQuestionLib->is_actif : 1) ? 'checked' : '' }}>
    @error('is_actif')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
