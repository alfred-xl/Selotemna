<?php

return [
    'phone' => env('SELOTEMNA_PHONE'),
    'whatsapp' => env('SELOTEMNA_WHATSAPP'),
    'email' => env('SELOTEMNA_EMAIL'),
    'address' => env('SELOTEMNA_ADDRESS'),
    'business_hours' => env('SELOTEMNA_BUSINESS_HOURS'),

    'featured_property' => [
        'name' => 'Omu Creek',
        'status' => 'Upcoming Project',
        'type' => 'Land allocation',
        'title' => 'Lagos State Government Allocation',
        'overview' => 'Omu Creek Estate is a planned residential community intended for people looking to build homes or invest in land.',
        'marketing' => 'The estate is exclusively marketed and managed by Selotemna Limited.',
        'locations' => ['Eti-Osa LGA', 'Ibeju-Lekki LGA', 'Epe LGA'],
        'title_information' => 'The land is free from government acquisition and adverse claims and is covered by a Lagos State Government Allocation.',
        'price_per_sqm' => 50000,
        'options' => [
            ['label' => 'Commercial plot', 'size_sqm' => 1000, 'price' => 50000000],
            ['label' => 'Standard plot', 'size_sqm' => 500, 'price' => 25000000],
            ['label' => 'Half plot', 'size_sqm' => 300, 'price' => 15000000],
        ],
        'payment_plan' => [
            'initial_deposit' => 5000000,
            'balance_period' => 'six months',
            'note' => 'Installment plans may attract a premium compared with outright payment.',
        ],
        'charges' => [
            ['label' => 'Deed of Assignment', 'value' => '5%'],
            ['label' => 'Registered Survey', 'value' => '₦1,500,000'],
            ['label' => 'Development Levy', 'value' => '₦5,000,000 per 500 sqm'],
        ],
        'documents' => [
            ['stage' => 'After the initial deposit', 'items' => ['Payment Receipt', 'Contract of Sale']],
            ['stage' => 'After full payment for the land', 'items' => ['Payment Receipt', 'Letter of Acknowledgment']],
            ['stage' => 'After payment of applicable statutory fees', 'items' => ['Deed of Assignment', 'Survey Plan', 'Physical Allocation Letter']],
        ],
        'planned_infrastructure' => [
            'Perimeter fencing',
            'Secure gatehouse',
            'Graded road network',
            'Drainage system',
            'Electricity infrastructure',
            'Recreational green areas',
        ],
        'allocation' => 'Physical allocation is conducted within 30 days after complete payment for the land and confirmation of the survey fee, upon completion of the Omu Creek Bridge.',
        'construction' => 'After physical allocation and collection of the building-layout guidelines, buyers may commence fencing and construction.',
        'policies' => [
            'Prolonged installment default without notice may result in contract revocation or plot reallocation, subject to a 20% administrative charge.',
            'Resale to a third party is permitted. Selotemna must be informed for security and documentation, and a 10% change-of-ownership fee applies.',
            'A discontinued transaction before full payment may qualify for a refund, subject to a 30% administrative and agency deduction. Refunds are typically processed within 60 days of the request.',
        ],
        'disclaimer' => 'Prices exclude applicable taxes. Availability and property information are subject to confirmation.',
        'video_url' => env('SELOTEMNA_OMU_CREEK_VIDEO_URL') ?: 'https://selotemna.boatengalfred.work/OMU%20CREEK%202%20VIDEO%201.mp4',
        'video_poster' => env('SELOTEMNA_OMU_CREEK_VIDEO_POSTER'),
        'short_video_url' => env('SELOTEMNA_OMU_CREEK_SHORT_VIDEO_URL') ?: 'https://selotemna.boatengalfred.work/SHORT%20FORM%201.mp4',
        'short_video_poster' => env('SELOTEMNA_OMU_CREEK_SHORT_VIDEO_POSTER'),
    ],

    'homepage_faq_ids' => [
        'omu-creek-overview',
        'omu-creek-title',
        'omu-creek-sizes',
        'omu-creek-pricing',
    ],

    'faq_groups' => [
        'general' => [
            'label' => 'General Information and Location',
            'items' => [
                ['id' => 'omu-creek-overview', 'question' => 'What is Omu Creek Estate?', 'answer' => 'Omu Creek Estate is a planned residential community intended for people looking to build homes or invest in land.'],
                ['id' => 'omu-creek-marketing', 'question' => 'Who is marketing Omu Creek Estate?', 'answer' => 'The estate is exclusively marketed and managed by Selotemna Limited.'],
                ['id' => 'omu-creek-location', 'question' => 'Where is Omu Creek Estate located?', 'answer' => 'Omu Creek Estate covers Eti-Osa LGA, Ibeju-Lekki LGA and Epe LGA.'],
            ],
        ],
        'title-sizes-pricing' => [
            'label' => 'Land Title, Sizes and Pricing',
            'items' => [
                ['id' => 'omu-creek-title', 'question' => 'What is the title on the land?', 'answer' => 'The land is free from government acquisition and adverse claims. It is covered by a Lagos State Government Allocation.'],
                [
                    'id' => 'omu-creek-sizes',
                    'question' => 'What plot sizes are available for purchase?',
                    'answer' => 'The available options are:',
                    'points' => ['Commercial plot: 1,000 square metres', 'Standard plot: 500 square metres', 'Half plot: 300 square metres'],
                ],
                [
                    'id' => 'omu-creek-pricing',
                    'question' => 'How much is a plot of land at Omu Creek Estate?',
                    'answer' => 'The current outright prices are:',
                    'points' => ['1,000 sqm: ₦50,000,000', '500 sqm: ₦25,000,000', '300 sqm: ₦15,000,000'],
                    'note' => 'Prices exclude applicable taxes. Availability and property information are subject to confirmation.',
                ],
            ],
        ],
        'payments-charges' => [
            'label' => 'Payments and Additional Charges',
            'items' => [
                ['id' => 'omu-creek-installments', 'question' => 'Can I pay in installments?', 'answer' => 'Yes. Selotemna Limited offers a payment plan with an initial deposit of ₦5,000,000 and the balance spread over six months. Installment plans may attract a premium compared with outright payment.'],
                [
                    'id' => 'omu-creek-charges',
                    'question' => 'Are there any other charges besides the cost of the land?',
                    'answer' => 'The following statutory fees apply for legal documentation and physical estate development:',
                    'points' => ['Deed of Assignment: 5%', 'Registered Survey: ₦1,500,000', 'Development Levy: ₦5,000,000 per 500 sqm'],
                ],
                [
                    'id' => 'omu-creek-documents',
                    'question' => 'What documents do I receive after payment?',
                    'answer' => 'Documents are supplied at the following payment stages:',
                    'points' => [
                        'After the initial deposit: Payment Receipt and Contract of Sale',
                        'After full payment for the land: Payment Receipt and Letter of Acknowledgment',
                        'After payment of applicable statutory fees: Deed of Assignment, Survey Plan and Physical Allocation Letter',
                    ],
                ],
            ],
        ],
        'infrastructure-allocation' => [
            'label' => 'Infrastructure and Allocation',
            'items' => [
                [
                    'id' => 'omu-creek-infrastructure',
                    'question' => 'What infrastructure will Selotemna Limited provide?',
                    'answer' => 'Planned infrastructure and amenities include:',
                    'points' => ['Perimeter fencing and a secure gatehouse', 'Graded road network', 'Drainage system', 'Electricity infrastructure', 'Recreational green areas'],
                ],
                ['id' => 'omu-creek-allocation', 'question' => 'When do I get physical allocation of my plot?', 'answer' => 'Physical allocation is conducted within 30 days after complete payment for the land and confirmation of the survey fee, upon completion of the Omu Creek Bridge.'],
                ['id' => 'omu-creek-construction', 'question' => 'Can I start building immediately after allocation?', 'answer' => 'After physical allocation and collection of the building-layout guidelines from the estate management, buyers may commence fencing and construction.'],
            ],
        ],
        'legal-administrative' => [
            'label' => 'Legal and Administrative Policies',
            'items' => [
                ['id' => 'omu-creek-default', 'question' => 'What happens if I default on my installment payments?', 'answer' => 'Buyers are encouraged to communicate with Selotemna if they experience unexpected financial delays. Prolonged default without notice may lead to contract revocation or plot reallocation, subject to a 20% administrative charge.'],
                ['id' => 'omu-creek-resale', 'question' => 'Can I resell my land later?', 'answer' => 'Yes. Omu Creek land may be resold to a third party. Selotemna Limited must be informed for security and documentation, and a 10% change-of-ownership fee applies.'],
                ['id' => 'omu-creek-refund', 'question' => 'Is there a refund policy?', 'answer' => 'Yes. If a buyer discontinues the transaction before full payment, a refund can be processed. A 30% administrative and agency fee will be deducted, and the refund is typically paid within 60 days of the request.'],
            ],
        ],
    ],

    'media' => [
        'development_aerial' => 'assets/images/selotemna-development-aerial.jpg',
        'earthworks_truck' => 'assets/images/selotemna-earthworks-truck.jpg',
        'building_construction' => 'assets/images/selotemna-building-construction.jpg',
    ],

    'editorial_media' => [
        'inspection' => [
            'path' => 'assets/images/selotemna-inspection-consultation.jpg',
            'source_url' => 'https://www.pexels.com/photo/a-man-talking-to-his-clients-while-looking-at-the-property-7489096/',
            'credit' => 'Photo by Gustavo Fring on Pexels',
            'alt' => 'A property professional speaking with clients during a viewing.',
        ],
        'contact' => [
            'path' => 'assets/images/selotemna-contact-meeting.jpg',
            'source_url' => 'https://www.pexels.com/photo/business-meeting-in-lagos-office-setting-30688596/',
            'credit' => 'Photo by Ninthgrid on Pexels',
            'alt' => 'Professionals discussing a project around a meeting table.',
        ],
    ],

    'projects' => [
        'ongoing' => [
            'label' => 'Ongoing Projects',
            'items' => [],
        ],
        'completed' => [
            'label' => 'Completed Projects',
            'items' => [],
        ],
        'upcoming' => [
            'label' => 'Upcoming Projects',
            'items' => [
                [
                    'slug' => 'omu-creek',
                    'name' => 'Omu Creek',
                    'status' => 'Upcoming Project',
                    'division' => 'Real Estate Development',
                    'summary' => 'A planned residential community for people looking to build homes or invest in land.',
                    'route' => 'omu-creek',
                ],
            ],
        ],
    ],

    /*
    | Testimonials must contain an approved quote, an approved public name, a
    | relevant division or project, and confirmed permission before rendering.
    */
    'testimonials' => [],
];
