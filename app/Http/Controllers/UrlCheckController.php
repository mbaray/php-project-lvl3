<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DiDom\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UrlCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(int $id)
    {
        $url = DB::table('urls')->find($id);
        if (is_null($url)) {
            return response('not found', 404);
        }

        try {
            $response = Http::get(optional($url)->name);
            $document = new Document($response->body());

            DB::table('url_checks')->insertGetId(
                [
                    'url_id' => $id,
                    'status_code' => $response->status(),
                    'h1' => optional($document->first('h1'))->text(),
                    'title' => optional($document->first('title'))->text(),
                    'description' => optional($document->first('meta[name="description"]'))->getAttribute('content'),
                    'created_at' => Carbon::now()
                ]
            );
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
        }

        return redirect()->route('urls.show', $id);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
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
