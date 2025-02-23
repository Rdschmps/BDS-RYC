<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\BillingAddressType;
use App\Repository\ArticleRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    private bool $useMockData = false; // Mettre à "false" quand la BDD sera prête

    /**
     * Affiche la page de validation du panier et du paiement
     */
    #[Route('/checkout', name: 'checkout', methods: ['GET', 'POST'])]
    public function checkoutPage(Request $request, CartService $cartService, ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $cartService->getCart($user);
        $total = 0;
        $cartItems = [];

        foreach ($cart as $articleId => $data) {
            if ($this->useMockData) {
                $article = [
                    'id' => $articleId,
                    'name' => "Produit #$articleId",
                    'price' => 10.00,
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

            $total += $article['price'] * $data['quantity'];
            $cartItems[] = [
                'product' => $article,
                'quantity' => $data['quantity']
            ];
        }

        $form = $this->createForm(BillingAddressType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $billingData = $form->getData();
            $request->getSession()->set('billing_address', $billingData);

            return $this->redirectToRoute('checkout_page');
        }

        return $this->render('payment/checkout.html.twig', [
            'cart' => $cartItems,
            'total' => $total,
            'form' => $form->createView()
        ]);
    }

    /**
     * Initialise le paiement Stripe et renvoie une session
     */
    #[Route('/checkout-session', name: 'checkout-session', methods: ['POST'])]
    public function checkout(Request $request, CartService $cartService, ArticleRepository $articleRepository): JsonResponse
    {
        Stripe::setApiKey($this->getParameter('stripe_secret_key'));
    
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non connecté.'], 403);
        }
    
        $billingData = $request->getSession()->get('billing_address');
        if (!$billingData) {
            return new JsonResponse(['error' => 'Adresse de facturation manquante.'], 400);
        }
    
        $cart = $cartService->getCart($user);
        if (empty($cart)) {
            return new JsonResponse(['error' => 'Le panier est vide.'], 400);
        }
    
        $lineItems = [];
        foreach ($cart as $articleId => $data) {
            if ($this->useMockData) {
                $article = [
                    'name' => "Produit #$articleId",
                    'price' => 10.00
                ];
            } else {
            $article = $articleRepository->find($articleId);
            if (!$article) {
                continue;
                }
            }
    
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $article->getName(),
                    ],
                    'unit_amount' => $article->getPrice() * 100,
                ],
                'quantity' => $data['quantity'],
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
    public function success(Request $request, CartService $cartService, ArticleRepository $articleRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $cartService->getCart($user);
        if (empty($cart)) {
            return $this->redirectToRoute('checkout_page');
        }

        $totalAmount = 0;
        $billingData = $request->getSession()->get('billing_address');

        foreach ($cart as $articleId => $data) {
            $article = $articleRepository->find($articleId);
            if ($article && $article->getStock() && $article->getStock()->getQuantity() >= $data['quantity']) {
                $totalAmount += $article->getPrice() * $data['quantity'];
                $article->getStock()->setQuantity($article->getStock()->getQuantity() - $data['quantity']);
                $entityManager->persist($article);
            }
        }

        $invoice = new Invoice();
        $invoice->setUser($user)
            ->setTransactionDate(new \DateTime())
            ->setAmount($totalAmount)
            ->setBillingAddress($billingData['address'])
            ->setBillingCity($billingData['city'])
            ->setBillingPostalCode($billingData['postal_code']);

        $entityManager->persist($invoice);
        $entityManager->flush();

        $cartService->clearCart($user);

        return $this->render('payment/success.html.twig', ['invoice' => $invoice]);
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
