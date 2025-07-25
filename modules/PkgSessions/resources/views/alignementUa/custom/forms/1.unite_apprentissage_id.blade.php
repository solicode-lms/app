<div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="unite_apprentissage_id" id="bulk_field_unite_apprentissage_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="unite_apprentissage_id">
            {{ ucfirst(__('PkgCompetences::uniteApprentissage.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="unite_apprentissage_id" 
            required
            
            
            name="unite_apprentissage_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($uniteApprentissages as $uniteApprentissage)
                    <option value="{{ $uniteApprentissage->id }}"
                        {{ (isset($entity) && $entity->unite_apprentissage_id == $uniteApprentissage->id) || (old('unite_apprentissage_id>') == $uniteApprentissage->id) ? 'selected' : '' }}>
                        {{ $uniteApprentissage }}
                    </option>
                @endforeach
            </select>
          @error('unite_apprentissage_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
</div>