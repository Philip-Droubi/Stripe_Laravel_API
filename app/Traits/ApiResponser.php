<?php

namespace App\Traits;

trait ApiResponser
{
    protected function success($data = null, $message = "ok", $code = 200)
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'returnedCode' => $code,
            'list' => $data,
        ], $code);
    }

    protected function fail($message = "", $code = 400, $data = null)
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
        $data['last_page'] = $objects->lastPage();
        $data['total'] = $objects->total();
        $data['perPage'] = (int)$objects->perPage();
        $data['currentPage'] = $objects->currentPage();
        return $data;
    }
}
