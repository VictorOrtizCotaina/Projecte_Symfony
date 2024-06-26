<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Entity\Topic;
use App\Form\ForumType;
use App\Form\TopicFilterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/forum")
 */
class ForumController extends AbstractController
{
    /**
     * @Route("/", name="forum_index", methods={"GET"})
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $group = $user->getIdUserGroup()->getIdUserGroup();

        if ($group == 1) {
            $forums = $this->getDoctrine()
                ->getRepository(Forum::class)
                ->findAll();
        } elseif ($group == 2){
            $forums = $this->getDoctrine()
                ->getRepository(Forum::class)
                ->findBy(["idUser" => $user->getIdUser()]);
        }

        return $this->render('forum/index.html.twig', [
            'forums' => $forums,
        ]);
    }


    /**
     * @Route("/view/{idForum}", defaults={"page": "1"}, name="forum_view", methods={"GET"})
     * @Route("/{idForum}/page/{page<[1-9]\d*>}", methods="GET", name="forum_view_paginated")
     */
    public function view(Forum $forum, Request $request, int $page): Response
    {
        $text = null;
        $startDate = null;
        $endDate = null;
        $idForum = $forum->getIdForum();

        $forum = $this->getDoctrine()
            ->getRepository(Forum::class)
            ->findbyForum($idForum);


        $form = $this->createForm(TopicFilterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $text = $form->get('text')->getData();
            $startDate = $form->get('startDate')->getData();
            $endDate = $form->get('endDate')->getData();
        }

        $topics = $this->getDoctrine()
            ->getRepository(Topic::class)
            ->findAllTopicbyForum($idForum, $page, $text, $startDate, $endDate);

        $topicFilter = $request->query->all();
        if (!empty($request->query->all()["topic_filter"])){
            $topicFilter = $request->query->all()["topic_filter"];
        }
        $query = ["topic_filter" => $topicFilter, "idForum" => $forum->getIdForum()];

        return $this->render('front-office/forum/show.forum.html.twig', [
            'forum' => $forum,
            'topics' => $topics,
            'title' => "Foro Programacion • " . $forum->getTitle(),
            'target_dir' => "/img/",
            'TopicfilterForm' => $form->createView(),
            'query' => $query,
        ]);
    }


    /**
     * @Route("/new", name="forum_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $forum = new Forum();
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($forum);
            $entityManager->flush();

            return $this->redirectToRoute('forum_index');
        }

        return $this->render('forum/new.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{idForum}", name="forum_show", methods={"GET"})
     */
    public function show(Forum $forum): Response
    {
        return $this->render('forum/show.html.twig', [
            'forum' => $forum,
        ]);
    }

    /**
     * @Route("/{idForum}/edit", name="forum_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Forum $forum): Response
    {
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('forum_index');
        }

        return $this->render('forum/edit.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idForum}", name="forum_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Forum $forum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forum->getIdForum(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($forum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('forum_index');
    }
}
