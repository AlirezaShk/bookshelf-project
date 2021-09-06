<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\InvalidISBNLengthException;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    // protected $fillable = [];

    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    static public function ISBNGenerator($length)
    {

        $digits = [];
        while(count($digits) !== ($length-1)) {
            array_push($digits, rand(0,9));
        }
        $sum = 0;
        if($length === 13) {
            for($i = 0; $i < count($digits); $i++) {
                $sum += $digits[$i] * (($i % 2)*2 + 1);
            }
            array_push($digits, $sum%10);
        } elseif($length === 10) {
            for($i = 0; $i < count($digits); $i++) {
                $sum += $digits[$i] * ($i+1);
            }
            array_push($digits, $sum%11);
        } else {
            throw new InvalidISBNLengthException;
        }

        return intval(implode('', $digits));
    }
}
