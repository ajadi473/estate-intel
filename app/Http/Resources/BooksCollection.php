<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BooksCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [ 
            $this->collection->map(function($data) {
                return [
                    'name' => $data['name'],
                    'isbn' => $data['isbn'],
                    'authors' => [
                        $data['authors'],
                    ],
                    'number_of_pages' => $data['numberOfPages'],
                    'publisher' => $data['publisher'],
                    'country' => $data['country'],
                    'release_date' => $data['released'],

                    
                ];
            })
        ];
    }
}
