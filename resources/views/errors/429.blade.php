@extends('errors.layout')

@section('title', 'Trop de requêtes')
@section('code', '429')
@section('message', $message ?? 'Vous avez effectué trop de requêtes en peu de temps. Veuillez patienter avant de réessayer.')
