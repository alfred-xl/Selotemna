<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class OmuCreekController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function __invoke(): View
    {
        return view('omu-creek', [
            'title' => 'Omu Creek Land Opportunity | Selotemna',
            'description' => 'Review verified Omu Creek land title, plot sizes, current prices, payment information, charges, allocation terms and inspection options.',
            'featuredProperty' => $this->content->featuredProperty(),
            'faqs' => $this->content->faqsByIds([
                'omu-creek-title',
                'omu-creek-pricing',
                'omu-creek-installments',
                'omu-creek-charges',
                'omu-creek-allocation',
                'omu-creek-construction',
            ]),
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
