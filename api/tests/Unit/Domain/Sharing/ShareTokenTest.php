<?php

use App\Domain\Sharing\ShareToken;

it('generates a token with correct length', function (): void {
    $token = ShareToken::generate();

    expect($token->value)->toBeString()
        ->and(strlen($token->value))->toBe(32);
});

it('creates from a valid string', function (): void {
    $value = str_repeat('a', 32);
    $token = ShareToken::fromString($value);

    expect($token->value)->toBe($value);
});

it('throws exception for invalid token length', function (): void {
    ShareToken::fromString('short-token');
})->throws(InvalidArgumentException::class, 'Invalid share token length');

it('converts to string', function (): void {
    $value = str_repeat('b', 32);
    $token = ShareToken::fromString($value);

    expect((string) $token)->toBe($value);
});
