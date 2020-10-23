<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Team extends Model
{
    //
    protected $table = 'teams';

    protected $appends = ['country_data','date_formatted'];

    public function getCountryDataAttribute(){

        $pais = DB::table('countries')->where('id', DB::table('teams')->where('id',$this->id)->select('country')->first()->country)->get();
        return $pais;
    }

    public function getDateFormattedAttribute(){
        return date("d-m-Y H:i a", strtotime( DB::table('teams')->where('id',$this->id)->select('created_at')->first()->created_at ) );
    }
}
