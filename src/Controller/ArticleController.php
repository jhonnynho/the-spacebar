<?php


namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\SlackClient;
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
    public function homepage(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->showAll();
        $articles = $articleRepository->findAllPublishedOrderedByNewest();
        return $this->render('article/homepage.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     * @param Article $article
     * @param SlackClient $slack
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function show(
        Article $article,
        SlackClient $slack,
        ArticleRepository $articleRepository
    ) {

        /*// Slack Integration!
        if($slug == 'khaaaaaan'){
            $slack->sendMessage('Khan', 'Hi There!');
        }

        $article = $articleRepository->show($slug);

        if(!$article){
            throw $this->createNotFoundException(sprintf('No article for slug "%s"', $slug));
        }*/

        // Slack Integration!
        if ($article->getSlug() == 'khaaaaaan') {
            $slack->sendMessage('Khan', 'Hi There!');
        }

        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart(Article $article, LoggerInterface $logger, ArticleRepository $articleRepository)
    {
        $count = $articleRepository->setHeartCounter($article);

        $logger->info('Article is being hearted!');

        return new JsonResponse(['hearts' => $count]);
    }

}