<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_page')]
    public function cart(CartService $cartService): Response
    {
        $useFakeData = true; // Active les données fictives
        
        $cart = $cartService->getCart();
        $products = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            if ($useFakeData) {
                // Produit fictif (sans base de données)
                $product = [
                    'id' => $productId,
                    'name' => "Produit $productId",
                    'price' => 1000, // 10€
                    'image' => 'default-image.jpg'
                ];
            } else {
                // Normalement, récupération depuis la base de données
                $product = $productRepository->find($productId);
            }

            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product['price'] * $quantity
                ];
                $total += $product['price'] * $quantity;
            }
        }

        return $this->render('cart/cart.html.twig', [
            'products' => $products,
            'total' => $total
        ]);
    }





    #[Route('/add-to-cart/{id}', name: 'add_to_cart', methods: ['GET'])]
    public function addToCart(int $id, CartService $cartService): RedirectResponse
    {
        $cartService->addToCart($id, 1);

        return $this->redirectToRoute('cart_page');
    }
}