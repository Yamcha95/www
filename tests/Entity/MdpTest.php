<?php
namespace App\Tests\Entity;

use App\Entity\Mdp;
use PHPUnit\Framework\TestCase;

class MdpTest extends TestCase
{
    public function testGetSetTitle()
    {
        $mdp = new Mdp();
        $mdp->setTitre('Mon compte Gmail');
        $this->assertEquals('Mon compte Gmail', $mdp->getTitre());
    }

    public function testGetSetPassword()
    {
        $mdp = new Mdp();
        $mdp->setMdp('motdepasse123');
        $this->assertEquals('motdepasse123', $mdp->getMdp());
    }

}
