<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MstClass;
use App\Models\TrnTeacherClass;
use App\Models\MstChapter;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = MstClass::with('teachers')->where('status','!=',2)->get();
        return view('admin.class.index',compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.class.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'class_title'  => 'required|max:100|unique:mst_classes'
        ];

        // Run the validator
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $class = MstClass::create($request->all());
        if ($class) {
        
            return redirect()->back()->with('message', [
                'type' => 'success',
                'title' => 'Create Class',
                'message' => 'Class created successfully.',
            ]);
        }

        return redirect()->back()->with('message', [
            'type' => 'failed',
            'title' => 'Create Class',
            'message' => 'Error while creating class details.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $class = MstClass::where('id',$id)->where('status','!=',2)->first();
        if ($class) {
            return view('admin.class.show',compact('class'));
        }
        return redirect()->route('admin.class.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $class = MstClass::where('id',$id)->where('status','!=',2)->first();
        if ($class) {
            return view('admin.class.edit',compact('class'));
        }
        return redirect()->route('admin.class.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'class_title'  => 'required|max:100|unique:mst_classes,class_title,'.$id,
            'status'       => 'required'
        ];

        // Run the validator
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $class = MstClass::find($id);
        if ($class) {
            $class->class_title = $request->input('class_title');
            $class->status = $request->input('status');
            $class->save();

            return redirect()->back()->with('message', [
                'type' => 'success',
                'title' => 'Update Class Details',
                'message' => 'Class details updated successfully.',
            ]);
        }

        return redirect()->back()->with('message', [
            'type' => 'failed',
            'title' => 'Update Class Details',
            'message' => 'Error while updating class details.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $class = MstClass::where('id',$id)->where('status','!=',2)->first();
        if ($class) {

            $count = TrnTeacherClass::where('class_id',$id)->where('status','!=',2)->count();

            if ($count > 0) {
                return [
                    'status' => false,
                    'title' => 'Remove Class',
                    'message' => 'This class is associated with many chapters. Please remove the association with the class and proceed.'
                ];
            }else{

                $class->class_title = $id . '_Del_' . $class->class_title;
                $class->status = 2;
                $class->save();

                TrnTeacherClass::where('class_id',$id)->delete();

                return [
                    'status' => true,
                    'title' => 'Remove Class',
                    'message' => 'Class removed successfully',
                ];
            }
        }else{
            return [
                'status' => false,
                'title' => 'Remove Class',
                'message' => 'Invalid operation'
            ];
        }
    }
}
