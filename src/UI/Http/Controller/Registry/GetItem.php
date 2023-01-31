<?php
declare(strict_types=1);

namespace App\UI\Http\Controller\Registry;


use App\TechTest\Registry\Application\Query\GetItemQuery;
use App\TechTest\Registry\Domain\Exception\InvalidItemValueException;
use App\TechTest\Registry\Domain\Exception\ItemNotFoundException;
use App\TechTest\Shared\Application\Query\QueryBus;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetItem
{
    public function __construct(private QueryBus $queryBus)
    {}

    /**
     * Checks the presence of an item with the provided value in the registry
     *
     * @OA\Response(
     *     response=200,
     *     description="The item with the provided value is present in the registry",
     *     @OA\JsonContent(
     *        @OA\Property(
     *          description="Represents the status of the response Ok/Ko",
     *          property="status",
     *          type="string"
     *        ),
     *        @OA\Property(
     *          description="The provided value",
     *          property="value",
     *          type="string"
     *        )
     *     )
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
    #[Route('/items/{value}', name: 'check-value', methods: ['GET'])]
    public function __invoke(string $value): Response
    {
        try {
            $getItemQuery = new GetItemQuery($value);
            $responseValue = $this->queryBus->query($getItemQuery);
        }
        catch (InvalidItemValueException $e) {
            return new JsonResponse(['status' => 'Ko', 'message' => $e->getMessage()],Response::HTTP_BAD_REQUEST);
        }
        catch (ItemNotFoundException $e) {
            return new JsonResponse(['status' => 'Ko', 'message' => $e->getMessage()],Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['status' => 'Ok', 'value' => $responseValue]);
    }
}
