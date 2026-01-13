<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentPublicController extends Controller
{
    public function index(Request $request)
    {
        $tournaments = Tournament::with('location')->upcoming()->orderBy('starts_at')->paginate(15);

        return view('tournaments.index', compact('tournaments'));
    }
}
