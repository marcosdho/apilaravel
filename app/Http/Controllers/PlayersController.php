<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserApi;
use App\Player;
use App\Country;
use App\Team;
use DB;

class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $player = DB::table('players');

        if(isset($request->id)){
            $player->where('id',$request->id);
        }
        if(isset($request->name)){
            $player->where('name',$request->name);
        }
        if(isset($request->team_id)){
            $player->where('team_id',$request->team_id);
        }
        if(isset($request->age)){
            $player->where('age',$request->age);
        }
        if(isset($request->squad_number)){
            $player->where('squad_number',$request->squad_number);
        }
        if(isset($request->position)){
            $player->where('position',$request->position);
        }
        if(isset($request->nationality)){
            $player->where('nationality',$request->nationality);
        }

        $data = $player->get()->toArray();

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
                'message'=>'no search with that parameter was found'
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
        $age          = $request->age??false;
        $name         = $request->name??false;
        $team_id      = $request->team_id??false;
        $squad_number = $request->squad_number??false;
        $position     = $request->position??false;
        $nationality  = $request->nationality??false;

        if(!$name){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing player name']);
        }
        if(!$age || !is_numeric($age)){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing player age or incorrect format']);
        }
        if(!$team_id || !is_numeric($team_id)){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing player team id or incorrect format']);
        }
        if(!Team::find($team_id)){
            return response()->json(['code'=>400,'type'=>'error','message'=>'team id not exists']);
        }
        if(!$squad_number || !is_numeric($squad_number)){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing player squad number or incorrect format']);
        }
        if(!$position){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing player position']);
        }
        if(!$nationality){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing player nationality']);
        }
        if(Player::where('team_id',$team_id)->where('squad_number',$squad_number)->exists()){
            return response()->json(['code'=>400,'type'=>'error','message'=>'duplicate squad number','actual_user'=>Player::where('team_id',$team_id)->where('squad_number',$squad_number)->get()->toArray()]);
        }


        $player = new Player();
        $player->name         = $name;
        $player->age          = $age;
        $player->team_id      = $team_id;
        $player->squad_number = $squad_number;
        $player->position     = $position;
        $player->nationality  = $nationality;

        if($player->save()){
            return response()->json([
                'code'=>200,
                'type'=>'success',
                'data'=>$player
            ]);
        }else{
            return response()->json([
                'code'=>400,
                'type'=>'error',
                'message'=>'Error on create new player'
            ]);
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
        //
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
        $name         = $request->name??false;
        $age          = $request->age??false;
        $team_id      = $request->team_id??false;
        $squad_number = $request->squad_number??false;
        $position     = $request->position??false;
        $nationality  = $request->nationality??false;

        if(!$name){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing player name']);
        }
        if(!$age){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing player age']);
        }
        if(!$team_id){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing player team_id']);
        }
        if(!$squad_number){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing player squad_number']);
        }
        if(!$position){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing player position']);
        }
        if(!$nationality){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing player nationality']);
        }

        $player = Player::find($id);

        if($player){

            Player::where('id',$id)->update([
                'name'         => $name,
                'age'          => $age,
                'team_id'      => $team_id,
                'squad_number' => $squad_number,
                'position'     => $position,
                'nationality'  => $nationality,

            ]);
            return response()->json(['code'=>'200','type'=>'success','data'=>$request->all()]);
        }else{
            return response()->json(['code'=>'400','type'=>'success','message'=>'player id not exist']);

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
        if(is_null($id)){
            return response()->json(['code'=>400,'type'=>'error','message'=>'missing id']);
        }
        $player = Player::find($id);
        if($player){

            Player::where('id',$id)->delete();

            return response()->json(['code'=>200,'type'=>'success','message'=>' player '.$player->name.' deleted']);
        }else{
            return response()->json(['code'=>400,'type'=>'error','message'=>'player id not exist']);
        }
    }
}
