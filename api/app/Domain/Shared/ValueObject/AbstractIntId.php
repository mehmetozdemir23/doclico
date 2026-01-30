<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use InvalidArgumentException;

abstract readonly class AbstractIntId implements IdInterface
{
    protected function __construct(
        public int $value
    ) {}

    public static function fromInt(int $value): static
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("Invalid ID: {$value}. Must be a positive integer.");
        }

        return new static($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(IdInterface $other): bool
    {
        return $other instanceof static && $this->value === $other->value();
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
