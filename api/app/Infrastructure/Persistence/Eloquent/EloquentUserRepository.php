<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Identity\Email;
use App\Domain\Identity\User;
use App\Domain\Identity\UserId;
use App\Domain\Identity\UserRepositoryInterface;
use App\Infrastructure\Persistence\Mapper\UserMapper;

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
            $model->email = $user->email->value;
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

    public function existsByEmail(Email $email): bool
    {
        return UserModel::where('email', $email->value)->exists();
    }
}
