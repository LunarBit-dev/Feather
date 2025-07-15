<?php

namespace App\Feather\Tickets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class TicketReply extends Model
{
    protected $table = 'feather_ticket_replies';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'is_internal',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    /**
     * Get the ticket this reply belongs to.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the user who wrote this reply.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this is an internal note.
     */
    public function isInternal(): bool
    {
        return $this->is_internal;
    }

    /**
     * Check if this reply is from staff.
     */
    public function isFromStaff(): bool
    {
        return $this->user->hasPermission('ticket.reply') ?? false;
    }
}
