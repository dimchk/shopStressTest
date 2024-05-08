<?php

namespace App\Infrostructure\DataFixtures;

use App\Domain\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $start = microtime(true);
        $blockStart = microtime(true);

        ini_set('memory_limit', '8G');
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);

        $batchSize = 10000;
        $totalRows = 100000;
        $generator = $this->generateRows($totalRows);
        $temp = [];

        foreach ($generator as $i => $row) {
            $product = new Product();
            $product->setName($row['name']);
            $product->setQuantity($row['quantity']);
            $product->setSku($row['sku']);
            $product->setPrice($row['price']);
            $manager->persist($product);
            $temp[] = $product;
            if (($i % $batchSize) === 0) {
                $manager->flush();
                foreach ($temp as $product) {
                    $manager->detach($product);
                }
                $temp = null;
                gc_enable();
                gc_collect_cycles();
                $blockEnd = microtime(true);
                $executionBlockTime = $blockEnd - $blockStart;
                echo "Row left: " . $totalRows  - $i . "\n";
                echo "Time spent fo this block" . $executionBlockTime . "\n";
                $blockStart = microtime(true);

            }
        }
        $manager->flush();
        $manager->clear();
        $end = microtime(true);
        $executionTime = $end - $start;

        echo "Row counts: " . $totalRows . "\n";
        echo "Script execution time: " . $executionTime . " seconds\n";
        echo "Memory usage: " . memory_get_usage() / 1000000 . " mb\n";
        echo "Memory peak usage: " . memory_get_peak_usage() / 1000000 . " mb\n";
    }

    private function generateRows(int $totalRows): \Generator
    {
        for ($i = 0; $i < $totalRows; $i++) {
            $hash = Uuid::uuid4()->toString();
            yield [
                'name' => 'Iphone model-' . $hash,
                'quantity' => 5,
                'price' => 1,
                'sku' => 'sku' . $hash,
            ];
        }
    }
}
