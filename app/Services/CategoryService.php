<?php

namespace App\Services;

use App\Exceptions\Category\CategoryException;
use App\Http\Dto\Category\CreateCategoryDto;
use App\Http\Dto\Category\DeleteCategoryDto;
use App\Http\Dto\Request\Category\CategoryCard;
use App\Http\Dto\Request\Category\GetCategory;
use App\Http\Dto\Request\Category\UpdateCategory;
use App\Http\Dto\Response\Category as CategoryDto;
use App\Http\Dto\Response\Customer\Category\CategoryWithChildren;
use App\Http\Dto\Response\Customer\Category\ParentCategory as ParentCategoryDto;
use App\Models\Category;
use App\Models\File;

class CategoryService
{
    public function createCategory(CreateCategoryDto $dto): Category
    {
        if (Category::where('name', $dto->name)->exists())
            CategoryException::duplicateCategoryName();

        $category = Category::create([
            'name' => $dto->name,
            'description_category' => $dto->descriptionCategory,
            'description_page' => $dto->descriptionPage,
            'parent_id' => $dto->parentId ?? null,
        ]);

        if ($dto->imageId)
            $category->image()->attach($dto->imageId);

        return $category;
    }
    public function getNestedCategories()
    {
        return Category::whereNull('parent_id')->get();
    }

    public function getAllCategories(?int $currentCategoryId = null)
    {
        if ($currentCategoryId === null) {
            return Category::all();
        }

        $category = Category::find($currentCategoryId);
        if (!$category) {
            CategoryException::categoryNotFound();
        }

        $excludedIds = $this->getDescendantIds($category);
        $excludedIds[] = $category->id;

        return Category::whereNotIn('id', $excludedIds)->get();
    }

    public function getCategoriesByNameWithImage(string $name)
    {
        return Category::where('name', 'like', '%' . $name . '%')->with('image')->get();
    }

    public function getCategoriesByIds(array $ids)
    {
        return Category::whereIn('id', $ids)->get();
    }

    public function getCategory(GetCategory $dto): CategoryDto
    {
        $category = Category::find($dto->id);
        if ($category === null)
            CategoryException::categoryNotFound();

        return CategoryDto::from($category);
    }

    public function getSubCategoriesCustomer(GetCategory $dto)
    {
        $category = Category::find($dto->id);
        if (!isset($category)) {
            CategoryException::categoryNotFound();
        }

        $categories = Category::where('parent_id', $dto->id)
            ->with('image:id,alt,name,mime_type,path')
            ->select("id", "name", "parent_id")
            ->get();
        if ($categories->isEmpty())
            return null;
        return $categories->map(function ($category) {
            return CategoryCard::from($category);
        })->toArray();
    }

    public function getNestedCategoriesCustomer()
    {
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->with('image:id,alt,name,mime_type,path')
                    ->select("id", "name", "parent_id");
            }, 'image:id,alt,name,mime_type,path'])
            ->select("id", "name", "parent_id")
            ->get();

        return $categories->map(function ($category) {
            return CategoryWithChildren::from($category);
        })->toArray();
    }

    public function getAllParentCategoriesCustomer()
    {
        return Category::whereNull('parent_id')
            ->with('image')
            ->get(['id', 'name'])
            ->map(function ($value) {
                return ParentCategoryDto::from($value);
            })->toArray();
    }

    public function getAllCategoriesCustomer()
    {
        return Category::with('image:path')
            ->get(['id', 'name']);
    }

    public function deleteCategory(DeleteCategoryDto $dto): bool
    {
        $dto->category->children()->update(['parent_id' => $dto->category->parent?->id]);
        $dto->category->image()->detach();
        return $dto->category->delete();
    }

    public function updateCategory(UpdateCategory $dto)
    {
        $category = Category::find($dto->id);

        if ($category === null)
            CategoryException::categoryNotFound();

        if (Category::where('name', $dto->name)->whereNot('id', $dto->id)->exists())
            CategoryException::duplicateCategoryName();

        $category->update([
            'name' => $dto->name,
            'description_category' => $dto->descriptionCategory,
            'description_page' => $dto->descriptionPage,
        ]);

        if ($dto->imageId !== null) {
            if (File::where('id', $dto->imageId)->exists()) {
                $category->image()->detach();
                $category->image()->attach($dto->imageId);
            } else {
                CategoryException::categoryNotFound(); //FileException
            }
        } else {
            $category->image()->detach();
        }

        if ($dto->parentId === $dto->id) {
            CategoryException::invalidParent();
        }

        if ($dto->parentId !== null) {
            $parentCategory = Category::find($dto->parentId);
            if ($parentCategory === null)
                CategoryException::categoryNotFound();

            if ($this->isDescendant($category, $parentCategory)) {
                CategoryException::invalidParent();
            }

            $category->update(['parent_id' => $dto->parentId]);
        } else {
            $category->update(['parent_id' => null]);
        }
    }

    private function isDescendant(Category $category, Category $potentialParent): bool
    {
        if ($potentialParent->id === $category->id) {
            return true;
        }

        $parent = $potentialParent->parent;

        while ($parent !== null) {
            if ($parent->id === $category->id) {
                return true;
            }
            $parent = $parent->parent;
        }

        return false;
    }

    private function getDescendantIds(Category $category): array
    {
        $descendantIds = [];

        foreach ($category->children as $child) {
            $descendantIds[] = $child->id;
            $descendantIds = array_merge($descendantIds, $this->getDescendantIds($child));
        }

        return $descendantIds;
    }
}
