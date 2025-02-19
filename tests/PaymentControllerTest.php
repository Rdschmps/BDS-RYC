<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaymentControllerTest extends WebTestCase
{
    /**
     * Teste la page de succès après le paiement
     */
    public function testPaymentSuccessPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/payment-success');

        // Vérifier si la page est bien chargée
        $this->assertResponseIsSuccessful();
        
        // Vérifier que le titre contient le message de succès
        $this->assertSelectorTextContains('h1', '🎉 Paiement Réussi !');
    }

    /**
     * Teste la page d'annulation après l'annulation du paiement
     */
    public function testPaymentCancelPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/payment-cancel');

        // Vérifier si la page est bien chargée
        $this->assertResponseIsSuccessful();

        // Vérifier que le titre contient le message d'annulation
        $this->assertSelectorTextContains('h1', '❌ Paiement Annulé');
    }

    /**
     * Teste la création de la session Stripe
     */
    public function testStripeCheckoutSession()
    {
        $client = static::createClient();

        // Simuler une requête POST pour créer une session Stripe
        $client->request('POST', '/checkout-session');

        // Vérifier si la réponse est bien celle attendue
        $this->assertResponseIsSuccessful();
        
        // Vérifier si la réponse contient un ID de session Stripe
        $this->assertJson($client->getResponse()->getContent());
        $data = json_decode($client->getResponse()->getContent(), true);
        
        // Vérifier que l'ID de session est bien présent
        $this->assertArrayHasKey('id', $data);
    }

    /**
     * Teste la page de validation du panier sans base de données
     * Utilise la variable de contrôle pour afficher les valeurs par défaut
     */
    public function testCheckoutPageWithoutDatabase()
    {
        // Simuler le client
        $client = static::createClient();
        
        // Simuler une requête GET pour accéder à la page de paiement
        $client->request('GET', '/checkout');

        // Vérifier que la page est bien chargée
        $this->assertResponseIsSuccessful();

        // Vérifier que le total du panier est visible sur la page
        $this->assertSelectorExists('.cart-total');
    }

    /**
     * Teste l'ajout de produit au panier et le calcul du total avant paiement
     */
    public function testAddToCartAndCheckout()
    {
        // Simuler un client
        $client = static::createClient();

        // Ajouter un produit au panier (exemple : ID 1)
        $client->request('GET', '/add-to-cart/1');
        
        // Accéder à la page de validation du panier
        $client->request('GET', '/checkout');

        // Vérifier que la page du panier est chargée correctement
        $this->assertResponseIsSuccessful();

        // Vérifier que le produit ajouté est bien visible
        $this->assertSelectorTextContains('.cart-product', 'Produit #1');
        
        // Vérifier que le total est calculé correctement (dans ce cas, valeur par défaut)
        $this->assertSelectorTextContains('.cart-total', 'Total: 10,00€');
    }
}