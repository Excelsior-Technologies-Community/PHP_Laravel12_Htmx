<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'priority',
        'completed',
        'due_date',
        'reminder_date',
        'notes'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'due_date' => 'date',
        'reminder_date' => 'date',
    ];

    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    public function scopePending($query)
    {
        return $query->where('completed', false);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today());
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', today())->where('completed', false);
    }

    public function getPriorityColorAttribute()
    {
        return [
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'red'
        ][$this->priority] ?? 'gray';
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->completed) {
            return 'bg-green-100 text-green-700';
        }
        
        if ($this->due_date && $this->due_date < today()) {
            return 'bg-red-100 text-red-700';
        }
        
        return 'bg-yellow-100 text-yellow-700';
    }

    public function getStatusTextAttribute()
    {
        if ($this->completed) {
            return 'Completed';
        }
        
        if ($this->due_date && $this->due_date < today()) {
            return 'Overdue';
        }
        
        return 'Pending';
    }
}