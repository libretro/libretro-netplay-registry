<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ORM\Entry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
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
        $route = $request->get('_route');

        // Creating formbuilder for handling API request of RetroArch.
        /** @var Form $form */
        $form = $this->createFormBuilder(
            null, [
                'csrf_protection' => false,
                'data_class'      => Entry::class,
                'empty_data'      => function (FormInterface $form) {
                    return Entry::fromSubmission(
                        $form->get('username')->getData(),
                        $form->get('corename')->getData(),
                        $form->get('coreversion')->getData(),
                        $form->get('gamename')->getData(),
                        $form->get('gamecrc')->getData()
                    );
                },
            ]
        )
            ->add('username', TextType::class)
            ->add('corename', TextType::class)
            ->add('coreversion', TextType::class)
            ->add('gamename', TextType::class)
            ->add('gamecrc', TextType::class)
            ->setMethod('GET')
            ->getForm();

        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) { // Assertions in Entry class are met.
            dump($request->request->all());
        }

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
