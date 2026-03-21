<?php

declare(strict_types=1);

namespace App\Domain\DocumentSequence;

final class DocumentNumberFormatter
{
    private const array PREFIXES = [
        'facture' => 'FAC',
        'devis' => 'DEV',
        'avoir' => 'AV',
    ];

    public const array SEQUENTIAL_TYPES = ['facture', 'devis', 'avoir'];

    public const array NUMBER_FIELDS = [
        'facture' => 'numero_facture',
        'devis' => 'numero_devis',
        'avoir' => 'numero_avoir',
    ];

    public static function format(string $type, int $year, int $number): string
    {
        $prefix = self::PREFIXES[$type] ?? strtoupper($type);

        return sprintf('%s-%d-%03d', $prefix, $year, $number);
    }

    public static function isSequential(string $type): bool
    {
        return in_array($type, self::SEQUENTIAL_TYPES, true);
    }
}
