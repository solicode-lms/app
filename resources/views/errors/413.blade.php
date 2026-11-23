@extends('errors.layout')

@section('title', 'Requête trop volumineuse')
@section('code', '413')
@section('message', $message ?? 'Le fichier ou la requête envoyée dépasse la limite de taille maximale autorisée.')
