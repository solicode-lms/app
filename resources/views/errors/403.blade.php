@extends('errors.layout')

@section('title', 'Accès non autorisé')
@section('code', '403')
@section('message', $message ?? 'Vous ne possédez pas les autorisations nécessaires pour accéder à cette ressource.')
