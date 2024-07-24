<?php
/**
 * Created by PhpStorm.
 * User: Sara
 * Date: 6/28/2023
 * Time: 12:05 AM
 */

namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponseHandler;
use App\Helpers\AppException;
use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function Ramsey\Uuid\v4;
use Illuminate\Support\Facades\DB;

class PostController
{
    /**
     * @OA\Get(
     *
     *     path="/v1/all-posts",
     *     tags={"Post"},
     *     summary="Manage Posts",
     *     operationId="getAllPosts",
     *
     *     security={
     *          {"user_access_token": {}}
     *     },
     *
     *      @OA\Response(response=200, description="messages.general.success"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=400, description="messages.general.failed"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *
     * )
     */
    public function getAllPosts(Request $request)
    {
        try {
            $response = [];
            $response['posts'] = self::getCachedPostsList($request);
            return ApiResponseHandler::success($response, __('messages.general.success'));
        } catch (\Exception $e) {
            AppException::log($e);
            return ApiResponseHandler::failure(__('messages.general.failure'), $e->getMessage());
        }
    }

    public function getCachedPostsList($request)
    {
        $page = $request->input('page') ?? 1;
        $perPage = 20;
        $cacheType = "all_posts";
//        return Cache::remember($cacheKey, env('CACHE_REMEMBER_SECONDS'), function () use($type, $perPage) {
        return Post::getPostsListing($perPage);
//        });
    }

    /**
     * @OA\Get(
     *     path="/v1/post/{id}",
     *     tags={"Post"},
     *     summary="get post details",
     *     operationId="getPostDetails",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(response=200,description="Success"),
     *     security={
     *          {"user_access_token": {}}
     *     }
     * )
     */
    public function getPostDetails(Request $request, $id)
    {
        try {
            $requestData = $request->all();
            $result = [];
            $post = Post::findById($id);

            if (!$post) {
                return ApiResponseHandler::failure(__('Post not found'));
            } else {
                $result['post'] = $post;
                return ApiResponseHandler::success($post);
            }
        } catch (\Exception $e) {
            AppException::log($e);
            return ApiResponseHandler::failure(__('messages.general.failure'), $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *
     *     path="/v1/post/add",
     *     tags={"Post"},
     *     summary="Add New Post",
     *     operationId="addPost",
     *
     *      @OA\Response(response=200, description="messages.general.success"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=400, description="messages.general.failed"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *
     *     @OA\RequestBody(
     *         description="Add New Post",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="author",
     *                     description="author name",
     *                     type="string",
     *                     example=""
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     description="Title",
     *                     type="string",
     *                     example=""
     *                 ),
     *                 @OA\Property(
     *                     property="content",
     *                     description="Content for the post",
     *                     type="string",
     *                     example=Null
     *                 ),
     *              )
     *         )
     *     ),
     *     security={
     *          {"user_access_token": {}}
     *     }
     * )
     */

    public function addPost(Request $request)
    {
        $response = [];
        try {
            $requestData = $request->all();
            DB::beginTransaction();

            #Add Posts
            // Check if the incoming request is valid...
            $validationRule = Post::getValidationRules('create', $requestData);
            $validator = Validator::make($requestData, $validationRule);
            if ($validator->fails()) {
                return ApiResponseHandler::validationError($validator->errors());
            }

            $customerId = $requestData['customer_id'];
            $customer = Customer::findById($customerId);
            if (empty($customer)) {
                return ApiResponseHandler::failure("Customer not found.");
            }

            // Retrieve the validated input data...
            $data = $this->storeOrUpdate($requestData, Constant::CRUD_STATES['created']);
            DB::commit();
            $response['all_posts'] = self::getCachedPostsList($request);
            $response['post'] = $data['post'];
            return ApiResponseHandler::success($response, "Post added successfully");
        } catch (\Exception $e) {
            DB::rollBack();
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
            if (!array_key_exists('is_active', $validated)) {
                $validated['is_active'] = Constant::POST_STATUS['Active'];
            }
            $post->title = $validated['title'];
            $post->author = $validated['author'];
            $post->content = $validated['content'];
            $post->status = $validated['is_active'] == 1 ? Constant::POST_STATUS['Active'] : Constant::POST_STATUS['InActive'];
            if ((!$post->save())) {
                throw new \Exception("Oopss we are facing some hurdle right now to process this action, please try again");
            }
            DB::commit();
            $return['post'] = $post;
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
     * @OA\Post(
     *
     *     path="/v1/post/edit/{id}",
     *     tags={"Post"},
     *     summary="Edit Post",
     *     operationId="editPost",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *
     *      @OA\Response(response=200, description="messages.general.success"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=400, description="messages.general.failed"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *
     *     @OA\RequestBody(
     *         description="Edit Post",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="author",
     *                     description="author name",
     *                     type="string",
     *                     example=""
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     description="Title",
     *                     type="string",
     *                     example=""
     *                 ),
     *                 @OA\Property(
     *                     property="content",
     *                     description="Content for the post",
     *                     type="string",
     *                     example=Null
     *                 ),
     *              )
     *         )
     *     ),
     *     security={
     *          {"user_access_token": {}}
     *     }
     * )
     */

    public function editPost(Request $request, $id)
    {
        $response = [];
        // Check if the incoming request is valid...
        $requestData = $request->all();
        $requestData['post_id'] = $id;
        $validationRule = Post::getValidationRules('update', $requestData);
        $validator = Validator::make($requestData, $validationRule);
        if ($validator->fails()) {
            return ApiResponseHandler::validationError($validator->errors());
        }
        // Retrieve the validated input data...
        $data = $this->storeOrUpdate($requestData, Constant::CRUD_STATES['updated'], $id);
        $response['all_posts'] = self::getCachedPostsList($request);
        $response['post'] = $data['post'];
        return ApiResponseHandler::success($response);
    }


    /**
     * @OA\Post(
     *
     *     path="/v1/post/delete/{id}",
     *     tags={"Post"},
     *     summary="Delete Post",
     *     operationId="deletePost",
     *
     *      @OA\Response(response=200, description="messages.general.success"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=400, description="messages.general.failed"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\RequestBody(
     *         description="Delete Post",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     description="post id",
     *                     type="object",
     *                     example=""
     *                 ),
     *              )
     *         )
     *     ),
     *     security={
     *          {"user_access_token": {}}
     *     }
     * )
     */

    public function deletePost(Request $request, $id)
    {
        try {
            $response = [];
            $requestData = $request->all();
            $requestData['id'] = $id;
            $validationRule = Post::getValidationRules('delete', $requestData);
            $validator = Validator::make($requestData, $validationRule);
            if ($validator->fails()) {
                return ApiResponseHandler::validationError($validator->errors());
            }
            Post::deleteRecord($requestData);

            $response['all_posts'] = self::getCachedPostsList($request);
            return ApiResponseHandler::success($response, __('messages.posts.deleted'));
        } catch (\Exception $e) {
            return ApiResponseHandler::serverError($e);
        }
    }
}