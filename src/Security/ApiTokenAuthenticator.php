<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\ApiTokenRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    private ApiTokenRepository $apiTokenRepository;
    public function __construct(ApiTokenRepository $apiTokenRepository,){
        $this->apiTokenRepository = $apiTokenRepository;
    }
    public function supports(Request $request): ?bool
    {
        return (bool) preg_match('@/api/.*@', $request->getPathInfo());
    }

    public function authenticate(Request $request): Passport
    {
        $authorizationHeader = $request->headers->get('Authorization');

        // skip beyond "Bearer "
        $credentials = substr($authorizationHeader, 7);

        return new SelfValidatingPassport(
            new UserBadge($credentials,function($credentials){
                return $this->loadUser($credentials);
            }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?JsonResponse
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?JsonResponse
    {
        return new JsonResponse([
            'message' => $exception->getMessageKey()
        ], 401);
    }

    public function loadUser(string $userIdentifier): User{
        $apiToken = $this->apiTokenRepository->findOneBy(['token' => $userIdentifier]);

        if (!$apiToken) {
            throw new CustomUserMessageAuthenticationException(
                'Missing or Invalid API Token'
            );
        }

        if($apiToken->isExpired()){
            throw new CustomUserMessageAuthenticationException(
                'Token expired'
            );
        }

        return $user = $apiToken->getOwner();
    }
}
