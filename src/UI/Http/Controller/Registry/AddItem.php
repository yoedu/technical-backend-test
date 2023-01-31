<?php
declare(strict_types=1);

namespace App\UI\Http\Controller\Registry;


use App\TechTest\Registry\Application\Command\AddItemCommand;
use App\TechTest\Registry\Domain\Exception\InvalidItemValueException;
use App\TechTest\Registry\Domain\Exception\ItemValueAlreadyExistException;
use App\TechTest\Shared\Application\Command\CommandBus;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AddItem
{
    public function __construct(private CommandBus $commandBus)
    {}

    /**
     * Add an item to the registry
     *
     * @OA\RequestBody(
     *      @OA\JsonContent(
     *        @OA\Property(
     *          description="The provided value",
     *          property="value",
     *          type="string"
     *        )
     *      )
     * )
     * @OA\Response(
     *     response=201,
     *     description="The item was added to the registry",
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

    #[Route('/items', name: 'add-item', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        if ($request->getContentType() !== 'json' || !$request->getContent()) {
            return new JsonResponse(['status' => 'Ko', 'message' => 'Json Body required'],Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

            if (!isset($data['value'])) {
                return new JsonResponse(['status' => 'Ko', 'message' => 'Value key required'],Response::HTTP_BAD_REQUEST);
            }

            $addItemCommand = new AddItemCommand($data['value']);
            $this->commandBus->handle($addItemCommand);
        }
        catch (\JsonException | InvalidItemValueException | ItemValueAlreadyExistException $e) {
            return new JsonResponse(['status' => 'Ko', 'message' => $e->getMessage()],Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['status' => 'Ok'], Response::HTTP_CREATED);
    }
}
