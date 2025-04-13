{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgWidgets::sectionWidget.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('sectionWidgets.edit', $itemSectionWidget->id) }}" class="btn btn-default float-right">
                        <i class="far fa-edit"></i>
                        {{ __('Core::msg.edit') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-sm-12">
                                <label for="ordre">{{ ucfirst(__('PkgWidgets::sectionWidget.ordre')) }}:</label>
                                <p>{{ $itemSectionWidget->ordre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="icone">{{ ucfirst(__('PkgWidgets::sectionWidget.icone')) }}:</label>
                                <p>{{ $itemSectionWidget->icone }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="titre">{{ ucfirst(__('PkgWidgets::sectionWidget.titre')) }}:</label>
                                <p>{{ $itemSectionWidget->titre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="sous_titre">{{ ucfirst(__('PkgWidgets::sectionWidget.sous_titre')) }}:</label>
                                <p>{{ $itemSectionWidget->sous_titre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="sys_color_id">{{ ucfirst(__('PkgWidgets::sectionWidget.sys_color_id')) }}:</label>
                                <p>{{ $itemSectionWidget->sys_color_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
