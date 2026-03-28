<?php

namespace App\Http\Controllers;

use App\Models\GoverningBodyMember;
use Illuminate\Http\Request;

class GoverningBodyController extends Controller
{
    public function index()
    {
        $members = GoverningBodyMember::with('role')->active()->ordered()->get();

        // Separate Top Member (Order 1 or -1) from others
        // If not found by order, fall back to Chief Patron
        $chiefPatron = $members->first(fn($m) => $m->order === 1 || $m->order === -1);
        
        if (!$chiefPatron) {
            $chiefPatron = $members->first(fn($m) => $m->role && strtolower($m->role->name) === 'chief patron');
        }

        $otherMembers = $members->reject(fn($m) => $m->id === ($chiefPatron->id ?? null));

        return view('governing-body.index', compact('members', 'chiefPatron', 'otherMembers'));
    }
}
