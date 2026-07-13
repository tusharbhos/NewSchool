<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\MstClass;
use App\Models\TrnTeacherClass;

use App\Mail\LoginCredentials;
use App\Mail\ResendLoginCredentials;

use Auth;
use Mail;
use Log;
class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('classes')->where('status','!=',2)->where('role',3)->get();
        Log::debug(json_encode($users,true));
        return view('admin.teacher.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = MstClass::where('status','!=',2)->get();
        return view('admin.teacher.create',compact('classes'));
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
        $params['role']         = 3; // Teacher
        $params['password']     = FacadesHash::make($password);
        $params['created_by']   = Auth::id();

        $user = User::create($params);

        if ($user) {
            if ($request->has('classes')) {
                $classes = $request->input('classes');
                for ($i=0; $i < sizeof($classes); $i++) { 
                    $trn_params['teacher_id'] = $user->id;
                    $trn_params['class_id'] = $classes[$i];
                    TrnTeacherClass::create($trn_params);
                }
            }
        }

        $email    = $user->email;
        $name     = $user->name;
        Mail::to($email)->send(new LoginCredentials($name,$password,$email));

        return redirect()->back()->with('message', [
            'type' => 'success',
            'title' => 'Add New Teacher',
            'message' => 'Teacher added successfully. Login credentials sent to their email.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $teacher = User::with('classes')->where('status','!=',2)->find($id);
        if ($teacher) {
            $classes = MstClass::where('status','!=',2)->get();
            return view('admin.teacher.show',compact('teacher','classes'));
        }
        return redirect()->route('admin.teacher.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacher = User::with('classes')->where('status','!=',2)->find($id);
        if ($teacher) {
            $classes = MstClass::where('status','!=',2)->get();
            return view('admin.teacher.edit',compact('teacher','classes'));
        }
        return redirect()->route('admin.teacher.index');
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

            TrnTeacherClass::where('teacher_id',$user->id)->delete();
            if ($request->has('classes')) {
                $classes = $request->input('classes');
                for ($i=0; $i < sizeof($classes); $i++) { 
                    $trn_params['teacher_id'] = $user->id;
                    $trn_params['class_id'] = $classes[$i];
                    TrnTeacherClass::create($trn_params);
                }
            }

            return redirect()->back()->with('message', [
                'type' => 'success',
                'title' => 'Update Teacher Details',
                'message' => 'Teacher details updated successfully.',
            ]);
        }

        return redirect()->back()->with('message', [
            'type' => 'failed',
            'title' => 'Update Teacher Details',
            'message' => 'Error while updating teacher details.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacher = User::where('id',$id)->where('status','!=',2)->first();
        if ($teacher) {
            $teacher->email  = $id . '_' . $teacher->email;
            $teacher->status = 2;
            $teacher->save();

            TrnTeacherClass::where('teacher_id',$id)->delete();

            return [
                'status' => true,
                'title' => 'Remove Teacher',
                'message' => 'Teacher removed successfully',
            ];
        }else{
            return [
                'status' => false,
                'title' => 'Remove Teacher',
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

    public function changePassword(Request $request, $id)
    {
        $rules = [
            'password'     => 'required|min:6|max:20'
        ];

        // Run the validator
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::find($id);

        if ($user) {
            $password = $request->input('password');
            $user->password =  FacadesHash::make($password);
            $user->save();

            $email    = $user->email;
            $name     = $user->name;
            Mail::to($email)->send(new LoginCredentials($name,$password,$email));

            return redirect()->back()->with('message', [
                'type' => 'success',
                'title' => 'Reset Password',
                'message' => 'Password reset successfully. Login credentials sent to their email.',
            ]);
        }else{
            return redirect()->back()->with('message', [
                'type' => 'failed',
                'title' => 'Reset Password',
                'message' => 'Unable to reset password.',
            ]);
        }
    }
}
