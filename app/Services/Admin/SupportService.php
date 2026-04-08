<?php

namespace App\Services\Admin;

use App\Models\SupportTicket;
use Illuminate\Database\Eloquent\Builder;

class SupportService
{
    public function query(): Builder
    {
        return SupportTicket::query()
            ->with('user')
            ->orderByDesc('id');
    }
}
