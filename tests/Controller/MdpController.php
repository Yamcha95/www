<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MdpControllerTest extends WebTestCase
{
    public function testAddMdpPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/mdp/add'); // adapte l’URL selon ta route

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Ajouter un mot de passe');

        $form = $crawler->selectButton('Enregistrer')->form();
        $form['mdp[title]'] = 'Test Gmail';
        $form['mdp[password]'] = 'motdepasse123';

        $client->submit($form);
        $client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Mot de passe ajouté avec succès');
    }

    // Ajoute d’autres tests pour modifier, supprimer, afficher la liste...
}
