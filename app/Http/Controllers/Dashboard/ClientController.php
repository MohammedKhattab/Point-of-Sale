<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request){


        $clients = Client::when($request->search , function($q) use ($request){
            return $q->where('name','like','%'.$request->search.'%')
            ->OrWhere('phone','like','%'.$request->search.'%')
            ->OrWhere('address','like','%'.$request->search.'%');
        })->latest()->paginate(5);

        return view('Dashboard.clients.index',compact('clients'));

    }/* end of index */

    public function create(){
        return view('Dashboard.clients.create');
    }/* end of create */

    public function store(Request $request){

        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);
        Client::create($request->all());
        session()->flash('success',__('site.added_successfully'));
        return redirect(route('dashboard.clients.index'));

    }/* end of store */

    public function edit(Client $client){

        return view('Dashboard.clients.edit',compact('client'));

    }/* end of edit */

    public function update(Request $request,Client $client){

        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $client->update($request->all());
        session()->flash('success',__('site.updated_successfully'));

        return redirect(route('dashboard.clients.index'));

    }/* end of update */

    public function destroy(Client $client){

        $client->delete();
        session()->flash('success',__('site.deleted_successfully'));
        return redirect(route('dashboard.clients.index'));

    }/* end of destroy */

}/* end of Client Controller */
