<?php

namespace App\Repositories;

use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BlogCategoryRepository.
 */
class BlogCategoryRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class; //абстрагування моделі BlogCategory
    }

    /**
     * Отримати модель для редагування в адмінці
     * @param int $id
     * @return Model
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     * Отримати список категорій для виводу в випадаючий список
     * @return Collection
     */
    public function getForComboBox()
    {
        // $result = $this->startConditions()->all(); //старий варіант

        $columns = implode(', ', [
            'id',
            'CONCAT(id, ". ", title) AS id_title',  //додаємо поле id_title
        ]);

        $result = $this
            ->startConditions()
            ->selectRaw($columns)
            ->toBase()  //не отримуємо повну колекцію моделей, а простий набір даних
            ->get();

        return $result;
    }

    /**
     * Отримати категорії з пагінацією
     * @param int|null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllWithPaginate($perPage = null)
    {
        $columns = ['id', 'title', 'parent_id'];

        $result = $this
            ->startConditions()
            ->select($columns)
            ->paginate($perPage);

        return $result;
    }
}
