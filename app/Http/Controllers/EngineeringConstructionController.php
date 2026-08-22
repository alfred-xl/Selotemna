<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class EngineeringConstructionController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function __invoke(): View
    {
        return view('engineering-construction', [
            'title' => 'Engineering & Construction | Selotemna',
            'description' => 'Discuss residential, commercial and real-estate development requirements with Selotemna’s Engineering & Construction division.',
            'projectGroups' => $this->content->projectGroups('Engineering & Construction'),
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
