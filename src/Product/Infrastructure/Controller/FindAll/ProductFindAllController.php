<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Controller\FindAll;

use OpenApi\Attributes as OA;
use App\_Shared\Infrastructure\ApiController;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Product\Application\FindAll\ProductFindAllQuery;
use App\Product\Application\ProductResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductFindAllController extends ApiController
{
    #[Route('/products', name: 'app_product_find_all', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns all the products information',
    )]
    #[OA\Tag(name: 'products')]
    public function findAll(QueryBus $bus): Response
    {
        $query = new ProductFindAllQuery();

        /** @var ProductResponse[] $products */
        [$products, $error] = $this->safeAsk(query: $query, bus: $bus);
        if ($error !== null) {
            return $this->error($error->getMessage());
        }

        return $this->toJson($products);
    }
}
