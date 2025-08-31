<?php

namespace App\Http\Controllers\Faceit;

use App\Helpers\JsonKeyNormalizer;
use App\Http\Controllers\BaseController;
use App\Services\Faceit\DataQueries\PlayerStatsQuery;
use App\Services\Faceit\FaceitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaceitController extends BaseController
{

    public function getPlayerStats(Request $request, FaceitService $faceit): JsonResponse
    {
        $nickname = $request->query('nickname');

        if (!$nickname) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing nickname parameter',
            ], 422);
        }

        try {
            $query = new PlayerStatsQuery($faceit, $nickname);
            $stats = $query->runQuery();

            $normalizedStats = JsonKeyNormalizer::normalizeKeys($stats);

            return response()->json([
                'status' => 'success',
                'data' => $normalizedStats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

}
