<?php

namespace Soda\Reports\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Models\Field;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class Report extends Model
{
    use OptionallyInApplicationTrait;

    protected $table = 'reports';

    protected $fillable = [
        'name',
        'description',
        'application_id',
        'class',
        'position',
        'times_ran',
        'last_run_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'last_run_at',
    ];

    public function fields()
    {
        return $this->morphToMany(Field::class, 'fieldable')->withPivot('position')->orderBy('pivot_position', 'asc');
    }

    public function scopeOrdered($q)
    {
        foreach (config('soda.reports.order') as $order => $dir) {
            $q->orderBy($order, $dir);
        }

        return $q;
    }
}
