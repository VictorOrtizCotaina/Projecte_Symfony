<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Entity\Topic;
use App\Form\FilterTopicType;
use App\Form\ForumType;
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
        $forums = $this->getDoctrine()
            ->getRepository(Forum::class)
            ->findAll();

        return $this->render('forum/index.html.twig', [
            'forums' => $forums,
        ]);
    }


    /**
     * @Route("/{idForum}", defaults={"page": "1"}, name="forum_show", methods={"GET"})
     * @Route("/{idForum}/page/{page<[1-9]\d*>}", methods="GET", name="forum_show_paginated")
     */
    public function show(Forum $forum, Request $request, int $page): Response
    {
        $text = null;
        $startDate = null;
        $endDate = null;
        $tag = null;
        $idForum = $forum->getIdForum();

        $forum = $this->getDoctrine()
            ->getRepository(Forum::class)
            ->findbyForum($idForum);


        $form = $this->createForm(FilterTopicType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $text = $form->get('text')->getData();
            $startDate = $form->get('startDate')->getData();
            $endDate = $form->get('endDate')->getData();
        }

        $topics = $this->getDoctrine()
            ->getRepository(Topic::class)
            ->findAllTopicbyForum($idForum, $page, $text, $startDate, $endDate);


        return $this->render('front-office/forum/show.forum.html.twig', [
            'forum' => $forum,
            'topics' => $topics,
            'title' => "Foro Programacion • " . $forum->getTitle(),
            'filterForm' => $form->createView(),
//            'query' => $request->query->all(),
            'target_dir' => "/img/"
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
     * @Route("/{idForum}", name="forum_show", methods={"GET"})
     */
    /*public function show(Forum $forum): Response
    {
        return $this->render('forum/show.html.twig', [
            'forum' => $forum,
        ]);
    }*/

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
