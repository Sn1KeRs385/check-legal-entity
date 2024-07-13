<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                           $id
 * @property int                           $inn
 * @property string                        $first_name
 * @property string                        $second_name
 * @property string                        $last_name
 * @property Carbon|null                   $created_at
 * @property Carbon|null                   $updated_at
 *
 * @property Collection<int, Organization> $organizations
 */
class PhysicalPerson extends Model
{
    // Лара кастит название класса в таблицу physical_people
    protected $table = 'physical_persons';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'inn',
        'first_name',
        'second_name',
        'last_name',
    ];

    /**
     * @return HasMany<Organization>
     */
    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class);
    }
}
