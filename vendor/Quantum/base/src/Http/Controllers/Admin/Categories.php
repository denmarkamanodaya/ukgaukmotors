<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Categories.php
 **/

namespace Quantum\base\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Quantum\base\Http\Requests\Admin\createCategoryChildRequest;
use Quantum\base\Http\Requests\Admin\CreateCategoryRequest;
use Quantum\base\Http\Requests\Admin\UpdateCategoryChildRequest;
use Quantum\base\Http\Requests\Admin\UpdateCategoryRequest;
use Quantum\base\Services\CategoryService;

class Categories extends Controller
{

    /**
     * @var CategoryService
     */
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    
    public function index()
    {
        $categories = $this->categoryService->getTopLevelCategories();
        //$icons = fontAwesomeList();
        $fajson = \Storage::get('public\sortedIcons.json');
        return view('base::admin.Categories.index', compact('categories', 'fajson'));
    }

    public function store(CreateCategoryRequest $createCategoryRequest)
    {
        $this->categoryService->createCategory($createCategoryRequest);
        return redirect('admin/categories');
    }

    public function storeChild(createCategoryChildRequest $createCategoryChildRequest)
    {
        $category = $this->categoryService->createCategoryChild($createCategoryChildRequest);
        return redirect('admin/category/'.$category->parent->slug);
    }

    public function show($id)
    {
        $category = $this->categoryService->getCategoryBySlug($id);
        $childCategories = $this->categoryService->getChildCategories($category->id);
        //$icons = fontAwesomeList();
        $fajson = \Storage::get('public\sortedIcons.json');
        return view('base::admin.Categories.manage', compact('category', 'childCategories', 'fajson'));
    }

    public function showTest()
    {
        $Categories = $this->categoryService->getCategoriesSortPosts('blog',2);

        dd($Categories);
    }

    public function editChild($parent_id, $child_id)
    {
        $category = $this->categoryService->getCategoryBySlug($parent_id);
        $childCategories = $this->categoryService->getChildCategories($category->id);
        $childCategory = $this->categoryService->getCategoryBySlug($child_id, $category->slug);
        //$icons = fontAwesomeList();
        $fajson = \Storage::get('public\sortedIcons.json');
        $categories = $this->categoryService->getTopLevelCategories()->pluck('name', 'id');
        return view('base::admin.Categories.manageChild', compact('category', 'childCategories', 'fajson', 'childCategory', 'categories'));
    }
    
    public function update(UpdateCategoryRequest $updateCategoryRequest, $id)
    {
        $category = $this->categoryService->updateCategory($updateCategoryRequest, $id);
        return redirect('admin/category/'.$category->slug);
    }

    public function updateChild(UpdateCategoryChildRequest $updateCategoryRequest, $parent_id, $child_id)
    {
        $category = $this->categoryService->updateCategoryChild($updateCategoryRequest, $parent_id, $child_id);
        return redirect('admin/category/'.$parent_id);
    }

    public function delete($id)
    {
        $this->categoryService->delete($id);
        return redirect('admin/categories');
    }

    public function deleteChild($parent_id, $child_id)
    {
        $this->categoryService->deleteChild($parent_id, $child_id);
        return redirect('admin/category/'.$parent_id);
    }

}