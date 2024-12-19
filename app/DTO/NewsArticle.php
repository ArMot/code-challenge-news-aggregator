<?php

namespace App\DTO;

use Carbon\Carbon;

class NewsArticle
{
    public string $title;
    public ?string $author;
    public ?string $description;
    public ?string $content;
    public string $url;
    public string $source;
    public string $category;
    public Carbon $published_at;
    public ?string $image_url;

    public function __construct(
        string $title,
        ?string $author,
        ?string $description,
        ?string $content,
        string $url,
        string $source,
        string $category,
        Carbon $published_at,
        ?string $image_url = null
    ) {
        $this->title = $title;
        $this->author = $author;
        $this->description = $description;
        $this->content = $content;
        $this->url = $url;
        $this->source = $source;
        $this->category = $category;
        $this->published_at = $published_at;
        $this->image_url = $image_url;
    }
    /**
     * @param array<int,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'],
            $data['author'] ?? null,
            $data['description'] ?? null,
            $data['content'] ?? null,
            $data['url'],
            $data['source'],
            $data['category'],
            new Carbon($data['published_at']),
            $data['image_url'] ?? null
        );
    }
}
