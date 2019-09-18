<?php namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Postcode
 * @package App\Models
 */
class Postcode extends Model {

    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['postcode', 'longitude', 'latitude'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'postcodes';
    protected $connection = 'mysqlPostCode';

    /**
     * Disable timestamps
     * @var bool
     */
    public $timestamps = false;

    /**
     * Search for the postcode
     * @param $query
     * @param $postcode
     * @return mixed
     */
    public function scopePostcode($query, $postcode)
    {
        $postcode = strtoupper(str_replace(' ', '', $postcode));
        return $query->where('postcode', '=', $postcode);
    }

}
