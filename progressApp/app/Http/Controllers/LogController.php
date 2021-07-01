<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Log;
use \App\Models\Activity;
use \App\Models\BodyParameter;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $activities = Activity::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();
        $bodyParameters = BodyParameter::all()->where('user_id', $user->id);
        $scope = new \Illuminate\Database\Eloquent\Collection;
        foreach ($activities as $activity) {
            $activityLogs = Log::orderBy('created_at', 'desc')->where('activity_id', $activity->activity_id)->get();
            foreach ($activityLogs as $activityLog)
                $scope->add($activityLog);
        }
        foreach ($bodyParameters as $bodyParameter) {
            $bodyParameterLogs = Log::orderBy('created_at', 'desc')->where('body_parameter_id', $bodyParameter->body_parameter_id)->get();
            foreach ($bodyParameterLogs as $bodyParameterLog)
                $scope->add($bodyParameterLog);
        }
        return $scope;
    }

    public function getBodyParamLog()
    {
        $user = auth()->user();

        $bodyParameters = DB::table('log')
            ->rightjoin('body_parameters', 'log.body_parameter_id', '=', 'body_parameters.body_parameter_id')
            ->select('log.log_id','log.value','body_parameters.body_parameter_name','log.created_at','body_parameters.body_parameter_id','body_parameters.unit')
            ->where('body_parameters.user_id', $user->id)
            ->orderByRaw('log.created_at DESC')
            ->get();
        return $bodyParameters;
    }
    public function getActvityLog()
    {
        $user = auth()->user();
        $activityLog = DB::table('log')
            ->rightjoin('activities','log.activity_id','=','activities.activity_id')
            ->select('log.log_id','log.value','log.created_at','activities.activity_name','activities.activity_id','activities.unit')
            ->where('activities.user_id',$user->id)
            ->orderBy('log.created_at','desc')
            ->get();
        return $activityLog;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required',
            'activity_id' => 'nullable|this_or_that:body_parameter_id',
            'body_parameter_id' => 'nullable'
        ]);
        $canStore = false;
        if ($request->activity_id) {
            $scope = Activity::all()->where('user_id', auth()->user()->id);
            foreach ($scope as $activity) {
                if ($activity->activity_id == $request->activity_id)
                    $canStore = true;
            }
        }
        if ($request->body_parameter_id) {
            $scope = BodyParameter::all()->where('user_id', auth()->user()->id);
            foreach ($scope as $bodyParameter) {
                if ($bodyParameter->body_parameter_id == $request->body_parameter_id)
                    $canStore = true;
            }
        }
        if ($canStore == false)
            return response(['message' => 'Can\'t create log - user doesn\'t own this activity or body parameter.'], 403); // error
        return Log::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $scope = $this->index();
        return $scope->find($id);
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
        if ($request->activity_id || $request->body_parameter_id)
            return response(['message' => 'Can\'t change foreign key.'], 403); // error
        $scope = $this->index();
        $log = $scope->find($id);
        if (!$log)
            return response(['message' => 'Can\'t update log - user doesn\'t own this log.'], 404); // error
        $log->update($request->all());
        return $log;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $scope = $this->index();
        $log = $scope->find($id);
        if (!$log)
            return response(['message' => 'Can\'t delete log - user doesn\'t own this log.'], 404); // error
        return $log->delete();
    }
}
