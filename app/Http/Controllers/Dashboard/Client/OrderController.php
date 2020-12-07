<?php

namespace App\Http\Controllers\Dashboard\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Oreder;
use App\Models\Product;
use Illuminate\Http\Request;
use Prophecy\Doubler\Generator\Node\ReturnTypeNode;

class OrderController extends Controller
{

    public function create(Client $client){

        $categories = Category::with('product')->get();
        $orders = $client->orders()->with('products')->paginate(5);
        return view('Dashboard.clients.orders.create',compact('categories','client','orders'));

    }/* end of create */



    public  function store(Request $request, Client $client){

        $request->validate([
            'products' => 'required|array',
        ]);

        $this->attach_order($request, $client);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.orders.index');

    }/* end of store */




    public function edit(Client $client , Oreder $order){
        $categories = Category::with('product')->get();
        return view('Dashboard.clients.orders.edit',compact('client','order','categories'));

    }/* end of edit */



    public function update(Request $request , Client $client , Oreder $order){

        $request->validate([
            'products' => 'required|array',
        ]);

        $this->dettach_order($order);

        $this->attach_order($request,$client);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.orders.index');

    }/* end of update */


    private function attach_order($request, $client){

        $order = $client->orders()->create([]);
        $order->products()->attach($request->products);
        $total_price = 0 ;

        foreach($request->products as $id=>$item)
        {

            $product = Product::findOrFail($id);
            $total_price += $product->sale_price  * $item['quantity'];

            $product->update([
                'stock' => $product->stock - $item['quantity'],
            ]);

        }/* end of foreach */

        $order->update([
            'total_price' =>  $total_price,
        ]);

    }/* end of attach_order function  */

    private function dettach_order($order){
        $products = $order->products;
        foreach ($products as $product) {

            $product->update([
                'stock' => $product->stock + $product->pivot->quantity
            ]);

        }//end of for each

        $order->delete();
    }

}/* end of controller */
