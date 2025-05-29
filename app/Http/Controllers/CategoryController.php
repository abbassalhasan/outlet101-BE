<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Str;
use Validator;

class CategoryController extends BaseController
{
     public function add_category(Request $req)
    {
         $validator = Validator::make($req->all(), [
            'name' => 'required|string',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $req->all();
        $input['slug'] = Str::slug($req->name);


        $category = Category::create($input);
        $success['category'] =  $category;

        return $this->sendResponse($success, "Category created successfully");
    }
    public function edit_category(Request $req,$id)
     {

        $category = Category::find($id);

        if (!$category) {
            return $this->sendError('category not found.');
        }

        $validator = Validator::make($req->all(), [
            'name' => 'sometimes|string',
            'description' => 'sometimes|string|nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $updateData = $req->only(['name', 'description']);

         if ($req->has('name')) {
        $updateData['slug'] = Str::slug($req->input('name'));
    }

        $category->update($updateData);

        return $this->sendResponse($category, 'Category updated successfully.');
    }
    public function delete_category($id)
    {
         $category = Category::find($id);

    if (!$category) {
        return $this->sendError('Category not found.');
    }

    $category->delete();

    return $this->sendResponse([], 'Category deleted successfully.');
    }
    public function get_categories()
   {
    return $this->sendResponse(Category::all(),'Category retrieved successfully');
   }

    public function get_category($id)
    {
         $category = Category::find($id);

    if (!$category) {
        return $this->sendError('Category not found.');
    }

    $success = [
        'category' => $category,
        'products' => $category->products
    ];

    return $this->sendResponse($success, 'Category retrieved successfully.');
    }
}
