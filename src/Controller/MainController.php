<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(): Response
    {

        return $this->render('main/home.html.twig');

    }
    #[Route('/test', name: 'main_test')]
    public function test(SerieRepository $serieRepository, EntityManagerInterface $entityManager): Response
    {

        $serie = new Serie();
        $serie
            ->setBackdrop("backdrop.png")
            ->setDateCreated(new \DateTime())
            ->setGenres("Thriller")
            ->setName("Utopia")
            ->setFirstAirDate(new \DateTime("-2 year"))
            ->setLastAirDate(new \DateTime("-2 month"))
            ->setPopularity(500)
            ->setPoster("poster.png")
            ->setStatus("Canceled")
            ->setTmdbId(12345)
            ->setVote(5);
//        //ECF
//        //Sauvegarde de mon instance grâce à l'entityManager
//        $entityManager->persist($serie);
//        $entityManager->flush();
//        dump($serie);
//
//        $serie->setName("Code Quantum");
//
//        //si j'ai un id, j'update
//        $entityManager->persist($serie);
//        $entityManager->flush();
//        dump($serie);
//
//        //je supprime
//        $entityManager->remove($serie);
//        $entityManager->flush();

        $serieRepository->save($serie, true);

        dump($serie);

        $username = "<h2>Erwan</h2>";
        $serie = ["title" => "The Witcher", "year"=>2019];
        return $this->render('main/test.html.twig', [
            "nameOfUser"=>$username,
            "serie"=>$serie

        ]);

    }

}
