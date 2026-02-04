<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductSevice
{

  private ProductRepository $productRepository;

  public function __construct(ProductRepository $productRepository)
  {
    $this->productRepository = $productRepository;
  }

  // *
  public function getAll(array $fields)
  {
    return $this->productRepository->getAll($fields);
  }

  // *
  public function getById(int $id, array $fields)
  {
    return $this->productRepository->getById($id, $fields ?? ['*']);
  }

  public function create(array $data)
  {
    // Hapus SKU jika user iseng mengirimkannya
    unset($data['sku']);

    // Handle Upload
    if (isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile) {
      $data['thumbnail'] = $this->uploadPhoto($data['thumbnail']);
    }

    // Panggil repo yang sudah kita pasangi logika SKU di atas
    return $this->productRepository->create($data);
  }

  // PRIVATE

  private function uploadPhoto(UploadedFile $photo)
  {
    return $photo->store('products', 'public');
  }
}
