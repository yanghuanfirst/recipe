<?php
namespace ysx\recipe\controllers;
 
use common\enums\codes\ResponseCode;
use common\helpers\OssHelper;
use common\helpers\ReturnHelper;
use common\helpers\Util;
use ysx\recipe\models\Recipe;
use ysx\recipe\models\RecipeCollect;
use common\models\youmi\FaceProcessLog;
use common\services\youmi\PictureService;
use Yii;
use yii\web\UploadedFile;
use frontend\controllers\BaseController;

class RecipeController extends BaseController
{
    public $requireLoginActions = [
        'upload-image',
        'add-recipe',
        'del-recipe',
        'collect-list',
        'collect',
        'my-recipe'
    ];
    protected $recipeType = [
        ["id"=>0,
            "value"=>"All"],
        ["id"=>1,
            "value"=>"western food"],
        ["id"=>2,
            "value"=>"philippine stew seasoning"],
        ["id"=>3,
            "value"=>"Guangdong cuisine"],
        ["id"=>4,
            "value"=>"Fujian cuisine"],
        ["id"=>5,
            "value"=>"Shandong cuisine"],
        ["id"=>6,
            "value"=>"Jiangxi cuisine"],
        ["id"=>7,
            "value"=>"Hubei cuisine"],
        ["id"=>8,
            "value"=>"Hainan cuisine"],
        ["id"=>9,
            "value"=>"Sichuan cuisine"],
        ["id"=>10,
            "value"=>"Hunan cuisine"],
    ];

    /**
     * @desc actionRecipeType 菜谱类型
     * @create_at 2025/2/26 11:06
     * @return array
     */
    function actionRecipeType():array
    {
        return $this->formatJson(0, 'success', ["type_list"=>$this->recipeType]);
    }
    /**
     * @desc actionIndex 首页菜谱列表
     * @create_at 2025/2/26 17:26
     * @return array|string
     */
    function actionIndex():array
    {
        $request = Yii::$app->request;
        $title = $request->get('title',"");//标题
        $type = $request->get('type',0);//类型
        $page = $request->get("page",1);
        $pageSize = $request->get("size",10);
        $recipeModel = new Recipe();
        $recipeModel->scenario = 'recipe_list';
        $recipeModel->load(Yii::$app->request->get(),"");
        if (!$recipeModel->validate()) {
            return $this->formatJson(ResponseCode::PARAM_CHECK_FAIL, current($recipeModel->getFirstErrors()));
        }
        $map = ["and"];
        if($title){
            $map[] = ['like','title',$title];
        }
        if($type){
            $map[] = ['type'=>$type];
        }
        $offset = ($page - 1) * $pageSize;
        $total = Recipe::find()->where($map)->count();
        $list = Recipe::find()->select(["id","title","cover_img","type","created_at"])->where($map)->orderBy([
            'id' => SORT_DESC,
        ])->offset($offset)->limit($pageSize)->asArray()->all();
        //查询推荐的3条的数据
        $recommend = Recipe::find()->select(["id","title","cover_img","type"])->where(["recommend"=>2])->limit(3)->asArray()->all();
        return $this->formatJson(0, 'success', compact('total','list','recommend'));
    }

    /**
     * @desc actionUploadImage  上传图片
     * @create_at 2025/2/26 22:01
     * @return array|string
     */
    function actionUploadImage(){
        $model = new Recipe();
        $model->scenario = 'upload_image';
        //为了通用，后续的字段名都用took
        $model->image_file = UploadedFile::getInstanceByName('took');
        Yii::info("食谱上传图片 " . json_encode($model->image_file, JSON_UNESCAPED_UNICODE), "appInfo");
        if (!$model->validate()) {
            //return json_encode(['success' => true, 'url' => Yii::getAlias('@web/uploads/') . basename($filePath)]);
            return $this->formatJson(ResponseCode::PARAM_CHECK_FAIL, current($model->getFirstErrors()));
        }
        $extension = substr($model->image_file->name, strrpos($model->image_file->name, '.') + 1);
        $object = 'recipe/'.Util::getNewName($extension);
        $configKey = self::getOssConfigKey();
        //$bucketName = self::getBucketName($configKey);
        $localFile = $model->image_file->tempName;
        $backImage = "";
        try {
            $res = OssHelper::uploadFile($object, $localFile, $configKey);
            if (!$res) {
                return ReturnHelper::error('Image upload failed, please try again', (object)[], ReturnHelper::ERR_AAR_FRONT);//OSS上传图片失败
            }
            $configKey = PictureService::getOssConfigKey();
            //身份证
            $backImage = OssHelper::getFileUrl($object, $configKey);
        }catch (\Exception $e){
            return ReturnHelper::error('Image upload failed, please try again.', (object)[], ReturnHelper::ERR_AAR_FRONT);//OSS上传图片失败
        }

        return $this->formatJson(0, 'success',["url"=>$backImage]);
    }

