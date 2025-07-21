<?php

namespace App\Controller;

use App\Entity\WebUser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{
    #[Route('/auth/signup', name: 'webapp_signup', methods: ['POST', 'OPTIONS'])]
    public function signup(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        LoggerInterface $logger
    ): JsonResponse {
        if ($request->getMethod() === 'OPTIONS') {
        // CORS preflight válasz
        return new JsonResponse(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type',
        ]);
    }
        try {
        $data = json_decode($request->getContent(), true);
        
        $logger->info('Received signup request', [
            'data' => $data,
            'raw_content' => $request->getContent()
        ]);

        if (!is_array($data)) {
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }

        if (empty($data['username'])) {
            return new JsonResponse(['error' => 'Username is required'], 400);
        }

        if (empty($data['password'])) {
            return new JsonResponse(['error' => 'Password is required'], 400);
        }

        // Duplikált username ellenőrzés
        if ($em->getRepository(WebUser::class)->findOneBy(['username' => $data['username']])) {
            return new JsonResponse(['error' => 'Username already taken'], 400);
        }

        $user = new WebUser();
        $user->setUsername($data['username']);
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => 'User registered successfully'], 201);

    } catch (UniqueConstraintViolationException $e) {
        return new JsonResponse(['error' => 'Username already exists'], 400);
    } catch (\Throwable $e) {
        // Debughoz fejlesztésnél:
        return new JsonResponse(['error' => 'Internal server error', 'details' => $e->getMessage()], 500);
        // élesben csak: return new JsonResponse(['error' => 'Internal server error'], 500);
    }
    }

    #[Route('/api/user', name: 'webapp_getuser', methods: ['GET'])]
    public function getCurrentUser(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Itt implementálhatod a felhasználói adatok lekérését
        // Például, ha a felhasználó azonosítója a JWT-ben van, akkor azt dekódolhatod és lekérheted az adatokat
        // Vagy ha session alapú autentikációt használsz, akkor a session-ből olvashatod ki a felhasználót
        $user = $this->getUser(); // Symfony beépített metódus, ami visszaadja a jelenlegi felhasználót
        // Példa válasz (cseréld le a tényleges logikára):
        return new JsonResponse([
            'username' => $user->getUsername(),
        ], 200);
    }
}
