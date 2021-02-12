<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PicturesRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

	private $repository;

	/**
	 * [__construct description]
	 * @param PicturesRepository $repository [description]
	 */
	public function __construct(PicturesRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * [displayHome description]
	 * @Route("/", name="home")
	 */
	public function displayHome()
	{
		$pictures = $this->repository->findAll();
		return $this->render("home/show.html.twig", compact('pictures'));
	}

	/**
	 * [showPicture description]
	 * @Route("/picture/{id}", name="picture_show")
	 */
	public function showPicture($id)
	{
		$picture = $this->repository->findOneBy(['id' => $id]);
		return $this->render("picture/display.html.twig", [
			'picture' => $picture
		]);
	}
}
