<?php
declare(strict_types=1);

namespace App\UI\Http\Controller\Registry;


use App\TechTest\Registry\Application\Service\InvertService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ToggleInvert
{
    public function __construct(private InvertService $invertService)
    {}

    /**
     * Toggle invert flag
     *
     * When invert flag is active, the responses for GET /items/{value} are inverted
     *
     * @OA\Response(
     *     response=204,
     *     description="The invert flag was toggled from true to false or from false to true",
     * )
     * @OA\Tag(name="items")
     */
    #[Route('/items/invert/toggle', name: 'invert-toggle', methods: ['POST'])]
    public function __invoke(): Response
    {
        $this->invertService->toggle();

        return new JsonResponse(['status' => 'Ok'], Response::HTTP_NO_CONTENT);
    }
}
