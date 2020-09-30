<?php 

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;
use App\Form\UserType;



class SecurityController extends AbstractController{

	/**
	/* @var UserRepository
	*/ 
	private $repository;


	public function __construct(UserRepository $repository){
		$this->repository = $repository;
	}

	/**
	 * [login description]
	 * @Route("/login", name="login")
	 * @return [type] [description]
	 */
	public function login(AuthenticationUtils $authenticationUtils){
		//dd($authenticationUtils);
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();
		$formSign = $this->createForm(UserType::class);
		return $this->render('security/login.html.twig',[
			'lastUsername' => $lastUsername,
			'error' => $error,
			'formSign' => $formSign->createView()
		]);
	}

	/**
	 * [signin description]
	 * @Route("/signin", name="signin")
	 * @return [type] [description]
	 */
	public function signin(Request $request, UserPasswordEncoderInterface $passwordEncoder){
		
		$user = new User();
		$form = $this->createForm(UserType::class, $user);
		//dd($request->isMethod('POST'));
		$form->handleRequest($request);
		if ($request->isMethod('POST')) {
			
			
			
			if ($form->isSubmitted() && $form->isValid()) {
				
				//$user->set_username($form->)
				//Encode the password
				$password = $passwordEncoder->encodePassword($user, $user->getPassword());
				$user->setPassword($password);
				$user->setRoles(['ROLE_USER']);
				
				//save user
				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($user);
				$entityManager->flush();

			}else{
				
				dd($form->getErrors(true));

			}
			
		}

		return $this->redirectToRoute('home');
		//dd($form);
		//$form->handleRequest($request);
		//$form->submit($request->request->get($form->getName()));
		
		
		
	}
}