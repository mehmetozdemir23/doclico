<?php

use App\Domain\Template\TemplateId;

it('creates from a valid int', function (): void {
    $id = TemplateId::fromInt(42);

    expect($id->value)->toBe(42);
});

it('throws exception for zero', function (): void {
    TemplateId::fromInt(0);
})->throws(InvalidArgumentException::class, 'Invalid ID: 0. Must be a positive integer.');

it('throws exception for negative int', function (): void {
    TemplateId::fromInt(-5);
})->throws(InvalidArgumentException::class, 'Invalid ID: -5. Must be a positive integer.');

it('compares same type IDs correctly', function (): void {
    $id1 = TemplateId::fromInt(42);
    $id2 = TemplateId::fromInt(42);
    $id3 = TemplateId::fromInt(99);

    expect($id1->equals($id2))->toBeTrue()
        ->and($id1->equals($id3))->toBeFalse();
});

it('converts to string', function (): void {
    $id = TemplateId::fromInt(42);

    expect((string) $id)->toBe('42');
});

it('returns value via method', function (): void {
    $id = TemplateId::fromInt(42);

    expect($id->value())->toBe(42);
});
