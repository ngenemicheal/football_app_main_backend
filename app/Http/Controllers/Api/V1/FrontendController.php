<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Blog;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\TeamService;

class FrontendController extends Controller
{

    protected $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function getBlogs(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);
        $cacheKey = "blogs.page.{$page}.per_page.{$perPage}";

        $blogs = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($perPage) {
            return Blog::latest()->paginate($perPage, ['id', 'title', 'message', 'author']);
        });
    
        return response()->json($blogs);
    }
    
    public function getProducts(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $products =  Product::latest()->paginate($perPage, ['id', 'product_name', 'description', 'stock_amount', 'price', 'image']);

        return response()->json($products);
    }

    public function getBlog($id)
    {
        // Fetch the blog post by its ID
        $blog = Blog::find($id);
    
        // If the blog post is not found, return a 404 response
        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }
    
        // Return the blog post as a JSON response
        return response()->json($blog);
    }

    public function getProduct($id)
    {
        // Fetch the product post by its ID
        $product = Product::find($id);
    
        // If the product post is not found, return a 404 response
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        // Return the product post as a JSON response
        return response()->json($product);
    }

    public function getTeamDetails()
    {
        $players = $this->teamService->getPlayers();
        $pastFixtures = $this->teamService->getPastFixtures();
        $upcomingFixtures = $this->teamService->getUpcomingFixtures();
        $leagueTable = $this->teamService->getLeagueTable()['standings'];

        return response()->json([
            'status' => 'success',
            'message' => 'Team Data gotten successfully',
            'players' => $players,
            'pastfixtures' => $pastFixtures,
            'upcomingFixtures' => $upcomingFixtures,
            'leagueTable' => $leagueTable,
        ]);
    }

    public function getVendors()
    {
        $vendors = User::where('role', 'vendor-admin')->pluck('name');

        return response()->json([
            'status' => 'success',
            'vendors' => $vendors
        ]);
    }

}