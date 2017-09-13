<?php

namespace Soda\Reports\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Models\Field;
use Soda\Cms\Database\Models\Traits\OptionallyBoundToApplication;

class Report extends Model
{
    use OptionallyBoundToApplication;

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

    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(
            config('laratrust.role'),
            'report_role',
            'report_id',
            config('laratrust.role_foreign_key')
        );
    }

    public function scopeOrdered($q)
    {
        foreach (config('soda.reports.order') as $order => $dir) {
            $q->orderBy($order, $dir);
        }

        return $q;
    }

    public function scopePermitted($q)
    {
        return $q->whereIn('id', function ($sq) {
            $rolesTable = $this->roles()->getTable();
            $userRoles = Auth::user()->roles->pluck('id');

            $sq->select('report_id')
                ->from($rolesTable)
                ->whereNull('role_id')
                ->orWhereIn('role_id', $userRoles);
        });
    }
}
