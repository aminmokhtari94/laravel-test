<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class MongoAudit extends Model implements \OwenIt\Auditing\Contracts\Audit
{
    use \OwenIt\Auditing\Audit;


//    /**
//     * Get the table associated with the model.
//     *
//     * @return string
//     */
//    public function getTable(){
//        return Audit::get
//    }
//
    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'old_values'   => 'json',
        'new_values'   => 'json',
        'auditable_id' => 'integer',
    ];

    /**
     * {@inheritdoc}
     */
    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * {@inheritdoc}
     */
    public function user()
    {
        return $this->morphTo();
    }
}
