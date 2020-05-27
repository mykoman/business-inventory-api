<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Business;
use App\Category;
use App\Rating;
use App\BusinessCategory;
use App\Image;
//use Picture;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $successStatus = 200;

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $business = new Business;
        $business->name = $request->input('name');
        $business->email = $request->input('email');
        $business->phone = $request->input('phone');
        $business->url = $request->input('url');
        $business->address = $request->input('address');
        $business->description = $request->input('description');
        $business->save();
        $business_id = $business->id;
        $busCategories = $request->input('category_id');
        foreach($busCategories as $cat){
            $businessCategory = new BusinessCategory;
            $businessCategory->business_id = $business_id;
            $businessCategory->category_id = $cat;
            $businessCategory->save();
        }

        $image = new Image;
        $image->business_id = $business_id;
        $image->picture = $request->input('image');
        $image->save();

        
        return response()->json(['data' => "sucessful", 'status'=>200], $this-> successStatus); 
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $businessId = $request->input('businessId');
        $business =Business::find($businessId);
        $business->name = $request->input('name');
        $business->email = $request->input('email');
        $business->phone = $request->input('phone');
        $business->url = $request->input('url');
        $business->address = $request->input('address');
        $business->description = $request->input('description');
        $business->save();

        //delete former categories
        $catFor = BusinessCategory::where('business_id', $businessId)->get();

        foreach($catFor as $fCat){
            $fCat->delete();
        }

        $business_id = $business->id;
        $busCategories = $request->input('category_id');
        foreach($busCategories as $cat){
            $businessCategory = new BusinessCategory;
            $businessCategory->business_id = $business_id;
            $businessCategory->category_id = $cat;
            $businessCategory->save();
        }

        $image = new Image;
        $image->business_id = $business_id;
        $image->picture = $request->input('image');
        $image->save();
        return response()->json(['data' => 'success', 'status'=>200], $this-> successStatus); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function loadDashboard()
    {
        $business = Business::count();
        $rating = Rating::count();
        $category = Category::count();

        $data['business'] =$business;
        $data['rating'] =$rating;
        $data['category'] =$category;
        $summary = ['summary'=> $data];

        return response()->json(['data' => $summary, 'status'=>200], $this-> successStatus); 
        
    }

    public function createCategory(Request $request)
    {
        $name = $request->input('name');
        $category = new Category;
        $category->name = $name;
        $category->save();
        $categories = Category::all();

        return response()->json(['data' => $categories, 'status'=>200], $this-> successStatus); 
    }

    public function fetchCategory()
    {
        $category = Category::all();
        return response()->json(['data' => $category, 'status'=>200], $this-> successStatus); 
    }

    public function searchStore(Request $request)
    {
        $query = $request->input('query');
        $business = Business::with('images')
        ->where('isActive', true)
                    ->where(function ($q) use ($query){
                        $q->where('name', 'LIKE','%'.$query.'%')->orWhere('description', 'LIKE','%'.$query.'%');
                    })->get();
        //write your filter query here ->where('name','LIKE','%'.$query.'%')

        return response()->json(['data' => $business, 'status'=>200], $this-> successStatus); 
    }
    

    public function businessDetail($id){
        $business = Business::find($id);
        $views = $business->views +1;
        $business->views = $views;
        $business->save();
        $averageRating = Rating::where('business_id', $id)->avg('rating');
        $business = Business::with(['images','business_categories'])->find($id);
        $business["rating"] = floor($averageRating);
        return response()->json(['data' => $business, 'status'=>200], $this-> successStatus); 
    }

    public function rating(Request $request)
    {
        $rating = $request->input('rating');
        $businessId = $request->input('businessId');
        $newRating = new Rating;
        $newRating->business_id = $businessId;
        $newRating->rating = $rating;
        $newRating->save();

        return response()->json(['data' => "successful", 'status'=>200], $this-> successStatus); 

    }

    public function adminBusiness(){
        $business = Business::all();
        return response()->json(['data' => $business, 'status'=>200], $this-> successStatus); 
    }


    public function process(Request $request)
    {
        $action = $request->input('action');
        $businessId = $request->input('businessId');
        if($action == "Suspend"){
            $business =Business::find($businessId);
            $business->isActive = false;
            $business->save();


            return response()->json(['data' => 'success', 'status'=>200], $this-> successStatus); 
        }

        if($action == "Delete"){
            Business::find($businessId)->delete();
            return response()->json(['data' => 'success', 'status'=>200], $this-> successStatus); 
        }

    }
}
