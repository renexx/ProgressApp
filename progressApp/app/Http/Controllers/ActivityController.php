<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Activity;
use Illuminate\Http\Resources\Json\ResourceCollection;
use \App\Http\Resources\Activity as ActivityResource;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $activity = Activity::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();
        return ActivityResource::collection($activity);
    }
    private function scope()
    {
        $user = auth()->user();
        return $activity = Activity::all()->where('user_id', $user->id);
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
            'activity_name' => 'required',
            'user_id' => 'required',

        ]);

        if ($request->user_id != auth()->user()->id)
            return response(['message' => 'Can\'t create activity for another user.'], 401); // error
        $scope = $this->index();
        foreach ($scope as $activity) {
            if ($activity->activity_name == $request->activity_name)
                return response(['message' => 'Activity with this name already exists.'], 400); // error
        }
        // return Activity::create($request->all());
        $activity = new Activity;
        $activity->activity_id = $request->activity_id;
        $activity->activity_name = $request->activity_name;
        $activity->user_id = $request->user_id;
        if ($request->unit == 'km')  $activity->unit = 'km';
        else if ($request->unit == 'kg') $activity->unit = 'kg';
        else if ($request->unit == 'm') $activity->unit = 'm';
        else if ($request->unit == null) $activity->unit = null;
        else return response(['message' => 'Bad unit, pls enter a valid unit or null.'], 400); // error

        $activity->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $activity = $this->scope();
        return $activity = $activity->find($id);
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
        if ($request->user_id)
            return response(['message' => 'Can\'t change foreign key.'], 403); // error
       
        $activity = $this->scope();
        $activity = $activity->find($id);
        if (!$activity)
            return response(['message' => 'User doesn\'t own this activity.'], 401); // error
        if ($request->unit == 'km')  $activity->unit = 'km';
        else if ($request->unit == 'kg') $activity->unit = 'kg';
        else if ($request->unit == 'm') $activity->unit = 'm';
        else if ($request->unit == null) $activity->unit = null;
        else return response(['message' => 'Bad unit, pls enter a valid unit or null.'], 400); // error

        $activity->update($request->all());
        return $activity;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $activity = $this->scope();
        $activity = $activity->find($id);

        if (!$activity)
            return response(['message' => 'Can\'t delete activity - user doesn\'t own this activity.'], 404); // error

        if ($activity->delete()) {
            return new ActivityResource($activity);
        }
        return null;
    }
}
