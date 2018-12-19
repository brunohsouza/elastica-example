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
use App\Service\DocumentService;
/**
 * Class DocumentController
 * @package App\Controller
 * @Route("/document")
 */
class DocumentController extends Controller
{

    /**
     * DocumentService Object
     * @var DocumentService
     */
    private $objDocumentService;

    /**
     * Create a document based on parameters given
     * @param $index
     * @param $type
     * @param Request $request
     * @FOSRest\Post("/{index}/{type}")
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function createDocument($index, $type, Request $request)
    {
        try {
            $container = $this->get("fos_elastica.index.$index.$type");
            $this->objDocumentService = new DocumentService($container);
            $parameters = $request->getContent();
            $response = $this->objElasticService->createDocument($parameters);

            return new Response(
                $this->json($response),
                Response::HTTP_OK,
                ['Content-type' => 'application/json']
            );
        } catch(\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update a document based on parameters given
     * @FOSRest\Put("/{index}/{type}/{id}")
     * @return Response
     */
    public function updateDocument($index, $type, $id, Request $request)
    {
        try {
            $container = $this->get("fos_elastica.index.$index.$type");
            $this->objDocumentService = new DocumentService($container);
            $parameters = $request->getContent();
            $response = $this->objDocumentService->updateDocument($id, $parameters);

            return new Response(
                $this->json($response),
                Response::HTTP_OK,
                ['Content-type' => 'application/json']
            );
        } catch(\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
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
            $this->objDocumentService = new DocumentService($container);
            $response = $this->objDocumentService->matchAll();

            return new Response(
                $this->json($response),
                Response::HTTP_OK,
                ['Content-type' => 'application/json']
            );
        } catch(\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
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
            $this->objDocumentService = new DocumentService($container);
            $parameters = $request->query->getIterator();
            $response = $this->objDocumentService->search($parameters);

            return new Response(
                $this->json($response),
                Response::HTTP_OK,
                ['Content-type' => 'application/json']
            );
        } catch(\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
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
            $this->objDocumentService = new DocumentService($finder);
            $response = $this->objDocumentService->getMap();

            return new Response(
                $this->json($response),
                Response::HTTP_OK,
                ['Content-type' => 'application/json']
            );
        } catch(\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
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
            $this->objDocumentService = new DocumentService($container);
            $parameters = $request->query->all();
            $response = $this->objDocumentService->match($parameters);

            return new Response(
                $this->json($response),
                Response::HTTP_OK,
                ['Content-type' => 'application/json']
            );
        } catch(\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Delete a document based on id given
     * @FOSRest\Delete("/{index}/{type}/{id}")
     * @return Response
     */
    public function deleteDocumentById($index, $type, $id)
    {
        try {
            $container = $this->get("fos_elastica.index.$index.$type");
            $this->objDocumentService = new DocumentService($container);
            $response = $this->objDocumentService->deleteDocumentById($id);

            return new Response(
                $this->json($response),
                Response::HTTP_OK,
                ['Content-type' => 'application/json']
            );
        } catch(\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param $index
     * @param $type
     * @param Request $request
     * @FOSRest\Post("/bulk/{index}/{type}")
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function populate($index, $type, Request $request)
    {
        try {
            $container = $this->get("fos_elastica.index.$index.$type");
            $this->objDocumentService = new DocumentService($container);
            $parameters = json_decode($request->getContent());
            $response = $this->objDocumentService->populate($parameters);

            return new Response(
                $this->json($response),
                Response::HTTP_OK,
                ['Content-type' => 'application/json']
            );
        } catch(\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}