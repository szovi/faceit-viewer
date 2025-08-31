<?php

namespace App\Http\Controllers\Faceit;

use App\Helpers\JsonKeyNormalizer;
use App\Http\Controllers\BaseController;
use App\Services\Faceit\DataQueries\PlayerEloStatsQuery;
use App\Services\Faceit\DataQueries\PlayerRecentMatchesQuery;
use App\Services\Faceit\DataQueries\PlayerStatsQuery;
use App\Services\Faceit\FaceitService;
use Exception;
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
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getPlayerRecentMatches(Request $request, FaceitService $faceit): JsonResponse
    {
        $nickname = $request->query('nickname');
        $gameId = $request->query('gameId');
        $offset = (int) $request->query('offset', 0);
        $limit = (int) $request->query('limit', 10);

        if (!$nickname) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing nickname parameter',
            ], 422);
        }

        try {
            $query = new PlayerRecentMatchesQuery($faceit, $nickname, $gameId, $offset, $limit);
            $matches = $query->runQuery();

            $normalizedMatches = JsonKeyNormalizer::normalizeKeys($matches);

            return response()->json([
                'status' => 'success',
                'data' => $normalizedMatches,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getPlayerEloTrend(Request $request, FaceitService $faceit): JsonResponse
    {
        $nickname = $request->query('nickname');
        $game = $request->query('game', config('faceit.default_game', 'cs2'));

        if (!$nickname) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing nickname parameter',
            ], 422);
        }

        try {

            $query = new PlayerEloStatsQuery($faceit, $nickname, $game);
            $stats = $query->runQuery();

            $normalized = JsonKeyNormalizer::normalizeKeys($stats);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'elo_snapshot' => [
                        'current_elo' => $normalized['segments'][0]['current_elo'] ?? null,
                        'average_elo' => $normalized['segments'][0]['average_elo'] ?? null,
                        'timestamp' => now()->format('Y-m-d H:i:s'),
                    ]
                ],
            ]);

        } catch (Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 400);
        }
    }


}
