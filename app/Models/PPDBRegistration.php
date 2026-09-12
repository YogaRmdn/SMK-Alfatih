<?php

namespace App\Models;

use App\Enums\RegistrationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PPDBRegistration extends Model
{
    use HasFactory;

    protected $table = 'ppdb_registrations';

    protected $fillable = [
        'registration_number',
        'name',
        'nisn',
        'birth_place',
        'birth_date',
        'gender',
        'address',
        'school_origin',
        'phone',
        'email',
        'parent_name',
        'program_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'status' => RegistrationStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PPDBRegistration $registration): void {
            if (is_null($registration->registration_number)) {
                $registration->registration_number = static::generateRegistrationNumber();
            }
        });
    }

    public static function generateRegistrationNumber(): string
    {
        $next = (static::max('id') ?? 0) + 1;

        return sprintf('PPDB-%s-%05d', now()->year, $next);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
