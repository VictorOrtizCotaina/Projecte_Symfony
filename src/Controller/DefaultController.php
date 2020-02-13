<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        // get the Link repository (it is like our model)
        $repository = $this->getDoctrine()->getRepository(Category::class);

        // retrieve all links
        $categories = $repository->findAll();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'categories' => $categories
        ]);
    }
}
