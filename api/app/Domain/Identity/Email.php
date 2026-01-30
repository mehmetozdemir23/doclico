<?php

declare(strict_types=1);

namespace App\Domain\Identity;

use App\Domain\Identity\Exception\InvalidEmailException;
use Stringable;

final readonly class Email implements Stringable
{
    private function __construct(
        public string $value
    ) {}

    public static function fromString(string $value): self
    {
        $normalized = strtolower(trim($value));

        if (! filter_var($normalized, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException($value);
        }

        return new self($normalized);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
