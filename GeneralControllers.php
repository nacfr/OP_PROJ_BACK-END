<?php
/**
 * Created by PhpStorm.
 * User: cecile
 * Date: 21/01/2018
 * Time: 17:10
 */

namespace Core\Controllers;

abstract class GeneralControllers
{
    protected $template;
    protected $viewpath;
	
	
	/**
	 * Génère les vues
	 *
	 * @param $view_content
	 * @param array $variables
	 */
	protected function render($view_content, $variables =[]){
    	ob_start();
    	
        extract($variables);
        require ($this->viewpath . DSEP . str_replace('.', DSEP, $view_content) . '.php');
        $content = ob_get_contents();

        ob_end_clean();
        require ($this->viewpath . DSEP . $this->template . '.php');

    }
	
	/**
	 * Vérifie les adresses emails
	 *
	 * @param $email
	 * @return bool
	 */
	protected function verifEmail($email){
		if (preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $email)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Vérifie les envoies de formulaire
	 * (si l'id en post est = à l'id en get)
	 *
	 * @param $get
	 * @param $post
	 * @return bool
	 */
	protected function verifSendForm($get, $post){
    	if ($get === $post){
    		return true;
	    }
	    else{
    		return false;
	    }
	}


}