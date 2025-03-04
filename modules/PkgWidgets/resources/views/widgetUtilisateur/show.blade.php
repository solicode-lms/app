{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgWidgets::widgetUtilisateur.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('widgetUtilisateurs.edit', $itemWidgetUtilisateur->id) }}" class="btn btn-default float-right">
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
                                <label for="user_id">{{ ucfirst(__('PkgWidgets::widgetUtilisateur.user_id')) }}:</label>
                                <p>{{ $itemWidgetUtilisateur->user_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="widget_id">{{ ucfirst(__('PkgWidgets::widgetUtilisateur.widget_id')) }}:</label>
                                <p>{{ $itemWidgetUtilisateur->widget_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="ordre">{{ ucfirst(__('PkgWidgets::widgetUtilisateur.ordre')) }}:</label>
                                <p>{{ $itemWidgetUtilisateur->ordre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="titre">{{ ucfirst(__('PkgWidgets::widgetUtilisateur.titre')) }}:</label>
                                <p>{{ $itemWidgetUtilisateur->titre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="sous_titre">{{ ucfirst(__('PkgWidgets::widgetUtilisateur.sous_titre')) }}:</label>
                                <p>{{ $itemWidgetUtilisateur->sous_titre }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="visible">{{ ucfirst(__('PkgWidgets::widgetUtilisateur.visible')) }}:</label>
                                <p>{{ $itemWidgetUtilisateur->visible }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
