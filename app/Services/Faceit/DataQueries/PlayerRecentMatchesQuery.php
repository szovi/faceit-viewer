<?php

namespace App\Services\Faceit\DataQueries;

use App\Services\Faceit\FaceitService;
use Exception;
use Illuminate\Http\Client\ConnectionException;

class PlayerRecentMatchesQuery extends BaseDataQuery
{
    protected string $playerId;
    protected string $gameId;
    protected int $offset;
    protected int $limit;

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    public function __construct(
        FaceitService $faceitService,
        string $playerIdOrNickname,
        string $gameId,
        int $offset = 0,
        int $limit = 20
    ) {
        parent::__construct($faceitService);

        $this->gameId = $gameId;
        $this->offset = $offset;
        $this->limit = $limit;

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
        $endpoint = "players/{$this->playerId}/games/{$this->gameId}/stats";

        return $this->faceitService->get($endpoint, [
            'offset' => $this->offset,
            'limit'  => $this->limit,
        ]);
    }

}