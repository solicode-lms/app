      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input 
              type="checkbox" 
              class="check-input" 
              name="fields_modifiables[]" 
              value="session_formation_id" 
              id="bulk_field_session_formation_id" 
              title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="session_formation_id">
            {{ ucfirst(__('PkgSessions::sessionFormation.singular')) }}
            
          </label>
                      <select 
            id="session_formation_id" 
            
            data-calcul='true'
            @if($itemProjet->id) disabled @endif
            name="session_formation_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($sessionFormations as $sessionFormation)
                    <option value="{{ $sessionFormation->id }}"
                        {{ (isset($itemProjet) && $itemProjet->session_formation_id == $sessionFormation->id) || (old('session_formation_id>') == $sessionFormation->id) ? 'selected' : '' }}>
                        {{ $sessionFormation }}
                    </option>
                @endforeach
            </select>
            @if($itemProjet->id)
                <input type="hidden" name="session_formation_id" value="{{ $itemProjet->session_formation_id }}">
            @endif
          @error('session_formation_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
