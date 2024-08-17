<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Item;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Student::all();
        return view('items', compact('items'));
    }

    public function store(Request $request)
    {
        $item = new Student();
        $item->name = $request->name;
        $item->division = $request->division;
        $item->save();

        return response()->json(['success' => 'Item Added Successfully']);
    }

    public function edit($id)
    {
        $item = Student::find($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = Student::find($id);
        $item->name = $request->name;
        $item->division = $request->division;
        $item->save();

        return response()->json(['success' => 'Item Updated Successfully']);
    }

    public function destroy($id)
    {
        $item = Student::find($id);
        $item->delete();

        return response()->json(['success' => 'Item Deleted Successfully']);
    }
    
}
