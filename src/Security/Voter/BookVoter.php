<?php

namespace App\Security\Voter;

use App\Entity\Book;
use App\Entity\UserPro;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class BookVoter extends Voter{
    public const EDIT = 'BOOK_EDIT';
    public const DELETE = 'BOOK_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Book;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Book $book */
        $book = $subject;

        /** @var UserPro $userPro */
        $book = $subject;

        return match($attribute) {
            self::EDIT, self::DELETE => $this->isOwner($book, $userPro),
            default => false,
        };
    }

    private function isOwner(Book $book, UserPro $userPro): bool
    {
        return $book->getUserPro() === $userPro;
    }
}
