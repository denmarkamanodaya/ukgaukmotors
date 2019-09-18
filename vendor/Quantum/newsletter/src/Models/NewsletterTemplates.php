<?php

namespace Quantum\newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class NewsletterTemplates extends Model
{
    use Sluggable;
    /**
 * The attributes that are fillable via mass assignment.
 *
 * @var array
 */
    protected $fillable = ['title', 'slug', 'content', 'template_type'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'newsletter_template';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('modelDatabase.newsletterTemplates'));
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function newsletters()
    {
        return $this->hasMany(\Quantum\newsletter\Models\Newsletter::class);
    }
}
