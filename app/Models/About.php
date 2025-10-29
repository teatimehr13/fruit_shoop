<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content', 'image'];
    protected $appends = ['img_url'];

    public function getImgUrlAttribute(){
        return $this->image ? '/storage/' . $this->image : $this->image;
    }
}
