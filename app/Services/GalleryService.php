<?php

namespace App\Services;

use App\Enums\Gallery\ImageTypeEnum;
use App\Exceptions\Gallery\FolderException;
use App\Exceptions\Gallery\ImageException;
use App\Http\Dto\Request\Gallery\DeleteFolder as DeleteFolderDto;
use App\Http\Dto\Request\Gallery\DeleteImage as DeleteImageDto;
use App\Http\Dto\Request\Gallery\GetFolder as GetFolderDto;
use App\Http\Dto\Request\Gallery\GetImage as GetImageDto;
use App\Http\Dto\Request\Gallery\CreateFolder;
use App\Http\Dto\Gallery\UploadImageDto;
use App\Http\Dto\Response\FolderWithContentCount as FolderWithContentCountDto;
use App\Models\File;
use App\Models\Folder;
use App\Models\Folderable;
use App\Http\Dto\Response\Folder as FolderDto;
use App\Http\Dto\Request\Gallery\GetGalleryItems as GetGalleryItemsDto;
use App\Http\Dto\Request\Gallery\MoveImage as MoveImageDto;
use App\Http\Dto\Request\Gallery\UpdateFolder;
use App\Http\Dto\Response\Image as ImageDto;
use App\Models\Fileable;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Pail\Files;

class GalleryService
{
    public function uploadImage(UploadImageDto $dto)
    {
        $originalName = pathinfo($dto->image->getClientOriginalName(), PATHINFO_FILENAME);
        $name = $this->getNewImageUniqueName($originalName, $dto->folderId);
        $originalExtension = $dto->image->getClientOriginalExtension();
        $path = $dto->image->store('images', 'public');

        $image = File::create([
            'name' => $name,
            'alt' => $dto->alt,
            'mime_type' => $originalExtension,
            'path' => $path,
            'folder_id' => $dto->folderId
        ]);

        if ($dto->folderId !== null) {
            $folder = Folder::find($dto->folderId);

            if ($folder === null)
                FolderException::folderNotFound();

            $folder->morphImages()->save($image);
        } else
            Folderable::create([
                'folder_id' => null,
                'folderable_id' => $image->id,
                'folderable_type' => File::class,
            ]);

        return $image;
    }

    public function createFolder(CreateFolder $dto)
    {
        $parent = Folder::find($dto->parentId);

        if (!$this->isFolderNameUniqueInParent($dto->name, $dto->parentId))
            FolderException::duplicateFolderName();

        $folder = Folder::create([
            'name' => $dto->name,
            'parent_id' => $dto->parentId,
        ]);

        if ($dto->parentId !== null) {
            $parent = Folder::find($dto->parentId);

            if ($parent === null)
                FolderException::folderNotFound();

            $parent->morphChildren()->attach($folder->id);
        } else {
            Folderable::create([
                'folder_id' => null,
                'folderable_id' => $folder->id,
                'folderable_type' => Folder::class,
            ]);
        }

        return FolderDto::from($folder);
    }

    public function updateFolder(UpdateFolder $dto)
    {
        $folder = Folder::find($dto->id);

        if ($folder === null)
            FolderException::folderNotFound();

        $parent = $folder->parent;

        if (!$this->isFolderNameUniqueInParent($dto->name, $parent->id ?? null))
            FolderException::duplicateFolderName();

        $folder->update(['name' => $dto->name]);
        return FolderDto::from($folder);
    }

    public function moveImage(MoveImageDto $dto)
    {
        $image = File::find($dto->id);
        if ($image === null)
            ImageException::imageNotFound();

        if ($image->folder_id == $dto->newFolderId)
            return ImageDto::from($image);

        if ($dto->newFolderId !== null) {
            $folder = Folder::find($dto->newFolderId);
            if ($folder === null)
                FolderException::folderNotFound();
        }

        Folderable::where('folderable_type', File::class)
            ->where('folderable_id', $image->id)
            ->update(
                [
                    'folder_id' => $dto->newFolderId
                ]
            );

        $image->update(
            [
                'name' => $this->getNewImageUniqueName($image->name, $dto->newFolderId),
                'folder_id' => $dto->newFolderId
            ]
        );

        return ImageDto::from($image);
    }

    private function isFolderNameUniqueInParent(string $name, ?int $parentId): bool
    {
        return !Folder::where('parent_id', $parentId)->where('name', $name)->exists();
    }

