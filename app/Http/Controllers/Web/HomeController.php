<?php

namespace App\Http\Controllers\Web;

use App\Helpers\ApiResponseHandler;
use App\Helpers\AppException;
use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
            $params = [];
            $userStatus = Constant::USER_STATUS;
            $params['stats'] = [
                'user_count' => DB::table('users')->where('status', Constant::USER_STATUS['Active'])->whereNull('deleted_at')->count(),
                'customer_count' => Post::where('status', Constant::POST_STATUS['Active'])->whereNull('deleted_at')->count(),
                'closet_count' => 0,//DB::table('closets')->count(),
                'products_sold' => "$3M",
                't_complains' => "1896",
                't_reviews' => "$12.6k",
            ];
            return view('dashboard.index', $params);
        }catch (\Exception $e){
            AppException::log($e);
            return ApiResponseHandler::failure(__('messages.general.failed'), $e->getMessage());
        }
    }
}
