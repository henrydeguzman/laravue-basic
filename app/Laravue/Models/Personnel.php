<?php
namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Personnel extends Model {
    protected $fillable = [
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
