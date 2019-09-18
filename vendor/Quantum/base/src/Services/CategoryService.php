<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : CategoryService.php
 **/

namespace Quantum\base\Services;

use Cache;
use Illuminate\Support\Collection;
use Quantum\base\Models\Categories;
use Quantum\blog\Models\Posts;

class CategoryService
{

    public function checkForCacheTags()
    {
        $cacheType = config('cache.default');
        if($cacheType == 'redis') return true;
        return false;
    }

    public function getTopLevelCategories()
    {
        return Categories::with('childrenCount')->whereNull('parent_id')->whereNull('user_id')->tenant()->orderBy('name', 'ASC')->get();
    }

    public function getChildCategories($id, $area=null)
    {
        return Categories::where('parent_id', $id)->whereNull('user_id')->tenant()->orderBy('name', 'ASC')->get();
    }

    public function getChildCategoriesCached($parentSlug)
    {
        if(\Auth::check())
        {
            return \Cache::remember('child_categories'.$parentSlug, 10, function() use($parentSlug) {
                $parent = $this->getCategoryBySlug($parentSlug);
                return Categories::with(['postCount'])->where('parent_id', $parent->id)->whereNull('user_id')->tenant()->orderBy('name', 'ASC')->get();
            });
        } else {
            return \Cache::remember('child_categories_public'.$parentSlug, 10, function() use($parentSlug) {
                $parent = $this->getCategoryBySlug($parentSlug);
                return Categories::with(['postCount'])->where('parent_id', $parent->id)->whereNull('user_id')->tenant()->orderBy('name', 'ASC')->get();
            });
        }

    }
    
    public function flattenAllChildren($categoriesToFlatten)
    {
        $Categories = new Collection();
        foreach ($categoriesToFlatten as $category)
        {
            $Categories->push($category);
            foreach ($category->children as $child)
            {
                $Categories->push($child);
            }
        }
        return $Categories;
    }
    
    public function getCategoriesSortPosts($slug, $area, $limit=null)
    {
        $Categories = $this->getChildCategoriesCached($slug);
        //$Categories = $this->flattenAllChildren($Categories);
        $Categories = $Categories->sortByDesc(function ($category, $key) {
            return $category['postCount']['aggregate'];
        });
        if($limit) return $Categories->take($limit);
        return $Categories;
    }

