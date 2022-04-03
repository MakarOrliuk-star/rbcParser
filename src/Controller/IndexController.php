<?php

namespace App\Controller;

use App\Repository\RbcNewsRepository;
use App\Services\RbcNews\CurlMethod;
use App\Services\RbcNews\Parser;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException as OptimisticLockExceptionAlias;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var RbcNewsRepository
     */
    private $newsRepository;

    /**
     * IndexController constructor.
     *
     * @param CurlMethod                 $loader
     * @param RbcNewsRepository      $newsRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(CurlMethod $loader, RbcNewsRepository $newsRepository, EntityManagerInterface $em)
    {
        $this->parser = new Parser($loader, $newsRepository, $em);
        $this->newsRepository = $newsRepository;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        $news = $this->newsRepository->findBy([], ['timestamp' => 'DESC'], 15);
        if (empty($news)) {
            try {
                $this->updateNews();
            }
            catch (ConnectionException | ORMException $e) {}
            $news = $this->newsRepository->findBy([], ['timestamp' => 'DESC'], 15);
        }
        return $this->render('index.html.twig', ['news' => $news]);
    }

    /**
     * @return RedirectResponse
     */
    public function renew(): RedirectResponse
    {
        try {
            $this->updateNews();
        } catch (ConnectionException | ORMException $e) {}

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/news/show/{id}", methods={"GET","HEAD"})
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(int $id): Response
    {
        $news = $this->newsRepository->find($id);
        return $this->render('show.html.twig', ['news' => $news]);
    }

    /**
     * @return void
     * @throws ORMException
     * @throws OptimisticLockExceptionAlias
     *
     * @throws ConnectionException
     */
    private function updateNews(): void
    {
        $this->parser->process();
    }
}