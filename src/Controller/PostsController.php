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

                $original = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME)
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


    public function editar(Request $request, Posts $post)
    {
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('foto')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $dt = new DateTime();
                $df = date_format($dt, 'Y-m-d-H-i-s');
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Ups! ha ocurrido un error, sorry :cdfdf');
                }
                $post->setFoto($newFilename);
            }

            $audioFile = $form->get('audio')->getData();
            if ($audioFile) {
                $originalFilename = pathinfo($audioFile->getClientOriginalName(), PATHINFO_FILENAME);
                $dt = new DateTime();
                $df = date_format($dt, 'Y-m-d-H-i-s');
                $newFilename = $originalFilename . '-' . $df . uniqid() . '.' . $audioFile->guessExtension();

                try {
                    $audioFile->move(
                        $this->getParameter('audios_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Ups! ha ocurrido un error, sorry :caudio');
                }
                $post->setAudio($newFilename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();


            return $this->render('posts/verPost.html.twig', [
                'id' => $post->getId(),
                'post' => $post
            ]);
        }
        return $this->render('posts/index.html.twig', [
            'edit' => true,
            'form' => $form->createView()
        ]);
    }


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
}