<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

//Préfixer la route au-dessus de la classe
#[Route('/series', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/{page}', name: 'list', requirements: ["page" => "\d+"])]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {

        //$series = $serieRepository->findBestSeries();
        // $series = $serieRepository->findBy([], ["popularity" => "DESC"], 48);
        $nbSeries = $serieRepository->count([]);
        $maxPage = ceil($nbSeries / Serie::MAX_RESULT);

        //gestion page inférieur à 1
        if ($page < 1) {

            return $this->redirectToRoute('serie_list', ['page' => 1]);

        } elseif ($page > $maxPage) {
            //gestion page sup >max
            return $this->redirectToRoute('serie_list', ['page' => $maxPage]);

        } else {

            $series = $serieRepository->findSeriesWithPagination($page);
            return $this->render('serie/list.html.twig', ['series' => $series, 'currentPage' => $page, 'maxPage' => $maxPage]);
        }
    }

    #[Route('/detail/{id}', name: 'show', requirements: ["id" => "\d+"])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {

        $serie = $serieRepository->find($id);

        if (!$serie) {
            throw $this->createNotFoundException("Oop ! Serie not found !");
        }
        return $this->render('serie/show.html.twig', ['serie' => $serie]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, SerieRepository $serieRepository): Response
    {

        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);

        //Permet d'extraire les données de la requête
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            //traitement de la donnée

            //récupération des champs non mapped
            $genres = $serieForm->get('genres')->getData();
            $serie->setGenres(implode(' / ', $genres));

            //new DateTime renvoi la date du jour
            $serie->setDateCreated(new \DateTime());

            //ajoute la série en BDD
            $serieRepository->save($serie, true);

            //après ajout, redirige vers la page de détail
            $this->addFlash('success', 'Serie added !');
            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);
        }

        return $this->render('serie/add.html.twig', ['serieForm' => $serieForm->createView()]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ["id" => "\d+"])]
    public function update(int $id, SerieRepository $serieRepository)
    {

        $serie = $serieRepository->find($id);

        $serieForm = $this->createForm(SerieType::class, $serie);

        if ($serieForm->isSubmitted()) {
            //traitement de la donnée

            //récupération des champs non mapped
            $genres = $serieForm->get('genres')->getData();
            $serie->setGenres(implode(' / ', $genres));

            //new DateTime renvoi la date du jour
            $serie->setDateModified(new \DateTime());

            //ajoute la série en BDD
            $serieRepository->save($serie, true);

            //après ajout, redirige vers la page de détail
            $this->addFlash('success', 'Serie added !');
            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);
        }

        return $this->render('serie/update.html.twig', ['serieForm' => $serieForm->createView()]);

    }

}
