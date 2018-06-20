<?php
/**
 * Created by PhpStorm.
 * User: Pruvost
 * Date: 20/06/2018
 * Time: 12:38
 */

namespace OC\LouvreBundle\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TicketTypeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        dump($this->getTimeOfDay($value));
        if($this->getTimeOfDay($value)){
            $this->context->addViolation($constraint->message);
        }
    }

    /**
     * @param $typeticket
     * @return bool
     */
    private function getTimeOfDay($typeticket){

        $date = new \DateTime( 'now',  new \DateTimeZone( 'Europe/Paris' ) );
        $time = date_format($date, 'H');

        if (is_null($typeticket)){
            return false;
        }
        if($typeticket === 'day' & $time >= 14){
            return true;
        }

    }

}