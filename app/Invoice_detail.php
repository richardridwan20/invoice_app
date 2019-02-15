<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice_detail extends Model
{
    protected $guarded = [];

    // Define accessornya
    public function getSubtotalAttribute(){
        // Mendapatkan total dari barang belanjaan.
        return number_format($this->qty * $this->price);
    }

    public function product(){
        // Untuk invoice detail reference ke table prodoct
        return($this->belongsTo(Product::class));
    }

    public function invoice(){
        // Untuk invoice detail reference ke table invoice
        return($this->belongsTo(Invoice::class));
    }
}
