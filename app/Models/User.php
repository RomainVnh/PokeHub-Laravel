<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'avatar', 'active_title', 'active_frame', 'active_sleeve', 'poketokens', 'level', 'xp', 'last_premium_booster_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_premium_booster_at' => 'datetime',
        ];
    }

    /**
     * XP required to reach a given level.
     * Formula: 100 * level^1.5 (exponential growth)
     */
    public static function xpForLevel(int $level): int
    {
        return (int) ceil(100 * pow($level, 1.5));
    }

    /**
     * XP needed to go from current level to next level.
     */
    public function xpToNextLevel(): int
    {
        return self::xpForLevel($this->level);
    }

    /**
     * Current XP progress as a percentage (0-100).
     */
    public function xpProgress(): float
    {
        $needed = $this->xpToNextLevel();
        return $needed > 0 ? min(100, ($this->xp / $needed) * 100) : 0;
    }

    /**
     * Award XP and handle level-ups. Returns number of levels gained.
     */
    public function awardXp(int $amount): int
    {
        $this->xp += $amount;
        $levelsGained = 0;

        while ($this->xp >= $this->xpToNextLevel()) {
            $this->xp -= $this->xpToNextLevel();
            $this->level++;
            $levelsGained++;
        }

        $this->save();

        return $levelsGained;
    }

    /**
     * Calculate XP from drawn cards based on rarity.
     */
    public static function calculateBoosterXp(array $drawnCards): int
    {
        $xpMap = [
            'common'       => 5,
            'uncommon'     => 8,
            'rare'         => 15,
            'ultra'        => 40,
            'illustration' => 80,
            'secret'       => 150,
        ];

        $totalXp = 0;
        foreach ($drawnCards as $card) {
            $rarity = strtolower($card['rarity'] ?? '');

            if (str_contains($rarity, 'secret') || str_contains($rarity, 'rainbow') || str_contains($rarity, 'hyper') || (str_contains($rarity, 'shiny') && str_contains($rarity, 'ultra'))) {
                $totalXp += $xpMap['secret'];
            } elseif (str_contains($rarity, 'illustration') || str_contains($rarity, 'amazing')) {
                $totalXp += $xpMap['illustration'];
            } elseif (str_contains($rarity, 'ultra') || str_contains($rarity, 'double') || str_contains($rarity, 'ace') || str_contains($rarity, ' ex') || str_contains($rarity, ' gx') || str_contains($rarity, 'vmax') || str_contains($rarity, 'vstar')) {
                $totalXp += $xpMap['ultra'];
            } elseif (str_contains($rarity, 'rare') || str_contains($rarity, 'holo')) {
                $totalXp += $xpMap['rare'];
            } elseif (str_contains($rarity, 'uncommon')) {
                $totalXp += $xpMap['uncommon'];
            } else {
                $totalXp += $xpMap['common'];
            }
        }

        return $totalXp;
    }
}
