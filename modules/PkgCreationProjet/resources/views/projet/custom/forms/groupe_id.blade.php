      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="groupe_id" 
              id="bulk_field_groupe_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="groupe_id">
            {{ ucfirst(__('PkgApprenants::groupe.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="groupe_id" 
            required
            @if($itemProjet->id) disabled @endif
            name="groupe_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($groupes as $groupe)
                    <option value="{{ $groupe->id }}"
                        {{ (isset($itemProjet) && $itemProjet->groupe_id == $groupe->id) || (old('groupe_id>') == $groupe->id) ? 'selected' : '' }}>
                        {{ $groupe }}
                    </option>
                @endforeach
            </select>
            @if($itemProjet->id)
                <input type="hidden" name="groupe_id" value="{{ $itemProjet->groupe_id }}">
            @endif
          @error('groupe_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
