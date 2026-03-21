<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Domain\Sharing\ShareId;
use App\Domain\Sharing\ShareToken;
use App\Infrastructure\Persistence\Eloquent\ShareModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShareModel>
 */
final class ShareFactory extends Factory
{
    protected $model = ShareModel::class;

    public function definition(): array
    {
        return [
            'id' => ShareId::generate()->value,
            'token' => ShareToken::generate()->value,
            'expires_at' => null,
            'shared_at' => now(),
            'downloads_count' => 0,
            'last_downloaded_at' => null,
            'views_count' => 0,
            'first_viewed_at' => null,
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (): array => [
            'expires_at' => now()->subDay(),
        ]);
    }
}
