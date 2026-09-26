<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PcSessionLog extends Model
{
    protected $fillable = [
        "cbt_session_id", "computer_id", "allocated_at", "released_at", "duration_minutes",
    ];

    protected $casts = [
        "allocated_at" => "datetime",
        "released_at"  => "datetime",
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(CbtSession::class, "cbt_session_id");
    }

    public function computer(): BelongsTo
    {
        return $this->belongsTo(Computer::class);
    }
}