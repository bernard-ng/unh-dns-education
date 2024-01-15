<?php

namespace App\DataFixtures;

use App\Entity\AuthServer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Class AppFixtures.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $schools = [
            [
                'SchoolCode' => 7100910102,
                'SchoolName' => 'Maadini',
                'Address' => '192.168.1.65',
            ],
            [

                'SchoolCode' => 7101210105,
                'SchoolName' => 'Galaxie',
                'Address' => '192.168.1.56'
            ],
            [
                'SchoolCode' => 7103210102,
                'SchoolName' => 'Ribambelle',
                'Address' => '192.168.1.25'
            ],
            [
                'SchoolCode' => 7101240103,
                'SchoolName' => 'Salama',
                'Address' => '192.168.1.57'
            ]
        ];

        foreach ($schools as $school) {
            $tld = new AuthServer();
            $tld
                ->setCode((string) $school['SchoolCode'])
                ->setName($school['SchoolName'])
                ->setIpAddress($school['Address'])
                ->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($tld);
        }

        $manager->flush();
    }
}
