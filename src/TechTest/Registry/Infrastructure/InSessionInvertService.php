<?php

declare(strict_types=1);

namespace App\TechTest\Registry\Infrastructure;

use App\TechTest\Registry\Application\Service\InvertService;
use Symfony\Component\HttpFoundation\RequestStack;

class InSessionInvertService implements InvertService
{
    private const SESSION_KEY = 'invert';

    public function __construct(private RequestStack $requestStack)
    {
    }

    public function inverted(): bool
    {
        return (bool) $this->requestStack->getSession()->get(self::SESSION_KEY);
    }

    public function toggle(): void
    {
        $session = $this->requestStack->getSession();

        if ($session->get(self::SESSION_KEY)) {
           $session->remove(self::SESSION_KEY);
           return;
        }
        $session->set(self::SESSION_KEY, true);
    }
}