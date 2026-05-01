<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
  protected $primaryKey = 'group_user_id';

    protected $fillable = [
        'group_user_name',
        'is_super_user'
    ];
     public function users()
    {
        return $this->hasMany(User::class,'group_user_id','group_user_id');
    }
}
