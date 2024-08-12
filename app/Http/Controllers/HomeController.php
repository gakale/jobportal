<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPosting;
use GrahamCampbell\Markdown\Facades\Markdown;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function list(){

        $jobs = JobPosting::all();

        return view('job-offer',compact('jobs'));
    }

    public function show($id) {
        $job = JobPosting::find($id);
    
        // Assurez-vous que la description est une chaîne non nulle
        $formatted_description = $job->formatted_description ?? '';
    
        // Convertir la description en HTML
        $formatted_description = Markdown::convertToHtml($formatted_description);
    
        return view('show', compact('job', 'formatted_description'));
    }

   
}
