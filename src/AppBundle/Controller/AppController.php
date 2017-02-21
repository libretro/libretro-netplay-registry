<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Entry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints\NotBlank;

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
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $route = $request->get('_route');
        $em    = $this->get('doctrine.orm.entity_manager');

        // Creating formbuilder for handling API request of RetroArch.
        /** @var Form $form */
        $form = $this->createFormBuilder(
            null, [
                'allow_extra_fields' => true,
            ]
        )
            ->add(
                'username', TextType::class, [
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'gamename', TextType::class, [
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'gamecrc', TextType::class, [
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'corename', TextType::class, [
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'coreversion', TextType::class, [
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'ip', TextType::class, [
                    'constraints' => [
                        new Ip(),
                    ],
                ]
            )
            ->setMethod('GET')
            ->getForm();

        $queryData = $request->query->all();
        if ($queryData) {
            $form->submit($queryData);

            if ($form->isSubmitted() && $form->isValid()) { // Assertions in Entry class are met.
                $entry = Entry::fromSubmission(
                    $form->get('username')->getData(),
                    $form->get('corename')->getData(),
                    $form->get('coreversion')->getData(),
                    $form->get('gamename')->getData(),
                    $form->get('gamecrc')->getData()
                );
                $ip    = $form->get('ip')->getData() ? $form->get('ip')->getData() : $request->getClientIp();
                $entry->setIp($ip);

                $errors = $this->get('validator')->validate($entry);

                if (count($errors) === 0) {
                    $em->persist($entry);
                    $em->flush();
                }
            }
        }

        $entries = $em->getRepository('AppBundle:Entry')->findAll();

        return $this->render(
            '@App/app/index.html.twig', [
                'isFallback' => $route === 'homepage_fallback',
                'entries'    => $entries,
            ]
        );
    }

    /**
     * @TODO: We can abstract this route to another controller, so API and the Web-Frontend are seperated.
     *
     * @TODO: We can remove the fallback, once the API changed on the client-side. RetroArch
     * @Route("/raw/", defaults={"_format": "raw"}, name="raw_entry_api_fallback")
     * @Route("/raw/data", defaults={"_format": "json"}, name="simplified_entry_api")
     * @Route("/raw/data.{_format}", defaults={"_format": "json"}, requirements={"_format": "json|xml|raw"},
     *                               name="full_entry_api")
     *
     * @param Request $request
     * @param string  $_format
     *
     * @return Response
     */
    public function rawAction(Request $request, $_format)
    {
        /** @var Entry[] $entries */
        $entries = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Entry')->findAll();
        if ($_format === 'json') {

            return new Response(json_encode($entries));
        } elseif ($_format === 'xml') {

            return $this->render('@App/app/data.xml.twig', ['entries' => $entries]);
        } else {

            $response = $this->render('@App/app/data.raw.twig', ['entries' => $entries]);
            $response->headers->set('Content-Type', 'text/plain');

            return $response;
        }
    }
}
