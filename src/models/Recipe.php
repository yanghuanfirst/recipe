<?php

namespace ysx\recipe\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Recipe extends ActiveRecord
{
    public $page;
    public $size;
    public $image_file;
    public function rules() :array
    {
        return [
            [['title', 'type', 'cover_img','detail'], 'safe'],
            // 列表的验证
            [['title'], 'string',
                'max' => 30,
                'skipOnEmpty' => true,
                'when' => function () {
                    return Yii::$app->request->get("title") !== '';
                },
                'on' => 'recipe_list',
                'tooLong' => 'The maximum length of the title is 30 characters',
            ],
            [['type'], 'integer',
                'skipOnEmpty' => true,
                'when' => function () {
                    return Yii::$app->request->get('type', null) !== null; // 只有当 type 被传递时才验证
                },
                'on' => 'recipe_list',
                'message' => 'Incorrect type parameter',
            ],
            [['page'], 'required',
                'when' => function () {
                    return Yii::$app->request->get('page', null) !== null;
                },
                'message' => 'Page cannot be empty.',
                'on' => 'recipe_list',
            ],
            [['page'], 'integer',
                'min' => 1,
                'message' => 'Page Incorrect type parameter',
                'tooSmall' => 'Page number minimum 1',
                'on' => 'recipe_list',
            ],
            [['size'], 'required',
                'when' => function () {
                    return Yii::$app->request->get('size', null) !== null;
                },
                'message' => 'Size cannot be empty.',
                'on' => 'recipe_list',
            ],
            [['size'], 'integer',
                'min' => 1,
                'max' => 100,
                'message' => 'Size Incorrect type parameter',
                'tooSmall' => 'Size number minimum 1',
                'tooBig' => 'Size number maximum 100',
                'on' => 'recipe_list',
            ],
            //上传图片验证
            ['image_file', 'required',
                'on' => 'upload_image',
                'message' => 'You must upload an image file.' // 自定义错误信息
            ],
            [['image_file'], 'file',
                'skipOnEmpty' => false,
                "maxSize"=>1024*1024,
                'mimeTypes' => 'image/*',"on"=>"upload_image",
                "message"=>"Images can only be uploaded in png,jpg,jpeg format and within 1M in size",
                'wrongExtension' => 'Only PNG, JPG, and JPEG formats are allowed.', // 格式错误
                'wrongMimeType' => 'Only pictures can be uploaded',
                'tooBig' => 'The file size must not exceed 1MB.', // 文件过大
            ],
            // 添加菜谱验证
            [
                ["title"],"required","message"=>"Please enter the title",'on' => 'add_recipe',
            ],
            [['title'], 'string',
                'max' => 200,
                "min" => 1,
                'on' => 'add_recipe',
                'tooLong' => 'The maximum length of the title is 30 characters',
                "tooShort" => 'The minimum length of the title is 1 characters'

            ],
            [
                ["type"],"required","message"=>"Please enter the type",'on' => 'add_recipe',
            ],
            [
                ['type'], 'integer',
                'min' => 0,//为了兼容前端，暂时不做验证。因为下发了一个全部all类型，类型是0，暂时不做验证。
                'tooSmall' => 'The minimum length of the type is 0',
                'on' => 'add_recipe',
            ],
            [
                ["cover_img"],"required","message"=>"Please enter the cover picture",'on' => 'add_recipe',
            ],
            [['cover_img'], 'string',
                "message"=>"The cover picture format is incorrect",
                'on' => 'add_recipe',
            ],
            [
                ["detail"],"required","message"=>"Please enter the detailed steps",'on' => 'add_recipe',
            ],
            [
                ['detail'], 'string',
                "message"=>"The detailed steps format is incorrect",
                'on' => 'add_recipe',
            ],
            //删除菜谱验证
            [
                ["id"],"required","message"=>"Missing parameter",'on' => 'del_recipe',
            ],
            [
                ["id"],"integer",
                'min' => 1,
                'tooSmall' => 'The minimum length of the type is 1',
                "message"=>"Missing parameter",'on' => 'del_recipe',
            ],

            //收藏列表验证
            [['title'], 'string',
                'max' => 30,
                'skipOnEmpty' => true,
                'when' => function () {
                    return Yii::$app->request->get("title") !== '';
                },
                'on' => 'collect_list',
                'tooLong' => 'The maximum length of the title is 30 characters',
            ],
            [['page'], 'required',
                'when' => function () {
                    return Yii::$app->request->get('page', null) !== null;
                },
                'message' => 'Page cannot be empty.',
                'on' => 'collect_list',
            ],
            [['page'], 'integer',
                'min' => 1,
                'message' => 'Page Incorrect type parameter',
                'tooSmall' => 'Page number minimum 1',
                'on' => 'collect_list',
            ],
            [['size'], 'required',
                'when' => function () {
                    return Yii::$app->request->get('size', null) !== null;
                },
                'message' => 'Size cannot be empty.',
                'on' => 'collect_list',
            ],
            [['size'], 'integer',
                'min' => 1,
                'max' => 100,
                'message' => 'Size Incorrect type parameter',
                'tooSmall' => 'Size number minimum 1',
                'tooBig' => 'Size number maximum 100',
                'on' => 'collect_list',
            ],
            //点击收藏的验证
            [
                ["id"],"required","message"=>"Missing parameter",'on' => 'collect',
            ],
            [
                ["id"],"integer",
                'min' => 1,
                'tooSmall' => 'The minimum length of the type is 1',
                "message"=>"Missing parameter",'on' => 'collect',
            ],
            //查看详情
            [
                ["id"],"required","message"=>"Missing parameter",'on' => 'detail',
            ],
            [
                ["id"],"integer",
                'min' => 1,
                'tooSmall' => 'The minimum length of the param is 1',
                "message"=>"Missing parameter",'on' => 'detail',
            ],
            //编辑菜谱验证
            [
                ["id"],"required","message"=>"Missing parameter",'on' => 'edit_recipe',
            ],
            [
                ["id"],"integer",
                'min' => 1,
                'tooSmall' => 'The minimum length of the param is 1',
                "message"=>"Missing parameter",'on' => 'edit_recipe',
            ],
            [
                ["title"],"required","message"=>"Please enter the title",'on' => 'edit_recipe',
            ],
            [['title'], 'string',
                'max' => 200,
                "min" => 1,
                'on' => 'edit_recipe',
                'tooLong' => 'The maximum length of the title is 30 characters',
                "tooShort" => 'The minimum length of the title is 1 characters'

            ],
            [
                ["type"],"required","message"=>"Please enter the type",'on' => 'edit_recipe',
            ],
            [
                ['type'], 'integer',
                'min' => 1,
                'tooSmall' => 'The minimum length of the type is 1',
                'on' => 'edit_recipe',
            ],
            [
                ["cover_img"],"required","message"=>"Please enter the cover picture",'on' => 'edit_recipe',
            ],
            [['cover_img'], 'string',
                "message"=>"The cover picture format is incorrect",
                'on' => 'edit_recipe',
            ],
            [
                ["detail"],"required","message"=>"Please enter the detailed steps",'on' => 'edit_recipe',
            ],
            [
                ['detail'], 'string',
                "message"=>"The detailed steps format is incorrect.",
                'on' => 'edit_recipe',
            ],

            //我的食谱验证
            [['page'], 'required',
                'when' => function () {
                    return Yii::$app->request->get('page', null) !== null;
                },
                'message' => 'Page cannot be empty.',
                'on' => 'my_recipe',
            ],
            [['page'], 'integer',
                'min' => 1,
                'message' => 'Page Incorrect type parameter',
                'tooSmall' => 'Page number minimum 1',
                'on' => 'my_recipe',
            ],
            [['size'], 'required',
                'when' => function () {
                    return Yii::$app->request->get('size', null) !== null;
                },
                'message' => 'Size cannot be empty.',
                'on' => 'my_recipe',
            ],
            [['size'], 'integer',
                'min' => 1,
                'max' => 100,
                'message' => 'Size Incorrect type parameter',
                'tooSmall' => 'Size number minimum 1',
                'tooBig' => 'Size number maximum 100',
                'on' => 'my_recipe',
            ],
        ];

    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public function scenarios() :array
    {
        $scenarios = parent::scenarios();
        $scenarios['recipe_list'] = ['title', 'type',"page","size"];
        $scenarios['upload_image'] = ['image_file'];
        $scenarios['add_recipe'] = ['title', 'type',"detail","cover_img"];
        $scenarios['del_recipe'] = ['id'];
        $scenarios['collect'] = ['id'];
        $scenarios['detail'] = ['id'];
        $scenarios['collect_list'] = ['title',"page","size"];
        $scenarios['my_recipe'] = ["page","size"];
        return $scenarios;
    }
}