    /**
     * @desc actionAddRecipe
     * @create_at 2025/2/26 10:29
     * @return array|string
     */
    function actionAddRecipe():array
    {
        $userId = $this->getLoginUserId();
        $data = Yii::$app->request->post();
        $recipeModel = new Recipe();
        $recipeModel->scenario = 'add_recipe';
        $recipeModel->load($data,'');
        Yii::info("userId: {$userId} 发布菜谱 " . json_encode($data, JSON_UNESCAPED_UNICODE), "appInfo");
        Yii::info("userId: {$userId} 发布菜谱2 " . json_encode($recipeModel->getErrors(), JSON_UNESCAPED_UNICODE), "appInfo");
        if (!$recipeModel->validate()) {
            return $this->formatJson(ResponseCode::PARAM_CHECK_FAIL, current($recipeModel->getFirstErrors()));
        }
        $recipeModel->user_id = $userId;
        $res = $recipeModel->save();
        if (!$res){
            return $this->formatJson(-1, "add recipe fail please try again");
        }
        return $this->formatJson(0, 'success'); //新增成功
    }

    /**
     * @desc actionDelRecipe 删除食谱
     * @create_at 2025/2/26 11:20
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    function actionDelRecipe():array
    {
        $userId = $this->getLoginUserId();
        $data = Yii::$app->request->post();
        $recipeModel = new Recipe();
        $recipeModel->scenario = 'del_recipe';
        $recipeModel->load($data,'');
        if (!$recipeModel->validate()) {
            return $this->formatJson(ResponseCode::PARAM_CHECK_FAIL, current($recipeModel->getFirstErrors()));
        }
        $info = Recipe::find()->where(["user_id"=>$userId,"id"=>$data["id"]])->one();
        if (!$info){
            return $this->formatJson(-1, "recipe not exist");
        }
        $res = $info->delete();
        if (!$res){
            return $this->formatJson(-1, "delete recipe fail please try again");
        }
        return $this->formatJson(0, 'success'); //删除成功
    }

    /**
     * @desc actionCollectList 收藏列表
     * @create_at 2025/2/26 15:20
     * @return array
     */
    function actionCollectList():array
    {
        $userId = $this->getLoginUserId();
        $request = Yii::$app->request;
        $title = $request->get('title',"");//标题
        $page = $request->get("page",1);
        $pageSize = $request->get("size",10);
        $recipeModel = new Recipe();
        $recipeModel->scenario = 'collect_list';
        $recipeModel->load(Yii::$app->request->get(),"");
        if (!$recipeModel->validate()) {
            return $this->formatJson(ResponseCode::PARAM_CHECK_FAIL, current($recipeModel->getFirstErrors()));
        }
        $collectInfo = RecipeCollect::find()->select(["user_id","recipe_id"])->where(["user_id"=>$userId])->asArray()->all();
        $recipeIds = array_column($collectInfo,"recipe_id");
        $list = [];
        $total = 0;
        if (empty($recipeIds)){
            return $this->formatJson(0, 'success',compact("list","total"));
        }
        $offset = ($page - 1) * $pageSize;
        $map = ["and"];
        if($title){
            $map[] = ['like','title',$title];
        }
        $map[] = ['id'=>$recipeIds];

        $list = Recipe::find()->where($map)->select(["id","title","cover_img","type"])->offset($offset)->limit($pageSize)->orderBy(["id"=>SORT_DESC])->asArray()->all();
        $total = Recipe::find()->where($map)->count();
        return $this->formatJson(0, 'success',compact("list","total"));
    }

