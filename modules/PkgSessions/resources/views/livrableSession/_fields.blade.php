{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('livrableSession-form')
<form 
    class="crud-form custom-form context-state container" 
    id="livrableSessionForm"
    action="{{ isset($bulkEdit) && $bulkEdit ? route('livrableSessions.bulkUpdate') : ($itemLivrableSession->id ? route('livrableSessions.update', $itemLivrableSession->id) : route('livrableSessions.store')) }}"
    method="POST"
    novalidate > 
    
    @csrf

    @if ($itemLivrableSession->id)
        @method('PUT')
    @endif
    @if ($bulkEdit && !empty($livrableSession_ids))
        @foreach ($livrableSession_ids as $id)
            <input type="hidden" name="livrableSession_ids[]" value="{{ $id }}">
        @endforeach
    @endif

    <div class="card-body">


  

  
    

    
    <div class="row">
        <x-form-field :defined_vars="get_defined_vars()" :entity="$itemLivrableSession" field="ordre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="ordre" id="bulk_field_ordre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="ordre">
            {{ ucfirst(__('PkgSessions::livrableSession.ordre')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="ordre"
                type="number"
                class="form-control"
                required
                
                
                id="ordre"
                placeholder="{{ __('PkgSessions::livrableSession.ordre') }}"
                value="{{ $itemLivrableSession ? $itemLivrableSession->ordre : old('ordre') }}">
          @error('ordre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemLivrableSession" field="titre" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="titre" id="bulk_field_titre" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="titre">
            {{ ucfirst(__('PkgSessions::livrableSession.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                
                id="titre"
                placeholder="{{ __('PkgSessions::livrableSession.titre') }}"
                value="{{ $itemLivrableSession ? $itemLivrableSession->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemLivrableSession" field="description" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-12">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="description" id="bulk_field_description" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="description">
            {{ ucfirst(__('PkgSessions::livrableSession.description')) }}
            
          </label>
                      <textarea rows="" cols=""
                name="description"
                class="form-control richText"
                
                
                
                id="description"
                placeholder="{{ __('PkgSessions::livrableSession.description') }}">{{ $itemLivrableSession ? $itemLivrableSession->description : old('description') }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemLivrableSession" field="session_formation_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="session_formation_id" id="bulk_field_session_formation_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="session_formation_id">
            {{ ucfirst(__('PkgSessions::sessionFormation.singular')) }}
            <span class="text-danger">*</span>
          </label>
                      <select 
            id="session_formation_id" 
            required
            
            
            name="session_formation_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($sessionFormations as $sessionFormation)
                    <option value="{{ $sessionFormation->id }}"
                        {{ (isset($itemLivrableSession) && $itemLivrableSession->session_formation_id == $sessionFormation->id) || (old('session_formation_id>') == $sessionFormation->id) ? 'selected' : '' }}>
                        {{ $sessionFormation }}
                    </option>
                @endforeach
            </select>
          @error('session_formation_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>

<x-form-field :defined_vars="get_defined_vars()" :entity="$itemLivrableSession" field="nature_livrable_id" :bulkEdit="$bulkEdit">

      <div class="form-group col-12 col-md-6">
          @if ($bulkEdit)
          <div class="bulk-check">
              <input type="checkbox" class="check-input" name="fields_modifiables[]" value="nature_livrable_id" id="bulk_field_nature_livrable_id" title="Appliquer ce champ à tous les éléments sélectionnés" data-toggle="tooltip">
          </div>
          @endif
          <label for="nature_livrable_id">
            {{ ucfirst(__('PkgCreationProjet::natureLivrable.singular')) }}
            
          </label>
                      <select 
            id="nature_livrable_id" 
            
            
            
            name="nature_livrable_id" 
            class="form-control select2">
             <option value="">Sélectionnez une option</option>
                @foreach ($natureLivrables as $natureLivrable)
                    <option value="{{ $natureLivrable->id }}"
                        {{ (isset($itemLivrableSession) && $itemLivrableSession->nature_livrable_id == $natureLivrable->id) || (old('nature_livrable_id>') == $natureLivrable->id) ? 'selected' : '' }}>
                        {{ $natureLivrable }}
                    </option>
                @endforeach
            </select>
          @error('nature_livrable_id')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  
</x-form-field>


    </div>
  


    </div>

    <div class="card-footer">
        <a href="{{ route('livrableSessions.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemLivrableSession->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
    
    @if ($bulkEdit)
        window.modalTitle = '{{__("PkgSessions::livrableSession.singular") }} : {{__("Core::msg.edition_en_masse") }}'
    @else
        window.modalTitle = '{{__("PkgSessions::livrableSession.singular") }} : {{$itemLivrableSession}}'
    @endif
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
