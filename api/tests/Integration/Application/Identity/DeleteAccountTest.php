<?php

use App\Application\Identity\DeleteAccount;
use App\Domain\Identity\UserId;
use App\Infrastructure\Persistence\Eloquent\ClientModel;
use App\Infrastructure\Persistence\Eloquent\DocumentModel;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\assertDatabaseMissing;

it('permanently deletes the user from the database', function (): void {
    $user = UserModel::factory()->create();

    app(DeleteAccount::class)->execute(UserId::fromString($user->id));

    assertDatabaseMissing('users', ['id' => $user->id]);
});

it('cascades deletion to clients', function (): void {
    $user = UserModel::factory()->create();
    ClientModel::factory()->for($user, 'user')->count(3)->create();

    app(DeleteAccount::class)->execute(UserId::fromString($user->id));

    expect(ClientModel::where('user_id', $user->id)->count())->toBe(0);
});

it('cascades deletion to documents', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    DocumentModel::factory()->for($user, 'user')->for($template, 'template')->count(2)->create();

    app(DeleteAccount::class)->execute(UserId::fromString($user->id));

    expect(DocumentModel::where('user_id', $user->id)->count())->toBe(0);
});

it('deletes password reset tokens', function (): void {
    $user = UserModel::factory()->create();
    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => 'some-token',
        'created_at' => now(),
    ]);

    app(DeleteAccount::class)->execute(UserId::fromString($user->id));

    assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
});

it('deletes the logo file from storage', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('logos/user-logo.jpg', 'fake-image-data');

    $user = UserModel::factory()->create(['logo' => 'logos/user-logo.jpg']);

    app(DeleteAccount::class)->execute(UserId::fromString($user->id));

    Storage::disk('public')->assertMissing('logos/user-logo.jpg');
});

it('does not delete other users', function (): void {
    $user = UserModel::factory()->create();
    $other = UserModel::factory()->create();

    app(DeleteAccount::class)->execute(UserId::fromString($user->id));

    assertDatabaseMissing('users', ['id' => $user->id]);
    expect(UserModel::find($other->id))->not->toBeNull();
});
