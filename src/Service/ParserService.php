<?php

namespace App\Service;

use GuzzleHttp\Client;
use React\EventLoop\Loop;
use React\Promise\Promise;
use App\Entity\Product;
use App\Entity\Seller;
use Doctrine\ORM\EntityManagerInterface;

class ParserService
{
    private $loop;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->loop = Loop::get();
        $this->entityManager = $entityManager;
    }

    private function asyncRequest(Client $client, $url): Promise
    {
        $deferred = new \React\Promise\Deferred();

        $client->getAsync($url)->then(
            function ($response) use ($deferred) {
                $data = json_decode($response->getBody(), true);
                $deferred->resolve($data);
            },
            function ($error) use ($deferred) {
                $deferred->reject($error);
            }
        );

        return $deferred->promise();
    }

    public function parseData($url, $pageCount = null)
    {
        $client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36',
                'Content-Type' => 'text/html; charset=UTF-8',
                'Accept' => 'application/json',
                'Accept-Language' => 'en-US,en;q=0.9',
            ],
        ]);
        $collectedCount = 1;
        $savedCount = 1;

        $promises = [];

        for ($i = 1; $i <= $pageCount; $i++) {
            $promises[] = $this->asyncRequest($client, $url . '?page=' . $i);
        }

        $allPromises = \React\Promise\all($promises);

        $allPromises->then(
            function ($results) use (&$collectedCount, &$savedCount) {
                foreach ($results as $result) {
                    $this->saveProduct($result);
                    $collectedCount += count($result);
                    $savedCount += count($result);

                }
            },
            function ($error) {

            }
        );

        return [
            'collectedCount' => $collectedCount,
            'savedCount' => $savedCount,
        ];
    }

    private function saveProduct($data)
    {
        $productRepository = $this->entityManager->getRepository(Product::class);

        foreach ($data as $item) {

            $product = new Product();
            preg_match('/\/(\d+)\//', $item['i2u tile-hover-target'], $matches);
            $sku = !empty($matches[1]) ? $matches[1] : null;

            $product->setSku($sku);
            $product->setPrice((int)preg_replace('/[^\d]/', '',$item['c3125-a1 tsHeadline500Medium c3125-c0']));
            $product->setName($item['tsBody500Medium']);
            $product->setReviewsCount((int)filter_var($item['ga25-a2 tsBodyControl400Small'], FILTER_SANITIZE_NUMBER_INT));
            $product->setSeller($this->getOrCreateSeller($item['s1j']));
            $product->setCreatedDateValue();
            $product->setUpdatedDateValue();

            $this->entityManager->persist($product);
            $this->entityManager->flush();
        }
    }

    private function getOrCreateSeller($sellerName)
    {
        $sellerRepository = $this->entityManager->getRepository(Seller::class);

        $seller = $sellerRepository->findOneBy(['name' => $sellerName]);

        if (!$seller) {
            $seller = new Seller();
            $seller->setName($sellerName);

            $this->entityManager->persist($seller);
            $this->entityManager->flush();
        }

        return $seller;
    }
}