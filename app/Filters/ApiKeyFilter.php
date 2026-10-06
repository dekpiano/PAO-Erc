<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SettingsModel;

class ApiKeyFilter implements FilterInterface
{
    /**
     * คีย์เริ่มต้น หากยังไม่ได้กำหนดใน .env หรือ Database
     */
    private const DEFAULT_API_KEY = 'pao-erc-itsupport-api-key-2026';

    public function before(RequestInterface $request, $arguments = null)
    {
        $response = service('response');

        // จัดการ Preflight CORS Request (OPTIONS)
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            return $this->attachCorsHeaders($response)
                        ->setStatusCode(200)
                        ->setBody('');
        }

        // ดึง API Key จาก Header หรือ Query String
        $incomingRequest = service('request');
        $apiKey = $incomingRequest->getHeaderLine('X-API-KEY');

        // ตรวจสอบ Authorization: Bearer <token>
        if (empty($apiKey)) {
            $authHeader = $incomingRequest->getHeaderLine('Authorization');
            if (!empty($authHeader)) {
                if (stripos($authHeader, 'Bearer ') === 0) {
                    $apiKey = trim(substr($authHeader, 7));
                } else {
                    $apiKey = trim($authHeader);
                }
            }
        }

        // เผื่อดึงจาก Query Parameter (เช่น ?api_key=xxx หรือ ?key=xxx)
        if (empty($apiKey)) {
            $apiKey = $incomingRequest->getGet('api_key') ?: $incomingRequest->getGet('key');
        }

        // ดึง Valid API Keys ที่อนุญาต
        $validKeys = $this->getValidApiKeys();

        if (empty($apiKey) || !in_array($apiKey, $validKeys, true)) {
            return $this->attachCorsHeaders($response)
                        ->setStatusCode(401)
                        ->setJSON([
                            'status'    => 401,
                            'error'     => 'Unauthorized',
                            'message'   => 'Missing or invalid API Key. Please provide a valid key in X-API-KEY header or Bearer Token.',
                            'timestamp' => date('Y-m-d H:i:s')
                        ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $this->attachCorsHeaders($response);
    }

    /**
     * แนบ Header สำหรับ CORS
     */
    private function attachCorsHeaders(ResponseInterface $response): ResponseInterface
    {
        return $response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Headers', 'X-API-KEY, Authorization, Content-Type, Accept, X-Requested-With, Origin')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, HEAD')
            ->setHeader('Access-Control-Max-Age', '86400');
    }

    /**
     * ดึงรายการ API Key ที่ถูกต้องจาก .env, Database, และ Default
     */
    private function getValidApiKeys(): array
    {
        $keys = [];

        // 1. จาก .env
        $envKey = env('IT_SUPPORT_API_KEY') ?: env('API_KEY');
        if (!empty($envKey)) {
            $keys[] = trim($envKey);
        }

        // 2. จาก Tb_Settings ใน Database
        try {
            $settingsModel = new SettingsModel();
            $dbKey = $settingsModel->getVal('itsupport_api_key');
            if (!empty($dbKey)) {
                $keys[] = trim($dbKey);
            }
        } catch (\Throwable $e) {
            // ละเว้นหากฐานข้อมูลยังไม่มีตาราง
        }

        // 3. Fallback default key
        $keys[] = self::DEFAULT_API_KEY;

        return array_unique(array_filter($keys));
    }
}
