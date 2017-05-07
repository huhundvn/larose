<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
	protected $table = 'order_detail';
	
	public function product()
    {
        return $this->belongsTo('App\Product', 'id', 'product_id');
    }

	public function unit()
	{
		return $this->belongsTo('App\Unit');
	}
}
