<?php
declare(strict_types=1);

namespace App\UI\Http\Controller\Registry;


use App\TechTest\Registry\Application\Query\CompareValuesQuery;
use App\TechTest\Registry\Domain\Exception\InvalidItemValueException;
use App\TechTest\Shared\Application\Query\QueryBus;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CompareItemValues
{
    public function __construct(private QueryBus $queryBus)
    {}

    /**
     * Compares a item set with the registry
     *
     * @OA\Parameter(
     *      name="values",
     *      in="query",
     *      required=true,
     *      description="A comma separated list of values"
     * )
     * @OA\Response(
     *     response=200,
     *     description="The items of the provided set that are not present in the registry",
     *     @OA\JsonContent(
     *        @OA\Property(
     *          description="Represents the status of the response Ok/Ko",
     *          property="status",
     *          type="string"
     *        ),
     *        @OA\Property(
     *          description="The provided value",
     *          property="values",
     *          type="array",
     *          @OA\Items(
     *            description="Values not present in the registry",
     *            type="string"
     *          )
     *        )
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
    #[Route('/items/compare', name: 'compare-values', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        try {
            $values = explode(',',$request->get('values',''));
            $compareValuesQuery = new CompareValuesQuery($values);

            $responseValues = $this->queryBus->query($compareValuesQuery);
        }
        catch (InvalidItemValueException $e) {
            return new JsonResponse(['status' => 'Ko', 'message' => $e->getMessage()],Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['status' => 'Ok', 'values' => $responseValues]);
    }
}
