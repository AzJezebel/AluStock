<?php

namespace Tests\Feature;

use App\Mail\ContactAcknowledgementMail;
use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Jeu d'essai du formulaire de contact (cas 1 à 12 du dossier projet).
 * Le jeton CSRF est désactivé automatiquement par Laravel en test : le cas 10
 * (requête sans jeton, HTTP 419) se vérifie à la main dans le navigateur.
 */
class ContactTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'nom'     => 'Marie Dupont',
            'email'   => 'marie@example.com',
            'sujet'   => 'Devis porte',
            'message' => 'Bonjour, je souhaite un devis pour une porte.',
        ], $overrides);
    }

    // Cas 1
    public function test_message_valide_est_enregistre_et_envoye(): void
    {
        Mail::fake();

        $this->post('/contact', $this->payload())->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'email'  => 'marie@example.com',
            'statut' => ContactMessage::STATUT_ENVOYE,
        ]);
        Mail::assertSent(ContactMessageMail::class);
        Mail::assertSent(ContactAcknowledgementMail::class);
    }

    // Cas 2
    public function test_champs_vides_sont_refuses(): void
    {
        $this->post('/contact', [])
             ->assertSessionHasErrors(['nom', 'email', 'sujet', 'message']);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    // Cas 3
    public function test_email_invalide_est_refuse(): void
    {
        $this->post('/contact', $this->payload(['email' => 'abc']))
             ->assertSessionHasErrors('email');
    }

    // Cas 4
    public function test_message_trop_court_est_refuse(): void
    {
        $this->post('/contact', $this->payload(['message' => 'court']))
             ->assertSessionHasErrors('message');
    }

    // Cas 5
    public function test_message_trop_long_est_refuse(): void
    {
        $this->post('/contact', $this->payload(['message' => str_repeat('a', 5001)]))
             ->assertSessionHasErrors('message');
    }

    // Cas 6
    public function test_champ_piege_rempli_est_refuse(): void
    {
        Mail::fake();

        $this->post('/contact', $this->payload(['website' => 'http://spam.example']))
             ->assertSessionHasErrors('website');

        $this->assertDatabaseCount('contact_messages', 0);
        Mail::assertNothingSent();
    }

    // Cas 7 : le texte est conservé tel quel (il sera échappé à l'affichage par Blade)
    public function test_balise_script_est_enregistree_sans_etre_executee(): void
    {
        Mail::fake();
        $text = '<script>alert(1)</script> bonjour tout le monde';

        $this->post('/contact', $this->payload(['message' => $text]))
             ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('contact_messages', ['message' => $text]);
    }

    // Cas 8 : 3 envois par minute et par IP, le 4e est refusé
    public function test_limitation_de_debit_au_quatrieme_envoi(): void
    {
        Mail::fake();

        for ($i = 0; $i < 3; $i++) {
            $this->post('/contact', $this->payload());
        }

        $this->post('/contact', $this->payload())->assertStatus(429);
    }

    // Cas 9 : le serveur de messagerie est en panne
    public function test_echec_d_envoi_conserve_le_message(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP indisponible'));

        $this->post('/contact', $this->payload())->assertSessionHas('error');

        $this->assertDatabaseHas('contact_messages', [
            'email'  => 'marie@example.com',
            'statut' => ContactMessage::STATUT_ECHEC,
        ]);
    }

    // Cas 11
    public function test_nom_trop_long_est_refuse(): void
    {
        $this->post('/contact', $this->payload(['nom' => str_repeat('a', 121)]))
             ->assertSessionHasErrors('nom');
    }

    // Cas 12
    public function test_apostrophes_et_guillemets_sont_acceptes(): void
    {
        Mail::fake();
        $text = 'L’ouvrage "ALN-877" et l\'option d\'achat, svp merci';

        $this->post('/contact', $this->payload(['message' => $text]))
             ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('contact_messages', ['message' => $text]);
    }
}
