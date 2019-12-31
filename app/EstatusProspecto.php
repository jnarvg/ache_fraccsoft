<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstatusProspecto extends Model
{
    //
    protected $table = 'estatus_crm';
    protected $primaryKey = 'id_estatus_crm';
    public $timestamps = false;

    protected $fillable =[
        'estatus_crm',
        'nivel',

    ];

    protected $guarded =[
    ];
}
