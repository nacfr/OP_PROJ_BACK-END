<?php
/**
 * Created by PhpStorm.
 * User: Pruvost
 * Date: 16/05/2018
 * Time: 13:47
 */

namespace OC\LouvreBundle\Controller;

use OC\LouvreBundle\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use OC\LouvreBundle\Form\BookingType;


class BookingController extends Controller
{
    public function bookingAction(Request $request)
    {
        $booking = new Booking();

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        $validator = $this->get('validator');
        $errors = $validator->validate($booking);
        $bookingprovider = $this->get('oc_louvre.bookingprovider');
        $summaries = $bookingprovider->getPendingOrder();

        if ($form->isSubmitted() && $form->isValid()) {

            if (count($errors) > 0) {
                return $this->render('@OCLouvre/Louvre/booking.html.twig', array(
                        'form' => $form->createView(),
                        'errors' => $errors
                    )
                );
            }

            $availableday = $this->getDispoTicketByDate($booking->getBookingdate());
            dump($availableday);
            if ($availableday) {
                $em = $this->getDoctrine()->getManager();

                //Hydrate les prix pour chaque tickets
                $tickets = $booking->getTickets();
                foreach ($tickets as $ticket) {
                    $dateofbirth = $ticket->getDateofbirth();
                    $reduceprice = $ticket->getReduceprice();
                    $price = $bookingprovider->getTicketPrice($dateofbirth, $reduceprice);

                    $ticket->setTypeticket($price['type']);
                    $ticket->setPrice($price['price']);
                }

                //Hydrate le prix total
                $booking->setTotalprice($bookingprovider->getTotalTicket($booking));

                $em->persist($booking);
                $em->flush();

                //Mise en session du token
                $this->get('session')->set('token', $booking->getBtoken());

                return $this->redirectToRoute('oc_louvre_summary', array('btoken' => $booking->getBtoken()));
            }
            $error = 'Le plafond du nombre de tickets disponible a été atteind. Veuillez choisir une autre journée.';
            $this->addFlash('info', $error);
            return $this->redirectToRoute("oc_louvre_homepage");

        }
        return $this->render('@OCLouvre/Louvre/booking.html.twig', array(
                'summaries' => $summaries,
                'form' => $form->createView()
            )
        );
    }

    /**
     * @param $date
     * @return bool
     */
    private function getDispoTicketByDate($date)
    {

        //$dates = $this->entityManager->getRepository('OCLouvreBundle:Booking')->findBy(['bookingdate' => $date]);
        $dates = $this->getDoctrine()->getManager()->getRepository('OCLouvreBundle:Booking')->findBy(['bookingdate' => $date]);
        $totaltickets = 0;
        foreach ($dates as $date) {
            $totaltickets += $date->getTickets()->count();
        }

        if ($totaltickets >= 2) {
            return false;
        } else {
            return true;
        }
    }
}