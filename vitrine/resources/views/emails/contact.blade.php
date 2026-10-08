<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Nouveau message de contact</title>
</head>
<body style="margin:0;padding:24px;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #d1d5db;">
        <tr>
            <td style="background:#0B1220;padding:16px 24px;color:#ffffff;font-size:13px;letter-spacing:2px;text-transform:uppercase;">
                ALUSTOCK &nbsp;·&nbsp; Nouveau message de contact
            </td>
        </tr>
        <tr>
            <td style="padding:24px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;line-height:1.5;">
                    <tr>
                        <td style="padding:4px 0;color:#6b7280;width:110px;">Nom</td>
                        <td style="padding:4px 0;"><strong>{{ $contact->nom }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0;color:#6b7280;">Courriel</td>
                        <td style="padding:4px 0;"><a href="mailto:{{ $contact->email }}" style="color:#0369a1;">{{ $contact->email }}</a></td>
                    </tr>
                    @if($contact->entreprise)
                        <tr>
                            <td style="padding:4px 0;color:#6b7280;">Entreprise</td>
                            <td style="padding:4px 0;">{{ $contact->entreprise }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td style="padding:4px 0;color:#6b7280;">Sujet</td>
                        <td style="padding:4px 0;">{{ $contact->sujet }}</td>
                    </tr>
                </table>

                <hr style="border:none;border-top:1px solid #e5e7eb;margin:20px 0;">

                <div style="font-size:14px;line-height:1.6;white-space:pre-wrap;">{{ $contact->message }}</div>

                <hr style="border:none;border-top:1px solid #e5e7eb;margin:20px 0;">

                <p style="font-size:12px;color:#6b7280;margin:0;">
                    Message #{{ $contact->id }} · reçu le {{ $contact->created_at->format('d/m/Y à H:i') }}
                    · IP {{ $contact->ip ?? 'inconnue' }}<br>
                    Répondez directement à ce courriel : la réponse ira à {{ $contact->email }}.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>