@extends('errors.layout')

@section('title', 'Session expirée')
@section('code', '419')
@section('message', $message ?? 'Votre session a expiré ou le jeton de sécurité est invalide. Veuillez rafraîchir la page et réessayer.')
