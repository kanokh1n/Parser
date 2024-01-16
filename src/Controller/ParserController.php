<?php
namespace App\Controller;

use App\Service\ParserService;
use App\Form\UrlFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParserController extends AbstractController
{
    private $parserService;

    public function __construct(ParserService $parserService)
    {
        $this->parserService = $parserService;
    }

    #[Route('/parser/form', name: 'parser_form')]
    public function form(): Response
    {
        $form = $this->createForm(UrlFormType::class);

        return $this->render('parser/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/parser/collect', name: 'parser_collect')]
    public function collectData(Request $request): Response
    {
        $url = '';
        $pageCount = 0;

        $form = $this->createForm(UrlFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $form->get('url')->getData();
            $pageCount = $form->get('pageCount')->getData();


            $result = $this->parserService->parseData($url, $pageCount);

            $collectedCount = $result['collectedCount'] ?? 0;
            $savedCount = $result['savedCount'] ?? 0;

            // Возвращаем форму вместе с результатами и введенными значениями
            return $this->render('parser/result.html.twig', [
                'collectedCount' => $collectedCount,
                'savedCount' => $savedCount,
                'form' => $form->createView(),
                'url' => $url, // Передаем URL в шаблон
                'pageCount' => $pageCount, // Передаем pageCount в шаблон

            ]);

        }

        // Если форма не валидна, возвращаем форму для повторного ввода
        return $this->render('parser/form.html.twig', [
            'form' => $form->createView(),
            'url' => $url,
            'pageCount' => $pageCount,
        ]);
    }
}
