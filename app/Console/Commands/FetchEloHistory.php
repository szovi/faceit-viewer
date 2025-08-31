<?php

namespace App\Console\Commands;

use App\Helpers\JsonKeyNormalizer;
use App\Models\EloHistory;
use App\Models\User;
use App\Services\Faceit\DataQueries\PlayerEloStatsQuery;
use App\Services\Faceit\FaceitService;
use Exception;
use Illuminate\Console\Command;

class FetchEloHistory extends Command
{
    protected $signature = 'faceit:fetch-elo {game? : The game to fetch ELO for}';
    protected $description = 'Fetch ELO history from Faceit and store in DB';

    public function __construct(FaceitService $faceit)
    {
        parent::__construct();
        $this->faceit = $faceit;
    }

    public function handle()
    {
        $game = $this->argument('game') ?? config('faceit.default_game', 'csgo');
        $this->info("Fetching Faceit ELO for game: {$game}");

        $users = User::whereNotNull('faceit_id')->get();
        if (!$users || $users->isEmpty()) {
            $this->error('No user found with any registered faceit ID.');
            return Command::FAILURE;
        }

        foreach ($users as $user) {
            try {
                $this->info("Fetching ELO for {$user->faceit_id} ({$user->name})");
                $query = new PlayerEloStatsQuery($this->faceit, $user->faceit_id, $game);
                $eloStats = $query->runQuery();

                $normalized = JsonKeyNormalizer::normalizeKeys($eloStats);
                $currentElo = $normalized['segments'][0]['current_elo'] ?? null;
                dd($normalized);
                if (!$currentElo) {
                    $this->warn('No elo points readble from json data');
                    continue;
                }

                $lastRecord = EloHistory::where('player_id', $user->id)
                    ->where('game_id', $game)
                    ->orderByDesc('recorded_at')
                    ->first();

                if (!$lastRecord || $lastRecord->elo !== $currentElo) {
                    $eloHistory = EloHistory::create([
                        'player_id' => $user->id,
                        'nickname' => $user->name,
                        'game_id' => $game,
                        'elo' => $currentElo,
                        'recorded_at' => now()->format('Y-m-d H:i:s'),
                    ]);

                    $eloHistory->save();

                    $this->info("✅ Saved new ELO for {$user->name}");
                } else {
                    $this->info("ℹ️ ELO unchanged for {$user->name}, skipping...");
                }

                $this->info("✅ Saved ELO for {$user->name}");
            } catch (Exception $exception) {
                $this->error("Error for {$user->name}: {$exception->getMessage()}");
            }
        }

        $this->info('🎉 Finished fetching ELO for all users.');

        return self::SUCCESS;
    }
}
