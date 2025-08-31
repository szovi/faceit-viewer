<?php

namespace App\Services\Faceit\DataQueries;

use App\Services\Faceit\FaceitService;
use Exception;
use Illuminate\Http\Client\ConnectionException;

class PlayerStatsQuery extends BaseDataQuery
{

    protected string $playerId;
    protected string $game;

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    public function __construct(
        FaceitService   $faceitService,
        string          $playerIdOrNickname,
        string          $game = null)
    {
        parent::__construct($faceitService);
        $this->game = $game ?? config('faceit.default_game');

        if (preg_match('/^[0-9a-fA-F-]{36}$/', $playerIdOrNickname)) {
            $this->playerId = $playerIdOrNickname;
        } else {
            $playerData = $this->faceitService->get("players", ['nickname' => $playerIdOrNickname]);
            $this->playerId = $playerData['player_id'] ?? null;
            if (!$this->playerId) {
                throw new Exception("Player not found: {$playerIdOrNickname}");
            }
        }
    }

    /**
     * @throws ConnectionException
     */
    public function runQuery(): array
    {
        $endpoint = "players/{$this->playerId}/stats/{$this->game}";

        return $this->faceitService->get($endpoint);
    }

}
