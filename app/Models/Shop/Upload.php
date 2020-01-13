<?php

namespace App\Models\Shop;

use Image;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model as EloquentModel;

use App\Models\Model;
use App\Models\User\User;
use App\Exceptions\InvalidParameterException;

class Upload extends Model
{
    protected $table = 'shop_uploads';
    private const SAVE_ROOT = 'uploads/attachemnts';

    protected $fillable = [
        'shop_id',
        'file_path',
        'file_size',
        'origin_name',
        'mime_type',
        'is_image',
        'file_width',
        'file_height',
        'attach_type',
        'attachable_type',
        'attachable_id',
        'attachable',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function attachable()
    {
        return $this->morphTo();
    }

    /**
     * 允许上传附件后辍
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-06
     * @return   array
     */
    public static function allowedExts()
    {
        $str = config('app.allow_exts');
        if (!$str) {
            return [];
        }
        return explode(',', $str);
    }

    /**
     * 图片裁剪方法
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-06
     * @param    string             $file_path 图片路径
     * @param    int                $max_width 最大宽度
     * @return   bool
     */
    public static function reduseImageSize(string $file_path, int $max_width):bool
    {
        if ($max_width <= 0) {
            return false;
        } else if(strpos($file_path, '/'.static::SAVE_ROOT) !== 0){
            return false;
        }

        $arr = explode('.', $file_path);
        if (!in_array(strtolower(end($arr)), ['png', 'jpg', 'jpeg'])){
            return false;
        }

        // 先实例化，传参是文件的磁盘物理路径
        $image = Image::make(Str::substr($file_path, 1));

        // 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {

            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        // 对图片修改后进行保存
        $image->save();
        return true;
    }

    /**
     * 裁剪图片
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-06
     * @param    int                $max_width 最大宽度
     * @return   bool
     */
    public function reduseSize(int $max_width):bool
    {
        if (!$this->is_image) {
            return false;
        }

        return static::reduseImageSize($this->file_path, $max_width);
    }

    /**
     * 保存店铺图片
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-06
     * @param    int                $shop_id     店铺ID
     * @param    string             $attach_type 文件用途
     * @param    UploadFile         $file        上传文件
     * @param    array              $options     选择项值
     * @return   Upload
     */
    public static function saveShopAttach(int $shop_id, string $attach_type, $file, $options = []):Upload
    {
        if (isset($options['folder']) && $options['folder']) {
            $folder = $options['folder'];
        } else {
            $folder = 'default';
        }
        // 构建存储的文件夹规则，值如：uploads/images/avatars/201709/21/
        // 文件夹切割能让查找效率更高。
        $folder_name = static::SAVE_ROOT . "/$folder/" . date("Ym", time()) . '/'.date("d", time());

        // 文件具体存储的物理路径，`public_path()` 获取的是 `public` 文件夹的物理路径。
        // 值如：/home/vagrant/Code/larabbs/public/uploads/images/avatars/201709/21/
        $upload_path = public_path() . '/' . $folder_name;

        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名，加前缀是为了增加辨析度
        // 值如：1493521050_7BVc9v9ujP.png
        $filename = time() . '_' . Str::random(10) . '.' . $extension;
        if (isset($options['file_prefix'])) {
            $filename = $options['file_prefix'] . '_' . $filename;
        }

        if (isset($options['allowed_ext']) && $options['allowed_ext']) {
            $allow_exts = $options['allowed_ext'];
        } else {
            $allow_exts = static::allowedExts();
        }

        // 如果上传的不是图片将终止操作
        if ( ! in_array($extension, $allow_exts)) {
            return false;
        }

        // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $filename);
        $local_name = "$folder_name/$filename";

        $data = [
            'shop_id' => $shop_id,
            'attach_type' => $attach_type,
            'file_size' => $file->getClientSize(),
            'mime_type' => $file->getClientMimeType(),
            'origin_name' => $file->getClientOriginalName(),
            'file_path' => "/$folder_name/$filename",
        ];

        if(strpos($data['mime_type'], 'image') === 0){
            $image = Image::make($local_name);
            $data['is_image'] = true;
            $data['file_width'] = $image->width();
            $data['file_height'] = $image->height();
        }else{
            $data['is_image'] = false;
            $data['file_width'] = 0;
            $data['file_height'] = 0;
        }

        $upload = new static($data);
        if (isset($options['attachable'])) {
            $upload->attachable()->associate($options['attachable']);
            $upload->save();
        }
        $upload->save();

        if(isset($options['max_width'])){
            // 当指定最大宽度后，对图片进行裁剪
            $upload->reduseSize($options['max_width']);
        }

        return $upload;
    }

    /**
     * 追加多态关联记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-07
     * @param    EloquentModel      $attachable  关联实例
     * @param    string             $attach_type 附件类型
     * @param    array/int          $ids         附件ID
     * @return   int
     */
    public static function morphAdd(EloquentModel $attachable, string $attach_type, $ids)
    {
        if (!$attachable) {
            throw new InvalidParameterException('关联记录不能为空');
        }

        if (!$attach_type) {
            throw new InvalidParameterException('上传文件类型不能为空');
        }

        if (empty($ids)) {
            throw new InvalidParameterException('上传文件ID不能为空');
        }

        $data = [
            'attachable_type' => $attachable->getMorphClass(),
            'attachable_id' => $attachable->id,
            'attach_type' => $attach_type,
        ];

        if (is_array($ids)) {
            $query = static::whereIn('id', $ids);
        } else {
            $query = static::where('id', intval($ids));
        }

        return $query->update($data);
    }

    /**
     * 设置多态关联记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-07
     * @param    EloquentModel      $attachable  关联实例
     * @param    string             $attach_type 附件类型
     * @param    array/int          $ids         附件ID
     * @return   array
     */
    public static function morphSet(EloquentModel $attachable, string $attach_type, $ids)
    {
        if (!$attachable) {
            throw new InvalidParameterException('关联记录不能为空');
        }

        if (!$attach_type) {
            throw new InvalidParameterException('上传文件类型不能为空');
        }

        if (empty($ids)) {
            throw new InvalidParameterException('上传文件ID不能为空');
        }

        if (is_array($ids)) {
            $update_query = static::whereIn('id', $ids);
            $delete_query = static::whereNotIn('id', $ids);
        } else {
            $id = intval($ids);
            $update_query = static::where('id', $id);
            $delete_query = static::where('id', '<>', intval($ids));
        }

        $data = [
            'attachable_type' => $attachable->getMorphClass(),
            'attachable_id' => $attachable->id,
            'attach_type' => $attach_type,
        ];
        // dd($data);

        $update_count = $update_query->update($data);
        foreach ($data as $name => $text) {
            $delete_query = $delete_query->where($name, $text);
        }

        $uploads = $delete_query->get();
        foreach ($uploads as $idx => $upload) {
            $upload->delete();
        }

        return ['update' => $update_count, 'delete' => $uploads->count()];
    }

    /**
     * 解除多态关联记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-07
     * @param    EloquentModel      $attachable  关联实例
     * @param    string             $attach_type 附件类型
     * @param    array/int          $ids         附件ID
     * @return   int
     */
    public static function morphRm(EloquentModel $attachable, string $attach_type, $ids)
    {
        if (!$attachable) {
            throw new InvalidParameterException('关联记录不能为空');
        }

        if (!$attach_type) {
            throw new InvalidParameterException('上传文件类型不能为空');
        }

        if (empty($ids)) {
            throw new InvalidParameterException('上传文件ID不能为空');
        }

        if (is_array($ids)) {
            $query = static::whereIn('id', $ids);
        } else {
            $query = static::where('id', intval($ids));
        }

        $data = [
            'attachable_type' => $attachable->getMorphClass(),
            'attachable_id' => $attachable->id,
            'attach_type' => $attach_type,
        ];
        foreach ($data as $name => $text) {
            $query = $query->where($name, $text);
        }

        $uploads = $query->get();
        foreach ($uploads as $idx => $upload) {
            $upload->delete();
        }

        return $uploads->count();
    }

    /**
     * 查询店铺指定类型最新一条附件记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-13
     * @param    int                $shop_id     店铺ID
     * @param    string             $attach_type 附件类型
     * @param    int                $id          附件ID
     * @return   Upload
     */
    public static function shopFind(int $shop_id, string $attach_type, $id = NULL) {
        $conditions = [
            'shop_id' => $shop_id,
            'attach_type' => $attach_type,
        ];
        if ($id) {
            $conditions['id'] = intval($id);
        }

        return static::where($conditions)
                    ->orderBy('id', 'DESC')
                    ->first();
    }

    /**
     * 查询店铺指定类型附件列表
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-13
     * @param    int                $shop_id     店铺ID
     * @param    string             $attach_type 附件类型
     * @param    int                $id          附件ID
     * @return   Collection
     */
    public static function shopGet(int $shop_id, string $attach_type, $id = NULL) {
        $conditions = [
            'shop_id' => $shop_id,
            'attach_type' => $attach_type,
        ];
        if ($id) {
            $conditions['id'] = intval($id);
        }

        return static::where($conditions)
                    ->orderBy('order', 'DESC')
                    ->orderBy('id', 'ASC')
                    ->get();
    }

    /**
     * 查询记录关联的最新一条记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-07
     * @param    EloquentModel      $attachable  关联实例
     * @param    string             $attach_type 附件类型
     * @param    int                $id          附件ID
     * @return   Upload
     */
    public static function morphFind(EloquentModel $attachable, string $attach_type, $id = NULL) {
        if (!$attachable) {
            throw new InvalidParameterException('关联记录不能为空');
        }

        if (!$attach_type) {
            throw new InvalidParameterException('上传文件类型不能为空');
        }

        $conditions = [
            'attachable_type' => $attachable->getMorphClass(),
            'attachable_id' => $attachable->id,
            'attach_type' => $attach_type,
        ];
        if ($id) {
            $conditions['id'] = intval($id);
        }

        return static::where($conditions)
                    ->orderBy('id', 'DESC')
                    ->first();
    }

    /**
     * 查询记录关联的所有记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-07
     * @param    EloquentModel      $attachable  关联实例
     * @param    string             $attach_type 附件类型
     * @return   Collection
     */
    public static function morphGet(EloquentModel $attachable, string $attach_type) {
        if (!$attachable) {
            throw new InvalidParameterException('关联记录不能为空');
        }

        if (!$attach_type) {
            throw new InvalidParameterException('上传文件类型不能为空');
        }

        $conditions = [
            'attachable_type' => $attachable->getMorphClass(),
            'attachable_id' => $attachable->id,
            'attach_type' => $attach_type,
        ];

        return static::where($conditions)
                    ->orderBy('order', 'DESC')
                    ->orderBy('id', 'ASC')
                    ->get();
    }

    /**
     * 图片的URL路径
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-06
     * @return   string
     */
    public function getFileUrlAttribute()
    {
        return config('app.url').$this->file_path;
    }
}
