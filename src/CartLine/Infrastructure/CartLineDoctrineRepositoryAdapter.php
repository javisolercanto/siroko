<?php

declare(strict_types=1);

namespace App\CartLine\Infrastructure;

use App\_Shared\Infrastructure\RepositoryAdapter;
use App\CartLine\Domain\Entity\CartLineProductId;
use App\CartLine\Domain\Entity\CartLine;
use App\CartLine\Domain\Entity\CartLineId;
use App\CartLine\Domain\CartLineRepositoryInterface;
use App\CartLine\Domain\Entity\CartLineCartId;
use App\CartLine\Infrastructure\Persistence\CartLineOrm;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends RepositoryAdapter<CartLineOrm>
 */
class CartLineDoctrineRepositoryAdapter extends RepositoryAdapter implements CartLineRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager, CartLineOrm::class);
    }

    public function find(CartLineId $id): ?CartLine
    {
        /** @var CartLineOrm|null $persistence */
        $persistence = $this->repository->find($id);
        if ($persistence === null) {
            return null;
        }

        return $this->toDomain($persistence);
    }

    public function findByCartId(CartLineCartId $cartId): array
    {
        /** @var CartLineOrm[] $persistences */
        $persistences = $this->repository->findBy(['cartId' => $cartId]);
        return array_map([$this, 'toDomain'], $persistences);
    }

    public function findByProductId(CartLineCartId $cartId, CartLineProductId $productId): ?CartLine
    {
        /** @var CartLineOrm|null $persistence */
        $persistence = $this->repository->findOneBy([
            'productId' => $productId,
            'cartId' => $cartId
        ]);

        return $persistence === null ? null : $this->toDomain($persistence);
    }

    public function save(CartLine $cartLine): CartLine
    {
        $persistence = $this->toPersistence($cartLine);

        $this->entityManager->persist($persistence);
        $this->entityManager->flush();

        return $cartLine;
    }

    public function update(CartLine $cartLine): CartLine
    {
        /** @var CartLineOrm|null $reference */
        $reference = $this->repository->find($cartLine->id());
        if ($reference === null) {
            throw new \Exception('Cart line not found');
        }

        $reference->merge($this->toPersistence($cartLine));
        $this->entityManager->flush();

        return $this->toDomain($reference);
    }

    public function delete(CartLine $cartLine): void
    {
        /** @var CartLineOrm|null $persistence */
        $persistence = $this->repository->find($cartLine->id());
        if ($persistence === null) {
            return;
        }

        $this->entityManager->remove($persistence);
        $this->entityManager->flush();
    }

    /**
     * Transform a CartLineOrm object into a CartLine object.
     *
     * @param CartLineOrm $object
     * @return CartLine
     */
    protected function toDomain(object $object): CartLine
    {
        return CartLine::fromPrimitives([
            'id' => $object->id(),
            'owner_id' => $object->ownerId(),
            'cart_id' => $object->cartId(),
            'product_id' => $object->productId(),
            'quantity' => $object->quantity(),
        ]);
    }

    /**
     * Transform a CartLine object into a CartLineOrm object.
     *
     * @param CartLine $object
     * @return CartLineOrm
     */
    protected function toPersistence(object $object): CartLineOrm
    {
        return new CartLineOrm(
            id: $object->id(),
            ownerId: $object->ownerId(),
            cartId: $object->cartId(),
            productId: $object->productId(),
            quantity: $object->quantity(),
        );
    }
}
