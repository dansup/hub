<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SiteController extends Controller {

    public function beta() {
        return view('site.beta');
    }

	public function intro() {
        return view('site.intro');
    }

    public function features() {
        return view('site.features');
    }

    public function federation() {
        return view('site.federation');
    }

    public function source() {
        return view('site.source');
    }

    public function reportAbuse() {
        return view('site.reportAbuse');
    }

    public function reportBug() {
        return view('site.reportBug');
    }

}
