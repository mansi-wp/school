<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ParentModel: Represents Parent profile/guard ("Parent" is a reserved keyword in PHP)
 */
class ParentModel extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'user_type',
        'is_active',
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'parent_phone', 'phone');
    }
}
