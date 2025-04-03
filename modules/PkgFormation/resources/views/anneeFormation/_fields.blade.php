{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('anneeFormation-form')
<form class="crud-form custom-form context-state container" id="anneeFormationForm" action="{{ $itemAnneeFormation->id ? route('anneeFormations.update', $itemAnneeFormation->id) : route('anneeFormations.store') }}" method="POST" novalidate>
    @csrf

    @if ($itemAnneeFormation->id)
        @method('PUT')
    @endif

    <div class="card-body row">

      <div class="form-group col-12 col-md-6">
          <label for="titre">
            {{ ucfirst(__('PkgFormation::anneeFormation.titre')) }}
            <span class="text-danger">*</span>
          </label>
           <input
                name="titre"
                type="input"
                class="form-control"
                required
                
                id="titre"
                placeholder="{{ __('PkgFormation::anneeFormation.titre') }}"
                value="{{ $itemAnneeFormation ? $itemAnneeFormation->titre : old('titre') }}">
          @error('titre')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="date_debut">
            {{ ucfirst(__('PkgFormation::anneeFormation.date_debut')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="date_debut"
                type="date"
                class="form-control datetimepicker"
                required
                
                id="date_debut"
                placeholder="{{ __('PkgFormation::anneeFormation.date_debut') }}"
                value="{{ $itemAnneeFormation ? $itemAnneeFormation->date_debut : old('date_debut') }}">

          @error('date_debut')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


      <div class="form-group col-12 col-md-6">
          <label for="date_fin">
            {{ ucfirst(__('PkgFormation::anneeFormation.date_fin')) }}
            <span class="text-danger">*</span>
          </label>
                      <input
                name="date_fin"
                type="date"
                class="form-control datetimepicker"
                required
                
                id="date_fin"
                placeholder="{{ __('PkgFormation::anneeFormation.date_fin') }}"
                value="{{ $itemAnneeFormation ? $itemAnneeFormation->date_fin : old('date_fin') }}">

          @error('date_fin')
            <div class="text-danger">{{ $message }}</div>
          @enderror
      </div>
  


<!--   AffectationProjet HasMany --> 


<!--   Groupe HasMany --> 

    </div>

    <div class="card-footer">
        <a href="{{ route('anneeFormations.index') }}" class="btn btn-default form-cancel-button">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemAnneeFormation->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@show


<script>

</script>
<script>
     window.modalTitle = '{{__("PkgFormation::anneeFormation.singular") }} : {{$itemAnneeFormation}}'
     window.contextState = @json($contextState);
     window.sessionState = @json($sessionState);
     window.viewState = @json($viewState);
</script>
