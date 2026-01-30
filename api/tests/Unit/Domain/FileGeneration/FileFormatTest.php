<?php

use App\Domain\FileGeneration\FileFormat;

it('returns correct extension for pdf', function (): void {
    expect(FileFormat::Pdf->extension())->toBe('pdf');
});

it('returns correct extension for docx', function (): void {
    expect(FileFormat::Docx->extension())->toBe('docx');
});

it('creates from valid string value', function (): void {
    expect(FileFormat::from('pdf'))->toBe(FileFormat::Pdf)
        ->and(FileFormat::from('docx'))->toBe(FileFormat::Docx);
});

it('returns null for invalid string value', function (): void {
    expect(FileFormat::tryFrom('invalid'))->toBeNull();
});
