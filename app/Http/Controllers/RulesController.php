<?php

namespace App\Http\Controllers;

use App\RuleDetail;
use DB;
use Validator;
use App\Rule;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RulesController extends Controller
{
    public function data()
    {
        return Rule::getRules();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.rules.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $redes = DB::table('networks')->orderBy('created_at', 'asc')->get();
        $tipos = DB::table('tipos')->orderBy('created_at', 'asc')->where('nombre', '!=', 'Terceros')->get();

        $q = [
          'redes' => $redes,
          'tipos' => $tipos
        ];
        return view('admin.rules.create', compact('q'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'red' => 'required',
            'tipo' => 'required',
            'nivel' => 'required',
            'comision_puntos' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()
                ->route('admin.rules.create')
                ->withErrors($validator)
                ->withInput();
        }


        Rule::storeRule($request->all());

        return redirect()
            ->route('admin.rules.index')
            ->with(['message' => 'Regla creada correctamente.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $redes = DB::table('networks')->orderBy('created_at', 'asc')->get();
        $tipos = DB::table('tipos')->orderBy('created_at', 'asc')->where('nombre', '!=', 'Terceros')->get();

        $rule = Rule::editRule($id);

        $q = [
            'redes' => $redes,
            'tipos' => $tipos,
            'rule' => $rule
        ];

        return view('admin.rules.edit', compact('q'));
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
    }

    public function updateDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'nivel' => 'required',
            'comision_puntos' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()
                ->route('admin.rules.create')
                ->withErrors($validator)
                ->withInput();
        }

        Rule::updateDetail($request->all());

        return redirect()
            ->route('admin.rules.index')
            ->with(['message' => 'Actualizacion realizada.']);
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
    }
}
