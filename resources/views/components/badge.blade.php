{{-- resources/views/components/badge.blade.php --}}
@props(['text' => null, 'background' => null, 'color' => null])

@php
  // Classes de base (Bootstrap compatible)
  $classes = 'badge px-2 py-1 rounded';

  // On prépare des variables CSS plutôt que du style inline (plus propre)
  $style = '';

  // Si background est un HEX, on alimente --badge-accent (qui sera mixé côté CSS)
  if (filled($background) && str_starts_with($background, '#')) {
      $style .= "--badge-accent: {$background};";
  } elseif (filled($background)) {
      // Sinon on considère que c’est une classe utilitaire (ex: bg-success)
      $classes .= " {$background}";
  }

  // Si color est un HEX, on alimente --badge-fg
  if (filled($color) && str_starts_with($color, '#')) {
      $style .= "--badge-fg: {$color};";
  } elseif (filled($color)) {
      // Sinon on considère que c’est une classe utilitaire (ex: text-dark)
      $classes .= " text-{$color}";
  }
@endphp

@if(filled($text))
  <span {{ $attributes->merge(['class' => $classes.' badge-etat'])->except(['background','color']) }}
        @if($style) style="{{ $style }}" @endif>
    {{ Str::limit($text, 50) }}
  </span>
@endif
