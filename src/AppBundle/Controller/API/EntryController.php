<?php

namespace AppBundle\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EntryController.
 *
 * @Route("/entry")
 */
class EntryController extends Controller
{
    /**
     * @TODO: Maybe use "/all" instead of "/data" for future APIs.
     *
     * @Route("/data", defaults={"_format": "json"}, name="simplified_entry_api")
     * @Route("/data.{_format}", defaults={"_format": "json"}, requirements={"_format": "json|xml|raw"},
     *                           name="full_entry_api")
     * @Method("GET")
     *
     * @param Request $request
     * @param string  $_format
     *
     * @return Response
     */
    public function listAction(Request $request, $_format)
    {
        $entries = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Entry')->findAll();
        if ($_format === 'json') {

            return new Response(json_encode($entries));
        } elseif ($_format === 'xml') {

            return $this->render('@App/api/entry/data.xml.twig', ['entries' => $entries]);
        } else {

            $response = $this->render('@App/api/entry/data.raw.twig', ['entries' => $entries]);
            $response->headers->set('Content-Type', 'text/plain');

            return $response;
        }
    }
}
