<?php

declare(strict_types=1);

namespace App\TechTest\Registry\Domain;

use App\TechTest\Registry\Domain\Exception\InvalidItemValueException;

class ItemValue
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @throws InvalidItemValueException
     */
    public static function fromString(string $value): self
    {
        if($value === '' || preg_match('/[^a-z0-9 ]/i', $value))
        {
            throw new InvalidItemValueException($value);
        }

        return new self($value);
    }

    public function isEqualTo(ItemValue $itemValue): bool
    {
        return $this->value === $itemValue->value();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}