    public function createCategory($request)
    {
        $category = Categories::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'icon' => ($request->icon != '0') ? $request->icon : null,
            'parent_id' => null,
            'user_id' => null,
            'system' => 0,
        ]);

        Categories::create([
            'name' => 'Uncategorised',
            'slug' => 'uncategorised',
            'parent_id' => $category->id,
            'area' => $category->slug,
            'description' => 'Uncategorised Content',
            'icon' => '',
            'user_id' => null,
            'system' => 1
        ]);
        

        flash('Category has been created.')->success();
        $this->cache_clear();
        \Activitylogger::log('Admin - Created Category : '.$category->name, $category);
    }

    public function createCategoryChild($request, $area='blog')
    {
        $parent = Categories::where('id', $request->parent_id)->whereNull('user_id')->tenant()->firstOrFail();

        $area = ((!$parent->area) || $parent->area == '') ? $parent->slug : $parent->area;

        $category = Categories::create([
            'name' => $request->child_name,
            'slug' => $request->child_slug,
            'description' => $request->child_description,
            'icon' => ($request->child_icon != '0') ? $request->child_icon : null,
            'parent_id' => $request->parent_id,
            'area' => $area,
            'user_id' => null,
            'system' => 0,
        ]);
        $category->parent = $parent;

        flash('Child Category has been created.')->success();
        $this->cache_clear();
        \Activitylogger::log('Admin - Created Category : '.$category->name, $category);
        return $category;
    }

    public function getCategoryBySlug($slug, $area=null)
    {
        if($area)
        {
            return Cache::remember('category_'.$slug.$area, 60, function() use($slug, $area) {
                return Categories::where('slug', $slug)->whereNull('user_id')->tenant()->whereHas('parent', function($query) use($area) {
                    $query->where('slug', $area)->tenant();
                })->firstOrFail();
            });
        }
        return Cache::remember('category_'.$slug, 60, function() use($slug) {
            return Categories::where('slug', $slug)->whereNull('user_id')->tenant()->firstOrFail();
        });
    }

    public function getCategoryBySlugParentLocked($slug, $area)
    {
        return Categories::where('slug', $slug)->where('area', $area)->whereNull('user_id')->tenant()->firstOrFail();
    }

    public function getCategoryById($id)
    {
        return Categories::where('id', $id)->whereNull('user_id')->tenant()->firstOrFail();
    }

    public function updateCategory($request, $slug)
    {
        $category = Categories::where('slug', $slug)->whereNull('user_id')->tenant()->firstOrFail();

        if($category->system == 1)
        {
            flash('Category can not be updated.')->error();
            return $category;
        }
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->description = $request->description;
        $category->icon = ($request->icon != '0') ? $request->icon : null;
        $category->save();

        flash('Category has been updated.')->success();
        $this->cache_clear();
        \Activitylogger::log('Admin - Updated Category : '.$category->name, $category);

        return $category;
    }

    public function updateCategoryChild($request, $parent_id, $child_id)
    {
        $parent = Categories::where('slug', $parent_id)->whereNull('user_id')->tenant()->firstOrFail();
        $category = Categories::where('slug', $child_id)->where('parent_id', $parent->id)->whereNull('user_id')->tenant()->firstOrFail();

        if($category->system == 1)
        {
            flash('Category can not be updated.')->error();
            return $category;
        }

        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->description = $request->description;
        $category->icon = ($request->icon != '0') ? $request->icon : null;
        $category->parent_id = $request->parent_id;
        $category->save();

        flash('Category has been updated.')->success();
        $this->cache_clear();
        \Activitylogger::log('Admin - Updated Child Category : '.$category->name, $category);

        return $category;
    }

    public function delete($slug)
    {
        $category = Categories::with('children')->where('slug', $slug)->whereNull('user_id')->tenant()->firstOrFail();

        if($category->system == 1)
        {
            flash('Category can not be deleted.')->error();
            return $category;
        }

        if($category->children->count() > 1)
        {
            flash('Category has Children, Please move or delete them first.')->error();
            return $category;
        }


        foreach ($category->children as $child)
        {
            $this->updatePostCategory($child->id, $category->parent_id);
            $child->delete();
        }



        $this->updatePostCategory($category->id, $category->parent_id);
        $category->delete();

        flash('Category has been deleted.')->success();
        $this->cache_clear();
        \Activitylogger::log('Admin - Deleted Category : '.$category->name, $category);

    }

    public function deleteChild($parent_id, $child_id)
    {
        $parent = Categories::where('slug', $parent_id)->whereNull('user_id')->tenant()->firstOrFail();
        $category = Categories::where('slug', $child_id)->where('parent_id', $parent->id)->whereNull('user_id')->tenant()->firstOrFail();
        if($category->system == 1)
        {
            flash('Category can not be deleted.')->error();
            return $category;
        }
        $this->updatePostCategory($category->id, $category->parent_id);
        $category->delete();
        flash('Child Category has been deleted.')->success();
        $this->cache_clear();
        \Activitylogger::log('Admin - Deleted Child Category : '.$category->name.' From Parent : '.$parent->name, $category);
    }

    private function updatePostCategory($id, $newId)
    {
        Posts::where('post_category_id', $id)->whereNull('user_id')->update(['post_category_id' => $newId]);
    }
    
    public function getChildCategoriesList($slug)
    {
        $parent = Categories::where('slug', $slug)->whereNull('user_id')->tenant()->firstOrFail();
        $categories = Categories::where('parent_id', $parent->id)->whereNull('user_id')->tenant()->pluck('name','id');
        return $categories;
    }

    public function cachedCategoryList($except=[])
    {
        $categories = Cache::rememberForever('categoryList', function () use($except) {
            return Categories::whereHas('children')->with(['children' => function($query) {
                $query->where('slug', '!=', 'uncategorised')
                    ->tenant()
                    ->orderBy('name', 'ASC');
            }])->tenant()->orderBy('name', 'ASC')->get();

        });

        $catList = [];
        foreach ($categories as $category)
        {
            if(in_array($category->name, $except)) continue;
            $catList[$category->name] = $category->children->pluck('name', 'id');
        }
        return $catList;
    }

    public function cachedSearchCategories($categories=[])
    {
        $cachekey = md5(serialize($categories));

        if(Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
            if (Cache::tags(['search_categories'])->has($cachekey)) {
                $searchCategories = Cache::tags(['search_categories'])->get($cachekey);
            } else {
                $searchCategories = Categories::whereIn('id', $categories)->tenant()->pluck('name');
                Cache::tags(['search_categories'])->put($cachekey, $searchCategories, 10);
            }
        } else {
            $searchCategories = Cache::remember('search_categories_'.$cachekey, 5, function () use ($categories) {
                return Categories::whereIn('id', $categories)->tenant()->pluck('name');
            });
        }
        return $searchCategories;
    }


    public function cache_clear()
    {
        $categories = Categories::tenant()->get();
        foreach ($categories as $category)
        {
            Cache::forget('category_'.$category->slug);
            Cache::forget('child_categories'.$category->slug);
            Cache::forget('child_categories_public'.$category->slug);
        }
        Cache::forget('categoryList');
        if(Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
            Cache::tags('search_categories')->flush();
        }
    }
}