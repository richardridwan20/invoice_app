<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Invoice;
use App\Product;
use App\Invoice_detail;
use PDF;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoice  = Invoice::with(['customer', 'detail'])->orderBy('created_at', 'DESC')->paginate(10);
        return view('invoices.index', compact('invoice'));
    }

    public function create(){
        $customers = Customer::orderBy('created_at', 'DESC')->get();
        return view('invoices.create', compact('customers'));
    }

    public function save(Request $request){
        
        // Validasi terlebih dulu IDnya
        $this->validate($request, [
            'customer_id' => 'required|exists:customers,id'
        ]);

        try{
            // Menyimpan data customer ke database tabel invoice
            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'total' => 0
            ]);

            // Redirect route ke invoice.edit dengan melempar parameter ID
            return redirect(route('invoice.edit', ['id' => $invoice->id]));
        }catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }

    }

    public function edit($id){

        $invoice = Invoice::with(['customer', 'detail', 'detail.product'])->find($id);
        $products = Product::orderBy('title', 'ASC')->get();
        return view('invoices.edit', compact('invoice', 'products'));

    }

    public function update(Request $request, $id){
        
        //Validasi
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer'
        ]);

        try {
            // Select data invoice berdasarkan ID
            $invoice = Invoice::find($id);
            // Select data product berdasarkan ID
            $product = Product::find($request->product_id);
            // Select data invoice detail yang berdasarkan product_id dan invoice_id
            $invoice_detail = $invoice->detail()->where('product_id', $product->id)->first();

            // Jika datanya ada
            if ($invoice_detail) {
                // Kalo ternyata ada datanya, maka qty bakal diupdate.
                $invoice_detail->update([
                    'qty' => $invoice_detail->qty + $request->qty
                ]);
            } else {
                // Kalo gaada datanya, kita bikin baru (create)
                Invoice_detail::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $request->product_id,
                    'price' => $product->price,
                    'qty' => $request->qty
                ]);
            }
            return redirect()->back()->with(['success' => 'Product telah ditambahkan.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function deleteProduct($id){
        // Select table details berdasarkan invoice id
        $detail = Invoice_detail::find($id);
        // Hapus
        $detail->delete();
        // Return lagi
        return redirect()->back()->with(['success' => 'Product telah dihapus.']);
    }

    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        $invoice->delete();
        return redirect()->back()->with(['success' => 'Data telah dihapus']);
    }


    public function generateInvoice($id){
        // Get data berdasarkan ID
        $invoice = Invoice::with(['customer', 'detail', 'detail.product'])->find($id);
        
        // Load PDF yang berujuk ke view nya print.blade.php dengan mengirimkan data dari invoice
        // Kemudian menggunakan pengaturan landscape A4
        $pdf = PDF::loadView('invoices.print', compact('invoice'))->setPaper('a4', 'landscape');
        return $pdf->stream();
    }
}