<?php

namespace App\Http\Controllers\Dashboard;

use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Monolog\Handler\BrowserConsoleHandler;
use Laratrust\Traits\LaratrustUserTrait;

class UserController extends Controller
{
    use LaratrustUserTrait;

    public function __construct()
    {
        $this->middleware(['permission:users_create'])->only('create');
        $this->middleware(['permission:users_read'])->only('index');
        $this->middleware(['permission:users_update'])->only('edit');
        $this->middleware(['permission:users_delete'])->only('destroy');
    }

    public function index(Request $request)
    {
        // dd($request->search);
        // $users = User::all();
        $users = User::whereRoleIs('admin')->when($request->search ,function($q) use ($request){
            return $q->where('first_name','like','%'.$request->search.'%')
            ->orWhere('last_name','like','%'.$request->search.'%');
        })->latest()->paginate(5);
        return view('Dashboard.users.index',compact('users'));
    }//end of index


    public function create()
    {
        return view('Dashboard.users.create');
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required',
            'password'=>'required|confirmed',
            'permissions'=>'required|min:1',
        ]);


        $request_data = $request->except(['password' , 'password_confirmation','permissions','image']);

        $request_data['password'] = bcrypt($request->password);

        if($request->image){
            Image::make($request->image)->resize(null, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(public_path('uploads/image_user/'.$request->image->hashName()));

            $request_data['image'] = $request->image->hashName();
        }

        $user = User::create($request_data);



        $user->attachRole('admin');

        // $user->attachPermission('users_create');
        $user->syncPermissions($request->permissions);

        session()->flash('success', __('site.added_successfully'));

        return redirect(route('dashboard.users.index'));

    }/* end of store */




    public function edit(User $user)
    {
        // dd($user);
        return view('Dashboard.users.edit',compact('user'));
    }


    public function update(Request $request, User $user)
    {

        // dd($request);

        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required',
            'permissions'=>'required|min:1',
        ]);

        $request_data = $request->except(['permissions','image']);

        if($request->image){
            if($request->image != 'default.png'){
                Storage::disk('public_uploads')->delete('/image_user/' . $user->image);
            }
            Image::make($request->image)->resize(null, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(public_path('uploads/image_user/'.$request->image->hashName()));

            $request_data['image'] = $request->image->hashName();
        }/* end of if main  */



        $user->update($request_data);


        $user->syncPermissions($request->permissions);
        session()->flash('success', __('site.updated_successfully'));

        return redirect(route('dashboard.users.index'));

    }


    public function destroy(User $user)
    {
        if ($user->image != 'default.png') {

            Storage::disk('public_uploads')->delete('/image_user/' . $user->image);

        }//end of if

        $user->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.users.index');

    }//end of destroy
}//end controller
