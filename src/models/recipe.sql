

CREATE TABLE `recipe` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '食谱标题',
  `cover_img` varchar(100) NOT NULL DEFAULT '' COMMENT '封面图片',
  `type` smallint(3) DEFAULT NULL COMMENT '类型',
  `recommend` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1：不推荐 2：推荐',
  `detail` text NOT NULL COMMENT '详细内容',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='食谱表';


INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('1','adsf23','sdaf','1','2','asdf','1','2024-05-02 22:15:10','2024-05-02 22:15:10');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('2','1231','2','1','2','12312','0',null,null);
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('4','麻婆豆腐','https://www.baidu,com/asdad.jpg','1','2','1:买豆腐 2：给饭店做','11','2025-02-09 22:54:52','2025-02-11 15:24:29');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('5','1231','2','1','1','12312','6359','2025-02-09 22:54:53','2025-02-09 22:54:53');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('7','1231','2','1','1','12312','11','2025-02-09 23:00:25','2025-02-09 23:00:25');
INSERT INTO `recipe` (`id`, `title`, `cover_img`, `type`, `recommend`, `detail`, `user_id`, `created_at`, `updated_at`) VALUES ('8','dfadfadfadfa','1','1','1','12312','6359','2025-02-26 16:26:11','2025-02-26 16:26:11');


CREATE TABLE `recipe_collect` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `recipe_id` int(11) NOT NULL DEFAULT '0' COMMENT '食谱表ID',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '收藏时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='收藏表';

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `recipe_collect` (`id`, `user_id`, `recipe_id`, `created_at`, `updated_at`) VALUES ('1','6359','4','2025-02-10 14:22:15','2025-02-10 14:22:15');
INSERT INTO `recipe_collect` (`id`, `user_id`, `recipe_id`, `created_at`, `updated_at`) VALUES ('5','6359','1','2025-02-26 17:34:01','2025-02-26 17:34:01');

