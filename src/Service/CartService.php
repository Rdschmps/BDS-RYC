<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cart; // ✅ Doit être présent ici
use App\Entity\Article;



class CartService
{
    private $session;

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    public function addToCart(int $articleId, int $quantity = 1, User $user, EntityManagerInterface $entityManager)

    {
        $cartItem = $entityManager->getRepository(Cart::class)->findOneBy([
            'user' => $user,
            'article' => $articleId
        ]);

        if ($cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + $quantity);
        } else {
            $cartItem = new Cart();
            $cartItem->setUser($user);
            $cartItem->setArticle($entityManager->getRepository(Article::class)->find($articleId));
            $cartItem->setQuantity($quantity);
            $entityManager->persist($cartItem);
        }

        $entityManager->flush();
    }


    public function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    public function clearCart()
    {
        $this->session->remove('cart');
    }


    public function removeFromCart(int $productId)
    {
        $cart = $this->session->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]); // Supprime l'article du panier
        }

        $this->session->set('cart', $cart);
    }


}
