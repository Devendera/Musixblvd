<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Faq;
class FaqController extends Controller
{
    /* Function for return view for faq page */
    public function getFaq()
    {
        $data = Faq::get();
        return view('web.faq', compact('data'));
    }
}
