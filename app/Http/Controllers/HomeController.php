<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
//   // Dashboard - Analytics
//   public function home()
//   {
//     $pageConfigs = ['pageHeader' => false];

//     return view('/content/dashboard/dashboard-analytics', ['pageConfigs' => $pageConfigs]);
//   }

  // Dashboard - Ecommerce
  public function home()
  {
    $pageConfigs = ['pageHeader' => false];

    return view('home', ['pageConfigs' => $pageConfigs]);
  }
}