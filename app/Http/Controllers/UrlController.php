<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lastChecks = DB::table('url_checks')
            ->select('url_id', 'status_code', DB::raw('MAX(created_at) as last_created_at'))
            ->groupBy('url_id', 'status_code');

        $urls = DB::table('urls')
            ->leftJoinSub($lastChecks, 'lastChecks', function ($join) {
                $join->on('urls.id', '=', 'lastChecks.url_id');
            })->get();

        return view('url.index', compact('urls'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('index');
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
            'url.name' => 'required|url|max:255'
        ]);

        if ($validator->fails()) {
            flash('Некорректный URL')->error();

            return redirect()->route('welcome')->withErrors($validator);
        }

        $urlName = $request->input('url.name');
        $urlScheme = parse_url($urlName, PHP_URL_SCHEME);
        $urlHost = parse_url($urlName, PHP_URL_HOST);
        $url = "{$urlScheme}://{$urlHost}";

        $id = DB::table('urls')->where('name', $url)->exists() ?
            DB::table('urls')->where('name', $url)->value('id') :
            DB::table('urls')->insertGetId(['name' => $url, 'created_at' => Carbon::now()]);

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
        $url = DB::table('urls')
            ->where('id', $id)
            ->first();

        $checks = DB::table('url_checks')
            ->where('url_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return view('url.show', compact('url'), compact('checks'));
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
