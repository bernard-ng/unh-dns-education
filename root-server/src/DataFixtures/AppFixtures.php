<?php

namespace App\DataFixtures;

use App\Entity\TLDServer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AppFixtures.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tld = new TLDServer();
        $tld
            ->setCode('71')
            ->setName('Haut-Katanga HK')
            ->setIpAddress('192.168.1.117')
            ->setCreatedAt(new \DateTimeImmutable());

        $manager->persist($tld);
        $manager->flush();
    }
}
