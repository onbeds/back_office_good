<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tipo;
use App\Http\Requests;
use Validator;
use App\Http\Controllers\Controller;

class TiposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function data()
    {
        return Tipo::getTipos();
    }

    public function index()
    {
        return view('admin.tipos.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tipos.create');
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
            'nombre' => 'required',
            'puntos_minimos' => 'required',
            'puntos_maximos' => 'required',
            'comision_maxima' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->route('admin.tipos.create')
                ->withErrors($validator)
                ->withInput();
        }

        $tipo = Tipo::searchByName($request['nombre']);

        if ((int)$request['puntos_maximos'] !=  0) {
            if ((int)$request['puntos_minimos'] > (int)$request['puntos_maximos']){
                return redirect()->back()->withErrors(['error' => 'Los puntos minimos no pueden ser menores o iguales a los puntos maximos.']);
            }
        }

        if (count($tipo) > 0) {
            return redirect()->back()->withErrors(['error' => 'El tipo ya existe.']);
        }

        Tipo::createTipo($request->all());

        return redirect()
            ->route('admin.tipos.index')
            ->with(['message' => 'Se ha creado el tipo correctamente.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return 'show';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipo = Tipo::editTipo($id);
        return view('admin.tipos.edit', compact('tipo'));
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
        $validator = Validator::make($request->all(), [
            'puntos_minimos' => 'required',
            'puntos_maximos' => 'required',
            'comision_maxima' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->route('admin.tipos.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        if ((int)$request['puntos_maximos'] !=  0) {
            if ((int)$request['puntos_minimos'] > (int)$request['puntos_maximos']){
                return redirect()->back()->withErrors(['error' => 'Los puntos minimos no pueden ser menores o iguales a los puntos maximos.']);
            }
        }

        Tipo::updateTipo($request->all(), $id);
        return redirect()
                ->route('admin.tipos.index')
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
