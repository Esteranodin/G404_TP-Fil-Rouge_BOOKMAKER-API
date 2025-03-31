<?php

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProDataPersister implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): object
    {
        if ($data instanceof User) {
            if ($data->getPassword()) {
                $hashedPassword = $this->passwordHasher->hashPassword($data, $data->getPassword());
                $data->setPassword($hashedPassword);
            }

            // $data->setRoles(['ROLE_USER']);

                if ($data->getUserPro()) {
                    $data->getUserPro()->setIsValidated(true);
                    $data->setRoles(['ROLE_USER_PRO']);
                }
                
            $this->entityManager->persist($data);
            $this->entityManager->flush();
        }

        return $data;
    }
}