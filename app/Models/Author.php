<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $table = 'authors';

    //protected $fillable = [];

    protected $casts = [
        'langs' => 'array',
    ];

    protected $guarded = [];

    /**
    * A constant to specify what filters does the search tool need.
    * Array, consisting of the code (value) and label (label) of each
    * Author search parameter based on their attributes.
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
            'code' => 'n',
            'label' => 'Name'
        ],
        [
            'code' => 'o',
            'label' => 'Origin'
        ],
        [
            'code' => 'b',
            'label' => 'Birth Date'
        ],
        [
            'code' => 'd',
            'label' => 'Obit'
        ]
    ];

    /**
    * @return Collection 
    * Returns the Books that belong to this Author. Related by a 
    * FOREIGN KEY;
    **/

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    /**
    * @return string 
    * Returns the full name of an attribute from First and Last name of the Author
    **/
    
    public function getNameAttribute()
    {
        return $this->fname . ' ' . $this->lname;
    }
}
