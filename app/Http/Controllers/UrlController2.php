<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use DiDom\Document;

class UrlController2 extends Controller
{
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url.name' => 'required|url|max:255'
        ]);

        if ($validator->fails()) {
            flash('Некорректный URL')->error();

            return redirect()->route('index')->withErrors($validator);
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

    public function show(int $id)
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

    public function storeCheck(int $id)
    {
        $url = DB::table('urls')->where('id', $id)->first();

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

        return redirect()->route('urls.show', $id);
    }
}
