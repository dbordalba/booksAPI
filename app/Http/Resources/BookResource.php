<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'author' => $this->author,
            'title' => $this->title,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'info' => $this->getBooksInfo(urlencode($this->author),urlencode($this->title))
        ];
    }

    function getBooksInfo($author, $title)
    {

        $info = [];
        $httpClient = new \GuzzleHttp\Client();
        $request =
            $httpClient
                ->get("https://www.googleapis.com/books/v1/volumes?q=intitle:\"{$title}\",inauthor:\"{$author}\"&fields=items(volumeInfo(title,authors,description,publisher,publishedDate,imageLinks(smallThumbnail)))&maxResults=1");

        $response = json_decode($request->getBody()->getContents());

    
        return $response;
    }



}
