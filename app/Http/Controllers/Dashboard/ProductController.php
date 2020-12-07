<?php

namespace App\Http\Controllers\Dashboard;

use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::all();
        $products = Product::when($request->search , function($q) use ($request){
                return $q->whereTranslationLike('name','%'.$request->search.'%');
            })->when($request->category_id , function($q) use ($request){
                return $q->where('category_id', $request->category_id);
            })->latest()->paginate(2);
        // $products = Product::paginate(2);

        return view('Dashboard.products.index',compact('products','categories'));
    }/* end of index */


    public function create()
    {
        $categories = Category::all();
        return view('Dashboard.products.create',compact('categories'));
    }/* end of create */


    public function store(Request $request)
    {
        $rules = ['category_id'=>'required'];
        foreach( config('translatable.locales') as $locale){
            $rules += [$locale.'.name'=> 'required|unique:product_translations,name'];
            $rules += [$locale.'.description'=>'required'];
        }
        $rules += ['purchase_price' => 'required'];
        $rules += ['sale_price' => 'required'];
        $rules += ['stok' => 'required'];
        $request->validate($rules);

        $request_data = $request->except(['image']);

        if($request->image){
            Image::make($request->image)->resize(null, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(public_path('uploads/image_product/'.$request->image->hashName()));

            $request_data['image'] = $request->image->hashName();
        }

        // dd($request_data);

        Product::create($request_data);
        session()->flash('success', __('site.added_successfully'));
        return redirect(route('dashboard.products.index'));

    }/* end of store */


    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('Dashboard.products.edit', compact('product','categories'));
    }/* end of edit */


    public function update(Request $request, Product $product)
    {
        $rules = ['category_id' => 'required'];
        foreach(config('translatable.locales') as $locale){
            $rules += [$locale.'.name'=>'required'];
            $rules += [$locale. '.description' => 'required'];
        }
        $rules += ['purchase_price' => 'required'];
        $rules += ['sale_price' => 'required'];
        $rules += ['stok' => 'required'];
        $request->validate($rules);

        $request_data = $request->except('image');

       if($request->image){
            if($request->image != 'default.png'){
                Storage::disk('public_uploads')->delete('/image_product/' . $product->image);
            }
            Image::make($request->image)->resize(null, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(public_path('uploads/image_product/'.$request->image->hashName()));

            $request_data['image'] = $request->image->hashName();
        }/* end of if main  for image */

        $product->update($request_data);
        session()->flash('sucess','updated_successfully');
        return redirect(route('dashboard.products.index'));


    }/* end of update */


    public function destroy(Product $product)
    {
        if($product->image != 'default.png'){
            Storage::disk('public_uploads')->delete('/image_product/' . $product->image);
        }
        $product->delete();
        session()->flash('success','deleted_successfully');
        return redirect(route('dashboard.products.index'));
    }/* end of destroy */

}/* end of controller */
