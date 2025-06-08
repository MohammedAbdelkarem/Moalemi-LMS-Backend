<?php

namespace App\Services\Favorite;

use App\Models\Doctor;
use App\Models\Article;

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


        return getOrPaginate(
            auth()->user()->favorites()
                    ->where('favoritable_type' , $class),
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
