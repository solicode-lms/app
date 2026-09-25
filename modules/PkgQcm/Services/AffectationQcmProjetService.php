<?php
namespace Modules\PkgQcm\Services;

use Modules\PkgQcm\Services\Base\BaseAffectationQcmProjetService;
use Modules\PkgQcm\Models\RealisationQcm;
use Modules\PkgQcm\Models\EtatRealisationQcm;
use Illuminate\Support\Str;

/**
 * Classe AffectationQcmProjetService pour gérer la persistance de l'entité AffectationQcmProjet.
 */
class AffectationQcmProjetService extends BaseAffectationQcmProjetService
{
    public function afterCreateRules($item)
    {
        $affectationProjet = $item->affectationProjet;
        if (!$affectationProjet) {
            return;
        }

        $apprenants = collect();
        if ($affectationProjet->sous_groupe_id && $affectationProjet->sousGroupe) {
            $apprenants = $affectationProjet->sousGroupe->apprenants;
        } elseif ($affectationProjet->groupe_id && $affectationProjet->groupe) {
            $apprenants = $affectationProjet->groupe->apprenants;
        }

        if ($apprenants->isEmpty()) {
            return;
        }
        $etatAFaire = EtatRealisationQcm::where('reference', 'A_FAIRE')->first();
        $etatId = $etatAFaire ? $etatAFaire->id : null;

        foreach ($apprenants as $apprenant) {
            RealisationQcm::create([
                'affectation_qcm_projet_id' => $item->id,
                'qcm_id' => $item->qcm_id,
                'apprenant_id' => $apprenant->id,
                'etat_realisation_qcm_id' => $etatId,
                'reference' => Str::uuid(),
            ]);
        }
    }

    /**
     * Scrape le contenu d'un lien de chapitre pour l'injecter dans le prompt IA
     */
    public function getTutoContent(string $url): string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) return "";
        
        return \Illuminate\Support\Facades\Cache::remember('tuto_scrape_' . md5($url), 86400, function () use ($url) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(5)->get($url);
                if (!$response->successful()) return "";
                
                $html = $response->body();
                $dom = new \DOMDocument();
                libxml_use_internal_errors(true);
                @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
                libxml_clear_errors();
                
                $xpath = new \DOMXPath($dom);
                $nodes = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " tuto-page ") or contains(concat(" ", normalize-space(@class), " "), " layout-page ")]');
                
                if ($nodes->length > 0) {
                    $text = "";
                    foreach ($nodes as $node) {
                        $text .= $node->textContent . "\n";
                    }
                    return trim(preg_replace('/\s+/', ' ', $text));
                }
            } catch (\Exception $e) {}
            
            return "";
        });
    }
}
