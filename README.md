# 食谱模块安装
### 1：直接在线上包，通过composer安装
```bash
composer require ysx123/recipe:dev-master --ignore-platform-reqs
```
### 2：在项目中增加模块的路由。修改配置文件：D:\www\ysx_www\ph02\shiny-pera-ios-dc\frontend\config\main.php
```php
 'components'=>[
 'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'rules' =>  $url_rules,
            'rules' =>  array_merge($url_rules,['recipe/<action:\w+>' => 'recipe/recipe/<action>']),
  ],
],
//增加模块
'modules' => [
        'recipe' => [
            'class' => 'ysx\recipe\Module',
        ],
    ],
```
### 3：app-api-doc项目增加url路由，用于混淆。修改url.php文件
```php
    //因为这是一个独立出去的模块。所以前面加个recipe模块名
    '/recipe/recipe/recipe-type' => "credit/syncabl1",
    '/recipe/recipe/index' => "credit/syncabl2",
    '/recipe/recipe/collect-list' => "credit/syncabl3",
    '/recipe/recipe/detail' => "credit/syncabl4",
    '/recipe/recipe/collect' => "credit/syncabl5",
    '/recipe/recipe/upload-image' => "credit/syncabl6",
    '/recipe/recipe/add-recipe' => "credit/syncabl7",
    '/recipe/recipe/del-recipe' => "credit/syncabl8",
    '/recipe/recipe/my-recipe' => "credit/syncabl9",
```
### 4:修改app-api-doc项目，增加文档，直接复制到相应项目的文档目录里。示例文档在：D:\www\ysx_www\app-api-doc\docs\ph_shiny_pera_ios\recipe.md

### 5：执行生成混淆路由和混淆字段
```bash
#混淆字段
php generater.php 目录名
#混淆路由
php generate_url.php 目录名 前缀
```

### 6：在对应包中导入sql语句，文件在D:\www\ysx_www\ph02\shiny-pera-ios-dc\recipe\models\recipe.sql