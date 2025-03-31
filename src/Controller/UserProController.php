<?php

namespace App\Controller;

use App\Service\UserProService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class UserProController extends AbstractController
{
    private UserProService $userProService;
    private SerializerInterface $serializer;

    public function __construct(
        UserProService $userProService,
        SerializerInterface $serializer
    ) {
        $this->userProService = $userProService;
        $this->serializer = $serializer;
    }

    /**
     * Créer une demande pour devenir professionnel
     */
    #[Route('/user-pro/request', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function requestUserPro(Request $request): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser instanceof \App\Entity\User) {
            return $this->json(['message' => 'Utilisateur non reconnu'], Response::HTTP_BAD_REQUEST);
        }

        // Vérifier si l'utilisateur a déjà une demande
        if ($currentUser->getUserPro()) {
            return $this->json(
                ['message' => 'Vous avez déjà une demande en cours ou un profil professionnel'],
                Response::HTTP_BAD_REQUEST
            );
        }

        // Récupérer les données de la requête (comme le numéro de téléphone)
        $data = json_decode($request->getContent(), true);
        $phone = $data['phone'] ?? null;

        // Créer la demande
        $userPro = $this->userProService->createUserProRequest($currentUser, $phone);

        // Retourner la réponse
        return $this->json(
            [
                'message' => 'Votre demande de statut professionnel a été enregistrée',
                'userPro' => $userPro
            ],
            Response::HTTP_CREATED,
            [],
            ['groups' => 'userPro:read']
        );
    }
}
