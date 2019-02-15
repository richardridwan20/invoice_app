<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{
    public function destroy($id){
        $product = Product::find($id); //Query ke database dengan id yang diterima parameter.
        $product->delete();//Menghapus data yang ada di database
        return redirect('/product')->with(['success' => '<strong>'. $product->title . '</strong> Dihapus.']);
    }

    public function edit($id){
        $product = Product::find($id); //Query ke database dengan id yang diterima parameter.
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id){
       
        //Buat ngevalidasi formnya nanti
        $this->validate($request, [
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer'
        ]);
    
        //Buat ngeinsert ke database
        try{
            $product = Product::find($id);
            $product->update([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock
            ]);

            //Redirect ke halaman awal dengan message sukses
            return redirect('/product')->with(['success' => '<strong>'. $product->title . '</strong> Diperbaharui.']);
        }catch(\Exception $e){
            //Apabila ada error maka dia akan redirect ke form create product dan menampilkan error message
            return redirect('/product/new')->with(['error' => $e->getMessage()]);
        }
       
    }

    public function save(Request $request){

        //Buat ngevalidasi formnya nanti
        $this->validate($request, [
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer'
        ]);

        //Buat ngeinsert ke database
        try{
            $product = Product::create([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock
            ]);

            //Redirect ke halaman awal dengan message sukses
            return redirect('/product')->with(['success' => '<strong>'. $product->title . '</strong> Telah disimpan.']);
        }catch(\Exception $e){
            //Apabila ada error maka dia akan redirect ke form create product dan menampilkan error message
            return redirect('/product/new')->with(['error' => $e->getMessage()]);
        }
    }


    public function index(){
        // Query ke database biar produknya diurutkan berdasarkan created at secara DESCENDING.
        // Kurang lebih code diatas digunakkan untuk "SELECT * FROM Products ORDER BY 'created_at' DESC"
        $products = Product::orderBy('created_at', 'DESC')->get();
        return view('products.index', compact('products'));
    }

    public function create(){
        return view('products.create');
    }
}
