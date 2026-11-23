@extends('errors.layout')

@section('title', 'Méthode HTTP non autorisée')
@section('code', '405')
@section('message', $message ?? 'La méthode HTTP utilisée n\'est pas autorisée pour cette requête.')
