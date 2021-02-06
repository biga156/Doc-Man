<?php

namespace App\Controller;

use App\Entity\Files;
use App\Form\FilesType;
use App\Repository\FilesRepository;
use App\Repository\DocumentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/files")
 */
class FilesController extends AbstractController
{
    /**
     * Note: in this page the user can view the files belonging to the documents
     *
     * @Route("/", name="files_index", methods={"GET"})
     */
    public function index(
        FilesRepository $filesRepository,
        DocumentsRepository $documentsRepository
    ): Response {
        if (isset($_GET['doc'])) {
            // * user mode

            $document = $documentsRepository->findById($_GET['doc']);
            $document = $document[0];

            return $this->render('files/index.html.twig', [
                'files' => $filesRepository->findAllFilesByDocument(
                    $_GET['doc']
                ),
                'doc' => $_GET['doc'],
                //'document'=> $documentsRepository-> findById($_GET["doc"]) ,
                'document' => $document,
            ]);
        } else {
            return $this->render('files/index.html.twig', [
                'files' => $filesRepository->findAll(),
                'doc' => 0,
                'document' => 0,
            ]);
        }
    }

    /**
     * @Route("/new", name="files_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $file = new Files();
        $form = $this->createForm(FilesType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$file = $form->get('name')->getData();
            //dd($form);
            $doc=$file->getDocuments()->getId();
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($file);
            $entityManager->flush();
          
            
              return $this->redirectToRoute('documents_show',['id'=>$doc]);
           
           
        }
        if (isset($_GET['doc'])) {
            // * user mode
            return $this->render('files/new.html.twig', [
                'file' => $file,
                'form' => $form->createView(),
                'doc' => $_GET['doc'],
            ]);
        } else {
            return $this->render('files/new.html.twig', [
                'file' => $file,
                'form' => $form->createView(),
                'doc' => 0,
            ]);
        }
    }

    /**
    * @Route("/download/{file}", name="download_action")
    */
    public function downloadAction(Files $file, DownloadHandler $downloadHandler): Response
    {
        //return $downloadHandler->downloadObject($file, 'file');
        //NOTE: with options (quickview for example in browser):
        return $downloadHandler->downloadObject($file, 'file', $objectClass = null, $fileName = null, $forceDownload = false);
    }

    /**
     * @Route("/{id}", name="files_show", methods={"GET"})
     */
    public function show(Files $file): Response
    {
        return $this->render('files/show.html.twig', [
            'file' => $file,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="files_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Files $file): Response
    {
        $form = $this->createForm(FilesType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('files_index');
        }
        if (isset($_GET['doc'])) {
            // * user mode
        return $this->render('files/edit.html.twig', [
            'file' => $file,
            'form' => $form->createView(),
            'doc' => $_GET['doc'],
        ]);
        }else{
            return $this->render('files/edit.html.twig', [
                'file' => $file,
                'form' => $form->createView(),
                'doc' =>0,
            ]);
        }
    }

    /**
     * @Route("/{id}", name="files_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Files $file): Response
    {
        if (
            $this->isCsrfTokenValid(
                'delete' . $file->getId(),
                $request->request->get('_token')
            )
        ) {
            //$this->get('vich_uploader.upload_handler')->remove($file, 'name');
            //$file->setFile(null);
            //$file->setName(null);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($file);
            $entityManager->flush();
        }

      
          return $this->redirectToRoute('files_index', ['doc'=>$_GET['doc']]);
         
    }
}
