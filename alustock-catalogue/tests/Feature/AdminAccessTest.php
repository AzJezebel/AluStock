<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Jeu d'essai de l'authentification administrateur (cas A1 à A5 du dossier projet).
 * Le cache de test est en mémoire (CACHE_STORE=array) : le compteur d'échecs
 * repart de zéro à chaque test.
 */
class AdminAccessTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.admin_password' => 'mot-de-passe-de-test']);
    }

    // Cas A4
    public function test_admin_sans_connexion_redirige_vers_la_connexion(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
        $this->get('/admin/ouvrages')->assertRedirect(route('admin.login'));
        $this->get('/admin/categories')->assertRedirect(route('admin.login'));
    }

    public function test_page_de_connexion_est_accessible(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    // Cas A1
    public function test_bon_mot_de_passe_ouvre_la_session(): void
    {
        $this->post('/admin/login', ['password' => 'mot-de-passe-de-test'])
             ->assertRedirect(route('admin.dashboard'));

        $this->assertTrue(session()->has('admin_logged_in'));
    }

    // Cas A2
    public function test_mauvais_mot_de_passe_est_refuse(): void
    {
        $this->post('/admin/login', ['password' => 'faux'])
             ->assertSessionHas('error', 'Mot de passe incorrect.');

        $this->assertFalse(session()->has('admin_logged_in'));
    }

    // Cas A3 : verrouillage au 5e échec, même avec le bon mot de passe
    public function test_verrouillage_apres_cinq_echecs(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', ['password' => 'faux']);
        }

        $this->post('/admin/login', ['password' => 'mot-de-passe-de-test']);

        $this->assertFalse(session()->has('admin_logged_in'));
        $this->assertStringContainsString('Trop de tentatives', session('error'));
    }

    // Cas A5 : pas de mot de passe par défaut
    public function test_mot_de_passe_vide_en_configuration_refuse_tout_acces(): void
    {
        config(['app.admin_password' => '']);

        $this->post('/admin/login', ['password' => 'x']);

        $this->assertFalse(session()->has('admin_logged_in'));
    }

    public function test_deconnexion_ferme_la_session(): void
    {
        $this->withSession(['admin_logged_in' => true])
             ->post('/admin/logout')
             ->assertRedirect(route('admin.login'));

        $this->assertFalse(session()->has('admin_logged_in'));
    }
}
