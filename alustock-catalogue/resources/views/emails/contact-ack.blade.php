<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Message bien reçu</title>
</head>
<body style="margin:0;padding:24px;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #d1d5db;">
        <tr>
            <td style="background:#0B1220;padding:16px 24px;color:#ffffff;font-size:13px;letter-spacing:2px;text-transform:uppercase;">
                ALUSTOCK &nbsp;·&nbsp; Accusé de réception
            </td>
        </tr>
        <tr>
            <td style="padding:24px;font-size:14px;line-height:1.6;">
                <p style="margin-top:0;">Bonjour {{ $contact->nom }},</p>
                <p>Nous avons bien reçu votre message et nous vous répondrons dès que possible.
                   Voici une copie de ce que vous nous avez envoyé :</p>

                <div style="border-left:3px solid #06B6D4;background:#f9fafb;padding:12px 16px;margin:16px 0;">
                    <div style="color:#6b7280;font-size:12px;margin-bottom:6px;">Sujet : {{ $contact->sujet }}</div>
                    <div style="white-space:pre-wrap;">{{ $contact->message }}</div>
                </div>

                <p style="font-size:12px;color:#6b7280;margin-bottom:0;">
                    Référence : #{{ $contact->id }} · {{ $contact->created_at->format('d/m/Y à H:i') }}<br>
                    Ceci est un message automatique, merci de ne pas y répondre.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>