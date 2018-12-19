<?php
/**
 * Created by PhpStorm.
 * User: brunohsouza
 * Date: 19/12/18
 * Time: 20:23
 */

namespace App\Controller;

use App\Service\IndexService;
use Elastica\Client;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 * @Route("/index")
 */
class IndexController extends Controller
{
    /**
     * DocumentService Object
     * @var IndexService
     */
    private $objIndexService;

    /**
     * @var Client
     */
    private $objClient;

    /**
     * IndexController constructor.
     */
    public function __construct()
    {
        $this->objClient = new Client();
    }

    /**
     * @FOSRest\Post("/{index}")
     */
    public function createIndex($index)
    {
        try {
            $this->objIndexService = new IndexService($this->objClient);

            return new Response(
                $this->json($this->objIndexService->createIndex($index)->getStats()->getData()),
                Response::HTTP_OK,
                ['Content-type' => 'application/json']
            );
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @FOSRest\Post("/{index}/{type}")
     */
    public function createType($index, $type)
    {
        try {
            $this->objIndexService = new IndexService($this->objClient);

            return new Response(
                $this->json($this->objIndexService->createType($index, $type)),
                Response::HTTP_OK,
                ['Content-type' => 'application/json']
            );
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}