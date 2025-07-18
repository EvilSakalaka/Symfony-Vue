<?php

namespace App\Controller;

use App\Entity\ApiUser;
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
    #[Route('/api/signup', name: 'webapp_signup', methods: ['POST', 'OPTIONS'])]
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
        if ($em->getRepository(ApiUser::class)->findOneBy(['username' => $data['username']])) {
            return new JsonResponse(['error' => 'Username already taken'], 400);
        }

        $user = new ApiUser();
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


}
