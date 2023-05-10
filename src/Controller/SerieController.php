<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

//Préfixer la route au-dessus de la classe
#[Route('/series', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(): Response
    {
        //TODO renvoyer la liste des série
        return $this->render('serie/list.html.twig');
    }

    #[Route('/{id}', name: 'show', requirements: ["id"=>"\d+"])]
    public function show(int $id): Response
    {
        dump($id);
        //TODO renvoyer le détail d'une série
        return $this->render('serie/show.html.twig');
    }

    #[Route('/add', name: 'add')]
    public function add(): Response
    {
        //TODO renvoyer un formulaire d'ajout d'une nouvelle série

        $serie = new serie();
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

        return $this->render('serie/add.html.twig');
    }

}
