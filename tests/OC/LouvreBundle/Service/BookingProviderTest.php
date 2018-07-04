<?php
	/**
	 * Created by PhpStorm.
	 * User: cecile
	 * Date: 03/07/2018
	 * Time: 23:25
	 */
	
	namespace Tests\OC\LouvreBundle\Service;
	
	
	use OC\LouvreBundle\Entity\Pricing;
	use PHPUnit\Framework\TestCase;
	use Doctrine\ORM\EntityManager;
	
	class BookingProviderTest extends TestCase
	{
		public function testCalcAge()
		{
			$date = '16-11-1985';
			
			$am = explode('-', $date);
			$an = explode('-', date('d-m-Y'));
			
			if (($am[1] < $an[1]) || (($am[1] == $an[1]) && ($am[1] <= $an[1])))
				return $an[2] - $am[2];
			
			$finaldate = $an[2] - $am[2] - 1;
			
			$this->assertEquals(32, $finaldate);
		}
		
		public function testCalcAgeObjectDate()
		{
			$dateofbirth = new \DateTime('16-11-1985');
			
			if ($dateofbirth instanceof \DateTime) {
				$date = date_format($dateofbirth, 'd-m-Y');
			}
			
			$am = explode('-', $date);
			$an = explode('-', date('d-m-Y'));
			
			if (($am[1] < $an[1]) || (($am[1] == $an[1]) && ($am[1] <= $an[1])))
				return $an[2] - $am[2];
			
			$finaldate = $an[2] - $am[2] - 1;
			
			$this->assertEquals(32, $finaldate);
		}
		
		public function testGetTicketPrice()
		{
			$pricing = [
				['title' => 'gratuit', 'minage' => '0', 'maxage' => '3', 'priceht' => '0.0', 'tva' => '0.2'],
				['title' => 'enfant', 'minage' => '4', 'maxage' => '11', 'priceht' => '6.67', 'tva' => '0.2'],
				['title' => 'normal', 'minage' => '12', 'maxage' => '59', 'priceht' => '13.33', 'tva' => '0.2'],
				['title' => 'senior', 'minage' => '60', 'maxage' => '150', 'priceht' => '10', 'tva' => '0.2'],
				['title' => 'reduit', 'minage' => '0', 'maxage' => '150', 'priceht' => '8.33', 'tva' => '0.2'],
			];
			
			$ages = 32;
			$reduceprice = 0;
			
			if ($reduceprice == true) {
				$price = round($pricing[4]['priceht'] + ($pricing[4]['priceht'] * $pricing[4]['tva']));
			} elseif ($ages >= $pricing[0]['minage'] && $ages <= $pricing[0]['maxage']) {
				$price = 0;
			} elseif ($ages >= $pricing[1]['minage'] && $ages <= $pricing[1]['maxage']) {
				$price = round($pricing[1]['priceht'] + ($pricing[1]['priceht'] * $pricing[1]['tva']));
			} elseif ($ages >= $pricing[2]['minage'] && $ages <= $pricing[2]['maxage']) {
				$price = round($pricing[2]['priceht'] + ($pricing[2]['priceht'] * $pricing[2]['tva']));
			} elseif ($ages >= $pricing[3]['minage'] && $ages <= $pricing[3]['maxage']) {
				$price = round($pricing[3]['priceht'] + ($pricing[3]['priceht'] * $pricing[3]['tva']));
			}
			
			$result = $price;
			
			$this->assertEquals(16, $result);
		}
		
	
	}