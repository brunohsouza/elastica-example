<?php
/**
 * Created by PhpStorm.
 * User: brunohsouza
 * Date: 12/12/18
 * Time: 17:42
 */

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ElasticService;
/**
 * Class ElasticController
 * @package App\Controller
 * @Route("/elastic")
 */
class ElasticController extends Controller
{

    /**
     * ElasticService Object
     * @var ElasticService
     */
    private $objElasticService;

    /**
     * Create a document based on parameters given
     * @param $index
     * @param $type
     * @param Request $request
     * @FOSRest\Post("/document/{index}/{type}")
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function createDocument($index, $type, Request $request)
    {
        try {
            $container = $this->get("fos_elastica.index.$index.$type");
            $this->objElasticService = new ElasticService($container);
            $parameters = $request->getContent();
            $response = $this->objElasticService->createDocument($parameters);

            return $this->json($response);
        } catch(\Exception $exception) {
            return new Response($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Update a document based on parameters given
     * @FOSRest\Put("/document/{index}/{type}/{id}")
     * @return Response
     */
    public function updateDocument($index, $type, $id, Request $request)
    {
        try {
            $container = $this->get("fos_elastica.index.$index.$type");
            $this->objElasticService = new ElasticService($container);
            $parameters = $request->getContent();
            $response = $this->objElasticService->updateDocument($id, $parameters);

            return $this->json($response);
        } catch(\Exception $exception) {
            return new Response($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Search for a Index and type
     * @FOSRest\Get("/match-all/{index}/{type}")
     */
    public function matchAll($index, $type)
    {
        try {
            $container = $this->get("fos_elastica.index.$index.$type");
            $this->objElasticService = new ElasticService($container);
            $response = $this->objElasticService->matchAll();

            return $this->json($response);
        } catch(\Exception $exception) {
            return new Response($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Search for a Index, type and parameter exactly requested
     * @FOSRest\Get("/search/{index}/{type}")
     */
    public function search($index, $type, Request $request)
    {
        try {
            $container = $this->get("fos_elastica.index.$index.$type");
            $this->objElasticService = new ElasticService($container);
            $parameters = $request->query->getIterator();
            $response = $this->objElasticService->search($parameters);

            return $this->json($response);
        } catch(\Exception $exception) {
            return new Response($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Return the structure for a Index
     * @FOSRest\Get("/map/{index}")
     */
    public function map($index)
    {
        try {
            $finder = $this->get("fos_elastica.index.$index");
            $this->objElasticService = new ElasticService($finder);
            $response = $this->objElasticService->getMap();

            return $this->json($response);
        } catch(\Exception $exception) {
            return new Response($exception->getMessage(), $exception->getCode());
        }

    }

    /**
     * Search for a Index, type and parameter that matches the indexed value
     * @param $index
     * @param $type
     * @param Request $request
     * @FOSRest\Get("/match/{index}/{type}")
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function match($index, $type, Request $request)
    {
        try {
            $container = $this->get("fos_elastica.index.$index.$type");
            $this->objElasticService = new ElasticService($container);
            $parameters = $request->query->all();
            $response = $this->objElasticService->match($parameters);

            return $this->json($response);
        } catch(\Exception $exception) {
            return new Response($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Delete a document based on id given
     * @FOSRest\Delete("/document/{index}/{type}/{id}")
     * @return Response
     */
    public function deleteDocumentById($index, $type, $id)
    {
        try {
            $container = $this->get("fos_elastica.index.$index.$type");
            $this->objElasticService = new ElasticService($container);
            $response = $this->objElasticService->deleteDocumentById($id);

            return $this->json($response);
        } catch(\Exception $exception) {
            return new Response($exception->getMessage(), 400);
        }
    }
}