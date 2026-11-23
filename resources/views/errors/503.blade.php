@extends('errors.layout')

@section('title', 'Service indisponible')
@section('code', '503')
@section('message', $message ?? 'Le service est momentanément indisponible pour maintenance ou mise à niveau.')
