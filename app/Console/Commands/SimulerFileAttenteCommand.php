<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;

class SimulerFileAttenteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:simuler-file-attente 
                            {--all : Traite toutes les affectations au lieu de la dernière}
                            {--reset : Réinitialise les colonnes de cache de file d\'attente avant de recalculer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rattrape et remplit les données réelles de file d\'attente Live Coding depuis l\'historique (Indicateur 05 — T_file)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("=== Rattrapage des Données de File d'Attente Live Coding (Indicateur 05 — T_file) ===");
        $this->comment("Source des données : historique des changements d'état (historique_realisation_taches)");

        $hasCacheCols = Schema::hasColumn('realisation_taches', 'cache_date_entree_file');
        if (!$hasCacheCols) {
            $this->error("La colonne 'cache_date_entree_file' n'existe pas dans la table 'realisation_taches'.");
            return 1;
        }

        $queryAff = AffectationProjet::with(['realisationProjets.realisationTaches.tache.phaseEvaluation', 'groupe', 'projet']);

        if (!$this->option('all')) {
            $affectation = $queryAff->latest('date_debut')->first();
            if (!$affectation) {
                $this->error("Aucune affectation de projet trouvée.");
                return 1;
            }
            $affectations = collect([$affectation]);
        } else {
            $affectations = $queryAff->get();
        }

        $totalTachesModifiees = 0;
        $totalPassagesEnregistres = 0;
        $totalEnAttente = 0;
        $delaisGlobaux = [];

        foreach ($affectations as $aff) {
            $titreProjet = $aff->projet->titre ?? "Affectation #{$aff->id}";
            $nomGroupe = $aff->groupe->nom ?? ($aff->groupe->code ?? 'Groupe');
            $this->newLine();
            $this->info("Traitement de l'Affectation #{$aff->id} : Projet [{$titreProjet}] — Groupe [{$nomGroupe}]");

            $delaisSession = [];

            foreach ($aff->realisationProjets as $rp) {
                $apprenant = $rp->apprenant;
                $nomApprenant = trim(($apprenant->prenom ?? '') . ' ' . ($apprenant->nom ?? ''));

                foreach ($rp->realisationTaches as $rt) {
                    // Règle didactique : traiter UNIQUEMENT les tâches de Live Coding / Prototype N2
                    $isPrototypeN2 = $rt->isPrototype() 
                        || optional($rt->tache?->phaseEvaluation)->code === 'N2';

                    if (!$isPrototypeN2) {
                        // Nettoyage de sécurité si une tâche hors N2 contenait des données de file
                        if ($rt->cache_date_entree_file !== null || $rt->cache_delai_attente_minutes !== null) {
                            DB::table('realisation_taches')->where('id', $rt->id)->update([
                                'cache_date_entree_file' => null,
                                'cache_date_debut_live_coding' => null,
                                'cache_delai_attente_minutes' => null,
                            ]);
                        }
                        continue;
                    }

                    $histList = HistoriqueRealisationTache::where('realisation_tache_id', $rt->id)
                        ->orderBy('id', 'asc')
                        ->get();

                    $dateEntree = null;
                    $dateDebut = null;

                    foreach ($histList as $h) {
                        $chg = $h->changement;
                        $dateH = Carbon::parse($h->dateModification ?? $h->created_at);

                        // Détection passage en file d'attente
                        if (stripos($chg, 'Prêt pour Live Coding') !== false || stripos($chg, 'READY_FOR_LIVE_CODING') !== false) {
                            $dateEntree = $dateH;
                            $dateDebut = null; // Réinitialise le début si nouvelle entrée (cycle de révision/remédiation)
                        }

                        // Détection démarrage Live Coding
                        if (stripos($chg, 'En Live Coding') !== false || stripos($chg, 'IN_LIVE_CODING') !== false) {
                            if ($dateEntree && $dateH->gte($dateEntree)) {
                                $dateDebut = $dateH;
                            }
                        }
                    }

                    // Règle didactique : "si une information qui n'existe pas il ne faut pas l'insérer"
                    if (!$dateEntree) {
                        // Nettoyage si des valeurs résiduelles étaient présentes
                        if ($rt->cache_date_entree_file !== null || $rt->cache_delai_attente_minutes !== null) {
                            DB::table('realisation_taches')->where('id', $rt->id)->update([
                                'cache_date_entree_file' => null,
                                'cache_date_debut_live_coding' => null,
                                'cache_delai_attente_minutes' => null,
                            ]);
                            $totalTachesModifiees++;
                        }
                        continue;
                    }

                    $updates = [
                        'cache_date_entree_file' => $dateEntree,
                    ];

                    $tacheTitre = $rt->tache->titre ?? 'Live Coding';

                    if ($dateDebut) {
                        // Passé en live coding : calcul du délai réel
                        // Règle didactique : "si on a 0 min dans file d'attente on garde aussi"
                        $delaiMinutes = max(0, (int) $dateEntree->diffInMinutes($dateDebut));
                        $updates['cache_date_debut_live_coding'] = $dateDebut;
                        $updates['cache_delai_attente_minutes'] = $delaiMinutes;

                        $delaisSession[] = $delaiMinutes;
                        $delaisGlobaux[] = $delaiMinutes;
                        $totalPassagesEnregistres++;

                        $this->line(" [Passé]     Apprenant : <fg=cyan>{$nomApprenant}</> | Tâche : {$tacheTitre}");
                        $this->line("             Entrée file : {$dateEntree} | Début Live : {$dateDebut} | Délai : <fg=green>{$delaiMinutes} min</>");
                    } else {
                        // Actuellement en file d'attente
                        $updates['cache_date_debut_live_coding'] = null;
                        $updates['cache_delai_attente_minutes'] = null;

                        $attenteActuelleMin = max(0, (int) $dateEntree->diffInMinutes(now()));
                        $delaisSession[] = $attenteActuelleMin;
                        $delaisGlobaux[] = $attenteActuelleMin;
                        $totalEnAttente++;

                        $this->line(" [En file]   Apprenant : <fg=yellow>{$nomApprenant}</> | Tâche : {$tacheTitre}");
                        $this->line("             Entrée file : {$dateEntree} | Attente actuelle : <fg=yellow>{$attenteActuelleMin} min</>");
                    }

                    DB::table('realisation_taches')->where('id', $rt->id)->update($updates);
                    $totalTachesModifiees++;
                }
            }

            if (!empty($delaisSession)) {
                $moyenneSession = round(array_sum($delaisSession) / count($delaisSession));
                $this->comment(" -> Moyenne observée pour cette session (T_file) : {$moyenneSession} min");
            } else {
                $this->comment(" -> Aucun passage ou attente enregistré dans l'historique de cette session.");
            }
        }

        $this->newLine();
        $this->info("=== Résumé du Rattrapage ===");
        $this->line(" - Tâches analysées/mises à jour : <info>{$totalTachesModifiees}</info>");
        $this->line(" - Passages Live Coding terminés : <info>{$totalPassagesEnregistres}</info>");
        $this->line(" - Apprenants actuellement en file : <info>{$totalEnAttente}</info>");

        $delaiMoyenGlobal = !empty($delaisGlobaux) ? round(array_sum($delaisGlobaux) / count($delaisGlobaux)) : 0;
        $this->line(" - Délai moyen global observé (T_file) : <fg=green;options=bold>{$delaiMoyenGlobal} min</>");

        return 0;
    }
}
