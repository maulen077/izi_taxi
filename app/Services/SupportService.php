<?php

namespace App\Services;

use App\Enums\SupportSubject;
use App\Models\SupportTicket;
use App\Models\User;

class SupportService
{
    public function __construct(
        private readonly MobileDataService $mobileDataService,
    ) {
    }

    public function create(User $user, array $data): array
    {
        $ticket = SupportTicket::create([
            'user_id' => $user->id,
            'subject' => SupportSubject::tryFrom($data['subject'] ?? '') ?? SupportSubject::Other,
            'message' => $data['message'],
            'status' => 'open',
            'contact_phone' => $data['contact_phone'] ?? $user->phone,
            'contact_email' => $data['contact_email'] ?? $user->email,
        ]);

        return [
            'ticket' => $this->mobileDataService->serializeSupportTicket($ticket),
            'message' => 'Request submitted successfully.',
        ];
    }

    public function index(User $user): array
    {
        $tickets = SupportTicket::query()
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return [
            'tickets' => $tickets->map(fn (SupportTicket $ticket) => $this->mobileDataService->serializeSupportTicket($ticket))->values(),
        ];
    }
}
