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
			
			
			$dates = $request->get("tabDate");
			
			$logger = $this->get('logger');
			$logger->info(print_r($request->get("tabDate"), true));
			
			$result = [];
			$result = $this->get('oc_louvre.bookingprovider')->getTest($dates);
			
			return new JsonResponse($result);
		}
		
		
		/*public function getorderAction(Request $request){
			
			
			$logger = $this->get('logger');
			$logger->info(print_r($request->get("tab"), true));
			
			
			$dates = $request->get("youpi");
			$tab = array();
			$nom = $request->get("guillaume");
			
			
			if( ! is_array($dates))
			{
				$dates = array($dates);
			}
			
			
			$logger->info($nom);
			foreach( $dates as $date)
			{
				$logger->info($date);
				$tab['date']=$date;
			}
			$tab['toto']=345;
			$tab['titi']='khgv';
			
			return new JsonResponse($tab);
		}*/
		
		
		/*public function titiAction(){
	    	//1 parametre en entrée : tab qui sera un tableau
	    	
	    	$tab = array();
	    	$tab['toto']=345;
			$tab['titi']='khgv';
	  
			// Créons nous-mêmes la réponse en JSON, grâce à la fonction json_encode()
			$response = new Response(json_encode($tab));
			
			// Ici, nous définissons le Content-type pour dire au navigateur
			// que l'on renvoie du JSON et non du HTML
			$response->headers->set('Content-Type', 'application/json');
			
			return $response;
		}*/
		
	}