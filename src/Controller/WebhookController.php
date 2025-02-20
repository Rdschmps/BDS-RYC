<?php
namespace App\Controller;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class WebhookController extends AbstractController
{
    #[Route('/webhook', name: 'stripe_webhook', methods: ['POST'])]
    public function webhook(Request $request): JsonResponse
    {
        // Stripe secret key (à utiliser en production, si nécessaire)
        Stripe::setApiKey($this->getParameter('stripe_secret_key'));
        // Décoder le contenu du webhook reçu de Stripe
        $payload = json_decode($request->getContent(), true);
        $eventType = $payload['type'] ?? null;
        // Vérifier si l'événement est un paiement réussi
        if ($eventType === 'checkout.session.completed') {
            // Logique pour mettre à jour la base de données ou effectuer d'autres actions
            // Par exemple : marquer une commande comme payée
        }
        // Retourner une réponse HTTP 200 pour indiquer à Stripe que l'événement est bien reçu
        return new JsonResponse(['status' => 'success']);
    }
}