<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Form\TopicType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/topic")
 */
class TopicController extends AbstractController
{
    /**
     * @Route("/", name="topic_index", methods={"GET"})
     */
    public function index(): Response
    {
        $topics = $this->getDoctrine()
            ->getRepository(Topic::class)
            ->findAll();

        return $this->render('topic/index.html.twig', [
            'topics' => $topics,
        ]);
    }


    /**
     * @Route("/{idTopic}", name="topic_show", methods={"GET"})
     */
    public function show(Topic $topic): Response
    {
        $topic = $this->getDoctrine()
            ->getRepository(Topic::class)
            ->findbyTopic($topic->getIdTopic());


        return $this->render('front-office/topic/show.topic.html.twig', [
            'topic' => $topic,
            'title' => "Foro Programacion • " . $topic->getTitle(),
            'target_dir' => "/img/"
        ]);
    }


    /**
     * @Route("/new", name="topic_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('topic_index');
        }

        return $this->render('topic/new.html.twig', [
            'topic' => $topic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idTopic}", name="topic_show", methods={"GET"})
     */
/*    public function show(Topic $topic): Response
    {
        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
        ]);
    }*/

    /**
     * @Route("/{idTopic}/edit", name="topic_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Topic $topic): Response
    {
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('topic_index');
        }

        return $this->render('topic/edit.html.twig', [
            'topic' => $topic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idTopic}", name="topic_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Topic $topic): Response
    {
        if ($this->isCsrfTokenValid('delete'.$topic->getIdTopic(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('topic_index');
    }
}
