<?php
	/**
	 * Created by PhpStorm.
	 * User: cecile
	 * Date: 19/06/2018
	 * Time: 22:50
	 */
	
	namespace OC\LouvreBundle\Validator;
	
	
	use Symfony\Component\Validator\Constraint;
	use Symfony\Component\Validator\ConstraintValidator;
	
	class HolidayValidator extends ConstraintValidator
	{
		public function validate($value, Constraint $constraint)
		{
			if ($this->getHolidays($value)) {
				$this->context->addViolation($constraint->message);
			}
		}
		
		
		/**
		 * @param $date
		 * @return bool
		 */
		private function getHolidays($date)
		{
			
			{
				if (is_null($date)) {
					$date = new \DateTime();
				}
				
				$easterDate = new \DateTime('@' . easter_date($date->format('Y')));
				
				$easterDate->setTimezone(new \DateTimeZone('Europe/Paris'));
				$date->setTimezone(new \DateTimeZone('Europe/Paris'));


				$publicHolidaysList = array(
					new \DateTime($date->format('Y-1-1')),
					new \DateTime($date->format('Y-4-2')),
					new \DateTime($date->format('Y-5-1')),
					new \DateTime($date->format('Y-5-8')),
					new \DateTime($date->format('Y-5-10')),
					new \DateTime($date->format('Y-5-21')),
					new \DateTime($date->format('Y-7-14')),
					new \DateTime($date->format('Y-8-15')),
					new \DateTime($date->format('Y-11-1')),
					new \DateTime($date->format('Y-11-11')),
					new \DateTime($date->format('Y-12-25')),
					(new \DateTime($easterDate->format('Y-m-d')))->modify('+ 1 day'),
					(new \DateTime($easterDate->format('Y-m-d')))->modify('+ 39 day'),
					(new \DateTime($easterDate->format('Y-m-d')))->modify('+ 50 day'),
				);
				
				usort($publicHolidaysList, function ($a, $b) {
					$interval = $a->diff($b);
					return $interval->invert ? 1 : -1;
				});
				
				return in_array($date, $publicHolidaysList);
			}
		}
	}