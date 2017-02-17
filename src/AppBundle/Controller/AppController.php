<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ORM\Entry;
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
        // This is demo code. I will remove it, after my changes are accepted.
        $entry1  = Entry::fromSubmission('Bob', 'PPSSPP', '1.0', 'Little Big Planet', 'MYCRC');
        $entry2  = Entry::fromSubmission('Pete', 'PPSSPP', '1.0', 'Little Big Planet', 'MYCRC');
        $entries = [$entry1, $entry2];

        return $this->render(
            '@App/app/index.html.twig', [
                'isFallback' => $route === 'homepage_fallback',
                'entries'    => $entries,
            ]
        );
    }
}
