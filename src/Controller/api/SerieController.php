<?php

namespace App\Controller\api;


use App\Entity\Serie;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


//Préfixer la route au-dessus de la classe
#[Route('/api/series', name: 'api_series_')]
class SerieController extends AbstractController{


    #[Route('', name: 'retrieve_all', methods: ['GET'])]
    public function retrieveAll(SerieRepository $serieRepository) : Response
    {
            $series = $serieRepository->findAll();
            return $this->json($series,200,[],['groups'=>'serie_data']);

    }
    #[Route('/{id}', name: 'retrieve_one', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function retrieveOne(int $id) : Response
    {


    }

    #[Route('', name: 'add_one', methods: ['POST'])]
    public function addOne(Request $request, SerializerInterface$serializer) : Response
    {
       // dd("coucou");
        $json = $request->getContent();

       // dd(json_decode($json));

        $serie=$serializer->deserialize($json, Serie::class, 'json');

        dd($serie);

    }


    #[Route('/{id}', name: 'delete_one', requirements: ['id'=>'\d+'], methods: ['DELETE'])]
    public function deleteOne(int $id) : Response
    {


    }
    #[Route('/{id}', name: 'update_one', methods: ['PUT'])]
    public function updateOne(int $id, Request $request,SerieRepository $serieRepository)  : Response
    {
        $serie=$serieRepository->find($id);
        if ($serie){

            //transforme un string au format JSOn en objet PHP
            $data = json_decode($request->getContent());
            if ($data->value) {
                $serie->setNbLike($serie->getNbLike() + 1);
            } else {

                $serie->setNbLike($serie->getNbLike() - 1);
            }
            $serieRepository->save($serie,true);
            return $this->json(['nbLike'=> $serie->getNbLike()]);
        }

        return $this->json(['error'=>'serie not found']);

    }



}
