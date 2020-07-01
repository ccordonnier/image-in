<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{
	/**
	 * [DisplayHome description]
	 * @Route("/", name="home")
	 */
	public function displayHome(){
		return $this->render("home/show.html.twig");
	}

	/**
	 * [showPicture description]
	 * @Route("/picture/{slug}", name="picture.display")
	 */
	public function showPicture($slug){
		dump($slug);
		return $this->render("home/display.html.twig", [
			'picture' => ucwords(str_replace('-',' ',$slug))]);

	}

	
}

?>