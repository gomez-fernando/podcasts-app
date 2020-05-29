<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Posts;
use App\Form\PostsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\HttpFoundation\FileBag;


class PostsController extends AbstractController
{
    /**
     * @Route("/guardar-post", name="guardarPost")
     */
    public function index(Request $request)
    {
        $post =new Posts();
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('foto')->getData();
            if ($brochureFile) {

            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename =  filter_var ( $originalFilename, FILTER_SANITIZE_STRING);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
            try {
                $brochureFile->move(
                    $this->getParameter('photos_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                throw new \Exception('Ups! ha ocurrido un error, sorry :c');
            }
            $post->setFoto($newFilename);
        }

            $audioFile = $form->get('audio')->getData();
            if ($audioFile) {

                $original = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename =  filter_var ( $original, FILTER_SANITIZE_STRING);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$audioFile->guessExtension();

                try {
                    $audioFile->move(
                        $this->getParameter('audios_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Ups! ha ocurrido un error, sorry :c');
                }
                $post->setAudio($newFilename);
            }

            // obtenemos el usuario logueado
            $user = $this->getUser();
            $post->setUser($user);
          
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('posts/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/post/{id}", name="verPost")
     */
    public function verPost($id, Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository(Posts::class)->find($id);
        return $this->render('posts/verPost.html.twig', ['post' => $post]);
    }


//    public function editar(Request $request, Posts $post)
//    {
//        $form = $this->createForm(PostsType::class, $post);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $brochureFile = $form->get('foto')->getData();
//            if ($brochureFile) {
//                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
//                // this is needed to safely include the file name as part of the URL
//                $safeFilename =  filter_var ( $originalFilename, FILTER_SANITIZE_STRING);
//                $newFilename = $originalFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
//                try {
//                    $brochureFile->move(
//                        $this->getParameter('photos_directory'),
//                        $newFilename
//                    );
//                } catch (FileException $e) {
//                    throw new \Exception('Ups! ha ocurrido un error, sorry :c');
//                }
//                $post->setFoto($newFilename);
//            }
//
//            $audioFile = $form->get('audio')->getData();
//            if ($audioFile) {
//                $originalFilename = pathinfo($audioFile->getClientOriginalName(), PATHINFO_FILENAME);
//                // this is needed to safely include the file name as part of the URL
//                $safeFilename =  filter_var ( $originalFilename, FILTER_SANITIZE_STRING);
//                $newFilename = $originalFilename . '-' . uniqid() . '.' . $audioFile->guessExtension();
//
//                try {
//                    $audioFile->move(
//                        $this->getParameter('audios_directory'),
//                        $newFilename
//                    );
//                } catch (FileException $e) {
//                    throw new \Exception('Ups! ha ocurrido un error, sorry :c');
//                }
//                $post->setAudio($newFilename);
//            }
//
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($post);
//            $em->flush();
//
//
//            return $this->render('posts/verPost.html.twig', [
//                'id' => $post->getId(),
//                'post' => $post
//            ]);
//        }
//        return $this->render('posts/index.html.twig', [
//            'edit' => true,
//            'form' => $form->createView()
//        ]);
//    }


    public function delete(Posts $post)
    {
        if(!$post){
            return $this->redirectToRoute('dashboard');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('dashboard');
    }


    public function editar(Posts $post)
    {
        return $this->render('posts/editar_view.html.twig', ['post' => $post]);
    }


    public function editarPodcast(Request $request, UserInterface $user)
    {

//dd($request);
        $em = $this->getDoctrine()->getManager();
        $id = $request->get("_id");
        $post = $em->getRepository(Posts::class)->find($id);
//        dd($request);
        $post->setId($request->get("_id"));
        $post->setTitulo($request->get("_titulo"));
        $post->setDescripcion($request->get("_descripcion"));
        $post->setUser($user);

//dd($request);
//        dump($request->files->all());
//        dump($request->files->get('_foto'));
//        die();
        if ($request->files->get('_foto') != "") {
//            dd($request->files->get("_foto"));
            $brochureFile = $request->files->get("_foto");
//            dd($brochureFile);
            if ($brochureFile) {

                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename =  filter_var ( $originalFilename, FILTER_SANITIZE_STRING);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Ups! ha ocurrido un error, sorry :c');
                }
                $post->setFoto($newFilename);
//                dd($newFilename);
            }
        }

        if ($request->files->get('_audio') != "") {
            $audioFile = $request->files->get('_audio');
            if ($audioFile) {

                $original = pathinfo($audioFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename =  filter_var ( $original, FILTER_SANITIZE_STRING);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$audioFile->guessExtension();

                try {
                    $audioFile->move(
                        $this->getParameter('audios_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Ups! ha ocurrido un error, sorry :c');
                }
                $post->setAudio($newFilename);
            }
        }
//        dd($post);

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

        return $this->redirectToRoute('dashboard');
    }
}