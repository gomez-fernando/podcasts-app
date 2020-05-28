<?php

namespace App\Controller;

use App\Entity\Posts;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(Request $request, PaginatorInterface $paginator, UserInterface $user)
    {
        $user = $this->getUser(); //OBTENGO AL USUARIO ACTUALMENTE LOGUEADO
        if ($user) {
            $em = $this->getDoctrine()->getManager();
            $query = $em->getRepository(Posts::class)->findBy(['user' => $user]);
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                4 /*limit per page*/
            );

            $count =($pagination->count());
            return $this->render('dashboard/index.html.twig', [
                'pagination' => $pagination,
                'count' => $count
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}