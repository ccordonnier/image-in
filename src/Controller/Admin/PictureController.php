<?php 

namespace App\Controller\Admin;

use App\Repository\PicturesRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PictureController extends AbstractController{
	
	private $repository;

	/**
	 * [__construct description]
	 * @param PicturesRepository $repository [description]
	 */
	public function __construct(PicturesRepository $repository){
		$this->repository = $repository;
	}

	public function index(){
		$pictures = $this->repository->findAll();
		return $this->render('admin/pictures/index.html.twig');
	}
}

?>