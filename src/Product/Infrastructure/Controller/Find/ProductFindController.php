<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Controller\Find;

use OpenApi\Attributes as OA;
use App\_Shared\Infrastructure\ApiController;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Product\Application\Find\ProductFindQuery;
use App\Product\Application\ProductResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductFindController extends ApiController
{
    #[Route('/product/{id}', name: 'app_product_find', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the product information',
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'The id of the product',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'products')]
    public function find(QueryBus $bus, string $id): Response
    {
        $query = new ProductFindQuery(id: $id);

        /** @var ProductResponse|null $product */
        [$product, $error] = $this->safeAsk(query: $query, bus: $bus);
        if ($error !== null) {
            return $this->error($error->getMessage());
        }

        if ($product === null) {
            return $this->notFound();
        }

        return $this->toJson($product);
    }
}
