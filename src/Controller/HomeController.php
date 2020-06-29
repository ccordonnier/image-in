<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{
	/**
	 * [DisplayHome description]
	 * @Route("/")
	 */
	public function displayHome(){
		return new Response("hello you're on the future homepage !");
	}

	/**
	 * [showPicture description]
	 * @Route("/picture/{slug}")
	 */
	public function showPicture($slug){
		/*dump($slug);*/
		return $this->render("home/display.html.twig", [
			'picture' => ucwords(str_replace('-',' ',$slug))]);

	}

	/**
	 * [test description]
	 * @Route("/test")
	 */
	public function test(){
		return new Response("test !");
	}
}

?>