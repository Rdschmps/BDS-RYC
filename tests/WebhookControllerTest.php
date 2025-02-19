<?php
/** lancement du test php bin/phpunit```php
 * php bin/phpunit
 * stripe logs tail
 * checkout.session.completed
 * payment_intent.failed
 * charge.refunded
 * 
*/ 

namespace App\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class WebhookControllerTest extends WebTestCase
{
    public function testWebhook()
    {
        // On simule un appel POST à l'URL de notre webhook
        $client = static::createClient();
        
        // Créer une "fake" requête du webhook
        $fakeEvent = json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'sess_1234567890',
                    'amount_total' => 5000,
                    'currency' => 'eur',
                ],
            ],
        ]);
        // Envoi de la requête au webhook
        $client->request('POST', '/webhook', [], [], ['CONTENT_TYPE' => 'application/json'], $fakeEvent);
        // Vérifier si le statut de la réponse HTTP est 200
        $this->assertResponseIsSuccessful();
        
        // Vérifier que la réponse contient le message attendu
        $this->assertJsonStringEqualsJsonString(
            '{"status":"success"}',
            $client->getResponse()->getContent()
        );
    }
}