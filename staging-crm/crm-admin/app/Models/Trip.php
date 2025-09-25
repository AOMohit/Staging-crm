<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'added_by', 'trip_type', 'name', 'start_date', 'end_date', 'price', 'duration_nights', 'continent', 'landscape', 'style', 'activity', 'overview', 'image', 'status', 'created_at', 'updated_at'];

    public function admin(){
        return $this->belongsTo(User::class, 'added_by', 'id');
    }

   public function getRelationManagerNamesAttribute()
    {
    if (!$this->relation_manager_id) return "N/A";
    $ids = json_decode($this->relation_manager_id, true);
    $ids = is_array($ids) ? $ids : [$ids];
    $names = [];
    foreach ($ids as $id) {
        $userId = is_array($id) && isset($id['id']) ? $id['id'] : $id;
        $user = \App\Models\User::find($userId);
        if ($user) $names[] = $user->name;
    }
    return $names ? implode(', ', $names) : "N/A";
    }


}