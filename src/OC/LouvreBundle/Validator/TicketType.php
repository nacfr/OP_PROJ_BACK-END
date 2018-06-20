<?php
/**
 * Created by PhpStorm.
 * User: Pruvost
 * Date: 20/06/2018
 * Time: 12:36
 */

namespace OC\LouvreBundle\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TicketType extends Constraint
{
    public $message = "Impossible de réserver un billet journée pour cette date";
}