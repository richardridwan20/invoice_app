<?php

namespace App\Observers;

use App\Invoice_detail;
use App\Invoice;

class Invoice_detailObserver
{
    public function generateTotal($invoiceDetail){
        // Mengambil invoice id
        $invoice_id = $invoiceDetail->invoice_id;

        // Select dari table invoice detail berdasarkan invoice id.
        $invoice_detail = Invoice_detail::where('invoice_id', $invoice_id)->get();

        // Cari jumlah total dari harga produk
        $total = $invoice_detail->sum(function($i){
            // Perkalian harga produk dan jumlah
            return $i->price * $i->qty;
        });

        // Mengupdate field total pada invoice
        $invoiceDetail->invoice->update([
            'total' => $total
        ]);


    }
    /**
     * Handle the invoice_detail "created" event.
     *
     * @param  \App\Invoice_detail  $invoiceDetail
     * @return void
     */
    public function created(Invoice_detail $invoiceDetail)
    {   
        //Menjalankan method yang diatas.
        $this->generateTotal($invoiceDetail);
    }

    /**
     * Handle the invoice_detail "updated" event.
     *
     * @param  \App\Invoice_detail  $invoiceDetail
     * @return void
     */
    public function updated(Invoice_detail $invoiceDetail)
    {
        //Menjalankan method yang diatas.
        $this->generateTotal($invoiceDetail);
    }

    /**
     * Handle the invoice_detail "deleted" event.
     *
     * @param  \App\Invoice_detail  $invoiceDetail
     * @return void
     */
    public function deleted(Invoice_detail $invoiceDetail)
    {
        //Menjalankan method yang diatas.
        $this->generateTotal($invoiceDetail);
    }

    /**
     * Handle the invoice_detail "restored" event.
     *
     * @param  \App\Invoice_detail  $invoiceDetail
     * @return void
     */
    public function restored(Invoice_detail $invoiceDetail)
    {
        //
    }

    /**
     * Handle the invoice_detail "force deleted" event.
     *
     * @param  \App\Invoice_detail  $invoiceDetail
     * @return void
     */
    public function forceDeleted(Invoice_detail $invoiceDetail)
    {
        //
    }
}
