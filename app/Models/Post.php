<?php

namespace App\Models;

use App\Helpers\Constant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['title', 'content', 'status'];


    public static $validationRules = [];

    public static function getValidationRules($type, $params = [])
    {
        $rules = [
            'create'        => [
                'title'  => 'required|max:255|string',
                'author' => 'required|max:255|string',
                'content' => 'required',
            ],
            'update'        => [
                'name'     => 'required',
                'author' => 'required',//|unique:users,email,' . $user_id,
            ],
        ];

        return $rules[$type];
    }

    public static function findById($id){
        return self::where('id', $id)->first();
    }

    public static function findByTitle($title){
        return self::whereRaw(" LOWER(title) LIKE '%" . strtolower($title) . "%' ")
            ->whereRaw(" UPPER(title) LIKE '%" . strtoupper($title) . "%' ")
            ->first();
    }

    public static function findByAuthor($author){
        return self::whereRaw(" LOWER(author) LIKE '%" . strtolower($author) . "%' ")
            ->whereRaw(" UPPER(author) LIKE '%" . strtoupper($author) . "%' ")
            ->first();
    }

    public static function findByStatus($status){
        return self::where('status', $status)->first();
    }

    public static function removePost( $customer_id )
    {
        self::where('id', $customer_id )->delete();
    }

    public function updatePost( $verifiedOtp )
    {
        $updateCols = [
            'is_verified' => Constant::Yes,
            'country_code' => $verifiedOtp->country_code,
            'phone_number' => $verifiedOtp->phone_number,
            'phone_verified_at' => Now(),
        ];
        $this->update($updateCols);
    }

    public static function createPost( $requestData )
    {
        $data = [
            'title'            => $requestData['title'],
            'author'             => $requestData['author'],
            'status'                => Constant::POST_STATUS['Active'],
        ];

        return self::create($data);
    }

    public static function getByFilters($filter)
    {
        $data = self::select('id', 'author', 'title', 'status', 'created_at','updated_at','deleted_at');
        $data = $data->withTrashed()->orderBy('id', 'DESC');

        if (count($filter))
        {
            if (!empty($filter['author_name']))
            {
                $data = $data->whereRaw(" LOWER(author) LIKE '%" . strtolower($filter['author_name']) . "%' ")
                            ->whereRaw(" UPPER(author) LIKE '%" . strtoupper($filter['author_name']) . "%' ");
            }
            if (!empty($filter['title']))
            {
                $data = $data->whereRaw(" LOWER(title) LIKE '%" . strtolower($filter['title']) . "%' ")
                    ->whereRaw(" UPPER(title) LIKE '%" . strtoupper($filter['title']) . "%' ");
            }

            if (isset($filter['status']))
            {
                $data = $data->where('status', $filter['status']);
            }

        }

        $count = $data->count();

//        if (isset($filter['start']) && isset($filter['length']))
//        {
//            $data->skip($filter['start'])->limit($filter['length']);
//        }

        return [
            'count'   => $count,
            'offset'  => isset($filter['start']) ? $filter['start'] : 0,
            'records' => $data->get()
        ];
    }
}
