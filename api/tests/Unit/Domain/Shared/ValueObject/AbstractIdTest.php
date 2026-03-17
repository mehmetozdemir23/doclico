<?php

use App\Domain\Document\DocumentId;
use App\Domain\Identity\UserId;
use App\Domain\Sharing\ShareId;

it('generates a valid UUID', function (): void {
    $id = UserId::generate();

    expect($id->value)->toBeString()
        ->and(strlen($id->value))->toBe(36);
});

it('creates from a valid string', function (): void {
    $value = '550e8400-e29b-41d4-a716-446655440000';
    $id = UserId::fromString($value);

    expect($id->value)->toBe($value);
});

it('throws exception for invalid UUID string', function (): void {
    UserId::fromString('invalid-uuid');
})->throws(InvalidArgumentException::class);

it('compares same type IDs correctly', function (): void {
    $value = '550e8400-e29b-41d4-a716-446655440000';
    $id1 = UserId::fromString($value);
    $id2 = UserId::fromString($value);
    $id3 = UserId::generate();

    expect($id1->equals($id2))->toBeTrue()
        ->and($id1->equals($id3))->toBeFalse();
});

it('does not equal different type IDs with same value', function (): void {
    $value = '550e8400-e29b-41d4-a716-446655440000';
    $userId = UserId::fromString($value);
    $documentId = DocumentId::fromString($value);

    expect($userId->equals($documentId))->toBeFalse();
});

it('converts to string', function (): void {
    $value = '550e8400-e29b-41d4-a716-446655440000';
    $id = UserId::fromString($value);

    expect((string) $id)->toBe($value);
});

it('works with different typed IDs', function (): void {
    $userId = UserId::generate();
    $documentId = DocumentId::generate();
    $shareId = ShareId::generate();

    expect($userId)->toBeInstanceOf(UserId::class)
        ->and($documentId)->toBeInstanceOf(DocumentId::class)
        ->and($shareId)->toBeInstanceOf(ShareId::class);
});
