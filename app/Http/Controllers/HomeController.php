<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard()
    {
        return view('dashboard.index');
    }

    public function adminDashboard()
    {
        return view('admin.index');
    }

    public function dossierManager()
    {
        return view('dossiers.index');
    }

    public function settingsManager()
    {
        return view('admin.settings');
    }

    public function reportsManager()
    {
        return view('admin.reports');
    }

    public function userManager()
    {
        return view('users.index');
    }


      public function profile()
    {
        return view('users.profile');
    }

     public function associationManager()
    {
        return view('associations.index');
    }
//////////////////////////////////////////////
     public function centreManager()
    {
        return view('centres.index');
    }
       public function reports()
    {
        return view('centres.report');
    }
       public function impact()
    {
        return view('centres.impact');
    }
       public function ressourceF()
    {
        return view('centres.ressf');
    }
         public function ressourcH()
    {
        return view('centres.ressh');
    }
         public function gestionnaire()
    {
        return view('centres.gestion');
    }
}
