<?php

namespace App\Controller;


use App\Form\ParserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParserController extends AbstractController
{
    #[Route('/parser', name: 'parser_form')]
    public function form(): Response
    {
        $form = $this->createForm(ParserType::class);

        return $this->render('parser/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/parser', name: 'parser_collect')]
    public function collectData(Request $request): Response
    {
        $form = $this->createForm(ParserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $url = $form->get('url')->getData();

            // Далее передайте URL в метод сервиса ParserService для обработки данных
            // Реализация этого метода останется за вами

            // Пример:
            // $parserService = $this->get(ParserService::class);
            // $result = $parserService->parseData($url);

            // Вывод статистики по собранным данным
            $collectedCount = 10; // Замените на реальное значение
            $savedCount = 8; // Замените на реальное значение

            return $this->render('parser/result.html.twig', [
                'collectedCount' => $collectedCount,
                'savedCount' => $savedCount,
            ]);
        }

        // Если форма не валидна, возвращаем пользователя на страницу с формой
        return $this->render('parser/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
