<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyType;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        // 1. Featured properties
        $featuredProperties = Property::where('status', 'published')
            ->where('is_featured', true)
            ->with(['area', 'category', 'type', 'primaryImage'])
            ->limit(6)
            ->get();

        // 2. Latest properties
        $latestProperties = Property::where('status', 'published')
            ->with(['area', 'category', 'type', 'primaryImage'])
            ->latest()
            ->limit(6)
            ->get();

        // 3. For Sale properties
        $saleProperties = Property::where('status', 'published')
            ->where('purpose', 'sell')
            ->with(['area', 'category', 'type', 'primaryImage'])
            ->latest()
            ->limit(6)
            ->get();

        // 4. For Rent properties
        $rentProperties = Property::where('status', 'published')
            ->where('purpose', 'rent')
            ->with(['area', 'category', 'type', 'primaryImage'])
            ->latest()
            ->limit(6)
            ->get();

        // 5. Patiala Areas / localities (top 6 with active property counts)
        $areas = Area::where('is_active', true)
            ->withCount(['properties' => function ($query) {
                $query->where('status', 'published');
            }])
            ->orderBy('properties_count', 'desc')
            ->limit(6)
            ->get();

        // 6. Categories & Types list for search bar
        $categories = PropertyCategory::where('is_active', true)->with('types')->get();

        // 7. Property types with counts for "Browse by Property Type"
        $propertyTypes = PropertyType::where('is_active', true)
            ->withCount(['properties' => function ($query) {
                $query->where('status', 'published');
            }])
            ->orderBy('properties_count', 'desc')
            ->limit(6)
            ->get();

        // 8. Mock Projects (Phase 1 placeholders for visual layout)
        $newProjects = [
            [
                'title' => 'Omaxe Patiala Heights',
                'builder' => 'Omaxe Group',
                'area' => 'Urban Estate',
                'price_range' => '₹45 Lakh - ₹85 Lakh',
                'status' => 'Under Construction',
                'image' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=400&q=80'
            ],
            [
                'title' => 'DLF Royal Enclave',
                'builder' => 'DLF Builders',
                'area' => 'Nabha Road',
                'price_range' => '₹65 Lakh - ₹1.2 Cr',
                'status' => 'Newly Launched',
                'image' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&w=400&q=80'
            ],
            [
                'title' => 'Vatika City Greens',
                'builder' => 'Vatika Developers',
                'area' => 'Rajpura Road',
                'price_range' => '₹35 Lakh - ₹75 Lakh',
                'status' => 'Ready to Move',
                'image' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=400&q=80'
            ]
        ];

        // 9. Mock Blog articles (Phase 1 placeholders)
        $latestBlogs = [
            [
                'title' => 'Top 5 Localities to Buy a Residential House in Patiala (2026)',
                'category' => 'Property Guides',
                'date' => 'Aug 18, 2026',
                'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=400&q=80',
                'slug' => 'top-localities-patiala-2026'
            ],
            [
                'title' => 'Step-by-Step Tenant Verification Guide for Landlords in Punjab',
                'category' => 'Legal Help',
                'date' => 'Aug 12, 2026',
                'image' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=400&q=80',
                'slug' => 'tenant-verification-punjab'
            ],
            [
                'title' => 'How to Negotiate Property Deals in Patiala Markets',
                'category' => 'Finance Tips',
                'date' => 'Jul 29, 2026',
                'image' => 'https://images.unsplash.com/photo-1554415707-6e8cfc93fe23?auto=format&fit=crop&w=400&q=80',
                'slug' => 'negotiate-property-deals-patiala'
            ]
        ];

        $totalForSale = Property::where('status', 'published')->where('purpose', 'sell')->count();
        $totalForRent = Property::where('status', 'published')->where('purpose', 'rent')->count();

        return view('home', compact(
            'featuredProperties',
            'latestProperties',
            'saleProperties',
            'rentProperties',
            'areas',
            'categories',
            'propertyTypes',
            'newProjects',
            'latestBlogs',
            'totalForSale',
            'totalForRent'
        ));
    }
}
