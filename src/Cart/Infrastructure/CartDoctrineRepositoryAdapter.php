<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure;

use App\_Shared\Infrastructure\RepositoryAdapter;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartId;
use App\Cart\Domain\CartRepositoryInterface;
use App\Cart\Domain\Entity\CartOwnerId;
use App\Cart\Infrastructure\Persistence\CartOrm;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends RepositoryAdapter<CartOrm>
 */
class CartDoctrineRepositoryAdapter extends RepositoryAdapter implements CartRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager, CartOrm::class);
    }

    public function find(CartId $id): ?Cart
    {
        /** @var CartOrm|null */
        $cart = $this->repository->find($id->value());
        if ($cart === null) {
            return null;
        }

        return $this->toDomain($cart);
    }

    public function findByOwnerId(CartOwnerId $ownerId): ?Cart
    {
        /** @var CartOrm|null */
        $cart = $this->repository->findOneBy(['ownerId' => $ownerId->value(), 'processed' => false]);
        if ($cart === null) {
            return null;
        }

        return $this->toDomain($cart);
    }

    public function save(Cart $cart): Cart
    {
        $persistence = $this->toPersistence($cart);

        $this->entityManager->persist($persistence);
        $this->entityManager->flush();

        return $cart;
    }

    public function update(Cart $cart): Cart
    {
        /** @var CartOrm|null $reference */
        $reference = $this->repository->find($cart->id());
        if ($reference === null) {
            throw new \Exception('Cart not found');
        }

        $reference->merge($this->toPersistence($cart));
        $this->entityManager->flush();

        return $this->toDomain($reference);
    }

    /**
     * Transform a CartOrm object into a Cart object.
     *
     * @param CartOrm $object
     * @return Cart
     */
    protected function toDomain(object $object): Cart
    {
        return Cart::fromPrimitives([
            'id' => $object->id(),
            'owner_id' => $object->ownerId(),
            'processed' => $object->processed(),
        ]);
    }

    /**
     * Transform a Cart object into a CartOrm object.
     *
     * @param Cart $object
     * @return CartOrm
     */
    protected function toPersistence(object $object): CartOrm
    {
        return new CartOrm(
            id: $object->id(),
            ownerId: $object->ownerId(),
            processed: $object->processed(),
        );
    }
}
