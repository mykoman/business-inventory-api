<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{

    public $successStatus = 200;
    //
    public function createCategory(Request $request)
    {
        $name = $request->input('name');
        $category = new Category;
        $category->name = $name;
        $category->save();
        $categories = Category::all();

        return response()->json(['data' => $categories, 'status'=>200], $this-> successStatus); 
    }
}
