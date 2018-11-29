<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Bundle extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bundle_name', 'bundle_price','product1', 'product2', 'product3'
    ];
}