<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/season', name: 'season_')]
class SeasonController extends AbstractController
{
    #[IsGranted("ROLE_USER")]
    #[Route('/add/{id}', name: 'add', requirements: ["page" => "\d+"])]
    public function add(
        SeasonRepository $seasonRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        int $id,
        SerieRepository $serieRepository): Response
    {
        //récupération de l'instance de la série
        $serie=  $serieRepository->find($id);


        $season = new Season();
        $season->setSerie($serie);

        $seasonForm=$this->createForm(SeasonType::class, $season);


        $seasonForm->handleRequest($request);

        if ($seasonForm->isSubmitted()  && $seasonForm->isValid()){

            /**
             * @var UploadedFile $file
             */
            $file=$seasonForm->get('poster')->getData();

            if($file){
                $newFileName = $season->getSerie()->getName()."-".$season->getNumber()."-".uniqid().".".$file->guessExtension();
                $file->move('img/posters/seasons', $newFileName);
                $season->setPoster($newFileName);

            }

            $seasonRepository->save($season, true);

            //Deux manières de sauvegarder en base de donnée
/*          $entityManager->persist($season);
            $entityManager->flush();*/

            $this->addFlash('success', 'Season added on '. $season->getSerie()->getName());
            return $this->redirectToRoute('serie_show', ['id'=>$season->getSerie()->getId()]);
        }

        return $this->render('season/add.html.twig', [
            'seasonForm'=>$seasonForm->createView(),
            'id'=>$serie->getId()
        ]);
    }
    #[IsGranted("ROLE_USER")]
    public function delete(SerieRepository $serieRepository){


    }

}
