<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController{
	/**
	 * [DisplayHome description]
	 * @Route("/")
	 */
	public function displayHome(){
		return new Response("hello you're on the future homepage !");
	}

	/**
	 * [showPicture description]
	 * @Route("/picture/1")
	 */
	public function showPicture(){
		return new Response("hello you're on the future picture page !");

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