<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgBlog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgBlog\Models\Article;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];


    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_tag');
    }

    public function __toString()
    {
        return $this->id;
    }

}
