<?php

namespace App\Security;

use App\Entity\Organization;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ApiTokenRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiKeyAuthenticator extends AbstractAuthenticator
{

    private $userRepository;
    private $apiTokenRepository;

    public function __construct(UserRepository $userRepository, ApiTokenRepository $apiTokenRepository)
    {
        $this->userRepository = $userRepository;
        $this->apiTokenRepository = $apiTokenRepository;
    }

    public function supports(Request $request): ?bool
    {
        return str_starts_with($request->getPathInfo(), '/api/');
    }

    public function authenticate(Request $request): Passport
    {
        $apiToken = $request->headers->get('x-api-key');

        // Test if api token has been provided
        if (null === $apiToken) {
            throw new CustomUserMessageAuthenticationException('No API key provided');
        }

        // Test if api token exists and if it's active
        $token = $this->apiTokenRepository->findOneBy([
            'token' => $apiToken,
            'active' => 1
        ]);

        if(null === $token){
            throw new CustomUserMessageAuthenticationException('Wrong API key provided');
        }

        // Test api token scopes
        $method = '';
        if ($request->isMethod('post')) {
            $method = 'post';
        }
        if ($request->isMethod('get')) {
            $method = 'get';
        }
        if ($request->isMethod('put')) {
            $method = 'put';
        }
        if ($request->isMethod('patch')) {
            $method = 'patch';
        }
        if ($request->isMethod('delete')) {
            $method = 'delete';
        }

        $entity = '';
        if(strpos($request->getPathInfo(), '/api/users') !== FALSE){
            $entity = 'Users';
        }
        if(strpos($request->getPathInfo(), '/api/organizations') !== FALSE){
            $entity = 'Organizations';
        }

        $getScopeMethod = 'is'.ucfirst($method).ucfirst($entity);

        if(method_exists($token, $getScopeMethod) && empty($token->$getScopeMethod())){
            throw new CustomUserMessageAuthenticationException('Scope not allowed');
        }

        // Exception for post users -- Allowed with api token only
        if($method == 'post' && $entity == 'Users'){
            return new SelfValidatingPassport(
                new UserBadge($apiToken, function() {
                    return new User();
                })
            );            
        }

        

        // Exception for post organization -- Allowed with api token only
        if($method == 'post' && $entity == 'Organizations'){
            return new SelfValidatingPassport(
                new UserBadge($apiToken, function() {
                    return new User();
                })
            );            
        }
        

        $email = false;
        $jwtToken = $request->headers->get('Authorization');
        if(strpos($jwtToken, ".") !== FALSE){
            $tokenParts = explode(".", $jwtToken);  
            $tokenHeader = base64_decode($tokenParts[0]);
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtHeader = json_decode($tokenHeader);
            $jwtPayload = json_decode($tokenPayload);
            $id = $jwtPayload->id;
        }

        $user = $this->userRepository->findOneBy([
            'id' => $id
        ]);

        return new SelfValidatingPassport(
            new UserBadge($id, function() use ($user) {
                if (!$user) {
                    throw new UserNotFoundException();
                }
                // if($user->isVerified() === false){
                //     throw new CustomUserMessageAuthenticationException('Email address not verified');
                // }
                return $user;
            })
        );

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'code' => Response::HTTP_UNAUTHORIZED,
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

}