<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = ['title', 'desc', 'user_id'];

    protected $primaryKey = 'id';

    protected $table = 'posts';
    use HasFactory;
}
