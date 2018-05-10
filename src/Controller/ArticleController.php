<?php


namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\MarkdownHelper;
use App\Service\SlackClient;
use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController
{

    private $isDebug;

    public function __construct(bool $isDebug)
    {

        $this->isDebug = $isDebug;
    }

    /**
     * @Route("/", name="app_homepage")
     * @return Response
     */
    public function homepage(ArticleRepository $articleRepository){

        $articles = $articleRepository->showAll();
        $articles = $articleRepository->findAllPublishedOrderedByNewest();
        return $this->render('article/homepage.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     * @param $slug
     * @return Response
     */
    public function show(/*string $slug,*/ Article $article, SlackClient $slack, ArticleRepository $articleRepository){

        /*// Slack Integration!
        if($slug == 'khaaaaaan'){
            $slack->sendMessage('Khan', 'Hi There!');
        }

        $article = $articleRepository->show($slug);

        if(!$article){
            throw $this->createNotFoundException(sprintf('No article for slug "%s"', $slug));
        }*/

        // Slack Integration!
        if($article->getSlug() == 'khaaaaaan'){
            $slack->sendMessage('Khan', 'Hi There!');
        }

        $comments = [
            'Curabitur eu venenatis massa. Integer at orci non nisi imperdiet feugiat.',
            'Duis justo sapien, pharetra ac risus non, fermentum tristique felis.',
            'Ut tincidunt libero quam, in sodales est lacinia nec.'
        ];

        return $this->render('article/show.html.twig',[
            'article' => $article,
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart($slug, LoggerInterface $logger){

        $logger->info('Article is being hearted!');

        return new JsonResponse(['hearts' => rand(5, 100)]);
    }

}