<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EloHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'elo_history';

    protected $fillable = [
        'elo',
        'recorded_at',
        'player_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];



}