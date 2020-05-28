<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Posts;
use App\Form\PostsType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


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
//                dd($brochureFile);

//                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
//                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
//                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

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

//                $originalFilename = pathinfo($audioFile->getClientOriginalName(), PATHINFO_FILENAME);
//                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
//                $newFilename = $safeFilename.'-'.uniqid().'.'.$audioFile->guessExtension();

                $original = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
//                dd($original);
                // this is needed to safely include the file name as part of the URL
                $safeFilename =  filter_var ( $original, FILTER_SANITIZE_STRING);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$audioFile->guessExtension();
//                dd($newFilename);

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

            // var_dump($post);
            // die();
          
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

    /**
     * @Route("/mis-posts", name="misPosts")
     */
//    public function misPosts()
//    {
//        $em = $this->getDoctrine()->getManager();
//        $user = $this->getUser();
//
//        $posts = $em->getRepository(Posts::class)->findBy(['user' => $user]);
//        return $this->render('misPosts.html.twigBORRAR', ['posts' => $posts]);
//    }


    public function editar(Request $request, Posts $post)
    {
//dd($post); die();
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('foto')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
//                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $dt = new DateTime();
                $df = date_format($dt, 'Y-m-d-H-i-s');
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
//                        'fdfdf.png'
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
//                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $dt = new DateTime();
                $df = date_format($dt, 'Y-m-d-H-i-s');
                $newFilename = $originalFilename . '-' . $df . uniqid() . '.' . $audioFile->guessExtension();
//                dd($newFilename);

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

//   /**
//     * @Route("/edit", name="edit")
//     */
//    public function edit(Request $request, UserInterface $user)
//    {
//        // dd($request);
//        $service_repo = $this->getDoctrine()->getRepository(Service::class);
//        // $services = $service_repo->findAll();
//        $service_array = $service_repo->findBy(['id' => $request->get("_id")]);
//
//        $service = $service_array[0];
//
//        // dd($service);
//        $category_repo = $this->getDoctrine()->getRepository(Category::class);
//        // $services = $service_repo->findAll();
//        $category = $category_repo->findBy(['id' => $request->get("_category")]);
//
//        $service->setName($request->get("_name"));
//        $service->setDescription($request->get("_description"));
//        $service->setCategory($category[0]);
//        $service->setCountry($request->get("_country"));
//        $service->setUrlService($request->get("_url_service"));
//
//        // var_dump($service);
//        $service->setCreatedAt(new \Datetime('now'));
//        // var_dump($user);
//        $service->setUser($user);
//        // dd($service);
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($service);
//        $em->flush();
//
//        return $this->redirectToRoute('my_services');
//    }

//    /**
//     * @Route("/eliminar-podcast", name="delete")
//     */
    public function delete(Posts $post)
    {
//        var_dump($post); die();
//        if (!$user || !$service || $user->getId() != $service->getUser()->getId()) {
//            return $this->redirectToRoute('my_services');
//        }
        if(!$post){
            return $this->redirectToRoute('dashboard');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('dashboard');
    }
}