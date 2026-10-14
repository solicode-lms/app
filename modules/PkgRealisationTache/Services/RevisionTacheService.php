<?php

namespace Modules\PkgRealisationTache\Services;

use Illuminate\Support\Facades\DB;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Models\RevisionTache;
use Modules\PkgRealisationTache\Services\Base\BaseRevisionTacheService;

/**
 * Classe RevisionTacheService pour gérer la persistance et la synchronisation des révisions de tâches.
 */
class RevisionTacheService extends BaseRevisionTacheService
{
    /**
     * Extrait et nettoie le message formateur à partir d'un texte ou d'une entrée d'historique.
     * Recherche prioritairement le marqueur officiel : "Remarques - formateur :"
     *
     * @param string|null $texte
     * @return string|null
     */
    public function extraireMessageFormateur(?string $texte): ?string
    {
        if (empty($texte)) {
            return null;
        }

        // 1. Recherche du marqueur officiel "Remarques - formateur :"
        if (preg_match('/Remarques\s*-\s*formateur\s*:\s*(.*?)(?:<\/br>|<br\s*\/?>|\n|$)/is', $texte, $matches)) {
            $msg = trim(strip_tags(html_entity_decode($matches[1])));
            if (!empty($msg)) {
                return $msg;
            }
        }

        // 2. Si le texte complet commence par ou contient le texte brut sans délimiteur br
        if (stripos($texte, 'Remarques - formateur :') !== false) {
            $parts = explode('Remarques - formateur :', $texte);
            if (isset($parts[1])) {
                $raw = explode('</br>', $parts[1])[0];
                $msg = trim(strip_tags(html_entity_decode($raw)));
                if (!empty($msg)) {
                    return $msg;
                }
            }
        }

        // 3. Fallback : nettoyage direct si texte fourni sans scories
        $nettoye = trim(strip_tags(html_entity_decode($texte)));
        return !empty($nettoye) ? $nettoye : null;
    }

    /**
     * Enregistre automatiquement une tentative de révision lorsqu'une tâche est renvoyée en révision.
     *
     * @param RealisationTache $realisationTache
     * @param string|null $messageFormateur
     * @return RevisionTache
     */
    public function enregistrerDemandeRevision(RealisationTache $realisationTache, ?string $messageFormateur = null): RevisionTache
    {
        $messageNettoye = $this->extraireMessageFormateur($messageFormateur ?? $realisationTache->remarque_evaluateur ?? $realisationTache->remarques);

        $nbExistants = RevisionTache::where('realisation_tache_id', $realisationTache->id)->count();
        $tentative = $nbExistants + 1;

        $revision = RevisionTache::create([
            'reference' => 'REV-RT' . $realisationTache->id . '-' . $tentative . '-' . substr(uniqid(), -4),
            'realisation_tache_id' => $realisationTache->id,
            'tentative' => $tentative,
            'date_demande' => now(),
            'message_formateur' => $messageNettoye,
            'est_valide' => false,
        ]);

        $this->synchroniserCacheRevisionDepuisRevisionTaches($realisationTache->id);

        return $revision;
    }

    /**
     * Recalcule et synchronise le cache_nombre_revisions sur la table realisation_taches
     * à partir des enregistrements réels dans revision_taches.
     *
     * @param int|RealisationTache $realisationTacheId
     * @return void
     */
    public function synchroniserCacheRevisionDepuisRevisionTaches($realisationTacheId): void
    {
        $id = is_object($realisationTacheId) ? $realisationTacheId->id : (int) $realisationTacheId;
        if (!$id) {
            return;
        }

        $nbRevisions = RevisionTache::where('realisation_tache_id', $id)->count();

        DB::table('realisation_taches')
            ->where('id', $id)
            ->update([
                'cache_nombre_revisions' => $nbRevisions,
                'updated_at' => now(),
            ]);
    }

    /**
     * Hook après création d'une révision
     */
    public function afterCreateRules($item)
    {
        if ($item && $item->realisation_tache_id) {
            $this->synchroniserCacheRevisionDepuisRevisionTaches($item->realisation_tache_id);
        }
    }

    /**
     * Hook après modification d'une révision
     */
    public function afterUpdateRules($item, $id)
    {
        if ($item && $item->realisation_tache_id) {
            $this->synchroniserCacheRevisionDepuisRevisionTaches($item->realisation_tache_id);
        }
    }

    /**
     * Hook après suppression d'une révision
     */
    public function afterDeleteRules($item, $id)
    {
        if ($item && $item->realisation_tache_id) {
            $this->synchroniserCacheRevisionDepuisRevisionTaches($item->realisation_tache_id);
        }
    }
}
