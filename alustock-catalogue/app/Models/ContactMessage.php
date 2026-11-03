<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    public const STATUT_EN_ATTENTE = 'en_attente';
    public const STATUT_ENVOYE     = 'envoye';
    public const STATUT_ECHEC      = 'echec';

    protected $fillable = [
        'nom',
        'email',
        'entreprise',
        'sujet',
        'message',
        'ip',
        'user_agent',
        'statut',
        'erreur',
        'envoye_le',
        'lu_le',
    ];

    protected $casts = [
        'envoye_le' => 'datetime',
        'lu_le'     => 'datetime',
    ];

    public function markSent(): void
    {
        $this->update([
            'statut'    => self::STATUT_ENVOYE,
            'envoye_le' => now(),
            'erreur'    => null,
        ]);
    }

    public function markFailed(string $error): void
    {
        $this->update([
            'statut' => self::STATUT_ECHEC,
            'erreur' => mb_substr($error, 0, 1000),
        ]);
    }
}