<?php

namespace App\Controller;

use DateTimeZone;
use App\Entity\Users;
use App\Form\RolesType;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
//use EasyCorp\Bundle\EasyAdminBundle\Contracts\Controller\CrudControllerInterface;
//use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
//for acces control's annotations:
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users")
 */
class UsersController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/", name="users_index", methods={"GET"})
     */
    public function index(UsersRepository $usersRepository): Response
    {
        return $this->render('users/index.html.twig', [
            'users' => $usersRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="users_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
                )
            );

            // Set their role
            $user->setRoles(['ROLE_USER']);
            $user->setCreatedAt(new \DateTime('now',  new DateTimeZone('Europe/Paris')));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('users_index');
        }

        return $this->render('users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="users_show", methods={"GET"})
     */
    public function show(Users $user): Response
    {
        return $this->render('users/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="users_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Users $user): Response
    {
        $role = $user->getRoles();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles($role);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
                )
            );

            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * NOTE: admin
     * @Route("/{id}/edit_admin", name="users_edit_admin", methods={"GET","POST"})
     */
    public function editAdmin(Request $request, Users $user): Response
    {
        $form = $this->createForm(RolesType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('users_index');
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN") //?or the user activ himself?
     * @Route("/{id}", name="users_make_archiv", methods={"DELETE"})
     */
    public function makeArchiv(Request $request, Users $user): Response
    {
        if (
            $this->isCsrfTokenValid(
                'delete' . $user->getId(),
                $request->request->get('_token')
            )
        ) {
            $entityManager = $this->getDoctrine()->getManager();
            //*changing role: ROLE_USER_DELETED
            $user->setRoles(['ROLE_USER_DELETED']);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('users_index');
    }

 /**
     * @IsGranted("ROLE_ADMIN") 
     * @Route("/{id}", name="users_make_activ", methods={"ENABLE"})
     */
    public function makeActiv(Request $request, Users $user): Response
    {
        if (
            $this->isCsrfTokenValid(
                'enable' . $user->getId(),
                $request->request->get('_token')
            )
        ) {
            $entityManager = $this->getDoctrine()->getManager();
            //*changing role: ROLE_USER
            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('users_index');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/delete", name="users_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Users $user): Response
    {
        if (
            $this->isCsrfTokenValid(
                'delete' . $user->getId(),
                $request->request->get('_token')
            )
        ) {
            $entityManager = $this->getDoctrine()->getManager();
            //*Delet final
            $entityManager->remove($user);
            $entityManager->flush();
            
        }

        return $this->redirectToRoute('users_index');
    }
}
