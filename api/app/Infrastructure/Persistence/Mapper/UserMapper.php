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
            googleId: $model->google_id,
            companyName: $model->company_name,
            siret: $model->siret,
            address: $model->address,
            phone: $model->phone,
            mentionsLegales: $model->mentions_legales,
            numeroTva: $model->numero_tva,
            logo: $model->logo,
            consentAcceptedAt: $model->consent_accepted_at?->toDateTimeImmutable(),
            consentPolicyVersion: $model->consent_policy_version,
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
        $model->google_id = $entity->googleId;
        $model->company_name = $entity->companyName;
        $model->siret = $entity->siret;
        $model->address = $entity->address;
        $model->phone = $entity->phone;
        $model->mentions_legales = $entity->mentionsLegales;
        $model->numero_tva = $entity->numeroTva;
        $model->logo = $entity->logo;
        $model->consent_accepted_at = $entity->consentAcceptedAt;
        $model->consent_policy_version = $entity->consentPolicyVersion;

        return $model;
    }
}
