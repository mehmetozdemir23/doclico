@component('mail::message')
# Bonjour{{ $recipientName ? ' ' . $recipientName : '' }},

Vous avez reçu un document partagé : **{{ $documentName }}**.

Cliquez sur le bouton ci-dessous pour le consulter et le télécharger.

@component('mail::button', ['url' => $shareUrl])
Voir le document
@endcomponent

---
*Ce lien de partage peut avoir une date d'expiration. Passé ce délai, il ne sera plus accessible.*

Cordialement,
L'équipe Doclico
@endcomponent
