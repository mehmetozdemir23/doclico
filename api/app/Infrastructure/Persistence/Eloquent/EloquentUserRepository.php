<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Identity\Email;
use App\Domain\Identity\User;
use App\Domain\Identity\UserId;
use App\Domain\Identity\UserRepositoryInterface;
use App\Infrastructure\Persistence\Mapper\UserMapper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function save(User $user): User
    {
        $model = UserMapper::toModel($user);
        $model->save();

        return UserMapper::toDomain($model);
    }

    public function update(User $user): void
    {
        $model = UserModel::find($user->id->value);

        if ($model) {
            $model->first_name = $user->firstName;
            $model->last_name = $user->lastName;
            $model->company_name = $user->companyName;
            $model->email = $user->email->value;
            $model->password = $user->password;
            $model->google_id = $user->googleId;
            $model->siret = $user->siret;
            $model->address = $user->address;
            $model->phone = $user->phone;
            $model->mentions_legales = $user->mentionsLegales;
            $model->numero_tva = $user->numeroTva;
            $model->logo = $user->logo;
            $model->save();
        }
    }

    public function findById(UserId $id): ?User
    {
        $model = UserModel::find($id->value);

        return $model ? UserMapper::toDomain($model) : null;
    }

    public function findByEmail(Email $email): ?User
    {
        $model = UserModel::where('email', $email->value)->first();

        return $model ? UserMapper::toDomain($model) : null;
    }

    public function findByGoogleId(string $googleId): ?User
    {
        $model = UserModel::where('google_id', $googleId)->first();

        return $model ? UserMapper::toDomain($model) : null;
    }

    public function existsByEmail(Email $email): bool
    {
        return UserModel::where('email', $email->value)->exists();
    }

    public function delete(UserId $id): void
    {
        $model = UserModel::find($id->value);

        if (! $model) {
            return;
        }

        if ($model->logo) {
            Storage::disk('public')->delete($model->logo);
        }

        DB::table('password_reset_tokens')->where('email', $model->email)->delete();

        DocumentModel::where('user_id', $id->value)->each(fn ($doc) => $doc->delete());

        $model->delete();
    }
}
