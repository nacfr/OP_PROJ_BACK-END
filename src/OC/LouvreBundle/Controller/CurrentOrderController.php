<?php
	/**
	 * Created by PhpStorm.
	 * User: cecile
	 * Date: 21/06/2018
	 * Time: 21:42
	 */
	
	namespace OC\LouvreBundle\Controller;
	
	
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Psr\Log\LoggerInterface;
	
	class CurrentOrderController extends Controller
	{
		
		public function getorderAction(Request $request){
			
			
			$data = $request->get("tab");
			
			$logger = $this->get('logger');
			$logger->info(print_r($request->get("tab")[0], true));
			
			$result = [];
			$result = $this->get('oc_louvre.bookingprovider')->getPendingOrder($data);
			
			return new JsonResponse($result);
		}
	}