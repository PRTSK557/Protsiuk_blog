<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Carbon\Carbon;
use App\Jobs\ProcessVideoJob;
use App\Jobs\GenerateCatalog\GenerateCatalogMainJob;

class DiggingDeeperController extends Controller
{
    /**
     * Відправляє у чергу завдання на обробку відео
     */
    public function processVideo()
    {
        ProcessVideoJob::dispatch();
        // Можна додати затримку або іншу чергу:
        // ->delay(10)
        // ->onQueue('name_of_queue')
    }

    /**
     * Запускає основне завдання генерації каталогу у чергу
     *
     * @link http://localhost:8000/digging_deeper/prepare-catalog
     *
     * Команда для запуску воркера:
     * php artisan queue:listen --queue=generate-catalog --tries=3 --delay=10
     */
    public function prepareCatalog()
    {
        GenerateCatalogMainJob::dispatch();
    }

    /**
     * Демонструє роботу з колекціями Laravel
     */
    public function collections()
    {
        $result = [];

        // Отримуємо всі пости, включно з видаленими (soft deleted)
        $eloquentCollection = BlogPost::withTrashed()->get();

        // Перетворюємо у колекцію Laravel
        $collection = collect($eloquentCollection->toArray());

        // Перший і останній елемент
        $result['first'] = $collection->first();
        $result['last'] = $collection->last();

        // Фільтрація по category_id = 10, з ключами по id
        $result['where']['data'] = $collection
            ->where('category_id', 10)
            ->values()
            ->keyBy('id');

        $result['where']['count'] = $result['where']['data']->count();
        $result['where']['isEmpty'] = $result['where']['data']->isEmpty();
        $result['where']['isNotEmpty'] = $result['where']['data']->isNotEmpty();

        // Перший елемент, у якого created_at пізніше ніж '2020-02-24 03:46:16'
        $result['where_first'] = $collection
            ->firstWhere('created_at', '>', '2020-02-24 03:46:16');

        // Перетворюємо колекцію у нові об'єкти з потрібними полями
        $result['map']['all'] = $collection->map(function ($item) {
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists = is_null($item['deleted_at']);
            return $newItem;
        });

        // Відфільтровуємо ті, що не існують (deleted)
        $result['map']['not_exists'] = $result['map']['all']
            ->where('exists', false)
            ->values()
            ->keyBy('item_id');

        // Трансформуємо оригінальну колекцію - додаємо Carbon у created_at
        $collection->transform(function ($item) {
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists = is_null($item['deleted_at']);
            $newItem->created_at = Carbon::parse($item['created_at']);
            return $newItem;
        });

        // Створюємо нові об'єкти з created_at для додавання у колекцію
        $newItem = new \stdClass;
        $newItem->id = 9999;
        $newItem->created_at = Carbon::now();

        $newItem2 = new \stdClass;
        $newItem2->id = 8888;
        $newItem2->created_at = Carbon::now();

        // Додаємо елементи у початок та кінець колекції
        $newItemFirst = $collection->prepend($newItem)->first();
        $newItemLast = $collection->push($newItem2)->last();

        // Витягуємо елемент за індексом 1 (видаляємо з колекції)
        $pulledItem = $collection->pull(1);

        // Фільтруємо колекцію — лише ті, у яких created_at є у п'ятницю 11-го числа
        $filtered = $collection->filter(function ($item) {
            if (!property_exists($item, 'created_at') || !($item->created_at instanceof Carbon)) {
                return false;
            }
            return $item->created_at->isFriday() && $item->created_at->day == 11;
        });

        // Сортування
        $sortedSimpleCollection = collect([5, 3, 1, 2, 4])->sort()->values();
        $sortedAscCollection = $collection->sortBy('created_at');
        $sortedDescCollection = $collection->sortByDesc('item_id');

        // Виводимо результат у дамп (для дебагу)
        dd(compact('filtered', 'sortedSimpleCollection', 'sortedAscCollection', 'sortedDescCollection'));
    }
}
