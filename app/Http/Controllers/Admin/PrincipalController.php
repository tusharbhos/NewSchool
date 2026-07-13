<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use App\Mail\LoginCredentials;
use App\Mail\ResendLoginCredentials;

use Auth;
use Mail;
use Log;

class PrincipalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('status','!=',2)->where('role',2)->get();
        return view('admin.principal.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.principal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'         => 'required|max:100',
            'email'        => 'required|email|unique:users',
            'phone_number' => 'max:20',
            'address'      => 'max:255'
        ];

        // Run the validator
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $password = Str::random(10);

        $params                 = $request->all();
        $params['role']         = 2; // Principal
        $params['password']     = FacadesHash::make($password);
        $params['created_by']   = Auth::id();

        $user = User::create($params);

        $email    = $user->email;
        $name     = $user->name;
        Mail::to($email)->send(new LoginCredentials($name,$password,$email));

        return redirect()->back()->with('message', [
            'type' => 'success',
            'title' => 'Add New Principal',
            'message' => 'Principal added successfully. Login credentials sent to their email.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $principal = User::where('id',$id)->where('status','!=',2)->first();
        if ($principal) {
            return view('admin.principal.show',compact('principal'));
        }
        return redirect()->route('admin.principal.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $principal = User::where('id',$id)->where('status','!=',2)->first();
        if ($principal) {
            return view('admin.principal.edit',compact('principal'));
        }
        return redirect()->route('admin.principal.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name'         => 'required|max:100',
            'email'        => 'required|email|unique:users,email,'.$id,
            'phone_number' => 'max:20',
            'address'      => 'max:255',
            'status'       => 'required'
        ];

        // Run the validator
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::find($id);
        if ($user) {
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->status = $request->input('status');

            if ($request->has('phone_number')) {
                $user->phone_number = $request->input('phone_number');
            }

            if ($request->has('address')) {
                $user->address = $request->input('address');
            }

            $user->save();

            return redirect()->back()->with('message', [
                'type' => 'success',
                'title' => 'Update Principal Details',
                'message' => 'Principal details updated successfully.',
            ]);
        }

        return redirect()->back()->with('message', [
            'type' => 'failed',
            'title' => 'Update Principal Details',
            'message' => 'Error while updating principal details.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $principal = User::where('id',$id)->where('status','!=',2)->first();
        if ($principal) {
            $principal->email = $id . '_' . $principal->email;
            $principal->status = 2;
            $principal->save();

            return [
                'status' => true,
                'title' => 'Remove Principal',
                'message' => 'Principal removed successfully',
            ];
        }else{
            return [
                'status' => false,
                'title' => 'Remove Principal',
                'message' => 'Invalid operation'
            ];
        }
    }

    /**
     * Reset the password of principle.
     */
    public function reset(string $id)
    {
        $user = User::where('id',$id)->where('status','!=',2)->first();
        if ($user) {
            $password = Str::random(10);
            $user->password = FacadesHash::make($password);
            $user->save();

            $email    = $user->email;
            $name     = $user->name;
            Mail::to($email)->send(new ResendLoginCredentials($name,$password,$email));

            return [
                'status' => true,
                'title' => 'Reset Password',
                'message' => 'Password reset successfully. Login credentials sent to their email.',
            ];
        }else{
            return [
                'status' => false,
                'title' => 'Reset Password',
                'message' => 'Invalid operation'
            ];
        }
    }
}
