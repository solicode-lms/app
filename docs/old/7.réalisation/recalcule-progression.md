       // 🔄 Recalcul global pour remplir le pourcentage_non_valide_cache des données existantes
        // Cela lance un effet domino : Ua -> Micro-compétence -> Compétence -> Module
        RealisationUa::chunk(50, function ($uas) {
            $service = new RealisationUaService();
            foreach ($uas as $ua) {
                $service->calculerProgression($ua);
            }
        });