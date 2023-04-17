<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemFormType;
use App\Form\ItemType;
use App\Repository\ConfigurationRepository;
use App\Repository\ItemRepository;
use App\Service\TaxCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ItemController extends AbstractController
{
    #[Route('/item/new', name: 'app_item_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, ItemRepository $itemRepository): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $itemRepository->save($item, true);

            $this->addFlash('success', 'Merci, un nouveau item a été créer');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/item/{id}', name: 'app_item_show')]
    #[IsGranted('ROLE_USER')]
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