    /**
     * @desc actionCollect 收藏或者取消收藏
     * @create_at 2025/2/26 17:23
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    function actionCollect():array
    {
        $userId = $this->getLoginUserId();
        $request = Yii::$app->request;
        $recipeModel = new Recipe();
        $recipeModel->scenario = 'collect';
        $recipeModel->load(Yii::$app->request->post(),"");
        if (!$recipeModel->validate()) {
            return $this->formatJson(ResponseCode::PARAM_CHECK_FAIL, current($recipeModel->getFirstErrors()));
        }
        $recipeId = $request->post("id",0);
        $collectInfo = RecipeCollect::find()->where(["user_id"=>$userId,"recipe_id"=>$recipeId])->one();
        //取消收藏
        if($collectInfo){
            $res = $collectInfo->delete();
        }else{
            //添加收藏
            $collectModel = new RecipeCollect();
            $collectModel->user_id = $userId;
            $collectModel->recipe_id = $recipeId;
            $res = $collectModel->save();
        }
        if(!$res){
            return $this->formatJson(-1, "action fail please try again");
        }
        return $this->formatJson(0, 'success');
    }

    /**
     * @desc actionDetail 查看详情
     * @create_at 2025/2/26 17:53
     * @return array
     */
    function actionDetail():array
    {
        $userId = $this->getLoginUserId();
        $request = Yii::$app->request;
        $recipeModel = new Recipe();
        $recipeModel->scenario = 'detail';
        $recipeModel->load(Yii::$app->request->get(),"");
        Yii::info("userId: {$userId} 食谱详情 " . json_encode(Yii::$app->request->get(), JSON_UNESCAPED_UNICODE), "appInfo");
        if (!$recipeModel->validate()) {
            return $this->formatJson(ResponseCode::PARAM_CHECK_FAIL, current($recipeModel->getFirstErrors()));
        }
        $recipeId = $request->get("id",0);
        $info = Recipe::find()->select(["id","title","cover_img","type","detail","created_at","user_id"])->where(["id"=>$recipeId])->asArray()->one();
        if(!$info)
            return $this->formatJson(-1, "recipe not exist");
        $info["is_collect"] = 0;//0：不显示收藏按钮  1：显示收藏按钮
        $info["is_delete"] = 0;//0：不显示删除按钮  1：显示删除按钮
        $info["is_collected"] = 0;//0：未收藏  1：已收藏
        if($userId){
            $info["is_collect"] = $info["user_id"] == $userId ? 0 : 1;//0：不显示收藏按钮  1：显示收藏按钮
            $info["is_delete"] = $info["user_id"] == $userId ? 1 : 0;//0：不显示删除按钮  1：显示删除按钮
            //判断是否收藏
            if($info["is_collect"] == 1){
                $collected = RecipeCollect::find()->select("id")->where(["user_id"=>$userId,"recipe_id"=>$recipeId])->asArray()->one();
                $info["is_collected"] = $collected ? 1 : 0;
            }
        }
        return $this->formatJson(0, 'success',compact("info"));
    }

    /**
     * @desc actionEditRecipe 编辑食谱
     * @create_at 2025/2/26 18:00
     * @return array
     */
    function actionEditRecipe():array
    {
        $userId = $this->getLoginUserId();
        $data = Yii::$app->request->post();
        $recipeModel = new Recipe();
        $recipeModel->scenario = 'edit_recipe';
        $recipeModel->load($data,'');
        if (!$recipeModel->validate()) {
            return $this->formatJson(ResponseCode::PARAM_CHECK_FAIL, current($recipeModel->getFirstErrors()));
        }
        $recipeModel = Recipe::find()->where(["user_id"=>$userId,"id"=>$data["id"]])->one();
        if(!$recipeModel){
            return $this->formatJson(-1, "recipe not exist");
        }
        $recipeModel->setAttributes($data);
        $res = $recipeModel->save();
        if (!$res){
            return $this->formatJson(-1, "edit recipe fail please try again");
        }
        return $this->formatJson(0, 'action success');
    }

    /**
     * @desc actionMyRecipe 我的发布
     * @create_at 2025/2/26 15:26
     * @return array
     */
    function actionMyRecipe():array
    {
        $userId = $this->getLoginUserId();
        $request = Yii::$app->request;
        $page = $request->get("page",1);
        $pageSize = $request->get("size",10);
        $recipeModel = new Recipe();
        $recipeModel->scenario = 'my_recipe';
        $recipeModel->load(Yii::$app->request->get(),"");
        if (!$recipeModel->validate()) {
            return $this->formatJson(ResponseCode::PARAM_CHECK_FAIL, current($recipeModel->getFirstErrors()));
        }
        $offset = ($page - 1) * $pageSize;
        $total = Recipe::find()->where(["user_id"=>$userId])->count();
        $list = Recipe::find()->where(["user_id"=>$userId])->select(["id","title","cover_img","type","created_at"])->orderBy([
            'id' => SORT_DESC,
        ])->offset($offset)->limit($pageSize)->asArray()->all();
        //收藏数量
        $collectCount = RecipeCollect::find()->where(["user_id"=>$userId])->count();
        return $this->formatJson(0, 'success', compact('total','list',"collectCount"));
    }


    /**
     * 获取oss配置的key
     *
     * @return string
     */
    public static function getOssConfigKey()
    {
        $ossName = 'defaultOss';
        if (ENV == 'prod' || defined('ENV_CONFIG')) {// 正式环境或者docker4环境
            $ossName = 'defaultOss';
        }
        return $ossName;
    }

    /**
     * 获取bucket
     *
     * @param string $configKey
     * @return string
     */
    public static function getBucketName($configKey = '')
    {
        if (empty($configKey)) {
            $configKey = self::getOssConfigKey();
        }
        $params = \Yii::$app->params;
        if (isset($params[$configKey])) {
            $bucket = $params[$configKey][YII_ENV.'Bucket'];
        } else {
            $bucket = '';
        }
        return $bucket;
    }



}