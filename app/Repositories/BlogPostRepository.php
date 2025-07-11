<?php

namespace App\Repositories;

use App\Models\BlogPost as Model;

/**
 * Class BlogPostRepository.
 */
class BlogPostRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Отримати список статей з пагінацією
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllWithPaginate()
    {
        $columns = ['id', 'title', 'slug', 'is_published', 'published_at', 'user_id', 'category_id'];

        $result = $this->startConditions()
            ->select($columns)
            ->orderBy('id', 'DESC')
            ->with([
                'category' => function ($query) {
                    $query->select(['id', 'title']);
                },
                'user:id,name',
            ])
            ->paginate(25);

        return $result;
    }

    /**
     * Отримати модель для редагування в адмінці
     * @param int $id
     * @return Model|null
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }
}
