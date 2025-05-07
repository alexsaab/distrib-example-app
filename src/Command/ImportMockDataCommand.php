<?php

namespace App\Command;

use App\Entity\ReturnData;
use App\Entity\Sale;
use App\Entity\Stock;
use App\Repository\ReturnDataRepository;
use App\Repository\SaleRepository;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-mock-data',
    description: 'Import mock data from JSON files into the database'
)]
class ImportMockDataCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectDir = dirname(__DIR__, 2);

        // --- ReturnData ---
        $returnsPath = $projectDir . '/templates/dataexamples/returns_mocking.json';
        $returnsData = json_decode(file_get_contents($returnsPath), true);
        foreach ($returnsData as $row) {
            if (!is_array($row) || count($row) < 6) continue;
            $entity = new ReturnData();
            $entity->setTaxId($row[0]);
            $entity->setBrand($row[1]);
            $entity->setSku($row[2]);
            $entity->setSalesDate(new \DateTime($row[3]));
            $entity->setReturnDate(new \DateTime($row[4]));
            $entity->setQuantity((int)$row[5]);
            $this->em->persist($entity);
        }
        $output->writeln('<info>Imported ReturnData</info>');

        // --- Sale ---
        $salesPath = $projectDir . '/templates/dataexamples/sales_mocking.json';
        $salesData = json_decode(file_get_contents($salesPath), true);
        foreach ($salesData as $row) {
            if (!is_array($row) || count($row) < 5) continue;
            $entity = new Sale();
            $entity->setTaxId($row[0]);
            $entity->setBrand($row[1]);
            $entity->setSku($row[2]);
            $entity->setSalesDate(new \DateTime($row[3]));
            $entity->setQuantity((int)$row[4]);
            $this->em->persist($entity);
        }
        $output->writeln('<info>Imported Sale</info>');

        // --- Stock ---
        $stocksPath = $projectDir . '/templates/dataexamples/stocks_mocking.json';
        $stocksData = json_decode(file_get_contents($stocksPath), true);
        foreach ($stocksData as $row) {
            if (!is_array($row) || count($row) < 4) continue;
            $entity = new Stock();
            $entity->setBrand($row[0]);
            $entity->setSku($row[1]);
            $entity->setStockDate(new \DateTime($row[2]));
            $entity->setQuantity((int)$row[3]);
            $this->em->persist($entity);
        }
        $output->writeln('<info>Imported Stock</info>');

        $this->em->flush();

        $output->writeln('<info>All mock data imported successfully!</info>');
        return Command::SUCCESS;
    }
}