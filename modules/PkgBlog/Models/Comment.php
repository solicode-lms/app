<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgBlog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Modules\PkgBlog\Models\Article;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'user_id', 'article_id'];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function __toString()
    {
        return $this->id;
    }

}
