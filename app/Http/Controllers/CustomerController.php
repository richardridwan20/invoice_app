<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;

class CustomerController extends Controller
{
    public function index(){
        $customers = Customer::orderBy('created_at', 'DESC')->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create(){
        return view('customers.add');
    }

    public function edit($id){
        $customer = Customer::find($id);
        return view('customers.edit', compact('customer'));
    }

    public function destroy($id){
        $customer = Customer::find($id); //Query ke database dengan id yang diterima parameter.
        $customer->delete();//Menghapus data yang ada di database
        return redirect('/customer')->with(['success' => '<strong>'. $customer->name . '</strong> Dihapus.']);
    }

    public function update(Request $request, $id){

        $this->validate($request, [
            'name' => 'required|string',
            'phone' => 'required|string|max:13',
            'address' => 'required|string',
            'email' => 'required|email|string|exists:customers,email'
        ]);

        try{
            $customer = Customer::find($id);
            $customer ->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
             //Redirect ke halaman awal dengan message sukses
             return redirect('/customer')->with(['success' => '<strong>'. $customer->name . '</strong> Telah diperbaharui.']);
        }catch(\Exception $e){
            return redirect('/customer/new')->with(['error' => $e->getMessage()]);
        }
    }

    public function save(Request $request){

        $this->validate($request, [
            'name' => 'required|string',
            'phone' => 'required|string|max:13',
            'address' => 'required|string',
            'email' => 'required|email|string|unique:customers,email'
        ]);

        try{
            $customer = Customer::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'email' => $request->email,
            ]);
             //Redirect ke halaman awal dengan message sukses
             return redirect('/customer')->with(['success' => '<strong>'. $customer->name . '</strong> Telah disimpan.']);
        }catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }

    }
}
