<?php

namespace App\Controller;

use App\Service\CartService;
use App\Repository\ArticleRepository;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends AbstractController
{
    private bool $useMockData = false; // Mettre à "false" quand la BDD sera prête

    /**
     * Affiche la page de validation du panier et du paiement
     */
    #[Route('/checkout', name: 'checkout_page', methods: ['GET'])]
    public function checkoutPage(CartService $cartService, ArticleRepository $articleRepository): Response
    {
        $cart = $cartService->getCart();
        $total = 0;
        $cartItems = [];

        foreach ($cart as $articleId => $quantity) {
            if ($this->useMockData) {
                // Valeurs par défaut si pas de BDD
                $article = [
                    'id' => $articleId,
                    'name' => "Produit #$articleId",
                    'price' => 10.00, // 10€ par défaut
                    'image' => 'default-image.jpg'
                ];
            } else {
                $article = $articleRepository->find($articleId);
                if (!$article) {
                    continue;
                }
                $article = [
                    'id' => $article->getId(),
                    'name' => $article->getName(),
                    'price' => $article->getPrice(),
                    'image' => $article->getImageUrl() ?? 'default-image.jpg'
                ];
            }

            $total += $article['price'] * $quantity;
            $cartItems[] = [
                'product' => $article,
                'quantity' => $quantity
            ];
        }

        return $this->render('payment/checkout.html.twig', [
            'cart' => $cartItems,
            'total' => $total
        ]);
    }

    /**
     * Initialise le paiement Stripe et renvoie une session
     */
    #[Route('/checkout-session', name: 'checkout', methods: ['POST'])]
    public function checkout(CartService $cartService, ArticleRepository $articleRepository): JsonResponse
    {
        Stripe::setApiKey($this->getParameter('stripe_secret_key'));

        $cart = $cartService->getCart();
        if (empty($cart)) {
            return new JsonResponse(['error' => 'Le panier est vide.'], 400);
        }

        $lineItems = [];
        foreach ($cart as $articleId => $quantity) {
            if ($this->useMockData) {
                $article = [
                    'name' => "Produit #$articleId",
                    'price' => 10.00 // 10€ par défaut
                ];
            } else {
                $article = $articleRepository->find($articleId);
                if (!$article) {
                    continue;
                }
                $article = [
                    'name' => $article->getName(),
                    'price' => $article->getPrice()
                ];
            }

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $article['name'],
                    ],
                    'unit_amount' => $article['price'] * 100, // Prix en centimes
                ],
                'quantity' => $quantity,
            ];
        }

        if (empty($lineItems)) {
            return new JsonResponse(['error' => 'Aucun produit valide trouvé.'], 400);
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('payment_success', [], 0),
            'cancel_url' => $this->generateUrl('payment_cancel', [], 0),
        ]);

        return new JsonResponse(['id' => $session->id]);
    }

    /**
     * Page de succès après paiement
     */
    #[Route('/payment-success', name: 'payment_success')]
    public function success(): Response
    {
        return $this->render('payment/success.html.twig');
    }

    /**
     * Page d'annulation du paiement
     */
    #[Route('/payment-cancel', name: 'payment_cancel')]
    public function cancel(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }
}
