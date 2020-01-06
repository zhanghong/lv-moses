<?php

namespace App\Models\Shop;

use Image;
use Illuminate\Support\Str;

use App\Models\Model;
use App\Models\User\User;

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

        $clones = ['attachable_type', 'attachable_id;'];
        foreach ($clones as $key => $name) {
            if (isset($options[$name])) {
                $data[$name] = $options[$name];
            }
        }

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
        $upload->save();

        if(isset($options['max_width'])){
            // 当指定最大宽度后，对图片进行裁剪
            $upload->reduseSize($options['max_width']);
        }

        return $upload;
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