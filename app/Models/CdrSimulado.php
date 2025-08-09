<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CdrSimulado extends Model
{
    use HasFactory;
    
    protected $table = 'cdr_simulado';
    
    // CDR no usa timestamps automáticos de Laravel
    public $timestamps = false;
    
    protected $fillable = [
        'calldate', 'clid', 'src', 'dst', 'dcontext', 'channel', 'dstchannel',
        'lastapp', 'lastdata', 'duration', 'billsec', 'disposition', 'amaflags',
        'accountcode', 'uniqueid', 'userfield', 'did', 'recordingfile', 'cnum',
        'cnam', 'outbound_cnum', 'outbound_cnam', 'dst_cnam', 'linkedid',
        'peeraccount', 'sequence'
    ];
    
    protected $casts = [
        'calldate' => 'datetime',
        'duration' => 'integer',
        'billsec' => 'integer',
        'amaflags' => 'integer',
        'sequence' => 'integer',
    ];
}
