<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgAutorisation\Models\Formateur;
use Modules\PkgFormation\Models\Filiere;

/**
 * Test de création de projet libre (sans session).
 * 
 * Basé sur le scénario : creation_projet_libre.scenario.mmd
 * 
 * @see docs/1.scenarios/PkgCreationProjet/Projet/creation_projet_libre.scenario.mmd
 */
class ProjetCreationTest extends TestCase
{
    use RefreshDatabase;

    protected ProjetService $projetService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->projetService = new ProjetService();
    }

    /**
     * Test : Création d'un projet libre (sans session_id).
     * 
     * Vérifie que :
     * 1. Le projet est créé avec succès
     * 2. Les données sont correctement enregistrées
     * 3. Le hook afterCreateRules ne génère pas de contenu (car pas de session_id)
     * 4. Le projet reste dans un état "vide" (conteneur)
     */
    public function test_formateur_peut_creer_un_projet_libre_sans_session()
    {
        // Arrange : Préparation des données de test
        $formateur = Formateur::factory()->create();
        $filiere = Filiere::factory()->create();

        $projetData = [
            'titre' => 'Projet Libre Test',
            'travail_a_faire' => '<p>Développer une application de gestion</p>',
            'critere_de_travail' => '<ul><li>Code propre</li><li>Tests unitaires</li></ul>',
            'description' => 'Description du projet libre',
            'nombre_jour' => 15,
            'formateur_id' => $formateur->id,
            'filiere_id' => $filiere->id,
            // Pas de session_id => Projet Libre
        ];

        // Act : Création du projet via le service
        $projet = $this->projetService->create($projetData);

        // Assert : Vérifications
        $this->assertInstanceOf(Projet::class, $projet);
        $this->assertDatabaseHas('projets', [
            'titre' => 'Projet Libre Test',
            'formateur_id' => $formateur->id,
            'filiere_id' => $filiere->id,
            'nombre_jour' => 15,
        ]);

        // Le projet ne doit pas avoir de tâches auto-générées (car pas de session)
        $this->assertEquals(0, $projet->taches()->count());

        // Vérification de la référence
        $this->assertNotNull($projet->reference);
    }

    /**
     * Test : Validation des données obligatoires.
     * 
     * Vérifie que la création échoue si des champs requis sont absents.
     */
    public function test_creation_projet_echoue_sans_donnees_obligatoires()
    {
        $this->expectException(\Exception::class);

        // Tentative de création sans titre (champ obligatoire)
        $this->projetService->create([
            'travail_a_faire' => '<p>Test</p>',
        ]);
    }

    /**
     * Test : Vérification du calcul du total_notes.
     * 
     * Le projet libre doit avoir un total_notes de 0 au départ.
     */
    public function test_projet_libre_initial_a_total_notes_zero()
    {
        // Arrange
        $formateur = Formateur::factory()->create();
        $filiere = Filiere::factory()->create();

        $projetData = [
            'titre' => 'Projet Notes Test',
            'travail_a_faire' => '<p>Test</p>',
            'critere_de_travail' => '<p>Test</p>',
            'nombre_jour' => 10,
            'formateur_id' => $formateur->id,
            'filiere_id' => $filiere->id,
        ];

        // Act
        $projet = $this->projetService->create($projetData);

        // Assert
        $this->assertEquals(0, $projet->total_notes);
    }

    /**
     * Test : Vérification de la génération automatique de la référence.
     */
    public function test_projet_genere_reference_automatiquement()
    {
        // Arrange
        $formateur = Formateur::factory()->create([
            'reference' => 'FORM-001'
        ]);
        $filiere = Filiere::factory()->create();

        $projetData = [
            'titre' => 'Projet Référence',
            'travail_a_faire' => '<p>Test</p>',
            'critere_de_travail' => '<p>Test</p>',
            'nombre_jour' => 10,
            'formateur_id' => $formateur->id,
            'filiere_id' => $filiere->id,
        ];

        // Act
        $projet = $this->projetService->create($projetData);

        // Assert
        $this->assertEquals('Projet Référence-FORM-001', $projet->reference);
    }

    /**
     * Test : Vérification que le hook afterCreateRules est bien appelé.
     * 
     * On vérifie indirectement via le fait qu'aucune tâche n'est créée
     * car session_id est null (condition du ProjetCrudTrait).
     */
    public function test_hook_after_create_ne_genere_pas_taches_sans_session()
    {
        // Arrange
        $formateur = Formateur::factory()->create();
        $filiere = Filiere::factory()->create();

        $projetData = [
            'titre' => 'Projet Hook Test',
            'travail_a_faire' => '<p>Test</p>',
            'critere_de_travail' => '<p>Test</p>',
            'nombre_jour' => 10,
            'formateur_id' => $formateur->id,
            'filiere_id' => $filiere->id,
        ];

        // Act
        $projet = $this->projetService->create($projetData);

        // Assert : Pas de tâches créées
        $this->assertCount(0, $projet->taches);

        // Le projet est bien en base
        $this->assertDatabaseHas('projets', [
            'id' => $projet->id,
            'titre' => 'Projet Hook Test',
        ]);
    }
}
