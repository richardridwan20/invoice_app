<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationshipToInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_details', function (Blueprint $table) {
            
            // Menghubungkan antara tabel Invoices dengan tabel Invoices Details
            $table->foreign('invoice_id')
            ->references('id')->on('invoices')
            ->onDelete('cascade');
            
            // Menghubungkan antara tabel Products dengan tabel Invoices Details
            $table->foreign('product_id')
            ->references('id')->on('products')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_details', function (Blueprint $table) {
            
            $table->dropForeign('invoice_details_invoice_id_foreign');
            $table->droForeign('invoice_details_product_id_foreign');
        
        });
    }
}
