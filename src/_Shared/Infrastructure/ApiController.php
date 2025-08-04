<?php

declare(strict_types=1);

namespace App\_Shared\Infrastructure;

use App\_Shared\Application\Response as ApplicationResponse;
use App\_Shared\Message\Command\Domain\Command;
use App\_Shared\Message\Command\Domain\CommandBus;
use App\_Shared\Message\Query\Domain\Query;
use App\_Shared\Message\Query\Domain\QueryBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Serializer\SerializerInterface;

abstract class ApiController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
    ) {}

    /**
     * @template T of object
     * 
     * Transform the request object received using a class reference to map properties
     * 
     * @param Request $request
     *
     * @param class-string<T> $document
     *
     * @return T|Response
     */
    protected function toDocument(Request $request, string $document): object
    {

        $object = $this->serializer->deserialize(
            data: $request->getContent(),
            type: $document,
            format: 'json',
        );

        $errors = $this->validator->validate($object);
        if (count($errors) > 0) {
            return $this->badRequest();
        }

        return $object;
    }

    protected function toJson(mixed $data): JsonResponse
    {
        return new JsonResponse(data: $data, status: Response::HTTP_OK);
    }

    protected function accepted(): Response
    {
        return new Response(status: Response::HTTP_ACCEPTED);
    }

    protected function notFound(): Response
    {
        return new Response(status: Response::HTTP_NOT_FOUND);
    }

    protected function badRequest(): Response
    {
        return new Response(status: Response::HTTP_BAD_REQUEST);
    }

    protected function unauthorized(): Response
    {
        return new Response(status: Response::HTTP_UNAUTHORIZED);
    }

    protected function forbidden(): Response
    {
        return new Response(status: Response::HTTP_FORBIDDEN);
    }

    protected function error(string $message): Response
    {
        return new JsonResponse(data: [
            'message' => $message,
        ], status: Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param QueryBus $bus
     * @param Query $query
     * 
     * @return array{?ApplicationResponse, ?\Throwable}
     */
    protected function safeAsk(QueryBus $bus, Query $query): array
    {
        try {
            return [$bus->ask($query), null];
        } catch (HandlerFailedException $exception) {
            $error = $exception->getPrevious();
            return [null, $error];
        }
    }

    /**
     * @param CommandBus $bus
     * @param Command $command
     *
     * @return ?\Throwable
     */
    protected function safeDispatch(CommandBus $bus, Command $command): ?\Throwable
    {
        try {
            $bus->dispatch($command);
            return null;
        } catch (HandlerFailedException $exception) {
            $error = $exception->getPrevious();
            return $error;
        }
    }
}
