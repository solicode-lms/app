@extends('errors.layout')

@section('title', 'Erreur interne du serveur')
@section('code', '500')
@section('message', $message ?? 'Une anomalie inattendue s\'est produite sur le serveur. Veuillez réessayer ultérieurement.')

@if (!empty($errorId))
    @section('error-id')
        Identifiant d'incident : {{ $errorId }}
    @endsection
@endif
