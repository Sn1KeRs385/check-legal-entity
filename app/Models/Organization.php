<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrganizationType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int              $id
 * @property int              $physical_person_id
 * @property OrganizationType $type
 * @property int              $inn
 * @property string           $name
 * @property Carbon|null      $created_at
 * @property Carbon|null      $updated_at
 * @property PhysicalPerson   $physicalPerson
 */
class Organization extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'inn',
        'name',
    ];

    /**
     * @var array<string, class-string>
     */
    protected $casts = [
        'type' => OrganizationType::class,
    ];

    /**
     * @return BelongsTo<PhysicalPerson, Organization>
     */
    public function physicalPerson(): BelongsTo
    {
        return $this->belongsTo(PhysicalPerson::class);
    }
}
