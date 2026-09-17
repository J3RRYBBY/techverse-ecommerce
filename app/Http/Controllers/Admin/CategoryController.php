<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function list()
    {
        $categories = Category::when(request('searchKey'), function ($query) {
            $query->where('name', 'like', '%' . request('searchKey') . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        $categoryCount = $categories->total();

        return view('admin.category.list', compact('categories', 'categoryCount'));
    }

    public function create(Request $request, CloudinaryService $cloudinary)
    {
        try {
            $this->validationCheck($request);

            $imageUrl = null;
            $imagePublicId = null;

            if ($request->hasFile('categoryImage')) {
                $uploaded = $cloudinary->upload($request->file('categoryImage'), 'techverse/categories');

                $imageUrl = $uploaded['url'];
                $imagePublicId = $uploaded['public_id'];
            }

            Category::create([
                'name' => $request->categoryName,
                'image' => $imageUrl,
                'image_public_id' => $imagePublicId,
            ]);

            return redirect()->back()->with('success', 'Category created successfully!');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator, 'create')
                ->withInput()
                ->with('open_modal', 'createCategoryModal');
        }
    }

    public function update(int $id, Request $request, CloudinaryService $cloudinary)
    {
        try {
            $request['id'] = $id;

            $this->validationCheck($request);

            $category = Category::findOrFail($id);

            $data = [
                'name' => $request->categoryName,
            ];

            if ($request->hasFile('categoryImage')) {
                /*
                |--------------------------------------------------------------------------
                | Upload new image first
                |--------------------------------------------------------------------------
                */

                $uploaded = $cloudinary->upload($request->file('categoryImage'), 'techverse/categories');

                /*
                |--------------------------------------------------------------------------
                | Delete old Cloudinary image
                |--------------------------------------------------------------------------
                */

                if ($category->image_public_id) {
                    $cloudinary->delete($category->image_public_id);
                }

                $data['image'] = $uploaded['url'];
                $data['image_public_id'] = $uploaded['public_id'];
            }

            $category->update($data);

            return to_route('category#list')->with('success', 'Category updated successfully!');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator, 'edit')
                ->withInput()
                ->with('open_modal', 'editCategoryModal')
                ->with('edit_name', $request->categoryName);
        }
    }

    public function delete(int $id, CloudinaryService $cloudinary)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->exists()) {
            return to_route('category#list')->with(
                'deleteError',
                'This category cannot be deleted because it is being used by a product.',
            );
        }

        // Delete image from Cloudinary
        if ($category->image_public_id) {
            $cloudinary->delete($category->image_public_id);
        }

        // Delete category from database
        $category->delete();

        return to_route('category#list')->with('success', 'Category Deleted Successfully!');
    }
    private function validationCheck(Request $request)
    {
        $rules = [
            'categoryName' => [
                'required',
                'min:2',
                'max:30',
                $request->filled('id') ? 'unique:categories,name,' . $request->id : 'unique:categories,name',
            ],
        ];

        if ($request->filled('id')) {
            $rules['categoryImage'] = 'nullable|file|mimes:jpg,jpeg,png,webp,avif|max:2048';
        } else {
            $rules['categoryImage'] = 'required|file|mimes:jpg,jpeg,png,webp,avif|max:2048';
        }

        $request->validate($rules);
    }
}
