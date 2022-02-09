<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;

class SiteController extends Controller
{
    public function index()
    {
        return view('sites.index');
    }

    public function show(Site $site)
    {
        return view("sites.show", compact("site"));
    }
    
    public function store()
    {
        
    }
    
    public function edit()
    {
        
    }

    public function update()
    {
        
    }


    public function delete(Site $site)
    {
        return view('sites.destroy', compact('site'));
    }

    public function destroy(Site $site)
    {
        if (auth()->user()->id !== $site->owner()->first()->id) {
            return back()->withErrors(["You are not allowed to modify this site"]);
        }
    
        $site->delete();
    
        return redirect()->route('sites.index')->with("success", "The site has been deleted");
    }
    
}
