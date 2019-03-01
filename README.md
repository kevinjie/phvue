# [Phvue](https://kevinjie.github.io/phvue/)

## 这是什么？

Phvue 是能够在 Phalcon 框架中使用 vue.js 开发多页面应用的开发环境。
既可以享受 phalcon 的良好性能，也可以方便使用 vue.js 的优秀特性。
安装此开发环境，只需通过极少的配置，就可以使用 phalcon 和 vuejs 的优秀特性，专注业务流程，提升开发效率。

## 安装准备

- 搭建 LNMP 开发环境
- 安装 phalcon7 及 phalcon7 开发工具
- 安装 composer
- 安装 node.js
- 安装 npm

以上安装方法请自行搜索

## 安装

### 下载源码，请执行以下命令

```
# 克隆代码
git clone git@github.com:kevinjie/phvue.git
cd phvue
```

### 代码下载安装完，执行以下命令

```
# 安装php依赖包
php composer.phar install
composer dump-autoload

# 安装npm依赖包
cd  public/demo
npm install
```

## 修改配置

### .env 配置运行环境及数据库连接

```
# 运行环境
APP_ENV=dev

# 数据库连接
DB_HOST = 127.0.0.1
DB_PORT = 3306
DB_NAME = test # 数据库自己改掉
DB_USERNAME =   # 账号自己改掉
DB_PASSWORD =   # 密码自己改掉
```

### module.js 配置 module 的名称、代理、controller 和 action

```
cd public/demo/config
vi module.js
```

根据项目设置名称（默认是 demo）。如果需要增加页面，则需将 controller 和 action 添加到 controllerAction 数组中（格式为 controller_action）。

## 开发及编译

### 开发

```
cd public/demo
npm run dev
```

在浏览器中打开[http://localhost:8081/index_index.html](http://localhost:8081/index_index.html)查看效果

### 编译

```
npm run build
```

在浏览器中打开[http://phvue.demo.com:8080/demo/index/index](http://phvue.demo.com:8080/demo/index/index)查看效果

## 参与

在项目安装及开发过程中，遇到任何问题以及有好的建议和想法，你可以提交 Issue。由人个人水平有限，有不足之处，欢迎交流指正，感谢您的使用！
