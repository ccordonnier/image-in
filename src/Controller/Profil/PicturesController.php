<?php

namespace App\Controller\Profil;



use App\Entity\Pictures;
use App\Entity\PictureLike;
use App\Repository\UserRepository;
//use App\Form

use APP\Controller\SecurityController;
use App\Repository\PicturesRepository;
use Doctrine\Persistence\ObjectManager;
use App\Repository\PictureLikeRepository;
use App\Controller\Admin\PictureController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
		$user = $this->security->getUser();
		$pictureUser = $picture->getUser();
		if ($user == null) {
			return $this->redirectToRoute('security_login', ['messageError' => 'Veuillez vous connecter pour modifier une image']);
		} else {
			if ($pictureUser->getId() != $user->getId()) {
				return $this->redirectToRoute('profil_pictures', ['error' => 'Vous ne pouvez pas modifier cette image']);
			}
		}

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
			'picture' => $picture,
			'formPicture' => $form->createView()
		]);
	}

	/**
	 * [index description]
	 * @Route("/profil/pictures/add", name="profil_pictures_add")
	 * @return [type] [description]
	 */
	public function add(Request $request, SluggerInterface $slugger)
	{

		//$picture = new Pictures();
		$picture = new Pictures;
		$user = $this->security->getUser();


		$form = $this->createFormBuilder($picture)
			->add("title", TextType::class, [
				"label" => "Titre de l'image",
				"attr" => [
					"name" => "Titre de l'image",
				]
			])
			->add("tag", TextType::class, [
				"label" => "Etiquettes ( séparées par un \";\" )",
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

				$file = $request->files->get('form')['name'];
				//dd($file);
				$basename = $file->getBasename();
				$originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

				$size = $file->getSize();
				$extension = $file->getExtension();

				if ($file) {
					$safeFilename = $slugger->slug($originalFilename);
					$newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
					$fileDirectory = 'images/users/' . $user->getId();
					try {
						$file->move(
							$fileDirectory,
							$newFilename
						);
					} catch (FileException $e) {
						// ... handle exception if something happens during file upload
					}

					$picture->setName($newFilename);
				}


				$picture->setSize($size);
				$picture->setDate(new \DateTime());
				$picture->setLikes(0);
				$picture->setUser($user);

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

	/**
	 * Permet de Liker ou unliker une image
	 *
	 * @Route("/picture/{id}/like", name="picture_like")
	 * 
	 * @param Pictures $picture
	 * @param PictureLikeRepository $likeRepository
	 * @return Response
	 */
	public function like(Pictures $picture, PictureLikeRepository $likeRepository): Response
	{
		$user = $this->getUser();
		$entityManager = $this->getDoctrine()->getManager();
		if (!$user) {
			return $this->json(['message' => 'Vous devez être connecté pour liker une image'], 403);
		}

		if ($picture->isLikedByUser($user)) {
			$like = $likeRepository->findOneBy([
				'picture' => $picture,
				'user' => $user
			]);


			$entityManager->remove($like);
			$entityManager->flush();
			return $this->json([
				'message' => 'Like supprimé',
				'likes' => $likeRepository->count([
					'picture' => $picture
				])
			], 200);
		}

		$like = new PictureLike();
		$like->setPicture($picture);
		$like->setUser($user);

		$entityManager->persist($like);
		$entityManager->flush();

		return $this->json([
			'message' => 'Like ajouté',
			'likes' => $likeRepository->count([
				'picture' => $picture
			])
		], 200);
	}
}
