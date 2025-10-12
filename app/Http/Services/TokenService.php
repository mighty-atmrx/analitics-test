<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Http\Exceptions\LoginPasswordRequiredException;
use App\Http\Exceptions\ServiceNotSupportTokenException;
use App\Models\Token;
use App\Repositories\ApiServiceTokenTypeRepository;
use App\Repositories\TokenRepository;
use App\Repositories\TokenTypeRepository;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Random\RandomException;

readonly class TokenService
{
    public function __construct(
        private TokenTypeRepository $tokenTypeRepository,
        private TokenRepository $tokenRepository,
        private ApiServiceTokenTypeRepository $apiServiceTokenTypeRepository,
    ){
    }

    /**
     * @throws RandomException
     * @throws LoginPasswordRequiredException
     */
    public function generateToken(int $tokenTypeId, array $data): string|array
    {
        $tokenType = $this->tokenTypeRepository->findById($tokenTypeId);
        if ($tokenType === 'login_password' && (empty($data['login']) || empty($data['password']))) {
            throw new LoginPasswordRequiredException();
        }

        return match ($tokenType) {
            'bearer' => $this->generateJwt($data),
            'api-key' => bin2hex(random_bytes(32)),
            'login_password' => $this->encodeLoginPassword($data),
            default => bin2hex(random_bytes(32)),
        };
    }

    /**
     * @throws RandomException
     */
    private function generateJwt(array $payload): array
    {
        $now = time();
        $accessExp = $now + config('jwt.access_exp');
        $refreshExp = $now + config('jwt.refresh_exp');

        $jwtPayload = array_merge([
            'sub' => $payload['account_id'] ?? 0,
            'iat' => $now,
            'exp' => $accessExp,
            'nbf' => $now,
        ], $payload);

        $secret = config('jwt.secret');

        return [
            'access_token' => JWT::encode($jwtPayload, $secret, 'HS256'),
            'refresh_token' => bin2hex(random_bytes(64)),
            'expires_at' => date('Y-m-d H:i:s', $accessExp),
            'refresh_expires_at' => date('Y-m-d H:i:s', $refreshExp),
        ];
    }

    private function encodeLoginPassword(array $data): string
    {
        if (empty($data['login']) || empty($data['password'])) {
            throw new InvalidArgumentException('Missing login or password for login_password token');
        }

        return base64_encode($data['login'] . ':' . $data['password']);
    }

    /**
     * @throws ServiceNotSupportTokenException
     * @throws RandomException|LoginPasswordRequiredException
     */
    public function create(array $data): Token
    {
        if (!$this->apiServiceTokenTypeRepository->exists((int)$data['token_type_id'], (int)$data['api_service_id'])) {
            throw new ServiceNotSupportTokenException();
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $tokenData = $this->generateToken((int)$data['token_type_id'], $data);

        if (is_array($tokenData)) {
            $data['token'] = $tokenData['access_token'];
            $data['refresh_token'] = $tokenData['refresh_token'];
            $data['expires_at'] = $tokenData['expires_at'];
            $data['refresh_expires_at'] = $tokenData['refresh_expires_at'];
        } else {
            $data['token'] = $tokenData;
            $data['refresh_token'] = null;
            $data['expires_at'] = null;
            $data['refresh_expires_at'] = null;
        }

        return $this->tokenRepository->create($data);
    }
}
