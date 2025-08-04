<?php

namespace App\Services\Favorite;

use App\Models\Doctor;
use App\Models\Article;
use App\Models\Favorite;

/**
 * Class FavoriteService.
 */
class FavoriteService
{
    public function setAsFavorite($context_id , $type)
    {
        $class = $this->classtype($type);

        auth()->user()->favorites()->firstOrCreate([
            'favoritable_id' => $context_id,
            'favoritable_type' => $class,
        ]);
    }

    public function setAsUnFavorite($context_id , $type)
    {
        $class = $this->classtype($type);

        auth()->user()->favorites()
                        ->where('favoritable_type' , $class)
                        ->where('favoritable_id' , $context_id)
                        ->delete();
    }

    public function getByType($data)
    {
        $class = $this->classType($data['type']);

        $favIds = Favorite::where('user_id' , auth()->id())
                ->where('favoritable_type' , $class)
                ->pluck('favoritable_id')
                ->toArray();

                // dd($favIds);
        if($data['type'] == 'doctor')
            $items = Doctor::whereIn('id' , $favIds)
                    ->with(['subCategories.category' , 'shifts' , 'user']);
        else
            $items = Article::whereIn('id' , $favIds);
        
        return getOrPaginate(
            $items,
            $data
        );
    }

    private function classType($type)
    {
        return ($type == 'doctor') 
                ? Doctor::class
                : Article::class;
    }
}
