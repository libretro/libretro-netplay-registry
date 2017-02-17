<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AppController.
 */
class AppController extends Controller
{
    /**
     * @TODO: We can remove the fallback, once the API changed on the client-side. RetroArch
     * @Route("/index.php", name="homepage_fallback")
     * @Route("/", name="homepage")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $route   = $request->get('_route');
        $entries = ['',  ''];

        return $this->render(
            '@App/app/index.html.twig', [
                'isFallback' => $route === 'homepage_fallback',
                'entries'    => $entries,
            ]
        );
    }
}
