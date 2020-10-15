<?php 

namespace App\Controller;

use App\Repository\PicturesRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{

	private $repository;

	/**
	 * [__construct description]
	 * @param PicturesRepository $repository [description]
	 */
	public function __construct(PicturesRepository $repository){
		$this->repository = $repository;
	}

	/**
	 * [displayHome description]
	 * @Route("/", name="home")
	 */
	public function displayHome(){
		$pictures = $this->repository->findAll();
		//dump($pictures);
		return $this->render("home/show.html.twig", compact('pictures'));
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