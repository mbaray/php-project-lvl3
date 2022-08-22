<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePostRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class UrlController extends Controller
{
    public function index(): View
    {
        $urls = DB::table('urls')->paginate(15);
        $urlChecks = DB::table('url_checks')
            ->select('url_id', 'status_code', DB::raw('MAX(created_at) as last_created_at'))
            ->groupBy('url_id', 'status_code')
            ->get()
            ->keyBy('url_id');

        return view('url.index', compact('urls'), compact('urlChecks'));
    }

    public function create(): View
    {
        return view('index');
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $url = $request->all();

        $id = DB::table('urls')->where('name', $url["url.name"])->exists() ?
            DB::table('urls')->where('name', $url["url.name"])->value('id') :
            DB::table('urls')->insertGetId(['name' => $url["url.name"], 'created_at' => Carbon::now()]);

        return redirect()->route('urls.show', $id);
    }

    public function show(int $id): View
    {
        $url = DB::table('urls')->find($id);
        if (is_null($url)) {
            abort(404);
        }

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
    public function edit(int $id)
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
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
    }
}
