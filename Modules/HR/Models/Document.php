<?php

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'hr_documents';
    protected $fillable = ['type','file_path','issued_at','expires_at','number'];
    protected $casts = ['issued_at'=>'date','expires_at'=>'date'];
    public function documentable(){ return $this->morphTo(); }
}
