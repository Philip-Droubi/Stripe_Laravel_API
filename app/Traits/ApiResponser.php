<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponser
{
    protected function success($data = null, $message = "ok", $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'returnedCode' => $code,
            'list' => $data,
        ], $code);
    }

    protected function fail($message = "", $code = 400, $data = null): JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'returnedCode' => $code,
            'list' => $data,
        ], $code);
    }

    protected function setPaginationData($objects, array $data): array
    {
        return $data + [
            'last_page' => $objects->lastPage(),
            'total' => $objects->total(),
            'perPage' => $objects->perPage(),
            'currentPage' => $objects->currentPage(),
        ];
    }
}
