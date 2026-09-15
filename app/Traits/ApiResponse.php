<?php

namespace App\Traits;

trait ApiResponse
{
    protected function ok(string $message, $data = [])
    {
        return $this->success(message: $message, data: $data, statusCode: 200);
    }

    protected function success(string $message, $data = [], $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $statusCode,
        ], $statusCode);
    }

    public function error($errors = [], $statusCode = null)
    {
        if (is_string($errors)) {
            return response()->json([
                'message' => $errors,
                'status' => $statusCode,
            ], $statusCode);
        }

        return response()->json([
            'errors' => $errors,
            'status' => $statusCode,
        ], $statusCode);
    }
}
