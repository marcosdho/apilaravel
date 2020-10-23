<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use App\Player;
use App\Country;
use DB;
use Illuminate\Support\Facades\Log;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $teams = DB::table('teams');

        if(isset($request->id)){
            $teams->where('id',$request->id);
        }
        if(isset($request->name)){
            $teams->where('name',$request->name);
        }
        if(isset($request->league)){
            $teams->where('league',$request->league);
        }
        if(isset($request->country)){
            $teams->where('country',$request->country);
        }

        $data = $teams->get()->toArray();

        if(!empty($data)){
            return response()->json([
                'code'=>200,
                'type'=>'success',
                'data'=>$data
            ]);
        }else{
            return response()->json([
                'code'=>400,
                'type'=>'error',
                'message'=>'no teams created'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $name = $request->name??false;
        $league = $request->league??false;
        $country = $request->country??false;

        if(!$name){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing team name']);
        }
        if(!$league){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing leage name']);
        }
        if(!$country){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing country id']);
        }
        if(!is_numeric($country)){
            return response()->json(['code'=>400,'type'=>'error','message'=>'incorrect country id format']);
        }
        if(!Country::find($country)){
            return response()->json(['code'=>400,'type'=>'error','message'=>'country id not exists']);
        }
        if(Team::where('name',$name)->exists()){
            return response()->json(['code'=>400,'type'=>'error','message'=>'team '.$name.' exist']);
        }

        $team = new Team();
        $team->name = $name;
        $team->league = $league;
        $team->country = $country;
        if($team->save()){
            return response()->json([
                'code'=>200,
                'type'=>'success',
                'data'=>Team::find($team->id)
            ]);
        }else{
            return response()->json(['code'=>400,'type'=>'error','message'=>'team update or insert fail']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $team = Team::where('id',$id)->get()->toArray();
        if(count($team)==1){
            return response()->json(['code'=>200,'type'=>'success','data'=>$team[0]]);
        }else{
            return response()->json(['code'=>400,'type'=>'error','message'=>'team id not exist']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $name = $request->name??false;
        $league = $request->league??false;
        $country = $request->country??false;

        if(!$name){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing team name']);
        }
        if(!$league){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing leage name']);
        }
        if(!$country){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing country id']);
        }
        if(!is_numeric($country)){
            return response()->json(['code'=>400,'type'=>'error','message'=>'incorrect country id format']);
        }
        if(!Country::find($country)){
            return response()->json(['code'=>400,'type'=>'error','message'=>'country id not exists']);
        }

        $team = Team::find($id);

        if($team){
            Team::where('id',$id)->update([
                'name'=>$name,
                'league'=>$league,
                'country'=>$country
            ]);
            return response()->json(['code'=>200,'type'=>'success','data'=>Team::find($id)]);
        }else{
            return response()->json(['code'=>400,'type'=>'error','message'=>'team id not exist']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $team = Team::find($id);
        $playerAsociated = Player::where('team_id',$id)->get();
        if($team){

            if(count($playerAsociated)>0){
                return response()->json(['code'=>400,'type'=>'error','message'=>'cannot be removed because it has players associated with the team '.$team->name]);
                exit;
            }

            Team::where('id',$id)->delete();

            return response()->json(['code'=>200,'type'=>'success','message'=>' Team '.$team->name.' deleted']);
        }else{
            return response()->json(['code'=>400,'type'=>'error','message'=>'team id not exist']);
        }
    }
}
