<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\Posts;
use App\Entity\Service;
use App\Form\RegisterType;
use Proxies\__CG__\App\Entity\Category;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/ver-perfil", name="verPerfil")
     */
    public function profile_view(UserInterface $user)
    {
        return $this->render('user/profile_view.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/editar-perfil", name="editarPerfil")
     */
    public function editarPerfil(Request $request, UserInterface $user, UserPasswordEncoderInterface $encoder)
    {
        if (!$user) {
            return $this->redirectToRoute('logout');
        }

        // var_dump($request);
        // die();
        $user->setNombre($request->get("_name"));
        $user->setApellidos($request->get("_surname"));
        $user->setEmail($request->get("_email"));
        if ($request->get("_password") != "") {
            //            cifrar contraseña
            $encoded = $encoder->encodePassword($user, $request->get("_password"));
            $user->setPassword($encoded);
        }
        // dd($user);
        // $podcast->setCreatedAt(new \Datetime('now'));
        // var_dump($user);
        // $podcast->setUser($user);
        // var_dump($podcast);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/eliminar-usuario", name="eliminar_usuario")
     */
    public function eliminar_usuario(UserInterface $user)
    {
        // die('hola');
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $em = $this->getDoctrine()->getManager();

            // conseguir todos los servicios
            $podcast_repo = $em->getRepository(Posts::class);

            // conseguir los servicios del usuario
            $podcasts = $podcast_repo->findBy(['user' => $user->getId()]);
            // eliminar todos los servicios
            foreach ($podcasts as $podcast) {
                $em->remove($podcast);
                $em->flush();
            }
            $em->flush();

        // eliminamos el usuario
        $em->remove($user);
        $em->flush();

        $user->setId(0);
//         dd($user);

        return $this->redirectToRoute('app_logout');
    }
}