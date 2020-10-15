<?php 

namespace App\Controller\Profil;



use App\Repository\UserRepository;
use App\Repository\PicturesRepository;

use App\Controller\Admin\PictureController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PicturesController extends AbstractController{
	
	private $repository;
	private $security;

	/**
	 * [__construct description]
	 * @param PicturesRepository $repository [description]
	 */
	public function __construct(PicturesRepository $repository, Security $security){
		$this->repository = $repository;
		$this->security = $security;
	}

	/**
	 * [index description]
	 * @Route("/profil/pictures", name="profil_pictures")
	 * @return [type] [description]
	 */
	public function index(){
		//$user->repository->find()
		$user = $this->security->getUser();
		$pictures = $this->repository->findByField('user_id',$user->getId(),100);
		return $this->render('profil/pictures.html.twig', compact('pictures'));
	}
}

?>