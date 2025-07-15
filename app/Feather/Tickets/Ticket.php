<?php

namespace App\Feather\Tickets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Ticket extends Model
{
    use SoftDeletes;

    protected $table = 'feather_tickets';

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'department',
        'user_id',
        'assigned_to',
        'closed_at',
        'closed_by',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    /**
     * Ticket statuses.
     */
    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_WAITING_CUSTOMER = 'waiting_customer';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';

    /**
     * Ticket priorities.
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Get the user who created the ticket.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user assigned to the ticket.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who closed the ticket.
     */
    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    /**
     * Get all replies for the ticket.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at', 'asc');
    }

    /**
     * Check if ticket is open.
     */
    public function isOpen(): bool
    {
        return in_array($this->status, [
            self::STATUS_OPEN,
            self::STATUS_IN_PROGRESS,
            self::STATUS_WAITING_CUSTOMER,
        ]);
    }

    /**
     * Check if ticket is closed.
     */
    public function isClosed(): bool
    {
        return in_array($this->status, [
            self::STATUS_RESOLVED,
            self::STATUS_CLOSED,
        ]);
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = config('feather.tickets.statuses');
        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get priority label.
     */
    public function getPriorityLabelAttribute(): string
    {
        $priorities = config('feather.tickets.priorities');
        return $priorities[$this->priority] ?? ucfirst($this->priority);
    }

    /**
     * Get department label.
     */
    public function getDepartmentLabelAttribute(): string
    {
        $departments = config('feather.tickets.departments');
        return $departments[$this->department] ?? ucfirst($this->department);
    }

    /**
     * Get priority color class.
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_LOW => 'text-blue-600',
            self::PRIORITY_NORMAL => 'text-green-600',
            self::PRIORITY_HIGH => 'text-orange-600',
            self::PRIORITY_URGENT => 'text-red-600',
            default => 'text-gray-600',
        };
    }

    /**
     * Get status color class.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_OPEN => 'text-blue-600',
            self::STATUS_IN_PROGRESS => 'text-yellow-600',
            self::STATUS_WAITING_CUSTOMER => 'text-orange-600',
            self::STATUS_RESOLVED => 'text-green-600',
            self::STATUS_CLOSED => 'text-gray-600',
            default => 'text-gray-600',
        };
    }

    /**
     * Scope for open tickets.
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', [
            self::STATUS_OPEN,
            self::STATUS_IN_PROGRESS,
            self::STATUS_WAITING_CUSTOMER,
        ]);
    }

    /**
     * Scope for closed tickets.
     */
    public function scopeClosed($query)
    {
        return $query->whereIn('status', [
            self::STATUS_RESOLVED,
            self::STATUS_CLOSED,
        ]);
    }

    /**
     * Scope for tickets assigned to a user.
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope for tickets by department.
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope for tickets by priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}
