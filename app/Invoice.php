<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = [];

    // Define accessornya
    public function getTaxAttribute(){
        // Mendapatkan pajak atau tax dari total dikali 2 persen.
        return ($this->total *2) /100;
    }

    public function getTotalPriceAttribute(){
        // Mendapatkan harga total setelah ditambah pajak
        return($this->total+(($this->total *2) /100));
    }

    public function customer(){
        // Untuk invoice reference ke table customer
        return($this->belongsTo(Customer::class));
    }

    public function detail(){
        // Untuk invoice reference ke table detail
        return($this->hasMany(Invoice_detail::class));
    }
}
