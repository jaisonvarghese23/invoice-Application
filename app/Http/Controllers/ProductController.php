<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use ReflectionFunctionAbstract;

class ProductController extends Controller
{
    public function products(){

     $products = Product::orderBy('id','DESC')->get();
     return response()->json([
        'products'=>$products
     ]);


    }
}
