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
	
	class CurrentOrderController extends Controller
	{
		public function getorderAction(Request $request){
			
			/*$datas = json_decode($request->get('data'), true);
			
			foreach ($datas as $data) {
				dump($data);
				return new JsonResponse($data);
			}*/
			$tab = array();
			$tab['toto']=345;
			$tab['titi']='khgv';
			
			return new JsonResponse($tab);
		}
		
		
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