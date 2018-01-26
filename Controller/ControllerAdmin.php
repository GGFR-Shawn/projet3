<?php

require_once 'ControllerSecure.php';
require_once 'Model/Post.php';
require_once 'Model/Comment.php';

/**
 * Controller des actions de l'espace administration du site
 */

class ControllerAdmin extends ControllerSecure
{
    private $post;
    private $comment;

    /**
     * Constructeur
     *
     */
    public function __construct()
    {
        $this->post = new Post;
        $this->comment = new Comment;
    }


    /**
     * Fonction pour afficher le nombre de posts et de commentaires lors que l'on est connecté
     * à l'espace administrateur.
     */
    public function index()
    {
        $nbPosts = $this->post->getNumberPosts();
        $nbComments = $this->comment->getNumberComments();
        $posts = $this->post->lastPost();
        $comments = $this->comment->lastComment();
        $login = $this->request->getSession()->getAttribut("login");
        $this->buildView(array('nbPosts' => $nbPosts, 'nbComments' => $nbComments, 'posts'=>$posts, 'comments' =>$comments, 'login' => $login));
    }

    public function chapters()
    {
        $posts = $this->post->getPosts(); // va chercher la méthode getPosts dans le Model Post.php l.20
        $this->buildView(array('posts' => $posts));
    }

    public function post()
    {
        $postId = $this->request->getParameter("id"); /* le parametre disparait lors de l'annonce de "public function post($postId)". On l'annonce
 du coup en début de la méthode en faisant appel à la méthode getParameter de la class Request */
        $post = $this->post->getPost($postId); // va chercher la méthode GetPost($postId) dans le Model Post.php l.33
        $this->buildView(array('post' => $post));
    }


    public function create()
    {
        $title = $this->request->getParameterByDefault('title');
        $content = $this->request->getParameterByDefault('content');


        if($title && $content)
        {
                $title = $this->request->getParameter('title');
                $content = $this->request->getParameter('content');
                $this->post->addPost($title, $content);
                $this->executeAction("chapters");
            }
        else
        {
                $this->buildView(array('title'=>$title,'content'=>$content));
        }
    }

    public function edit()
    {
        $post['id'] = $this->request->getParameter('id'); // récupérer le paramètre de l'ID

        if($post['id'] != 0)
        {
            $post = $this->post->getPost($post['id']); // récupère le post avec la méthode getPost
            $post['title'] = $this->request->getParameterByDefault('title'); // affiche moi le titre par défault que je récupere avec value dans post.php
            $post['content'] = $this->request->getParameterByDefault('content'); // affiche moi le contenu par défault que je récupere avec value dans post.php
        }
        else
        {
            throw new Exception('Erreur 404');
        }




        if($post['title']  && $post['content'])
        {
            $title = $this->request->getParameter('title');
            $content = $this->request->getParameter('content');
            $this->post->updatePost($title, $content,$post['id']);
            $this->executeAction("post");
        }
        else
        {
            $this->buildView(array('title'=> $title ?? $post['title'] ,'content' => $content ?? $post['content']));
            // ?? retourne le premier opérande s'il existe et s'il n'est pas NULL.
        }
    }


    //$post = $this->post->getPost('id');
    //$post = $this->post->getPost($post['id']);
    //recuprerle post sinon lever une 404 */

    /*
    public function edit()
    {
        $id = $this->request->getParameter("id");
        //1- recuperer le post associé à l'id $post


        //2- si j'arrive sur cettepage en POST avec title et content de soumis
             // mettre à jour $post dans la base et redirection
        // sinon
            //

        $title = $this->request->getParameter("title"); // name dans le formulaire
        $content = $this->request->getParameter("content"); // content dans le formulaire
        // Sauvegarde du commentaire
        $this->post->editPost($title, $content); // va chercher la méthode dans le Model post l.63


        $this->buildView(array('title' => $title, 'content'=> $content));
    } */

}
