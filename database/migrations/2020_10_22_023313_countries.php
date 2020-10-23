<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use GuzzleHttp\Client;
use App\Country;

class Countries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function(Blueprint $table){
            $table->bigIncrements('id',1000);
            $table->string('name')->nullable();
            $table->string('name_es')->nullable();
            $table->string('alpha2code')->nullable();
            $table->string('alpha3code')->nullable();
            $table->string('calling_code')->nullable();
            $table->string('capital')->nullable();
            $table->string('region')->nullable();
            $table->string('lat')->nullable();
            $table->string('lan')->nullable();
            $table->string('demonym')->nullable();
            $table->string('timezones')->nullable();
            $table->string('currencie')->nullable();
            $table->string('symbol')->nullable();
            $table->string('flag')->nullable();
            $table->boolean('active')->default(0) ;
            $table->timestamps();
        });

        $url = 'https://restcountries.eu/rest/v2/all';
            $client = new \GuzzleHttp\Client([
                'headers' => [ 'Accept' => 'application/json' ]
            ]);
            $response = $client->request('GET', $url);

            $data =  json_decode($response->getBody());
            foreach($data as $country){
                $co = new Country();
                $co->name = $country->name;
                $co->name_es = ($country->translations->es==null) ? $country->name:$country->translations->es;
                $co->alpha2code = $country->alpha2Code;
                $co->alpha3code = $country->alpha3Code;
                $co->calling_code = (count($country->callingCodes)>0)?$country->callingCodes[0]:"";
                $co->capital = $country->capital;
                $co->region = $country->region;
                $co->lat = (count($country->latlng)>0)?$country->latlng[0]:"";
                $co->lan = (count($country->latlng)>0)?$country->latlng[1]:"";
                $co->demonym = $country->demonym;
                $co->timezones = (count($country->timezones)>0) ? $country->timezones[0]:"";
                $co->currencie = (count($country->currencies)>0) ? $country->currencies[0]->code:"";
                $co->symbol = (count($country->currencies)>0) ?$country->currencies[0]->symbol:"";
                $co->flag = $country->flag;
                $co->save();
            }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
