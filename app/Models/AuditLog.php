<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'description',
        'before_values',
        'after_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'before_values' => 'array',
        'after_values' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function record(
        string $action,
        ?Model $auditable = null,
        array $before = [],
        array $after = [],
        ?string $description = null,
        ?Request $request = null,
    ): ?self {
        if (! Schema::hasTable('audit_logs')) {
            return null;
        }

        $request ??= request();

        return static::query()->create([
            'user_id' => $request->user()?->id,
            'action' => $action,
            'auditable_type' => $auditable?->getMorphClass(),
            'auditable_id' => $auditable?->getKey(),
            'description' => $description,
            'before_values' => $before ?: null,
            'after_values' => $after ?: null,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
        ]);
    }
}
