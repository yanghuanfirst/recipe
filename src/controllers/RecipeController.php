<?php
namespace ysx\recipe\controllers;

use common\enums\codes\ResponseCode;
use common\helpers\OssHelper;
use common\helpers\ReturnHelper;
use common\helpers\Util;
use common\models\Recipe;
use common\models\RecipeCollect;
use common\models\youmi\FaceProcessLog;
use common\services\youmi\PictureService;
use Yii;
use yii\web\UploadedFile;

class RecipeController extends BaseController
{
    public $requireLoginActions = [
        'upload-image',
        'add-recipe',
        'del-recipe',
        'collect-list',
        'collect',
        'detail'


    ];

    /**
     * @desc actionIndex 首页菜谱列表
     * @create_at 2025/2/9 17:26
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
        $recommend = Recipe::find()->where(["recommend"=>2])->limit(3)->asArray()->all();
        return $this->formatJson(0, 'success', compact('total','list','recommend'));
    }

    /**
     * @desc actionUploadImage  上传图片
     * @create_at 2025/2/9 22:01
     * @return array|string
     */
    function actionUploadImage(){
        //$userId = $this->getLoginUser();
        $model = new Recipe();
        $model->scenario = 'upload_image';
        $model->image_file = UploadedFile::getInstanceByName('image_file');
        if ($model->validate()) {
            //return json_encode(['success' => true, 'url' => Yii::getAlias('@web/uploads/') . basename($filePath)]);
        } else {
            //print_r($model->errors);
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
     * @create_at 2025/2/10 10:29
     * @return array|string
     */
    function actionAddRecipe():array
    {
        $userId = $this->getLoginUserId();
        $data = Yii::$app->request->post();
        $recipeModel = new Recipe();
        $recipeModel->scenario = 'add_recipe';
        $recipeModel->load($data,'');
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
     * @create_at 2025/2/10 11:20
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
     * @create_at 2025/2/10 15:20
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

        $list = Recipe::find()->where($map)->offset($offset)->limit($pageSize)->orderBy(["id"=>SORT_DESC])->asArray()->all();
        $total = Recipe::find()->where($map)->count();
        return $this->formatJson(0, 'success',compact("list","total"));
    }

    /**
     * @desc actionCollect 收藏或者取消收藏
     * @create_at 2025/2/10 17:23
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
     * @create_at 2025/2/10 17:53
     * @return array
     */
    function actionDetail():array
    {
        $request = Yii::$app->request;
        $recipeModel = new Recipe();
        $recipeModel->scenario = 'detail';
        $recipeModel->load(Yii::$app->request->get(),"");
        if (!$recipeModel->validate()) {
            return $this->formatJson(ResponseCode::PARAM_CHECK_FAIL, current($recipeModel->getFirstErrors()));
        }
        $recipeId = $request->get("id",0);
        $info = Recipe::find()->select(["id","title","cover_img","type","detail","created_at"])->where(["id"=>$recipeId])->asArray()->one();

        return $this->formatJson(0, 'success',compact("info"));
    }

    /**
     * @desc actionEditRecipe 编辑食谱
     * @create_at 2025/2/10 18:00
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