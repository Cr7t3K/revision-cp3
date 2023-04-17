<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ConfigurationRepository;
use App\Service\TaxCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    #[Route('/item/{id}', name: 'app_item_show')]
    public function show(
        Item $item,
        TaxCalculator $taxCalculator,
        ConfigurationRepository $configurationRepository,
    ): Response {
        $this->addFlash('success', 'Bienvenue sur mon site');

        $tva = $configurationRepository->findOneBy(['name' => 'tva']);
        $priceTTC = $taxCalculator->calculate($item->getPrice(), (float) $tva->getValue());

        return $this->render('item/show.html.twig', [
            'item' => $item,
            'priceTTC' => $priceTTC
        ]);
    }
}
