<?php

namespace App\Http\Controllers;

use App\platform;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $platforms = Platform::orderBy('created_at', 'ASC')->get();

        return response()->json($platforms, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\platform  $platform
     * @return \Illuminate\Http\Response
     */
    public function show(platform $platform)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\platform  $platform
     * @return \Illuminate\Http\Response
     */
    public function edit(platform $platform)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\platform  $platform
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, platform $platform)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\platform  $platform
     * @return \Illuminate\Http\Response
     */
    public function destroy(platform $platform)
    {
        //
    }
}
