<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;

	public $table = "authors";
    
	protected $dates = ['deleted_at'];


	public $fillable = [
	    "objectId",
	    "organization_name",
	    "organization_name_mm",
	    "objectId",
		"authorImg",
		"authorInfoEng",
		"authorInfoMM",
		"authorName",
		"authorNameMM",
		"authorTitleEng",
		"authorTitleMM"
	];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "objectId" => "string",
		"organization_name" => "string",
		"organization_name_mm" => "string",
		"authorImg" => "string",
		"authorInfoEng" => "string",
		"authorInfoMM" => "string",
		"authorName" => "string",
		"authorNameMM" => "string",
		"authorTitleEng" => "string",
		"authorTitleMM" => "string"
    ];

	public static $rules = [
	    
	];

}
