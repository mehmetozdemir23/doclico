<?php

declare(strict_types=1);

namespace App\Application\Sharing;

use App\Domain\Document\Document;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Identity\User;
use App\Domain\Identity\UserRepositoryInterface;
use App\Domain\Sharing\ShareRepositoryInterface;
use DateTimeImmutable;
use Throwable;

final readonly class SendShareReminders
{
    public function __construct(
        private ShareRepositoryInterface $shareRepository,
        private DocumentRepositoryInterface $documentRepository,
        private UserRepositoryInterface $userRepository,
        private ShareReminderNotifierInterface $notifier,
        private string $frontendUrl,
        private int $reminderDelayDays = 3,
    ) {}

    public function execute(): int
    {
        $threshold = new DateTimeImmutable("-{$this->reminderDelayDays} days");
        $shares = $this->shareRepository->findNotRemindedOlderThan($threshold);

        $sent = 0;

        foreach ($shares as $share) {
            $document = $this->documentRepository->findById($share->documentId);

            if (! $document instanceof Document) {
                continue;
            }

            $user = $this->userRepository->findById($document->userId);

            if (! $user instanceof User) {
                continue;
            }

            try {
                $this->notifier->notify(
                    userEmail: $user->email->value,
                    userName: $user->firstName,
                    documentName: $document->name,
                    shareUrl: $share->shareUrl($this->frontendUrl),
                );
            } catch (Throwable) {
                continue;
            }

            $share->markReminded();
            $this->shareRepository->update($share);

            $sent++;
        }

        return $sent;
    }
}
