<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cart;
use App\Entity\Article;

class CartService
{
    private $session;
    private $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->session = $requestStack->getSession();
        $this->entityManager = $entityManager;
    }

    public function addToCart(int $articleId, int $quantity = 1, User $user)
    {
        $article = $this->entityManager->getRepository(Article::class)->find($articleId);
        if (!$article) {
            throw new \Exception("L'article n'existe pas.");
        }

        $cartItem = $this->entityManager->getRepository(Cart::class)->findOneBy([
            'user' => $user,
            'article' => $article
        ]);

        if ($cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + $quantity);
        } else {
            $cartItem = new Cart();
            $cartItem->setUser($user);
            $cartItem->setArticle($article);
            $cartItem->setQuantity($quantity);
            $this->entityManager->persist($cartItem);
        }

        $this->entityManager->flush();
        $this->updateSessionCart($user);
    }

    public function getCart(User $user): array
    {
        $sessionCart = $this->session->get('cart', []);
        $cartFromDb = $this->entityManager->getRepository(Cart::class)->findBy(['user' => $user]);

        if (!empty($cartFromDb)) {
            $cart = [];
            foreach ($cartFromDb as $cartItem) {
                $cart[$cartItem->getArticle()->getId()] = [
                    'id' => $cartItem->getArticle()->getId(),
                    'name' => $cartItem->getArticle()->getName(),
                    'price' => $cartItem->getArticle()->getPrice(),
                    'quantity' => $cartItem->getQuantity(),
                    'total' => $cartItem->getQuantity() * $cartItem->getArticle()->getPrice()
                ];
            }

            $this->session->set('cart', $cart);
            return $cart;
        }
        return $sessionCart;
    }

    public function clearCart(User $user)
    {
        // Supprime les données de la session
        $this->session->remove('cart');

        // Supprime les articles du panier en base de données
        $cartItems = $this->entityManager->getRepository(Cart::class)->findBy(['user' => $user]);
        foreach ($cartItems as $cartItem) {
            $this->entityManager->remove($cartItem);
        }

        $this->entityManager->flush();
    }

    public function removeFromCart(int $articleId, User $user)
    {
        // Supprime de la session
        $cart = $this->session->get('cart', []);
        if (isset($cart[$articleId])) {
            unset($cart[$articleId]);
        }
        $this->session->set('cart', $cart);

        // Supprime de la BDD
        $cartItem = $this->entityManager->getRepository(Cart::class)->findOneBy([
            'user' => $user,
            'article' => $articleId
        ]);

        if ($cartItem) {
            $this->entityManager->remove($cartItem);
            $this->entityManager->flush();
        }
    }

    private function updateSessionCart(User $user)
    {
        $cartItems = $this->entityManager->getRepository(Cart::class)->findBy(['user' => $user]);

        $cart = [];
        foreach ($cartItems as $cartItem) {
            $cart[$cartItem->getArticle()->getId()] = [
                'id' => $cartItem->getArticle()->getId(),
                'name' => $cartItem->getArticle()->getName(),
                'price' => $cartItem->getArticle()->getPrice(),
                'quantity' => $cartItem->getQuantity(),
                'total' => $cartItem->getQuantity() * $cartItem->getArticle()->getPrice()
            ];
        }

        $this->session->set('cart', $cart);
    }
}
