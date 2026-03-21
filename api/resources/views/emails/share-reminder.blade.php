@component('mail::message')
# Bonjour {{ $userName }},

Votre document **{{ $documentName }}** a été partagé il y a plus de 3 jours et n'a pas encore été téléchargé par votre client.

Vous pouvez renvoyer le lien ou en créer un nouveau depuis votre tableau de bord.

@component('mail::button', ['url' => $shareUrl])
Voir le document partagé
@endcomponent

---
*Cet email a été envoyé automatiquement par Doclico. Si votre client a déjà reçu le document, ignorez ce message.*

Cordialement,
L'équipe Doclico
@endcomponent
