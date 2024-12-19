<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgBlog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Modules\PkgBlog\Models\Category;
use Modules\PkgBlog\Models\Tag;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content', 'category_id', 'user_id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    public function __toString()
    {
        return $this->id;
    }

}
