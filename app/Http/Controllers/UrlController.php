<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UrlController extends Controller
{
    public function index()
    {
        $urls = DB::table('urls')->get();

        return view('url.index', compact('urls'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url.name' => 'url|between:0,255'
        ]);

        if ($validator->fails()) {
            flash('Некорректный URL')->error();

            return redirect()->route('index');
        }

        $urlName = $request->input('url.name');
        $urlScheme = parse_url($urlName, PHP_URL_SCHEME);
        $urlHost = parse_url($urlName, PHP_URL_HOST);
        $url = "{$urlScheme}//{$urlHost}";

        $id = DB::table('urls')->where('name', $url)->exists() ?
            DB::table('urls')->where('name', $url)->value('id') :
            DB::table('urls')->insertGetId(['name' => $url, 'created_at' => Carbon::now()]);

//        if (DB::table('urls')->where('name', $url)->exists()) {
//            $id = DB::table('urls')
//                ->where('name', $url)
//                ->value('id');
//
//            return redirect()->route('urls.show', $id);
//        }
//
//        $id = DB::table('urls')->insertGetId([
//            'name' => $url,
//            'created_at' => Carbon::now()
//        ]);

        return redirect()->route('urls.show', $id);
    }

    public function storeCheck(int $id)
    {
        DB::table('url_checks')->insertGetId(
            [
                'url_id' => $id,
                'status_code' => '200',
                'h1' => 'h1',
                'title' => 'title',
                'description' => 'description',
                'created_at' => Carbon::now()
            ]
        );

        return redirect()->route('urls.show', $id);
    }

    public function show(int $id)
    {
        $url = DB::table('urls')
            ->where('id', $id)
            ->first();

        $checks = DB::table('url_checks')
            ->where('url_id', $id)
            ->orderByDesc('id')
            ->get();

        return view('url.show', compact('url'), compact('checks'));
    }
}
