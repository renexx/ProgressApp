<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\BodyParameter;
use Illuminate\Http\Resources\Json\ResourceCollection;
use \App\Http\Resources\BodyParameter as BodyParameterResource;

class BodyParameterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $bodyParam = BodyParameter::all()->where('user_id', $user->id);
        return BodyParameterResource::collection($bodyParam);
    }

    private function scope()
    {
        $user = auth()->user();
        return $bodyParam = BodyParameter::all()->where('user_id', $user->id);
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
            'body_parameter_name' => 'required',
            'user_id' => 'required'
        ]);
        if ($request->user_id != auth()->user()->id)
            return response(['message' => 'Can\'t create body parameter for another user.'], 401); // error
        $scope = $this->index();
        foreach ($scope as $bodyParameter) {
            if ($bodyParameter->body_parameter_name == $request->body_parameter_name)
                return response(['message' => 'Body parameter with this name already exists.'], 400); // error
        }
        $bodyParameter = new BodyParameter;
        if ($request->unit == 'kg')  $bodyParameter->unit = 'kg';
        else if ($request->unit == 'lb') $bodyParameter->unit = 'lb';
        else if ($request->unit == '%') $bodyParameter->unit = '%';
        else if ($request->unit == 'bmi') $bodyParameter->unit = 'bmi';
        else if ($request->unit == 'cm') $bodyParameter->unit = 'cm';
        else if ($request->unit == null) $bodyParameter->unit = null;
        else   return response(['message' => 'Bad unit, pls enter a valid unit or null.'], 400); // error
        $bodyParameter->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bodyParam = $this->scope();
        return $bodyParam = $bodyParam->find($id);
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
    
        $bodyParameter = $this->scope();
        $bodyParameter = $bodyParameter->find($id);
        if (!$bodyParameter)
            return response(['message' => 'User doesn\'t own this body parameter.'], 401); // error

        if ($request->unit == 'kg')  $bodyParameter->unit = 'kg';
        else if ($request->unit == 'lb') $bodyParameter->unit = 'lb';
        else if ($request->unit == '%') $bodyParameter->unit = '%';
        else if ($request->unit == 'bmi') $bodyParameter->unit = 'bmi';
        else if ($request->unit == 'cm') $bodyParameter->unit = 'cm';
        else if ($request->unit == null) $bodyParameter->unit = null;
        else return response(['message' => 'Bad unit, pls enter a valid unit or null.'], 400); // error
        $bodyParameter->update($request->all());
        return $bodyParameter;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bodyParameter = $this->scope();
        $bodyParameter = $bodyParameter->find($id);
        if (!$bodyParameter)
            return response(['message' => 'Can\'t delete body parameter - user doesn\'t own this body parameter.'], 404); // error
        if ($bodyParameter->delete()) {
            return new BodyParameterResource($bodyParameter);
        }
        return null;
    }
}
