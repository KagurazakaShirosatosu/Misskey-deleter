Misskey Deleter  
---------
Misskey deleter 可以定时自动删除您账号里的 notes 。  

使用方法：  
1. 首先在您的Misskey实例上获取拥有必要权限的token。token通常在 `https://example.com/settings/api` （请把example.com替换成贵实例之地址）中生成。  
2. 在您的服务器上安装好 php 8 和 composer。  
3. clone 本项目到您的机器上。  
4. 运行 `composer install` 安装依赖。  
5. 将token等相关参数填写到代码中。  
6. 在 screen/tmux/nohup 中运行 `index.php`。  
