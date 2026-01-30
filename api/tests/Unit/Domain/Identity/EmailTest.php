<?php

use App\Domain\Identity\Email;
use App\Domain\Identity\Exception\InvalidEmailException;

it('creates from a valid email', function (): void {
    $email = Email::fromString('test@example.com');

    expect($email->value)->toBe('test@example.com');
});

it('normalizes email to lowercase', function (): void {
    $email = Email::fromString('Test@EXAMPLE.COM');

    expect($email->value)->toBe('test@example.com');
});

it('trims whitespace', function (): void {
    $email = Email::fromString('  test@example.com  ');

    expect($email->value)->toBe('test@example.com');
});

it('throws exception for invalid email', function (): void {
    Email::fromString('invalid-email');
})->throws(InvalidEmailException::class, 'Invalid email format: invalid-email');

it('throws exception for empty string', function (): void {
    Email::fromString('');
})->throws(InvalidEmailException::class);

it('compares emails correctly', function (): void {
    $email1 = Email::fromString('test@example.com');
    $email2 = Email::fromString('TEST@example.com');
    $email3 = Email::fromString('other@example.com');

    expect($email1->equals($email2))->toBeTrue()
        ->and($email1->equals($email3))->toBeFalse();
});

it('converts to string', function (): void {
    $email = Email::fromString('test@example.com');

    expect((string) $email)->toBe('test@example.com');
});
