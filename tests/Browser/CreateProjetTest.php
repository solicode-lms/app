<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Browser\Components\RichText;
use Tests\Browser\Pages\Login;
use Tests\DuskTestCase;

class CreateProjetTest extends DuskTestCase
{
    public function test_01_formateur_peut_creer_un_projet_via_modal()
    {
        $this->browse(function (Browser $browser) {
            $loginPage = new Login();
            $loginPage->loginAs($browser, 'fouad.essarraj@ofppt-edu.ma', '12345678');

            $browser
            ->visit('/admin/PkgCreationProjet/projets')
            ->waitFor('.addEntityButton')
            ->click('.addEntityButton')
            ->waitFor('#projetForm')
            ->pause(500)
        
            ->type('#titre', 'Projet Test Dusk')
            ->select('#filiere_id', 1)
            ->type('#nombre_jour', 5)
        
            // ✅ Utilisation du composant RichText
            ->tap(function ($browser) {
                (new RichText('#travail_a_faire'))->fill($browser, 'Contenu testé via <b>Dusk</b>');
                (new RichText('#critere_de_travail'))->fill($browser, 'Critères <i>automatisés</i> par Dusk');
            })
        
            ->pause(500)
            ->press('Ajouter')
            ->waitForText('Projet Test Dusk')
            ->assertSee('Projet Test Dusk');
        });
    }

    public function test_02_formateur_peut_ajouter_une_tache_au_projet()
{
    $this->browse(function (Browser $browser) {
        $loginPage = new Login();
        $loginPage->loginAs($browser, 'fouad.essarraj@ofppt-edu.ma', '12345678');

        $browser->visit('/admin/PkgCreationProjet/projets')
            ->waitFor('#projets-crud-card-body table tbody tr:first-child .editEntity')
            ->click('#projets-crud-card-body table tbody tr:first-child .editEntity')
            ->pause(2000)
            ->waitFor('#edit-projet-tab')

            // 🧭 Aller à l'onglet Tâches
            ->click('a#projet-hasmany-tabs-tache-tab')
            ->pause(500)
            ->waitFor('#projet-hasmany-tabs-tache')

            // ➕ Cliquer sur "Ajouter" dans la section Tâches
            ->with('#projet-hasmany-tabs-tache', function (Browser $tab) {
                $tab->waitFor('.addEntityButton')->click('.addEntityButton');
            })

            // 📝 Remplir le formulaire modal
            ->waitFor('#tacheForm')
            ->type('#titre', 'Tâche test Dusk')
            ->select('#priorite_tache_id', 1); // s'il n'est pas auto-rempli

        $browser->pause(500)
            ->press('Ajouter')
            ->waitForText('Tâche test Dusk')
            ->assertSee('Tâche test Dusk');
    });
}

}
