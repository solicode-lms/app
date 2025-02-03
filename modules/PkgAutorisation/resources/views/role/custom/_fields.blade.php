@extends('PkgAutorisation::role._fields')
@section('role-form')

<form action="{{ $itemRole->id ? route('roles.update', $itemRole->id) : route('roles.store') }}" method="POST">
    @csrf

    @if ($itemRole->id)
        @method('PUT')
    @endif

    <div class="card-body">

        <div class="form-group">
            <label for="name">
                {{ ucfirst(__('PkgAutorisation::role.name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="name"
                type="input"
                class="form-control"
                id="name"
                placeholder="{{ __('PkgAutorisation::role.name') }}"
                value="{{ $itemRole ? $itemRole->name : old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="guard_name">
                {{ ucfirst(__('PkgAutorisation::role.guard_name')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="guard_name"
                type="input"
                class="form-control"
                id="guard_name"
                placeholder="{{ __('PkgAutorisation::role.guard_name') }}"
                value="{{ $itemRole ? $itemRole->guard_name : old('guard_name') }}">
            @error('guard_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="users">
                {{ ucfirst(__('PkgAutorisation::User.plural')) }}
            </label>
            <select
                id="users"
                name="users[]"
                class="form-control select2"
                multiple="multiple">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ (isset($itemRole) && $itemRole->users && $itemRole->users->contains('id', $user->id)) || (is_array(old('users')) && in_array($user->id, old('users'))) ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('users')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        

        
        <div class="form-group">
            <label for="permissions" data-toggle="collapse" data-target="#collapseExample">
                {{ ucfirst(__('Core::Feature.plural')) }}
            </label>
    
            
            <div id="features-list" class="features-list icheck-info d-inline">
           
                @foreach ($sysModules as $sysModule)

                <div class="sys-module card">
                   <div
                    data-toggle="collapse" 
                    data-target="#collapseModule{{$sysModule->id}}" 
                    aria-expanded="false" 
                    aria-controls="collapseModule{{$sysModule->id}}"
                    class="card-header">
                        <h2 class="card-title">
                            <b>
                                {{ $sysModule->name }}
                            </b>
                            
                        </h2>
                    </div>
                        
                    <div id="collapseModule{{$sysModule->id}}"  data-parent="#features-list"  class="collapse card-body collapsecard-body">
                        @foreach ($sysModule->featureDomains as $featureDomain)
                            <div class="feature-domain">
                                <h5>{{ $featureDomain->name }}</h5>
            
                                @foreach ($featureDomain->features as $feature)
                                    <div class="feature-item icheck-info ">
                                       
                                        <input 
                                            type="checkbox" 
                                            id="feature-{{ $feature->id }}" 
                                            name="features[]" 
                                            value="{{ $feature->id }}"
                                            {{ $itemRole->permissions->pluck('id')->intersect($feature->permissions->pluck('id'))->isNotEmpty() ? 'checked' : '' }}>
                                        <label for="feature-{{ $feature->id }}">
                                            {{ $feature->name }}
                                            @if ($feature->description)
                                                <small class="text-muted">({{ $feature->description }})</small>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                  
                    </div>
                @endforeach
            </div>
            


            @error('features')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        
    


    </div>

    <div class="card-footer">
        <a href="{{ route('roles.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $itemRole->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>
@endsection