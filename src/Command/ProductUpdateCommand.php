<?php

namespace App\Command;

use App\Entity\Product;
use App\Service\ParserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ProductUpdateCommand extends Command
{
    private $entityManager;
    private $parserService;

    public function __construct(EntityManagerInterface $entityManager, ParserService $parserService)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->parserService = $parserService;
    }
}