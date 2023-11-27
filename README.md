# agile-admin-core

> 简介:这是敏捷后台核心接口项目，使用laravel8作为框架来做的，主要解决市场上laravel8前后端分离项目的短缺问题，可以快速开发后台模块，agile-admin前端项目地址：https://github.com/liaoyimin123/agile-admin

部署环境

php7.4 + mysql5.7 + nginx/apache

部署流程

1.git 拉取代码
git clone https://github.com/liaoyimin123/agile-admin-core.git

2.启动服务
php artisan serve

3.项目根目录复制.env.example文件重命名为.env文件
配置mysql连接信息即可

4.自动化导入数据表结构
php artisan migrate

5.自动化导入数据
php artisan db:seed