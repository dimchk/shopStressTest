<?php

namespace App\Infrostructure\DataFixtures;

use App\Domain\Entity\Order;
use App\Domain\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class OrderFixtures extends Fixture
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
            $product = new Order();
            $product->setProducts($row['name']);
            $product->setSessionId($row['sessionId']);
            $product->setTotalAmount($row['amount']);
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
                echo "Row left: " . $totalRows - $i . "\n";
                echo "Time spent fo this block - " . round($executionBlockTime, 2) . "\n";
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
                'name' => '[{"id":' . rand(0, 30000) . '}]',
                'sessionId' => $hash,
                'amount' => rand(0, 100)
            ];
        }
    }
}
