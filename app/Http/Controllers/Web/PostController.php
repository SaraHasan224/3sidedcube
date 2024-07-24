<?php

namespace App\Http\Controllers\Web;

use App\Helpers\ApiResponseHandler;
use App\Helpers\AppException;
use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
            $data['status'] = Constant::POST_STATUS;
            return view('post.index',$data);
        }catch (\Exception $e){
            AppException::log($e);
            return ApiResponseHandler::failure(__('messages.general.failed'), $e->getMessage());
        }
    }

    private function storeOrUpdate($validated, $state, $id = false)
    {
        DB::beginTransaction();
        if ($state == Constant::CRUD_STATES['created']) {
            $post = new Post();
        } else {
            $post = Post::findById($id);
        }
        try {
            if(!array_key_exists('is_active', $validated)) {
                $validated['is_active'] = Constant::POST_STATUS['InActive'];
            }
            $post->title = $validated['title'];
            $post->author = $validated['author'];
            $post->content = $validated['content'];
            $post->status = $validated['is_active'] == 1 ? Constant::POST_STATUS['Active'] : Constant::POST_STATUS['InActive'];
            if ((!$post->save()))
            {
                throw new \Exception("Oopss we are facing some hurdle right now to process this action, please try again");
            }
            DB::commit();
            $return['type'] = 'success';
            $action = array_flip(Constant::CRUD_STATES);
            $return['message'] = 'Post has been ' . $action[$state] . ' successfully.';
            return $return;
        } catch (\Exception $e) {
            AppException::log($e);
            DB::rollback();
            $return['type'] = 'errors';
            $get_environment = env('APP_ENV', 'local');
            if ($get_environment == 'local') {
                $return['message'] = $e->getMessage();
            } else {
                $return['message'] = "Oopss we are facing some hurdle right now to process this action, please try again";
            }
            return $return;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['customer'] = Customer::findById($id);
        $data['status'] = Constant::POST_STATUS;
        return view('post.edit.index', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::findById($id);
        if(empty($post))
        {
            return redirect('/post')->with('warning_msg', "Record not found.");
        }else{
            $data['post'] = $post;
            $data['status'] = Constant::POST_STATUS;
            return view('post.edit', $data);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Check if the incoming request is valid...
        $requestData = $request->all();

        $validationRule = Customer::getValidationRules('update', $requestData);
        $validator = Validator::make($requestData, $validationRule);
        if ($validator->fails())
        {
            return ApiResponseHandler::validationError($validator->errors());
        }
        // Retrieve the validated input data...
        $data = $this->storeOrUpdate($requestData, Constant::CRUD_STATES['updated'], $id);
        return ApiResponseHandler::success($data);
    }

    /**
     * Get list of the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getListingRecord(Request $request)
    {
        try {
            $filter = $request->all();
            $usersRecord = Post::getByFilters($filter);
            $response = $this->makeDatatable($usersRecord);
            return $response;
        } catch (\Exception $e) {
            AppException::log($e);
            dd($e->getTraceAsString());
        }
    }

    private function makeDatatable($data)
    {
        return DataTables::of($data['records'])
            ->addColumn('check', function ($rowdata) {
                $class = '';
                $disabled = '';
                if (!empty($rowdata->deleted_at))
                {
                    $disabled = 'disabled="disabled"';
                }
                return '<input 
                            value="' . $rowdata->id . '"
                            type="checkbox" ' . $disabled . ' 
                            name="data_raw_id[]"  
                            class="theClass ' . $class . '"
                        />';
            })
            ->addColumn('id', function ($rowdata) {
                $disabledClass = "";
                $url = url("/post/" . $rowdata->id.'/edit');
                $target = "_blank";
                return '<a target="'.$target.'" href="'.$url.'" class="'.$disabledClass.'" >' . $rowdata->id . '</a>';
            })
            ->addColumn('title', function ($rowdata) {
                return $rowdata->title;
            })
            ->addColumn('author', function ($rowdata) {
                return $rowdata->author;
            })
            ->addColumn('status', function ($rowdata) {
                $isActive = !empty($rowdata->status) ? $rowdata->status : Constant::POST_STATUS['InActive'];
                $userStatus = array_flip(Constant::POST_STATUS);
                return '<label class="badge badge-' . Constant::POST_STATUS_STYLE[$isActive] . '"> ' . $userStatus[$isActive] . '</label>';
            })
            ->addColumn('created_at', function ($rowdata) {
                return Helper::dated_by(null,$rowdata->created_at);
            })
            ->addColumn('updated_at', function ($rowdata) {
                return Helper::dated_by(null,$rowdata->updated_at);
            })
            ->rawColumns([
                'check',
                'id',
                'status',
                'created_at',
                'updated_at'
            ])
            ->setOffset($data['offset'])
            ->with([
                "recordsTotal" => $data['count'],
                "recordsFiltered" => $data['count'],
            ])
            ->setTotalRecords($data['count'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['status'] = Constant::POST_STATUS;
        $data['post'] = [];
        return view('post.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Check if the incoming request is valid...
        $requestData = $request->all();
        $validationRule = Post::getValidationRules('create', $requestData);
        $validator = Validator::make($requestData, $validationRule);
        if ($validator->fails())
        {
            return ApiResponseHandler::validationError($validator->errors());
        }
        // Retrieve the validated input data...
        $data = $this->storeOrUpdate($requestData, Constant::CRUD_STATES['created']);
        return ApiResponseHandler::success($data);
    }

    /**
     * Remove all the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRecords(Request $request)
    {
        try
        {
            $requestData = $request->all();
            $validationErrors = Helper::validationErrors($request, [
                'delete_ids' => 'required',
            ]);

            if ($validationErrors)
            {
                return ApiResponseHandler::validationError($validationErrors);
            }
            if ($requestData['action'] == 'delete')
            {
                Post::deleteRecords($requestData);
                return ApiResponseHandler::success([], __('messages.posts.deleted'));
            }
            return ApiResponseHandler::success([]);
        }
        catch (\Exception $e)
        {
            return ApiResponseHandler::serverError($e);
        }
    }
}
