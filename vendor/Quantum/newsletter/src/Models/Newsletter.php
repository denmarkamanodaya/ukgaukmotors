<?php

namespace Quantum\newsletter\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Newsletter extends Model
{
    use Sluggable;
    /**
 * The attributes that are fillable via mass assignment.
 *
 * @var array
 */
    protected $fillable = ['title', 'slug', 'summary', 'description', 'confirm_non_member', 'visible_in_lists',
        'allow_subscribers', 'news_code', 'email_from', 'email_from_name', 'newsletter_templates_id', 'featured_image',
        'autojoin_register', 'autojoin_start_responder', 'autojoin_send_welcome_email', 'multisite_sites', 'shot_template_id'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'newsletter';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('modelDatabase.newsletter'));
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(\Quantum\base\Models\Role::class, 'newsletter_roles');
    }

    public function noJoin()
    {
        return $this->belongsToMany(\Quantum\newsletter\Models\Newsletter::class, 'newsletter_no_join', 'newsletter_id', 'sub_id');
    }

    public function roleMove()
    {
        return $this->hasMany(\Quantum\newsletter\Models\NewsletterRoleMove::class);
    }

    public function subscribers()
    {
        return $this->hasMany(\Quantum\newsletter\Models\NewsletterSubscriber::class);
    }

    public function subscribersActive()
    {
        return $this->hasMany(\Quantum\newsletter\Models\NewsletterSubscriber::class)->where('email_confirmed', 1)->where('active', 1)->with('user');
    }

    public function mails()
    {
        return $this->hasMany(\Quantum\newsletter\Models\NewsletterMail::class);
    }

    public function responders()
    {
        return $this->hasMany(\Quantum\newsletter\Models\NewsletterMail::class)->where('message_type', 'responder')->orderBy('position', 'ASC');
    }

    public function sentMail()
    {
        return $this->hasMany(\Quantum\newsletter\Models\NewsletterSentMail::class);
    }

    public function pages()
    {
        return $this->hasMany(\Quantum\newsletter\Models\NewsletterPage::class);
    }

    public function import()
    {
        return $this->hasMany(\Quantum\newsletter\Models\NewsletterImport::class);
    }

    public function importQueue()
    {
        return $this->hasMany(\Quantum\newsletter\Models\NewsletterImportQueue::class);
    }

    public function subscriberCount()
    {
        return $this->hasOne(\Quantum\newsletter\Models\NewsletterSubscriber::class, 'newsletter_id')
            ->selectRaw('newsletter_id, count(*) as aggregate')
            ->groupBy('newsletter_id');

    }

    public function template()
    {
        return $this->belongsTo(\Quantum\newsletter\Models\NewsletterTemplates::class, 'newsletter_templates_id');
    }

    public function shotTemplate()
    {
        return $this->belongsTo(\Quantum\newsletter\Models\NewsletterTemplates::class, 'shot_template_id');
    }
}
