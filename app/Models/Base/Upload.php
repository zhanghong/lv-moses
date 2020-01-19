<?php

namespace App\Models\Base;

use DB;
use Image;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model as EloquentModel;

use App\Models\Model;
use App\Models\User\User;
use App\Exceptions\InvalidParameterException;

class Upload extends Model
{
    protected $table = 'uploads';
    private const SAVE_ROOT = 'uploads';

    protected $fillable = [
        'attach_type',
    ];

    public function ownable()
    {
        return $this->morphTo();
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
     * 拥有者查询作用域
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-18
     * @param    Query              $query       Query实例
     * @param    Model              $ownable     拥有者实例
     * @param    string             $attach_type 文件类型
     * @return   Query
     */
    public function scopeOwnQuery($query, $ownable, $attach_type)
    {
        if (!$ownable) {
            throw new InvalidParameterException('文件拥有者不能为空');
        } else if (!$ownable->id) {
            throw new InvalidParameterException('文件拥有者主键不能为空');
        } else if (!$attach_type) {
            throw new InvalidParameterException('文件类型不能为空');
        }

        return $query->where('ownable_type', $ownable->getMorphClass())
                    ->where('ownable_id', $ownable->id)
                    ->where('attach_type', $attach_type);
    }

    /**
     * 保存上传文件
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-18
     * @param    Model              $ownable     拥有者实例
     * @param    string             $attach_type 文件类型
     * @param    UploadFile         $file        上传文件
     * @param    array              $options     选择项值
     * @return   Upload
     */
    public static function saveAttach($ownable, $attach_type, $file, $options = [])
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

        $upload = new static;
        $upload->ownable()->associate($ownable);
        $upload->attach_type = $attach_type;
        $upload->file_size = $file->getClientSize();
        $upload->mime_type = $file->getClientMimeType();
        $upload->origin_name = $file->getClientOriginalName();
        $upload->file_path = "/$folder_name/$filename";

        if(strpos($upload->mime_type, 'image') === 0){
            $image = Image::make($local_name);
            $upload->is_image = true;
            $upload->file_width = $image->width();
            $upload->file_height = $image->height();
        }else{
            $upload->is_image = false;
            $upload->file_width = 0;
            $upload->file_height = 0;
        }

        $attachable = null;
        if (isset($options['attachable'])) {
            $attachable = $options['attachable'];
        }

        $order = 0;
        if ($attachable && $attachable->id) {
            $upload->attachable()->associate($attachable);
            $last = static::ownFirst($ownable, $attach_type, $attachable);
            if ($last) {
                $order = $last->order + 1;
            }
        }
        $upload->order = $order;
        $upload->save();

        if(isset($options['max_width']) && $options['max_width'] > 0){
            // 当指定最大宽度后，对图片进行裁剪
            $upload->reduseSize($options['max_width']);
        }

        return $upload;
    }

    /**
     * 追加上传文件
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-18
     * @param    Model              $ownable     拥有者实例
     * @param    string             $attach_type 文件类型
     * @param    Model              $attachable  所属实例
     * @param    int/array          $ids         文件主键
     * @return   int
     */
    public static function ownAdd(EloquentModel $ownable, string $attach_type, EloquentModel $attachable, $ids)
    {
        $query = static::ownQuery($ownable, $attach_type);

        if (!$attachable) {
            throw new InvalidParameterException('所属实例不能为空');
        } else if (!$attachable->id) {
            throw new InvalidParameterException('文件实例主键不能为空');
        } else if (empty($ids)) {
            throw new InvalidParameterException('文件主键不能为空');
        }

        if (is_array($ids)) {
            $id_str = implode(',', $ids);
            $query = $query->whereIn('id', $ids)->orderByRaw(DB::raw("FIELD(id, $id_str)"));
        } else {
            $query = $query->where('id', intval($ids));
        }

        // 取出 attachable 已有上传文件最后一条记录
        $last = static::ownFirst($ownable, $attach_type, $attachable, ['order' => 'DESC']);
        if ($last) {
            $num = $last->order + 1;
        } else {
            $num = 1;
        }

        $uploads = $query->get();
        foreach ($uploads->all() as $key => $item) {
            $item->attachable_type = $attachable->getMorphClass();
            $item->attachable_id = $attachable->id;
            $item->order = $num++;
            if ($item->isDirty()) {
                $item->save();
            }
        }

        return $uploads->count();
    }

