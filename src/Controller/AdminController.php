<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="dashboard")
     */
    public function adminDashboard(): Response
    {

        return $this->render('admin/admin.html.twig', [
        ]);
    }
}