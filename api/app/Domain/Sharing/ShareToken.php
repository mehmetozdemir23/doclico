<?php

declare(strict_types=1);

namespace App\Domain\Sharing;

use InvalidArgumentException;
use Stringable;

final readonly class ShareToken implements Stringable
{
    private const TOKEN_LENGTH = 32;

    private function __construct(
        public string $value
    ) {}

    public static function generate(): self
    {
        $bytes = random_bytes(self::TOKEN_LENGTH / 2);

        return new self(bin2hex($bytes));
    }

    public static function fromString(string $value): self
    {
        if (strlen($value) !== self::TOKEN_LENGTH) {
            throw new InvalidArgumentException('Invalid share token length');
        }

        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
