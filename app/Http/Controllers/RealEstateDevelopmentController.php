<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class RealEstateDevelopmentController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function __invoke(): View
    {
        return view('real-estate-development', [
            'title' => 'Real Estate Development | Selotemna',
            'description' => 'Explore Selotemna land, property and development opportunities, including the verified Omu Creek land opportunity.',
            'featuredProperty' => $this->content->featuredProperty(),
            'projectGroups' => $this->content->projectGroups('Real Estate Development'),
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
