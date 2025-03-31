<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserPro;
use Doctrine\ORM\EntityManagerInterface;

class UserProService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Crée une demande de statut professionnel pour un utilisateur
     */
    public function createUserProRequest(User $user, ?string $phone = null): UserPro
    {
        // Créer un nouvel objet UserPro
        $userPro = new UserPro();
        $userPro->setUser($user);
        $userPro->setIsValidated(false);
        
        if ($phone) {
            $userPro->setPhone($phone);
        }
        
        // Mettre à jour les rôles de l'utilisateur
        $roles = $user->getRoles();
        if (!in_array('ROLE_USER_PRO_PENDING', $roles)) {
            $roles[] = 'ROLE_USER_PRO_PENDING';
            $user->setRoles($roles);
        }
        
        $this->entityManager->persist($userPro);
        $this->entityManager->flush();
        
        return $userPro;
    }

    /**
     * Valide une demande de statut professionnel
     */
    public function validateUserPro(UserPro $userPro): void
    {
        $userPro->setIsValidated(true);
        
        $user = $userPro->getUser();
        $roles = $user->getRoles();
        
        // Supprimer ROLE_USER_PRO_PENDING
        if (($key = array_search('ROLE_USER_PRO_PENDING', $roles)) !== false) {
            unset($roles[$key]);
        }
        
        // Ajouter ROLE_USER_PRO
        if (!in_array('ROLE_USER_PRO', $roles)) {
            $roles[] = 'ROLE_USER_PRO';
        }
        
        $user->setRoles(array_values($roles));
        
        $this->entityManager->flush();
    }

    /**
     * Rejette une demande de statut professionnel
     */
    public function rejectUserProRequest(UserPro $userPro): void
    {
        $user = $userPro->getUser();
        $roles = $user->getRoles();
        
        // Supprimer ROLE_USER_PRO_PENDING
        if (($key = array_search('ROLE_USER_PRO_PENDING', $roles)) !== false) {
            unset($roles[$key]);
            $user->setRoles(array_values($roles));
        }
        
        // Supprimer le UserPro
        $this->entityManager->remove($userPro);
        $this->entityManager->flush();
    }
}