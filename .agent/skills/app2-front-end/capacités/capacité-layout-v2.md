# Capacité : Layouts et Vues V2 (Tailwind & Architecture Modulaire)

## 1. Principe de Séparation Layout / Contenu
Toute page ou vue d'administration V2 doit strictement séparer le gabarit d'infrastructure (Shell) du contenu métier (Indicateurs / Formulaires / Listes).

### Règles d'Or (Inviolables)
1. **Héritage obligatoire** : Toutes les vues de gestion et tableaux de bord V2 doivent étendre `@extends('layouts.admin-v2')`.
2. **Interdiction de Duplication du Shell** : Une vue Blade ne doit **JAMAIS** contenir les balises `<!DOCTYPE html>`, `<html>`, `<head>`, la balise `<aside>` de sidebar, le `<header>` global ou les scripts de toggle. Tout ce code est centralisé dans le layout.
3. **Indicateurs Fonctionnels Uniquement** : Ne **JAMAIS** intégrer dans les vues de production des blocs ou cartes issus de maquettes statiques non fonctionnelles. Les vues contiennent exclusivement les indicateurs calculés et fonctionnels du sprint actif.

---

## 2. Structure Standard d'une Vue V2

```blade
@extends('layouts.admin-v2')

@section('title', 'Titre de la Page — Solicode LMS V2')
@section('page_title', 'Titre Principal')
@section('page_subtitle', 'Sous-titre contextuel')

@section('content')
    <!-- En-tête contextuel éventuel -->
    <section class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        ...
    </section>

    <!-- Sections d'indicateurs fonctionnels réels -->
    <section class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @include('PkgTableauBord::components.kpi-amorcage', ['type' => '...', 'kpi' => $kpiData])
        </div>
    </section>
@endsection
```

---

## 3. Emplacement et Rôles des Fichiers V2
- **Layout principal** : `resources/views/layouts/admin-v2.blade.php`
- **Sidebar V2 (Partial)** : `resources/views/layouts/sidebar-v2.blade.php`
- **Composants d'indicateurs** : `modules/PkgTableauBord/resources/views/components/`
- **Vues spécifiques par rôle** :
  - `modules/PkgTableauBord/resources/views/apprenant/index.blade.php`
  - `modules/PkgTableauBord/resources/views/formateur/index.blade.php`
  - `modules/PkgTableauBord/resources/views/responsable/index.blade.php`
