<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Carbon\Carbon;

class DiggingDeeperController extends Controller
{
    /**
     * Базова інформація
     * @url https://laravel.com/docs/11.x/collections#introduction
     *
     * Довідкова інформація
     * @url https://laravel.com/api/11.x/Illuminate/Support/Collection.html
     *
     * Варіант колеції для моделі eloquent
     * @url https://laravel.com/api/11.x/Illuminate/Database/Eloquent/Collection.html
     *
     */

    public function collections()
    {
        $result = [];

        /**
         * @var \Illuminate\Database\Eloquent\Collection $eloquentCollection
         */
        $eloquentCollection = BlogPost::withTrashed()->get();

        /**
         * @var \Illuminate\Support\Collection $collection
         */
        $collection = collect($eloquentCollection->toArray());

        $result['first'] = $collection->first();
        $result['last'] = $collection->last();

        $result['where']['data'] = $collection
            ->where('category_id', 10)
            ->values()
            ->keyBy('id');

        $result['where']['count'] = $result['where']['data']->count();
        $result['where']['isEmpty'] = $result['where']['data']->isEmpty();
        $result['where']['isNotEmpty'] = $result['where']['data']->isNotEmpty();

        $result['where_first'] = $collection
            ->firstWhere('created_at', '>' , '2020-02-24 03:46:16');

        $result['map']['all'] = $collection->map(function ($item) {
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists = is_null($item['deleted_at']);

            return $newItem;
        });

        $result['map']['not_exists'] = $result['map']['all']
            ->where('exists', '=', false)
            ->values()
            ->keyBy('item_id');

        // Трансформуємо колекцію, додаємо Carbon в created_at
        $collection->transform(function ($item) {
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists = is_null($item['deleted_at']);
            $newItem->created_at = Carbon::parse($item['created_at']);

            return $newItem;
        });

        // Створюємо нові елементи з властивістю created_at, щоб уникнути помилок
        $newItem = new \stdClass;
        $newItem->id = 9999;
        $newItem->created_at = Carbon::now();

        $newItem2 = new \stdClass;
        $newItem2->id = 8888;
        $newItem2->created_at = Carbon::now();

        // Додаємо елементи в колекцію
        $newItemFirst = $collection->prepend($newItem)->first();
        $newItemLast = $collection->push($newItem2)->last();
        $pulledItem = $collection->pull(1);

        // Фільтруємо лише ті, у яких є valid created_at (Carbon)
        $filtered = $collection->filter(function ($item) {
            if (!property_exists($item, 'created_at') || !($item->created_at instanceof Carbon)) {
                return false;
            }

            return $item->created_at->isFriday() && $item->created_at->day == 11;
        });

        $sortedSimpleCollection = collect([5, 3, 1, 2, 4])->sort()->values();
        $sortedAscCollection = $collection->sortBy('created_at');
        $sortedDescCollection = $collection->sortByDesc('item_id');

         dd(compact('filtered', 'sortedSimpleCollection', 'sortedAscCollection', 'sortedDescCollection'));
    }
}
