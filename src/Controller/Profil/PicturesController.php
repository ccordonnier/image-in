<?php

namespace App\Controller\Profil;



use App\Entity\Pictures;
use App\Repository\UserRepository;
//use App\Form

use App\Repository\PicturesRepository;
use App\Controller\Admin\PictureController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PicturesController extends AbstractController
{

	private $repository;
	private $security;

	/**
	 * [__construct description]
	 * @param PicturesRepository $repository [description]
	 */
	public function __construct(PicturesRepository $repository, Security $security)
	{
		$this->repository = $repository;
		$this->security = $security;
	}

	/**
	 * [index description]
	 * @Route("/profil/pictures", name="profil_pictures")
	 * @return [type] [description]
	 */
	public function index()
	{
		$user = $this->security->getUser();
		$pictures = $this->repository->findByField('user_id', $user->getId(), 100);
		return $this->render('profil/pictures/display.html.twig', compact('pictures'));
	}

	/**
	 * [index description]
	 * @Route("/profil/pictures/edit/{id}", name="profil_pictures_edit")
	 * @return [type] [description]
	 */
	public function edit($id)
	{
		$picture = $this->repository->find($id);
		$form = $this->createFormBuilder($picture)
			->add("title", TextType::class, [
				"label" => "Titre de l'image",
				"attr" => [
					"name" => "Titre de l'image",
				],
			])
			->add("tag", TextType::class, [
				"label" => "Etiquettes ( séparées par un \";\" )",
			])

			->add("submit", SubmitType::class, [
				"label" => "Valider"
			])
			->getForm();


		return $this->render('profil/pictures/edit.html.twig', [
			//'error' => $error,
			'formPicture' => $form->createView()
		]);
	}

	/**
	 * [index description]
	 * @Route("/profil/pictures/add", name="profil_pictures_add")
	 * @return [type] [description]
	 */
	public function add(Request $request)
	{

		//$picture = new Pictures();
		$picture = new Pictures;



		$form = $this->createFormBuilder($picture)
			->add("title", TextType::class, [
				"label" => "Titre de l'image",
				"attr" => [
					"name" => "Titre de l'image",
				]
			])
			->add("tag", TextType::class, [
				"label" => "Etiquettes",
			])
			->add("name", FileType::class, [
				"label" => "Ajouter un fichier"
			])
			->add("submit", SubmitType::class, [
				"label" => "Valider"
			])

			->getForm();


		$form->handleRequest($request);
		if ($request->isMethod('POST')) {
			if ($form->isSubmitted() && $form->isValid()) {
				$file = $request->files->get('file');
				dd($request);

				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($picture);
				$entityManager->flush();
				return $this->redirectToRoute('profil_pictures_edit', ['id' => $picture->getId()]);
			} else {
				dd($form->getErrors(true));
			}
		}
		return $this->render('profil/pictures/edit.html.twig', [
			'formPicture' => $form->createView()
		]);
	}
}