    public function getAllItems(GetGalleryItemsDto $dto)
    {
        if ($dto->folderId !== null) {
            $folder = Folder::find($dto->folderId);

            if ($folder === null)
                FolderException::folderNotFound();

            return $folder
                ->items()
                ->with("folderable")
                ->orderByRaw("folderable_type = '" . addslashes(Folder::class) . "' DESC")
                ->latest()
                ->paginate(30, page: $dto->page);
        } else {
            return Folderable::where('folder_id', null)
                ->with("folderable")
                ->orderByRaw("folderable_type = '" . addslashes(Folder::class) . "' DESC")
                ->latest()
                ->paginate(30, page: $dto->page);
        }
    }

    public function searchGalleryItems(
        string $search,
        ?int $folderId = null,
        int $page = 1
    ) {
        $words = collect(explode(' ', $search))
            ->map(fn($w) => trim($w))
            ->filter();

        return Folderable::query()
            ->when(
                $folderId,
                fn($q) =>
                $q->where('folder_id', $folderId)
            )

            ->where(function ($q) use ($words) {

                $q->where(function ($q) use ($words) {
                    $q->where('folderable_type', File::class)
                        ->whereHas('folderable', function ($q) use ($words) {
                            foreach ($words as $word) {
                                $q->where(function ($q) use ($word) {
                                    $q->where('name', 'like', "%{$word}%");
                                });
                            }
                        });
                })

                    ->orWhere(function ($q) use ($words) {
                        $q->where('folderable_type', Folder::class)
                            ->whereHas('folderable', function ($q) use ($words) {
                                foreach ($words as $word) {
                                    $q->where('name', 'like', "%{$word}%");
                                }
                            });
                    });
            })
            ->with('folderable')
            ->paginate(
                perPage: 30,
                page: $page
            );
    }

    public function getImage(GetImageDto $dto): ImageDto
    {
        $image = File::find($dto->id);

        if ($image === null)
            ImageException::imageNotFound();

        return ImageDto::from($image);
    }

    public function getImagesByIds(array $ids): Collection
    {
        return File::whereIn('id', $ids)->orderByRaw('FIELD(id, ' . implode(',', $ids) . ')')->get();
    }

    public function getFolder(GetFolderDto $folder): FolderDto
    {
        $folder = Folder::find($folder->id);

        if ($folder === null)
            FolderException::folderNotFound();

        return FolderDto::from($folder);
    }

    private function getNewImageUniqueName(string $name, ?int $folderId): string
    {
        $safeRegexName = preg_quote($name, '/');
        $count = File::where('name', 'REGEXP', "^($safeRegexName \(\d+\)|$safeRegexName)$")->where('folder_id', '=', $folderId)->count();
        if ($count > 0) {
            return $name . ' (' . ($count + 1) . ')';
        }
        return $name;
    }

    public function getImageRelations(int $id)
    {
        $image = File::find($id);

        $categories = $image->categories->pluck('name')->toArray();

        $products = $image->patterns()
            ->with('product')
            ->get()
            ->pluck('product')
            ->filter()
            ->unique('id')
            ->take(3)
            ->pluck('name')
            ->toArray();

        $slides = $image->slides()
            ->get()
            ->map(function ($slide) {
                return match ($slide->pivot->type) {
                    ImageTypeEnum::DESKTOP_SLIDE->value => 'اسلایدر دسکتاپ',
                    ImageTypeEnum::MOBILE_SLIDE->value => 'اسلایدر موبایل',
                    default => 'اسلایدر'
                };
            })
            ->unique()
            ->toArray();


        $relations = [];
        if (!empty($categories))
            $relations[] = 'دسته‌بندی‌ها: ' . implode(" - ", $categories);
        if (!empty($products))
            $relations[] = "محصولات: " . implode(" - ", $products);
        if (!empty($slides)) {
            $relations[] = 'اسلایدرها: ' . implode(' - ', $slides);
        }

        return implode("<br>", $relations);
    }

    public function getFolderWithContentCount(GetFolderDto $dto): FolderWithContentCountDto
    {
        $folder = Folder::find($dto->id);
        if ($folder === null)
            FolderException::folderNotFound();

        return FolderWithContentCountDto::from(
            FolderDto::from($folder),
            $folder->children()->count(),
            $folder->images()->count()
        );
    }

    public function deleteImage(DeleteImageDto $dto): ImageDto
    {
        $image = File::find($dto->id);
        if ($image === null)
            ImageException::imageNotFound();

        Fileable::where('file_id', $image->id)
            ->delete();

        Folderable::where('folderable_type', File::class)
            ->where('folderable_id', $image->id)
            ->delete();

        $image->delete();

        return ImageDto::from($image);
    }

    public function deleteFolder(DeleteFolderDto $dto): void
    {
        $folder = Folder::find($dto->id);
        if ($folder === null)
            FolderException::folderNotFound();

        $folder->deleteCompletely();
    }
}
