<?php
namespace App\Controller;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class PaymentController extends AbstractController
{
    #[Route('/checkout', name: 'checkout', methods: ['POST'])]
    public function checkout(Request $request): JsonResponse
    {
        Stripe::setApiKey($this->getParameter('stripe_secret_key'));
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Produit Test',
                    ],
                    'unit_amount' => 5000, // 50.00€
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('payment_success', [], 0),
            'cancel_url' => $this->generateUrl('payment_cancel', [], 0),
        ]);
        return new JsonResponse(['id' => $session->id]);
    }
    #[Route('/payment-success', name: 'payment_success')]
    public function success()
    {
        return $this->render('payment/success.html.twig');
    }
    #[Route('/payment-cancel', name: 'payment_cancel')]
    public function cancel()
    {
        return $this->render('payment/cancel.html.twig');
    }
}