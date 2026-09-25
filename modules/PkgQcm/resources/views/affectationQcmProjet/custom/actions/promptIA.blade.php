<a
    title="Générer un prompt IA"
    href="{{ route('affectationQcmProjets.prompt', [
            'id' => $entity->id,
            'showIndex' => true,
    ]) }}"
    class="btn btn-default btn-sm"
    data-id="{{ $entity->id }}">
    <i class="fas fa-robot"></i>
</a>
