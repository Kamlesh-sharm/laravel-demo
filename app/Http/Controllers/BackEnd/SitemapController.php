<?php

namespace App\Http\Controllers\BackEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Sitemap\SitemapGenerator;
use App\Models\Language;
use App\Models\Sitemap;
use Illuminate\Support\Facades\Session;

class SitemapController extends Controller
{
  public function index(Request $request)
  {
    $data['langs'] = Language::all();
    $data['sitemaps'] = Sitemap::orderBy('id', 'DESC')->paginate(10);
    return view('backend.sitemap.index', $data);
  }

  public function store(Request $request)
  {
    $data = new Sitemap();
    $input = $request->all();

    $filename = 'sitemap' . uniqid() . '.xml';
    @mkdir(public_path('assets/sitemaps/'), 0775, true);
    SitemapGenerator::create($request->sitemap_url)->writeToFile(public_path('assets/sitemaps/') . $filename);
    $input['filename']    = $filename;
    $input['sitemap_url'] = $request->sitemap_url;
    $data->fill($input)->save();

    Session::flash('success', 'Sitemap Generate Successfully');
    return "success";
  }

  public function download(Request $request)
  {
    return response()->download(public_path('assets/sitemaps/') . $request->filename);
  }

  public function update(Request $request)
  {
    $data  = Sitemap::where('id', $request->id)->first();
    $input = $request->all();
    @unlink(public_path('assets/sitemaps/') . $data->filename);

    $filename = 'sitemap' . uniqid() . '.xml';
    SitemapGenerator::create($data->sitemap_url)->writeToFile(public_path('assets/sitemaps/') . $filename);
    $input['filename']  = $filename;

    $data->update($input);
    Session::flash('success', 'Feed updated successfully!');
    return back();
  }

  public function delete($id)
  {
    $sitemap = Sitemap::where('id', $id)->first();
    @unlink(public_path('assets/sitemaps/') . $sitemap->filename);
    $sitemap->delete();

    Session::flash('success', 'Sitemap file deleted successfully!');
    return back();
  }
}
