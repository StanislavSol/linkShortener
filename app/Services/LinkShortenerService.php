<?php

namespace App\Services;

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Str;

class LinkShortenerService
{
    public function create(array $data, User $user): Link
    {
        $shortCode = $this->generateUniqueCode();

        return $user->links()->create([
            'original_url' => $data['original_url'],
            'short_code' => $shortCode,
        ]);
    }

    public function delete(Link $link, User $user): void
    {
        if ($link->user_id !== $user->id) {
            abort(403);
        }

        $link->delete();
    }

    protected function generateUniqueCode(int $length = 6): string
    {
        do {
            $code = Str::random($length);
        } while (Link::where('short_code', $code)->exists());

        return $code;
    }
}
