<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Oreder;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index(Request $request){

        // $orders = Oreder::paginate(5);
        $orders = Oreder::WhereHas('client', function($q) use ($request){

            return $q->where('name','like','%'.$request->search.'%');

        })->paginate(5);

        return view('Dashboard.orders.index',compact('orders'));
    }/* end of index */


    public function products(Oreder $order){

        $products = $order->products;
        // dd($products);
        return view('Dashboard.orders._products',compact('order','products'));

    }/* end of products */

    public function destroy(Oreder $order)
    {
        $products = $order->products;
        foreach ($products as $product) {

            $product->update([
                'stock' => $product->stock + $product->pivot->quantity
            ]);

        }//end of for each

        $order->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.orders.index');

    }//end of order


}/* end of class */
