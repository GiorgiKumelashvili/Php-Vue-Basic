<?php

/** @noinspection PhpInconsistentReturnPointsInspection */

namespace app\controllers\auth;

use app\core\Application;
use Exception;
use Firebase\JWT\JWT;

class TokenController {
    private const ALGORITHM = 'HS256';
    private const ACCESS_EXPIRE = 5;
    private const REFESH_EXPIRE = 10;

    /**
     * @param array $data
     * @param string $whichToken --> access, refresh
     * @return string
     */
    public static function generateToken(array $data, string $whichToken) {
        $issuer = $_ENV['FRONT_ACCESS_LOCATION'];
        $accessKey = '';
        $exp = '';

        if ($whichToken === 'access') {
            $accessKey = $_ENV['ACCESS_TOKEN_SECRET'];
            $exp = self::ACCESS_EXPIRE;
        }
        elseif ($whichToken === 'refresh') {
            $accessKey = $_ENV['REFRESH_TOKEN_SECRET'];
            $exp = self::REFESH_EXPIRE;
        }
        else {
            Application::$app
                ->response
                ->sendResponse('error', "Incorrect name for token");
        }

        $payload = [
            'iss' => $issuer,       // issuer
            'iat' => time(),        // issued at
            'exp' => time() + $exp, // expires at

            // identify user
            'identifier' => $data['identifier']
        ];

        return JWT::encode($payload, $accessKey, self::ALGORITHM);
    }

    public static function validateAccessToken(): void {
        try {
            $token = self::getBearerToken();
            if ($token) {
                JWT::decode($token, $_ENV['ACCESS_TOKEN_SECRET'], [self::ALGORITHM]);
            }
            else {
                Application::$app->response->sendResponse('error', "Token not found");
            }
        }
        catch (Exception $e) {
            Application::$app->response->sendResponse('error', "Token expired");
        }
    }

    public static function refreshAccessToken(): void {
        try {
            $token = self::getBearerToken();
            $message = '';

            if ($token) {
                $payload = (array)JWT::decode($token, $_ENV['REFRESH_TOKEN_SECRET'], [self::ALGORITHM]);
                $postData = json_decode(file_get_contents('php://input'), true);

                if ($postData && $postData['data'] && $postData['data'] === 'refreshToken') {
                    $data = [
                        "accessToken" => self::generateToken($payload, 'access'),
                        "message" => "generated new access token"
                    ];

                    Application::$app->response->sendResponse('success', $data);
                }
                else {
                    $message = "Invalid post data must be ['data': 'refreshToken']";
                }
            }
            else {
                $message = "No token found";
            }

            if ($message) {
                Application::$app->response->sendResponse('error', $message);
            }
        }
        catch (Exception $e) {
            Application::$app->response->sendResponse('error', "Refresh token expired");
        }
    }

    // get access token from header
    private static function getBearerToken() {
        $headers = self::getAuthorizationHeader();

        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    // Get header Authorization
    private static function getAuthorizationHeader() {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        }
        elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
}