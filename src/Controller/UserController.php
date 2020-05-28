<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Posts;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

        $user->setNombre($request->get("_name"));
        $user->setApellidos($request->get("_surname"));
        $user->setEmail($request->get("_email"));
        if ($request->get("_password") != "") {
            // cifrar contraseña
            $encoded = $encoder->encodePassword($user, $request->get("_password"));
            $user->setPassword($encoded);
        }
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
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $em = $this->getDoctrine()->getManager();

        // conseguir todos los podcasts
        $podcast_repo = $em->getRepository(Posts::class);

        // conseguir los podcasts del usuario
        $podcasts = $podcast_repo->findBy(['user' => $user->getId()]);
        // eliminar todos los podcasts
        foreach ($podcasts as $podcast) {
            $em->remove($podcast);
            $em->flush();
        }
        $em->flush();

        // eliminamos el usuario
        $em->remove($user);
        $em->flush();

        $user->setId(0);

        return $this->redirectToRoute('app_logout');
    }
}
