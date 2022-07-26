-- ----------------------------
-- 创建数据库
-- ----------------------------
CREATE DATABASE `myframe`;
-- ----------------------------
-- 选择数据库
-- ----------------------------
USE `myframe`;

# 用户表
CREATE TABLE `cms_user`(
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(32) DEFAULT '' NOT NULL UNIQUE COMMENT '用户名',
  `password` CHAR(32) DEFAULT '' NOT NULL COMMENT '密码',
  `salt` CHAR(32) DEFAULT '' NOT NULL COMMENT '密码salt'
) DEFAULT CHARSET=utf8mb4;

# 添加管理员记录
INSERT INTO `cms_user` VALUES
(1, 'admin', MD5(CONCAT(MD5('123456'), 'salt')), 'salt');

# 栏目表
CREATE TABLE `cms_category`(
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(15) NOT NULL COMMENT '名称',
  `sort` INT NOT NULL DEFAULT 0 COMMENT '排序'
) DEFAULT CHARSET=utf8mb4;

# 添加栏目数据
INSERT INTO `cms_category` VALUES
(1, '生活', 0),
(2, '资讯', 1),
(3, '编程', 2),
(4, '互联网', 3),
(5, '科技', 4);

# 文章表
CREATE TABLE `cms_article`(
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `cid` INT UNSIGNED DEFAULT 0 NOT NULL COMMENT '栏目ID',
  `title` VARCHAR(80) DEFAULT '' NOT NULL COMMENT '标题',
  `author` VARCHAR(15) DEFAULT '' NOT NULL COMMENT '作者',
  `image` VARCHAR(255) DEFAULT '' NOT NULL COMMENT '封面图',
  `show` TINYINT DEFAULT 0 NOT NULL COMMENT '是否发布',
  `views` INT UNSIGNED DEFAULT 0 NOT NULL COMMENT '阅读量',
  `content` TEXT NOT NULL COMMENT '内容',
  `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT '创建时间'
) DEFAULT CHARSET=utf8mb4;

# 添加默认文章数据
INSERT INTO `cms_article` VALUES
(1, 1, '这是第一篇文章', 'admin', '', 1, 0, '<p>欢迎使用 PHP内容管理系统！</p><p>这是一篇系统自动生成的文章，您可以修改或删除。</p>', now()),
(2, 1, 'PHP微信公众平台开发', 'admin', '2019-11/21/ed27a1ba3b93801cde7a4d0f2ff26958.png', 1, 0, '在“智能手机”时代，没有人不识微信！', now()),
(3, 1, '要想提高PHP的编程效率，你必须知道的49个要点', 'admin', '', 1, 0, '', now()),
(4, 1, '想少走弯路，就看这篇文章：PHPer职业发展规划与技能需求！', 'admin', '', 1, 0, '', now()),
(5, 1, '前端必学框架Bootstrap，3天带你从入门到精通，免费分享！', 'admin', '', 1, 0, '', now()),
(6, 1, 'MySQL手册免费分享', 'admin', '', 1, 0, '', now());

CREATE TABLE `cms_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '标签名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '显示状态0显示1不显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cms_tag` VALUES
(1, 'PHP', 0, 0),
(2, 'JAVA', 1, 0),
(3, 'Python', 2, 0),
(4, 'MySQL', 3, 0),
(5, 'C++', 4, 0);