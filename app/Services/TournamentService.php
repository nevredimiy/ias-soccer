<?php

namespace App\Services;

use App\Models\Team;
use Illuminate\Support\Collection;

class TournamentService
{
    private static array $romanTemplate = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV'];

    public static function getTeamsByEvent(int $eventId): Collection
    {
        return Team::where('event_id', $eventId)->with(['color', 'players', 'event'])->get();
    }

    public static function extractPlayers(Collection $teams): array
    {
        $players = [];

        foreach ($teams as $team) {
            foreach ($team->players as $player) {
                $players[] = [
                    'id' => $player->id,
                    'number' => $player->number,
                    'name' => $player->full_name,
                    'photo' => $player->photo,
                    'rating' => $player->rating,
                    'team' => $team->name,
                    'color' => $team->color->name ?? '–',
                ];
            }
        }

        return $players;
    }

    public static function getRomanRounds(Collection $teams): array
    {
        $teams->loadMissing('event.tournament');
        $countRounds = optional($teams->first()?->event?->tournament)->count_rounds ?? 0;

        return $countRounds < 2
            ? []
            : array_slice(self::$romanTemplate, 0, $countRounds);
    }
}

