<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $fillable = ['heading_1', 'heading_2', 'category', 'brand', 'name', 'article', 'description', 'price', 'guarantee', 'accessibility'];
    //
}
