<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mapper;

use App\Domain\Identity\Email;
use App\Domain\Identity\User;
use App\Domain\Identity\UserId;
use App\Infrastructure\Persistence\Eloquent\UserModel;

final class UserMapper
{
    public static function toDomain(UserModel $model): User
    {
        return new User(
            id: UserId::fromString($model->id),
            firstName: $model->first_name,
            lastName: $model->last_name,
            email: Email::fromString($model->email),
            password: $model->password,
        );
    }

    public static function toModel(User $entity): UserModel
    {
        $model = new UserModel;
        $model->id = $entity->id->value;
        $model->first_name = $entity->firstName;
        $model->last_name = $entity->lastName;
        $model->email = $entity->email->value;
        $model->password = $entity->password;

        return $model;
    }
}
