<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CircularResource;
use App\Models\Circular;

class CircularController extends Controller
{
    public function index()
    {
        $circulars = Circular::activeForDriver()->get();

        return response()->json([
            'data' => [
                'circulars' => CircularResource::collection($circulars),
            ],
            'message' => 'success',
            'code' => getMsgCode('success'),
        ]);
    }
}
