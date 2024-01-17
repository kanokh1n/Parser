<?php


namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;
use App\Entity\Product;
use App\Entity\Seller;
use Doctrine\ORM\EntityManagerInterface;

class ParserService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function collect($url, $pageCount)
    {
        $collectedCount = 0;
        $savedCount = 0;


        for ($i = 1; $i <= $pageCount; $i++) {
            $currentUrl = $url . '?' . http_build_query(['page' => $i]);
            $crawler = $this->getCrawler($currentUrl);


            if (!$crawler) {
                break;
            }

            $products = $this->parseProducts($crawler);

            if (empty($products)) {
                break;
            }

            foreach ($products as $productData) {
                $this->saveProduct($productData);

                $collectedCount++;
                $savedCount++;

            }
        }

        return ['collectedCount' => $collectedCount,
                'savedCount' => $savedCount];
    }

    private function getCrawler($url)
    {
        $headers = [
            'User_Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
        ];
        try {
            $client = HttpClient::create();

            $response = $client->request('GET', $url,[
                'headers' => $headers,
            ]);


            if ($response->getStatusCode() !== 200) {
                return null;
            }

            $content = $response->getContent();
            $crawler = new Crawler($content, $url);

            return $crawler;
        } catch (ExceptionInterface $e) {


            return null;
        }
    }

    private function parseProducts(Crawler $crawler)
    {
        $products = [];

        $crawler->filter('.ix.i0x')->each(function ($node) use (&$products) {

            $name = $node->filter('tsBody500Medium')->text();

            $seller = 'seller';

            $price = $node->filter('.c3125-a1 tsHeadline500Medium c3125-c0')->text();

            $reviewsCount = 10;

            $href = $node->filterXPath('//a')->attr('href');
            preg_match('/(\d+)(?=\?|$)/', $href, $matches);
            $sku = $matches[1] ?? null;

            $products[] = [
                'price' => $price,
                'name' => $name,
                'reviews_count' => $reviewsCount,
                'seller' => $seller,
                'sku' => $sku,
            ];

        });

        return $products;
    }

    private function saveProduct($productData)
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        $sellerRepository = $this->entityManager->getRepository(Seller::class);


        $seller = $sellerRepository->findOneBy(['name' => $productData['seller']]);

        if (!$seller) {
            $seller = new Seller();
            $seller->setName($productData['seller']);
            $this->entityManager->persist($seller);
            $this->entityManager->flush();
        }


        $product = $productRepository->findOneBy(['sku' => $productData['sku']]);

        if ($product) {

            $product->setPrice($productData['price']);
            $product->setName($productData['name']);
            $product->setReviewsCount($productData['reviews_count']);
            $product->setSeller($seller);
            $product->setUpdatedDateValue();
        } else {

            $product = new Product();
            $product->setSku($productData['sku']);
            $product->setPrice($productData['price']);
            $product->setName($productData['name']);
            $product->setReviewsCount($productData['reviews_count']);
            $product->setSeller($seller);
            $product->setCreatedDateValue();
            $product->setUpdatedDateValue();
            $this->entityManager->persist($product);
        }

        $this->entityManager->flush();
    }
}