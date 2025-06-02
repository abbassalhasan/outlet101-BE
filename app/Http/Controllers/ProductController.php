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
            'stock'=> 'required|integer',
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
    public function delete_product($id)
   {
    $product = Product::find($id);

    if (!$product) {
        return $this->sendError('Product not found.');
    }


    if (!empty($product->imgSrc)) {
        $imagePath = str_replace('/storage/', '', $product->imgSrc);
        Storage::disk('public')->delete($imagePath);
    }

    $product->delete();

    return $this->sendResponse([], 'Product deleted successfully.');
  }

    public function edit_product(Request $req, $id)
    {

        $product = Product::find($id);

        if (!$product) {
            return $this->sendError('Product not found.');
        }

        $validator = Validator::make($req->all(), [
            'name' => 'sometimes|string',
            'description' => 'sometimes|string|nullable',
            'price' => 'sometimes|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'stock'=> 'integer',
            'category_id'=> 'integer'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $updateData = $req->only(['name', 'description', 'price', 'stock', 'category_id']);

         if ($req->has('name')) {
        $updateData['slug'] = Str::slug($req->input('name'));
    }

        if ($req->hasFile('image')) {
            $image = $req->file('image');
            $imageName = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('products', $imageName, 'public');
            $updateData['imgSrc'] = Storage::url($path);

            if (!empty($product->imgSrc)) {
                $oldImage = str_replace('/storage/', '', $product->imgSrc);
                Storage::disk('public')->delete($oldImage);
            }
        }

        $product->update($updateData);

        return $this->sendResponse($product, 'Product updated successfully.');
    }
    public function get_product($id)
{
    if (empty($id)) {
       return $this->sendResponse(Product::all(), 'Product retrieved successfully.');

    }
    $product = is_numeric($id)
        ? Product::find($id)
        : Product::where('slug', $id)->first();

    if (!$product) {
        return $this->sendError('Product not found.');
    }

    return $this->sendResponse($product, 'Product retrieved successfully.');
}
   public function get_products()
   {
    return $this->sendResponse(Product::all(),'Product retrieved successfully');
   }
}
