@extends('errors.layout')

@section('title', 'Page introuvable')
@section('code', '404')
@section('message', $message ?? 'Désolé, la ressource ou la page demandée est introuvable ou a été déplacée.')
