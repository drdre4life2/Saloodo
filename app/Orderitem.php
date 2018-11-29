<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Orderitem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'product_id'
    ];
    
    public function Order()
    {
        return $this->belongsTo('App\Order');
    }
}