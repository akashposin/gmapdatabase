<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class location extends Model
{
    protected $table = 'location';

    function insert($name,$description,$address,$latitude,$longitude,$image)
    {
        $data = array(
            'name' => $name,
            'description' => $description,
            'address' => $address,
            'lat' => $latitude,
            'lng' => $longitude,
            'image' => $image
        );
        $j = DB::table('location')->insertGetId($data);
        return $j;
    }

    function delete_row($id)
    {
        $d = DB::table('location')->where('id',$id)->delete();
        return $d;
    }

}