    /**
     * 设置上传文件
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-18
     * @param    Model              $ownable     拥有者实例
     * @param    string             $attach_type 文件类型
     * @param    Model              $attachable  所属实例
     * @param    int/array          $ids         文件主键
     * @return   int
     */
    public static function ownSet(EloquentModel $ownable, string $attach_type, EloquentModel $attachable, $ids)
    {
        $update_query = static::ownQuery($ownable, $attach_type);
        $delete_query = static::ownQuery($ownable, $attach_type);

        if (!$attachable) {
            throw new InvalidParameterException('所属实例不能为空');
        } else if (!$attachable->id) {
            throw new InvalidParameterException('文件实例主键不能为空');
        }

        if (empty($ids)) {
            // 删除所有图片
            $update_query->where('id', 0);
        } else if (is_array($ids)) {
            $id_str = implode(',', $ids);
            $update_query->whereIn('id', $ids)->orderByRaw(DB::raw("FIELD(id, $id_str)"));
            $delete_query->whereNotIn('id', $ids);
        } else {
            $id = intval($ids);
            $update_query->where('id', $id);
            $delete_query->where('id', '<>', intval($ids));
        }

        $num = 1;
        $updates = $update_query->get()->all();
        foreach ($updates as $key => $item) {
            $item->attachable_type = $attachable->getMorphClass();
            $item->attachable_id = $attachable->id;
            $item->order = $num++;
            if ($item->isDirty()) {
                $item->save();
            }
        }

        $deletes = $delete_query->where('attachable_type', $attachable->getMorphClass())
                        ->where('attachable_id', $attachable->id)
                        ->get()
                        ->all();
        foreach ($deletes as $key => $item) {
            $item->delete();
        }

        return ['update' => count($updates), 'delete' => count($deletes)];
    }

    /**
     * 删除上传文件
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-18
     * @param    Model              $ownable     拥有者实例
     * @param    string             $attach_type 文件类型
     * @param    Model              $attachable  所属实例
     * @param    int/array          $ids         文件主键
     * @return   int
     */
    public static function ownRm(EloquentModel $ownable, string $attach_type, EloquentModel $attachable, $ids = null)
    {
        $query = static::ownQuery($ownable, $attach_type);

        if (!$attachable) {
            throw new InvalidParameterException('所属实例不能为空');
        } else if (!$attachable->id) {
            throw new InvalidParameterException('文件实例主键不能为空');
        }

        $query->where('attachable_type', $attachable->getMorphClass())
            ->where('attachable_id', $attachable->id);

        if (empty($ids)) {
            // nothing
        } else if (is_array($ids)) {
            $query->whereIn('id', $ids);
        } else {
            $query->where('id', intval($ids));
        }

        $uploads = $query->get();
        foreach ($uploads as $key => $item) {
            $item->delete();
        }
        $uploads->each(function($item, $key) {
            $item->delete();
        });

        return $uploads->count();
    }

    /**
     * 查找第一条关联记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-18
     * @param    Model              $ownable     拥有者实例
     * @param    string             $attach_type 文件类型
     * @param    Model              $attachable  所属实例
     * @param    array              $options     额外参数
     *                              $options.ids    主键列表
     *                              $options.order  排列方式
     * @return   Upload
     */
    public static function ownFirst(EloquentModel $ownable, string $attach_type, $attachable = null, $options = [])
    {
        $query = static::ownQuery($ownable, $attach_type);

        if ($attachable && $attachable->id) {
            $query = $query->where('attachable_type', $attachable->getMorphClass())
                        ->where('attachable_id', $attachable->id);
        }

        $ids = null;
        if (isset($options['ids'])) {
            $ids = $options['ids'];
        }
        if (empty($ids)) {
            // nothing
        } else if (is_array($ids)) {
            $query = $query->whereIn('id', $ids);
        } else {
            $query = $query->where('id', intval($ids));
        }

        $order_type = null;
        if (isset($options['order'])) {
            $order_type = $options['order'];
        }

        return $query->withOrder($order_type)->first();
    }

    /**
     * 查询所有关联记录
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-18
     * @param    Model              $ownable     拥有者实例
     * @param    string             $attach_type 文件类型
     * @param    Model              $attachable  所属实例
     * @param    array              $options     额外参数
     *                              $options.ids    主键列表
     *                              $options.order  排列方式
     * @return   Upload
     */
    public static function ownGet(EloquentModel $ownable, string $attach_type, $attachable = null, $options = [])
    {
        $query = static::ownQuery($ownable, $attach_type);

        if ($attachable && $attachable->id) {
            $query = $query->where('attachable_type', $attachable->getMorphClass())
                        ->where('attachable_id', $attachable->id);
        }

        $ids = null;
        if (isset($options['ids'])) {
            $ids = $options['ids'];
        }
        if (empty($ids)) {
            // nothing
        } else if (is_array($ids)) {
            $query = $query->whereIn('id', $ids);
        } else {
            $query = $query->where('id', intval($ids));
        }

        $order_type = null;
        if (isset($options['order'])) {
            $order_type = $options['order'];
        }
        return $query->withOrder($order_type)->get();
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
