{{-- Affichage Text / Html --}}
@if($nature === 'html')
    {!! $entity->{$column} !!}
@else
    {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($entity->{$column}, 30) !!}
@endif