<?php

namespace App\Controller\Admin;

use App\Entity\UserPro;
use App\Service\UserProService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminUserProController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserProService $userProService;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserProService $userProService
    ) {
        $this->entityManager = $entityManager;
        $this->userProService = $userProService;
    }

    /**
     * Liste toutes les demandes en attente
     */
    #[Route('/user-pro/pending', methods: ['GET'])]
    public function getPendingRequests(): JsonResponse
    {
        $pendingRequests = $this->entityManager->getRepository(UserPro::class)
            ->findBy(['isValidated' => false], ['requestedAt' => 'DESC']);
            
        return $this->json(
            $pendingRequests,
            Response::HTTP_OK,
            [],
            ['groups' => 'userPro:read']
        );
    }

    /**
     * Valide une demande de statut professionnel
     */
    #[Route('/user-pro/{id}/validate', methods: ['POST'])]
    public function validateUserPro(UserPro $userPro): JsonResponse
    {
        $this->userProService->validateUserPro($userPro);
        
        return $this->json([
            'message' => 'Utilisateur professionnel validé avec succès',
            'userPro' => $userPro
        ], Response::HTTP_OK, [], ['groups' => 'userPro:read']);
    }
    
    /**
     * Rejette une demande de statut professionnel
     */
    #[Route('/user-pro/{id}/reject', methods: ['POST'])]
    public function rejectUserPro(UserPro $userPro): JsonResponse
    {
        $this->userProService->rejectUserProRequest($userPro);
        
        return $this->json([
            'message' => 'Demande de statut professionnel rejetée'
        ], Response::HTTP_OK);
    }
}