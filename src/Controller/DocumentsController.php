<?php

namespace App\Controller;

use DateTimeZone;
//use App\Entity\Users;
use App\Entity\Documents;
use App\Form\DocumentsType;
use App\Form\DocumentsLimitedType;
use App\Repository\TagsRepository;
use App\Repository\FilesRepository;
use App\Repository\DocumentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

//* There are for EasyAdmin dashboard:
//use EasyCorp\Bundle\EasyAdminBundle\Contracts\Controller\CrudControllerInterface;
//use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

/**
 *
 * @Route("/documents")
 */
class DocumentsController extends AbstractController
{
    /**
     * Note: all the docs of the active user (My documents)
     * @Route("/", name="documents_index", methods={"GET"})
     */
    public function index(DocumentsRepository $documentsRepository, TagsRepository $tagsRepository, Request $request ): Response
    {
        /*$form = $this->createForm(SelectType::class, $select);
        $form->handleRequest($request);*/

        return $this->render('documents/index.html.twig', [
            'documents' => $documentsRepository->findDocumentsByUser(
                $_GET['u']
            ),
            'allTags' => $tagsRepository->findAll(),
            //'selectForm' => $form->createView(),
            
        ]);
    }

    /**
     * Note: all public documents
     * @Route("/documents_public", name="documents_public", methods={"GET"})
     */
    public function indexPublic(
        DocumentsRepository $documentsRepository, TagsRepository $tagsRepository
    ): Response {
        return $this->render('documents/index.html.twig', [
            'documents' => $documentsRepository->findAllPublic(),
            'allTags' => $tagsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="documents_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $document = new Documents();
        $form = $this->createForm(DocumentsType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
  
          
            $document->setCreatedAt(new \DateTime('now',  new DateTimeZone('Europe/Paris')));
            $document->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($document);
            $entityManager->flush();
            
      
       
            return $this->redirectToRoute('files_new', ['doc'=>$document->getId()]);
        }

        return $this->render('documents/new.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

      /**
       * NOTE: document creator for the non-verified users
     * @Route("/new/limited", name="documents_limited_new", methods={"GET","POST"})
     */
    public function newLimited(Request $request): Response
    {
        $document = new Documents();
        $form = $this->createForm(DocumentsLimitedType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document->setValidity(new \DateTime('now',  new DateTimeZone('Europe/Paris')));
            $document->setAlarm(new \DateTime('now-1 Days',  new DateTimeZone('Europe/Paris')));
            $document->setCreatedAt(new \DateTime('now',  new DateTimeZone('Europe/Paris')));
            $document->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($document);
            $entityManager->flush();

            // return $this->redirectToRoute('documents_index');
            return $this->redirectToRoute('files_new', ['doc'=>$document->getId()]);
        }

        return $this->render('documents/new.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="documents_show", methods={"GET"})
     */
    public function show(Documents $document,  FilesRepository $filesRepository ): Response
    {
      
        return $this->render('documents/show.html.twig', [
            'document' => $document,
            'files' => $filesRepository->findAllFilesByDocument($document->getId()),
            
        ]);
    }

    /**
     * @Route("/{id}/edit", name="documents_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Documents $document): Response
    {
        $form = $this->createForm(DocumentsType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('documents_index',['u'=>$this->getUser()->getId()]);
        }

        return $this->render('documents/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

     /**
     * Route("/{id}/addFile", name="documents_add_file", methods={"GET","POST"})
     */
   /* public function addFile(Request $request, Documents $document): Response
    {
        $form = $this->createForm(DocumentsType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('files_new');
        }

        return $this->render('documents/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }*/
    
    /**
     * @Route("/{id}/delete", name="documents_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Documents $document): Response
    {
        if (
            $this->isCsrfTokenValid(
                'delete' . $document->getId(),
                $request->request->get('_token')
            )
        ) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('documents_index',['u'=>$this->getUser()->getId()]);
    }

   
    
}
