<?php

namespace App\Policies;

use App\Models\LinkVisit;
use App\Models\User;

class LinkVisitPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, LinkVisit $linkVisit): bool
    {
        return $user->id === $linkVisit->link->user_id;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, LinkVisit $linkVisit): bool
    {
        return false;
    }

    public function delete(User $user, LinkVisit $linkVisit): bool
    {
        return false;
    }
}
