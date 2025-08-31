<?php

namespace App\Services\Faceit\DataQueries;

use App\Services\Faceit\FaceitService;
use Exception;
use Illuminate\Http\Client\ConnectionException;

class PlayerEloStatsQuery extends BaseDataQuery
{
    protected string $playerId;
    protected string $gameId;
    protected int $from;
    protected int $to;
    protected int $limit;
    protected int $offset;

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    public function __construct(
        FaceitService $faceitService,
        string $playerIdOrNickname,
        string $gameId,
        int $from = 0,
        int $to = 0,
        int $limit = 20,
        int $offset = 0
    ) {
        parent::__construct($faceitService);

        $this->gameId = $gameId;
        $this->from = $from ?: strtotime('-1 month'); // default 1 hónap
        $this->to = $to ?: time();
        $this->limit = $limit;
        $this->offset = $offset;

        if (preg_match('/^[0-9a-fA-F-]{36}$/', $playerIdOrNickname)) {
            $this->playerId = $playerIdOrNickname;
        } else {
            $playerData = $this->faceitService->get("players", ['nickname' => $playerIdOrNickname]);
            $this->playerId = $playerData['player_id'] ?? throw new Exception("Player not found: {$playerIdOrNickname}");
        }
    }

    /**
     * @throws ConnectionException
     */
    public function runQuery(): array
    {
        return $this->faceitService->get(
            "players/{$this->playerId}/games/{$this->gameId}/stats",
            [
                'from' => $this->from,
                'to' => $this->to,
                'limit' => $this->limit,
                'offset' => $this->offset,
            ]
        );
    }

}