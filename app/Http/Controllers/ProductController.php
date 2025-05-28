<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Storage;
use Str;
use Validator;

class ProductController extends BaseController
{
    public function add_product(Request $req)
    {
         $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'price'=> 'required|integer',
            'amount'=> 'required|integer',
            'description'=> 'nullable|string',
            'category_id'=> 'integer|required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:4096'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $req->all();
        $input['slug'] = Str::slug($req->name);

         if ($req->hasFile('image')) {
            $image = $req->file('image');
            $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('products', $imageName, 'public');
            Storage::setVisibility($path, 'public');
            $input['imgSrc'] = Storage::url($path);
        }

        $product = Product::create($input);
        $success['product'] =  $product;

        return $this->sendResponse($success, "Product created successfully");
    }
}
