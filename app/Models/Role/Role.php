<?php

namespace App\Models\Role;

use Illuminate\Database\Eloquent\Model;

/**
 * Role
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @author jcshow
 * @package App\Models\Role
 */
class Role extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name'
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'name' => 'string',
    ];
}
