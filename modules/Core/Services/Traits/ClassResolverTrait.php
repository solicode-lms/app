<?php

namespace Modules\Core\Services\Traits;

trait ClassResolverTrait
{
    /**
     * Résout dynamiquement le nom de la classe à partir de son nom court (ex: "Apprenant"),
     * en cherchant dans les namespaces des modules déclarés dans SoliLMS.
     * En cas d'absence dans la liste en dur, cherche dans modules.json.
     *
     * @param string $className Nom court de la classe (ex: "Apprenant")
     * @return object|null Instance de la classe si trouvée, sinon null
     */
    public function resolveClassByName(string $className): ?object
    {
        $modulePaths = [
            'PkgApprenants', 'PkgFormation', 'PkgCompetences', 'PkgCreationProjet',
            'PkgRealisationProjets', 'PkgCreationTache', 'PkgRealisationTache',
            'PkgApprentissage', 'PkgEvaluateurs', 'PkgNotification', 'PkgAutorisation',
            'PkgWidgets', 'PkgSessions', 'PkgGapp', 'Core'
        ];

        $tryInstantiate = function ($module, $className) {
            $fqcn = "Modules\\$module\\Services\\$className";
            if (class_exists($fqcn)) {
                return new $fqcn();
            }
            $fqcnEntity = "Modules\\$module\\Models\\$className";
            if (class_exists($fqcnEntity)) {
                return new $fqcnEntity();
            }
            return null;
        };

        foreach ($modulePaths as $module) {
            $instance = $tryInstantiate($module, $className);
            if ($instance) return $instance;
        }

        // Fallback : Chercher dans db_schemas/modules.json
        $modulesFile = base_path('db_schemas/modules.json');
        if (file_exists($modulesFile)) {
            $json = json_decode(file_get_contents($modulesFile), true);
            if (is_array($json)) {
                foreach (array_keys($json) as $module) {
                    if (!in_array($module, $modulePaths)) {
                        $instance = $tryInstantiate($module, $className);
                        if ($instance) return $instance;
                    }
                }
            }
        }

        return null;
    }
}
