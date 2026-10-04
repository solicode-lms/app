      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="filiere_id" 
              id="bulk_field_filiere_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="filiere_id">
            {{ ucfirst(__('PkgFormation::filiere.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="filiere_id" 
            @if(!$itemProjet->id)
            data-target-dynamic-dropdown='#session_formation_id, #groupe_id'
            data-target-dynamic-dropdown-api-url="{{route('sessionFormations.getData')}}, {{route('groupes.getData')}}"
            data-target-dynamic-dropdown-filter='filiere_id, filiere_id'
            @endif
            required
            data-calcul='true'
            @if($itemProjet->id) disabled @endif
            name="filiere_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($filieres as $filiere)
                    <option value="{{ $filiere->id }}"
                        {{ (isset($itemProjet) && $itemProjet->filiere_id == $filiere->id) || (old('filiere_id>') == $filiere->id) ? 'selected' : '' }}>
                        {{ $filiere }}
                    </option>
                @endforeach
            </select>
            @if($itemProjet->id)
                <input type="hidden" name="filiere_id" value="{{ $itemProjet->filiere_id }}">
            @endif
          @error('filiere_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
