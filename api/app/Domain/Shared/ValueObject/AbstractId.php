<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

abstract readonly class AbstractId implements IdInterface
{
    protected function __construct(
        public string $value
    ) {}

    public static function generate(): static
    {
        return new static(Uuid::uuid7()->toString());
    }

    public static function fromString(string $value): static
    {
        if (! Uuid::isValid($value)) {
            throw new InvalidArgumentException("Invalid UUID: {$value}");
        }

        return new static($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(IdInterface $other): bool
    {
        return $other instanceof static && $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
