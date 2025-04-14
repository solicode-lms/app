<x-badge 
:text="Str::limit($entity->etatRealisationTache->nom ?? '', 20)" 
:background="$entity->etatRealisationTache->sysColor->hex ?? '#6c757d'" 
/> 