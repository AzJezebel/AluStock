<?php
// database/seeders/AdminUserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Crée (ou met à jour) ton compte admin. Modifie les valeurs ci-dessous directement.
     * Relançable sans risque : on cherche l'utilisateur par son email.
     */
    public function run(): void
    {
        $user = User::firstOrNew(['email' => 'admin@alustock.fr']);

        // forceFill : 'role' n'est pas dans le #[Fillable] du modèle User
        $user->forceFill([
            'name'     => 'admin', // nom affiché dans le back-office
            'password' => 'password',   // haché automatiquement (cast 'hashed' du modèle)
            'role'     => 'super_admin',  // 'admin' ou 'super_admin' (voir isAdmin() / isSuperAdmin())
        ])->save();
    }
}