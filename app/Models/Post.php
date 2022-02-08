<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Elasticquent\ElasticquentTrait;

class Post extends Model
{
    use HasFactory;
    use ElasticquentTrait;

    protected $fillable = [
        'title', 'slug', 'body', 'published', 'user_id'
    ];

    protected $mappingProperties = [
        'title' => [
          'type' => 'text',
          'analyzer' => 'standard',
        ],
        'body' => [
          'type' => 'text',
          'analyzer' => 'standard',
        ],
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeUnpublished($query)
    {
        return $query->where('published', false);
    }
}
