<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DiDom\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;

class UrlCheckController extends Controller
{
    public function store(int $id): RedirectResponse
    {
        $url = DB::table('urls')->find($id);
        abort_unless($url, 404);
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
            flash('Страница успешно проверена');
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
        }

        return redirect()->route('urls.show', $id);
    }
}
