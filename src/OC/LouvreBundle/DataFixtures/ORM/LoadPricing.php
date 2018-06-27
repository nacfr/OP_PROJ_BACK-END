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
            ['title' => 'gratuit', 'minage' => '0', 'maxage' => '3', 'priceht' => '0', 'tva' => '0.2', 'registrationprincing' => $date],
            ['title' => 'enfant', 'minage' => '4', 'maxage' => '11', 'priceht' => '6.67', 'tva' => '0.2', 'registrationprincing' => $date],
            ['title' => 'normal', 'minage' => '12', 'maxage' => '59', 'priceht' => '13.33', 'tva' => '0.2', 'registrationprincing' => $date],
            ['title' => 'senior', 'minage' => '60', 'maxage' => '150', 'priceht' => '10.00', 'tva' => '0.2', 'registrationprincing' => $date],
            ['title' => 'reduit', 'minage' => 'null', 'maxage' => 'null', 'priceht' => '8.33', 'tva' => '0.2', 'registrationprincing' => $date],

        ];

        foreach ($listtab as $tab){
            //Création des tarifs
            $pricing = new Pricing();
            $pricing->setTitle($tab['title']);
            $pricing->setMinage($tab['minage']);
            $pricing->setMaxage($tab['maxage']);
            $pricing->setMaxage($tab['maxage']);
	        $pricing->setPriceht($tab['priceht']);
	        $pricing->setTva($tab['tva']);
            $pricing->setRegistrationpricing($tab['registrationprincing']);

            $manager->persist($pricing);
        }

        $manager->flush();
    }
}