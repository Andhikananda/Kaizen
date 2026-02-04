<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
  private $repo;

  public function __construct(
    CategoryRepository $categoryRespository
  ) {
    $this->repo = $categoryRespository;
  }

  public function getAll(array $fields)
  {
    return $this->repo->getAll($fields);
  }

  public function getById(int $id, array $fields)
  {
    return $this->repo->getById($id, $fields ?? ['*']);
  }

  public function create(array $data)
  {
    if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
      $data['photo'] = $this->uploadPhoto($data['photo']);
    }

    return $this->repo->create($data);
  }

  public function update(int $id, array $data)
  {
    $fields = ['id', 'photo'];
    $category = $this->repo->getById($id, $fields);

    if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
      if (!empty($category->photo)) {
        $this->deletePhoto($category->photo);
      }
      $data['photo'] = $this->uploadPhoto($data['photo']);
    }

    return $this->repo->update($id, $data);
  }

  public function delete(int $id)
  {
    $fields = ['id', 'photo'];
    $category = $this->repo->getById($id, $fields);

    if ($category->photo) {
      $this->deletePhoto($category->photo);
    }

    $this->repo->delete($id);
  }

  // PRIVATE

  private function uploadPhoto(UploadedFile $photo)
  {
    return $photo->store('categories', 'public');
  }

  private function deletePhoto(string $photoPath)
  {
    $relativePath = 'categories/' . basename($photoPath);
    if (Storage::disk('public')->exists($relativePath)) {
      Storage::disk('public')->delete($relativePath);
    }
  }
}
