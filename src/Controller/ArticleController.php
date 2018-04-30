<?php


namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController
{
    /**
     * @Route("/")
     * @return Response
     */
    public function homepage(){
        return new Response("Symfony 4");
    }

    /**
     * @Route("/news/{slug}")
     * @param $slug
     * @return Response
     */
    public function show(string $slug){

        $comments = [
            'Curabitur eu venenatis massa. Integer at orci non nisi imperdiet feugiat.',
            'Duis justo sapien, pharetra ac risus non, fermentum tristique felis.',
            'Ut tincidunt libero quam, in sodales est lacinia nec.'
        ];

        return $this->render('article/show.html.twig',[
            'title' => ucwords(str_replace('-', ' ', $slug)),
            'comments' => $comments
        ]);
    }

}