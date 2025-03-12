<?php
use yii\db\Migration;

class m230225_123456_create_recipe_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB CHARSET=utf8mb4 COMMENT="食谱表"';
        }

        $this->createTable('{{%recipe}}', [
            'id' => $this->primaryKey()->unsigned()->comment('主键ID'),
            'title' => $this->string(200)->notNull()->defaultValue('')->comment('食谱标题'),
            'cover_img' => $this->string(255)->notNull()->defaultValue('')->comment('封面图片'),
            'type' => $this->smallInteger(3)->defaultValue(null)->comment('类型'),
            'recommend' => $this->tinyInteger(1)->notNull()->defaultValue(1)->comment('1：不推荐 2：推荐'),
            'detail' => $this->text()->notNull()->comment('详细内容'),
            'user_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('用户ID'),
            'created_at' => $this->timestamp()->defaultValue(null)->comment('创建时间'),
            'updated_at' => $this->timestamp()->defaultValue(null)->comment('更新时间'),
        ], $tableOptions);

        $this->execute("INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('28','Classic Spaghetti Carbonara','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303175652_ogsscg3fdi.png?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930211812&Signature=rEsPtTPcxKAczhe7GW0MY4RWHbE%3D','1','2','Ingredients
200g spaghetti
100g pancetta (or bacon), diced
2 large eggs
50g grated Parmesan cheese
2 cloves garlic, minced
1 tbsp olive oil
Salt and black pepper to taste
Fresh parsley for garnish (optional)
Instructions
Cook the Pasta

Bring a large pot of salted water to a boil.
Add the spaghetti and cook until al dente (about 8-10 minutes).
Reserve ½ cup of pasta water before draining.
Prepare the Sauce

In a bowl, whisk together eggs, Parmesan cheese, and black pepper. Set aside.
Cook the Pancetta

Heat olive oil in a large pan over medium heat.
Add diced pancetta and cook until crispy (about 4-5 minutes).
Add minced garlic and sauté for 30 seconds.
Combine Ingredients

Reduce the heat to low and add the drained pasta to the pan. Toss to coat.
Remove the pan from heat and slowly pour in the egg mixture, stirring quickly to create a creamy sauce.
If needed, add reserved pasta water to adjust the consistency.
Serve

Plate the pasta and sprinkle extra Parmesan cheese on top.
Garnish with fresh parsley and serve immediately.','6359','2025-03-03 17:53:50','2025-03-03 17:53:50');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('30','Spicy Sichuan Mapo Tofu','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303182321_wrs5w8mdwo.png?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930213401&Signature=gfK5swVfnUXMaCwHg1jkH8Toc14%3D','9','1','Ingredients
400g soft tofu, cut into cubes
150g ground pork (optional, can be substituted with mushrooms for vegetarian)
2 tbsp Sichuan broad bean paste (Doubanjiang)
1 tbsp fermented black beans, minced
2 cloves garlic, minced
1 tsp ginger, minced
1 tbsp Sichuan peppercorns
1 tsp chili flakes (adjust to taste)
1 tbsp soy sauce
1 cup chicken or vegetable broth
1 tsp cornstarch mixed with 2 tbsp water (for thickening)
2 green onions, chopped
1 tbsp cooking oil
Instructions
Prepare the Ingredients

Cut the tofu into bite-sized cubes and soak in warm salted water for a few minutes to firm up. Drain before use.
Toast the Sichuan Peppercorns

Heat a dry pan and toast the Sichuan peppercorns until fragrant. Crush them into a coarse powder using a mortar and pestle or a grinder.
Cook the Pork and Aromatics

Heat 1 tbsp oil in a pan or wok over medium heat.
Add the ground pork and stir-fry until browned.
Add garlic, ginger, and fermented black beans, stirring until aromatic.
Add the Spicy Sauce

Stir in the broad bean paste (Doubanjiang) and chili flakes, frying for 30 seconds to release the flavor.
Pour in the broth and soy sauce, then gently add the tofu cubes.
Simmer and Thicken

Let it simmer for about 5 minutes, allowing the tofu to absorb the flavors.
Stir in the cornstarch mixture and cook until the sauce thickens.
Final Touches

Sprinkle crushed Sichuan peppercorns and chopped green onions on top.
Serve hot with steamed rice.','6359','2025-03-03 18:25:00','2025-03-03 18:25:00');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('32','Kung Pao Chicken','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303223451_z0xawm7du4.png?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930228491&Signature=cmNdwcuDlFETTjC07DCBjhIVXoI%3D','9','1','Category: Sichuan Cuisine
Flavor: Spicy, sweet, and nutty
Main Ingredients: Chicken breast, dried red chilies, peanuts, green onions, garlic, soy sauce, vinegar
Cooking Steps:
Cut chicken into small cubes and marinate with soy sauce and cornstarch.
Stir-fry the chicken until golden brown, then remove from pan.
Sauté dried chilies, garlic, and green onions until fragrant.
Add the chicken back and toss with soy sauce, vinegar, and sugar.
Stir in peanuts and cook for another minute.
Serve hot, garnished with sesame seeds and chopped scallions.','6359','2025-03-03 22:34:58','2025-03-03 22:34:58');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('33','Braised Pork Belly','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303224054_l0b9f9kcgl.png?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930228854&Signature=TDa55F9mE%2FvongivsIPR839tnKU%3D','9','1','Flavor: Savory, slightly sweet
Main Ingredients:
500g pork belly, cut into cubes
2 tbsp soy sauce
1 tbsp rock sugar
2 slices ginger
2 cloves garlic
1 star anise
1 cup water
Steps:

Blanch the pork belly in boiling water for 5 minutes. Drain and set aside.
In a pan, melt rock sugar until caramelized, then add pork belly and stir-fry.
Add soy sauce, ginger, garlic, and star anise, then pour in water.
Cover and simmer for 1.5 hours until the pork is tender and the sauce thickens.','6359','2025-03-03 22:41:02','2025-03-03 22:41:02');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('34','Sweet and Sour Pork','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303224324_w0z1mcfcqk.png?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930229004&Signature=08MVxovDMlWW1n30avBugY%2F7H40%3D','3','1','Flavor: Tangy, sweet, crispy
Main Ingredients:
300g pork loin, cut into cubes
1/2 cup cornstarch
1/2 cup bell peppers, diced
1/2 cup pineapple chunks
2 tbsp vinegar
2 tbsp ketchup
1 tbsp sugar
1 tbsp soy sauce
Steps:

Coat the pork pieces in cornstarch and deep-fry until crispy. Set aside.
In a wok, stir-fry bell peppers and pineapple for 2 minutes.
Mix vinegar, ketchup, sugar, and soy sauce in a bowl, then pour into the wok.
Add fried pork and toss well until coated in sauce. Serve hot.','6359','2025-03-03 22:43:33','2025-03-03 22:43:33');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('35','Hot Pot','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303224513_7nia1go392.png?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930229113&Signature=TlfNap6l8m9dP%2F5Qh4ez02IU76Q%3D','9','2','Flavor: Spicy, numbing
Main Ingredients:
3 dried chili peppers
1 tbsp Sichuan peppercorns
1 tbsp doubanjiang (fermented chili bean paste)
4 cups chicken broth
Assorted meats, tofu, mushrooms
Steps:

Heat oil in a pot, add dried chilies, peppercorns, and doubanjiang. Stir-fry until fragrant.
Pour in chicken broth and bring to a boil.
Add meats, tofu, and vegetables. Cook for 2-3 minutes before eating.','6359','2025-03-03 22:45:24','2025-03-03 22:45:24');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('36','Cantonese Shrimp Dumplings','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303224641_zldniy0uti.jpg?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930229201&Signature=Vo9BaLDDLD%2F8SsVfJAa0gILhP%2BY%3D','3','1','Flavor: Light, delicate
Main Ingredients:
200g shrimp, chopped
1/4 cup bamboo shoots, finely diced
1 tbsp soy sauce
1 tsp sesame oil
10 dumpling wrappers
Steps:

Mix shrimp, bamboo shoots, soy sauce, and sesame oil.
Place 1 spoonful of filling in each dumpling wrapper and fold.
Steam over high heat for 5 minutes. Serve with soy sauce.','6359','2025-03-03 22:46:48','2025-03-03 22:46:48');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('37','Beef Wellington','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303224804_u3p3pm21gb.png?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930229284&Signature=epsAb0B5Gk8lqSpHqLsJj4YfSqE%3D','1','1','Flavor: Rich, buttery, flaky
Main Ingredients:
300g beef tenderloin
1 sheet puff pastry
1 cup mushrooms, finely chopped
1 tbsp Dijon mustard
1 egg, beaten
Steps:

Sear the beef for 2 minutes per side. Brush with Dijon mustard.
Cook mushrooms until dry, then spread over puff pastry.
Place beef on top, wrap in pastry, and brush with egg wash.
Bake at 400°F (200°C) for 25 minutes.','6359','2025-03-03 22:48:12','2025-03-03 22:48:12');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('38','Spaghetti Carbonara','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303224947_7hogjz9a7i.png?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930229387&Signature=WjGDbVff4DQoddHBzVb9sA94c0o%3D','1','1','Flavor: Creamy, cheesy
Main Ingredients:
200g spaghetti
100g pancetta, diced
2 eggs
1/2 cup Parmesan cheese
Black pepper
Steps:

Cook spaghetti and save 1/2 cup pasta water.
Fry pancetta until crispy.
Whisk eggs, cheese, and pepper together.
Toss hot spaghetti with egg mixture, adding pasta water to create a creamy sauce.','6359','2025-03-03 22:50:03','2025-03-03 22:50:03');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('39','Grilled Salmon with Lemon Butter','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303225250_yeyp4ucl4w.jpg?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930229570&Signature=Q4gy%2F%2FfhaZw8R98ViyCQRTRFZWo%3D','1','1','Flavor: Fresh, citrusy
Main Ingredients:
1 salmon fillet
1 tbsp butter
1/2 lemon, sliced
1 tsp garlic, minced
1 tbsp parsley, chopped
Steps:

Season salmon with salt, pepper, and lemon juice.
Grill for 5 minutes per side until golden.
Melt butter, add garlic and parsley, then pour over salmon.','6359','2025-03-03 22:52:57','2025-03-03 22:52:57');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('40','French Onion Soup','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303231217_mc7570qt29.png?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930230737&Signature=MaICo8jZ2Wx%2BPnr8Rfo8Y%2F19sEg%3D','1','1','Flavor: Deep, caramelized, cheesy
Main Ingredients:
3 onions, sliced
2 cups beef broth
1/2 cup white wine
1/2 baguette, sliced
1/2 cup cheese, grated
Steps:

Slowly caramelize onions in butter for 20 minutes.
Add wine and broth, simmer for another 15 minutes.
Pour into bowls, top with toasted baguette and cheese.
Broil until cheese is melted and bubbly.','6359','2025-03-03 22:55:28','2025-03-03 22:55:28');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('41','Classic Caesar Salad','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303225627_ju83ngjpdy.png?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930229787&Signature=ZRfBGAxoo1pXR%2F%2BqIobNEC3QD5o%3D','1','1','Flavor: Crunchy, creamy, savory
Main Ingredients:
1 Romaine lettuce head
1/2 cup croutons
1/4 cup Parmesan cheese
2 tbsp Caesar dressing
Steps:

Chop lettuce and mix with dressing.
Toss with croutons and Parmesan cheese.
Serve chilled.','6359','2025-03-03 22:56:34','2025-03-03 22:56:34');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('42','Filipino Adobo','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303230006_yn22pas6qk.jpg?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930230006&Signature=61a5M5WqRum4uM65uMGnbj1fqmQ%3D','2','1','Flavor: Savory, tangy, garlicky
Main Ingredients:
500g chicken (or pork), cut into pieces
1/2 cup soy sauce
1/4 cup vinegar
4 cloves garlic, minced
2 bay leaves
1 tsp black peppercorns
1 cup water
1 tbsp sugar
Steps:

Marinate the chicken (or pork) in soy sauce, vinegar, garlic, bay leaves, and peppercorns for 30 minutes.
Heat oil in a pan, add marinated meat, and brown on all sides.
Pour in the marinade and add water. Bring to a boil, then simmer for 30 minutes.
Add sugar, stir well, and cook until the sauce thickens.
Serve with steamed rice.','6359','2025-03-03 23:00:12','2025-03-03 23:00:12');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('43','Filipino Sour Soup','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303230131_seja3yb66r.jpg?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930230091&Signature=%2BpSX9cuq%2FLxu0JinaSpfl2FszT0%3D','2','1','Flavor: Sour, savory
Main Ingredients:
500g pork ribs (or shrimp, fish)
6 cups water
2 tomatoes, quartered
1 onion, sliced
5 pieces tamarind (or 2 tbsp tamarind paste)
1 cup string beans
1 cup water spinach (kangkong)
1 eggplant, sliced
1 tbsp fish sauce
Salt and pepper to taste
Steps:

In a pot, boil pork ribs in water for 30 minutes.
Add tomatoes, onion, and tamarind. Simmer until meat is tender.
Strain out tamarind, mash it, and return the juice to the pot (skip this step if using tamarind paste).
Add eggplant, string beans, and fish sauce. Cook for 5 minutes.
Add water spinach last, season with salt and pepper, then serve hot.','6359','2025-03-03 23:01:39','2025-03-03 23:01:39');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('44','Oxtail Peanut Stew','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303230311_zlez6rvcdk.png?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930230191&Signature=5SNRRDFB5Gjok2%2Bb5e0WlDYRw4U%3D','2','1','Flavor: Nutty, savory, rich
Main Ingredients:
500g oxtail (or beef shank)
6 cups water
1/2 cup peanut butter
1/4 cup ground rice (or cornstarch slurry)
1 eggplant, sliced
1 bunch string beans, cut into pieces
1/2 cup banana blossoms (optional)
1 tbsp annatto powder (for color)
3 cloves garlic, minced
1 onion, chopped
Salt and fish sauce to taste
Steps:

Boil the oxtail in water for 2 hours until tender. Save the broth.
In a separate pan, sauté garlic and onion, then add annatto powder.
Stir in peanut butter and ground rice, then slowly add the broth. Mix well.
Add the boiled oxtail and simmer for 10 minutes.
Add eggplant, string beans, and banana blossoms. Cook for another 5 minutes.
Serve with shrimp paste (bagoong) on the side.','6359','2025-03-03 23:03:17','2025-03-03 23:03:17');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('45','Grilled Chicken Inasal','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303230422_74b109duae.jpg?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930230262&Signature=czJTSgqraZGo50vPN9sMH667FoA%3D','2','2','Flavor: Smoky, tangy, slightly sweet
Main Ingredients:
500g chicken thighs or drumsticks
1/4 cup calamansi juice (or lime juice)
1/4 cup coconut vinegar
4 cloves garlic, minced
1 tbsp annatto oil (or vegetable oil)
1 tbsp soy sauce
1 tbsp sugar
Salt and pepper to taste
Steps:

Mix calamansi juice, vinegar, garlic, soy sauce, sugar, salt, and pepper in a bowl.
Marinate chicken for at least 1 hour (overnight for best flavor).
Grill over medium heat, brushing with annatto oil occasionally, until fully cooked (about 15-20 minutes per side).
Serve with garlic rice and dipping sauce (soy sauce + calamansi juice).','6359','2025-03-03 23:04:29','2025-03-03 23:04:29');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('46','Filipino Caramel Flan','http://ph02-pera-life-ios.oss-ap-southeast-6.aliyuncs.com/recipe/20250303230535_okqv3kngvl.png?OSSAccessKeyId=LTAI5tBg8SgAttbiT8E54G3C&Expires=1930230335&Signature=QcitAiB%2BEZBTQZ6sld9nyFVV7fI%3D','2','1','lavor: Sweet, creamy, silky
Main Ingredients:
10 egg yolks
1 can (300ml) condensed milk
1 can (370ml) evaporated milk
1/2 cup sugar (for caramel)
1 tsp vanilla extract
Steps:

In a small pan, melt sugar over low heat until golden brown. Pour into flan molds.
In a bowl, whisk egg yolks, condensed milk, evaporated milk, and vanilla.
Strain the mixture and pour it into the caramel-lined molds.
Cover with foil and steam for 30-40 minutes or bake in a water bath at 160°C for 1 hour.
Cool, refrigerate, then invert onto a plate before serving.','6359','2025-03-03 23:05:42','2025-03-03 23:05:42');");
    }

    public function down()
    {
        $this->dropTable('{{%recipe}}');
    }
}
