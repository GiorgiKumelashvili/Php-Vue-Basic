<?php

namespace app\core\Routing;

class Response {
    /**
     * @param string $condition --> success, error
     * @param string|array $data
     */
    public function sendResponse(string $condition, $data): void {
        $response = ["statuscode" => null];

        // Response data
        if (gettype($data) === 'string') {
            $response['message'] = $data;
        }
        else {
            $response['data'] = $data;
        }

        // Response statuscode
        if ($condition === 'success') {
            $response['statuscode'] = 1;
        }
        elseif ($condition === 'error') {
            $response['statuscode'] = 0;
        }
        else {
            $response['statuscode'] = 2; // code error
        }

        exit(json_encode($response));
    }
}