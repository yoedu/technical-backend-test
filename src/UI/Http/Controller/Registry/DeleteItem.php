<?php
declare(strict_types=1);

namespace App\UI\Http\Controller\Registry;


use App\TechTest\Registry\Application\Command\DeleteItemCommand;
use App\TechTest\Registry\Domain\Exception\InvalidItemValueException;
use App\TechTest\Registry\Domain\Exception\ItemNotFoundException;
use App\TechTest\Shared\Application\Command\CommandBus;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DeleteItem
{
    public function __construct(private CommandBus $commandBus)
    {}

    /**
     * Deletes an item from the registry
     *
     * @OA\Response(
     *     response=204,
     *     description="The item was deleted from the registry",
     * )
     * @OA\Response(
     *     response=404,
     *     description="The item with the provided value is not present in the registry",
     *     @OA\JsonContent(
     *          ref="#/components/schemas/Error"
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="The provided value is not valid",
     *     @OA\JsonContent(
     *          ref="#/components/schemas/Error"
     *     )
     * )
     * @OA\Tag(name="items")
     */

    #[Route('/items/{value}', name: 'delete-item', methods: ['DELETE'])]
    public function __invoke(string $value): Response
    {
        try {
            $deleteItemCommand = new DeleteItemCommand($value);
            $this->commandBus->handle($deleteItemCommand);
        }
        catch (InvalidItemValueException $e) {
            return new JsonResponse(['status' => 'Ko', 'message' => $e->getMessage()],Response::HTTP_BAD_REQUEST);
        }
        catch (ItemNotFoundException $e) {
            return new JsonResponse(['status' => 'Ko', 'message' => $e->getMessage()],Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['status' => 'Ok'], Response::HTTP_NO_CONTENT);
    }
}
