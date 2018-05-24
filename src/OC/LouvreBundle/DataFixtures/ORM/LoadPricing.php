<?php
/**
 * Created by PhpStorm.
 * User: Pruvost
 * Date: 24/05/2018
 * Time: 14:06
 */

namespace OC\LouvreBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\LouvreBundle\Entity\Pricing;


class LoadPricing implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $date = new \DateTime();

        $listtab = [
            ['title' => 'Gratuit', 'priceht' => '0', 'registrationprincing' => $date],
            ['title' => 'Enfant', 'priceht' => '6.67', 'registrationprincing' => $date],
            ['title' => 'Normal', 'priceht' => '13.33', 'registrationprincing' => $date],
            ['title' => 'Sénior', 'priceht' => '10.00', 'registrationprincing' => $date],
            ['title' => 'Réduit', 'priceht' => '8.33', 'registrationprincing' => $date],

        ];

        foreach ($listtab as $tab){
            //Création des tarifs
            $pricing = new Pricing();
            $pricing->setTitle($tab['title']);
            $pricing->setPriceht($tab['priceht']);
            $pricing->setRegistrationpricing($tab['registrationprincing']);

            $manager->persist($pricing);
        }

        $manager->flush();
    }
}