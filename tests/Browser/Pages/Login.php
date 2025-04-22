<?php
namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class Login extends Page
{
    public function url()
    {
        return '/login'; // ou '/admin/login' si c’est différent
    }

    // public function login(Browser $browser, $email, $password)
    // {
    //     $browser->visit($this->url())
    //             ->type('email', $email)
    //             ->type('password', $password)
    //             ->press('Sign In') // ou 'Login' selon le bouton
    //             ->assertPathIs('/admin'); // ou autre chemin après login
    // }

    public function loginAs(Browser $browser, $email, $password)
    {
        $browser->visit($this->url())
                ->type('email', $email)
                ->type('password', $password)
                ->press('Sign In') // adapte selon ton bouton
                ->assertAuthenticated();
    }
}
