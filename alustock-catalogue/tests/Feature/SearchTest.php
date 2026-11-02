<?php

namespace Tests\Feature;

use App\Models\Ouvrage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Jeu d'essai de la recherche (cas A6 du dossier projet) : jokers LIKE échappés
 * et ouvrages inactifs jamais exposés.
 */
class SearchTest extends TestCase
{
    use RefreshDatabase;

    private function ouvrage(string $reference, string $nom, bool $actif = true): Ouvrage
    {
        return Ouvrage::create(['reference' => $reference, 'nom' => $nom, 'est_actif' => $actif]);
    }

    public function test_une_saisie_trop_courte_ne_renvoie_rien(): void
    {
        $this->ouvrage('ALN-1', 'Porte battante');

        $this->getJson('/search/autocomplete?q=p')->assertOk()->assertExactJson([]);
    }

    // Cas A6 : « %_ » ne doit pas être interprété comme « tout »
    public function test_les_jokers_sql_sont_traites_comme_du_texte(): void
    {
        $this->ouvrage('ALN-1', 'Porte battante');
        $this->ouvrage('ALN-2', 'Fenêtre coulissante');

        $this->getJson('/search/autocomplete?q=%25_')->assertOk()->assertExactJson([]);
    }

    public function test_un_ouvrage_actif_est_trouve(): void
    {
        $this->ouvrage('ALN-1', 'Porte battante');

        $this->getJson('/search/autocomplete?q=Porte')->assertOk()->assertSee('ALN-1');
    }

    public function test_un_ouvrage_inactif_n_est_jamais_expose(): void
    {
        $this->ouvrage('ALN-9', 'Porte secrète', false);

        $this->getJson('/search/autocomplete?q=secrète')->assertOk()->assertDontSee('ALN-9');
    }
}
