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

    protected $casts = [
        'langs' => 'array',
    ];

    protected $guarded = [];

    /**
    * A constant to specify what filters does the search tool need.
    * Array, consisting of the code (value) and label (label) of each
    * Book search parameter based on their attributes.
    **/

    public const SEARCH_FILTERS = [
        [
            'code' => 'f',
            'label' => 'Freeword',
        ],
        [
            'code' => 'id',
            'label' => 'ID',
        ],
        [
            'code' => 'l',
            'label' => 'Languages'
        ],
        [
            'code' => 'ud',
            'label' => 'Last Update Date'
        ],
        [
            'code' => 'cd',
            'label' => 'Create Date'
        ],
        [
            'code' => 't',
            'label' => 'Title'
        ],
        [
            'code' => 'g',
            'label' => 'Genre'
        ],
        [
            'code' => 'i',
            'label' => 'ISBN'
        ],
        [
            'code' => 'au',
            'label' => 'Author\'s Name'
        ],
        [
            'code' => 'ai',
            'label' => 'Author\'s Id'
        ],
        [
            'code' => 'rd',
            'label' => 'Release Date'
        ],
    ];

    /**
    * @return Collection 
    * Returns the Author that owns this Book. Related by a 
    * FOREIGN KEY;
    **/

    public function author()
    {
        return $this->belongsTo(Author::class);
    }


    /**
    * Book ISBN code generator.
    * 
    * @param int $length
    * Either 10 or 13,
    * 
    * @return string 
    * Returns a valid ISBN-10 or ISBN-13 code
    * 
    * @throws InvalidISBNLengthException
    * Incase provided $length is neither 10 nor 13
    **/

    static public function ISBNGenerator(int $length)
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
            if($sum%10 === 0)
                array_push($digits, $sum%10);
            else
                array_push($digits, 10-$sum%10);
        } elseif($length === 10) {
            for($i = 0; $i < count($digits); $i++) {
                $sum += $digits[$i] * ($i+1);
            }
            array_push($digits, 
                $sum%11 === 10 ? 'X' : $sum%11
            );
        } else {
            throw new InvalidISBNLengthException;
        }

        return implode('', $digits);
    }
}
