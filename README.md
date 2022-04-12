# webman-biz-framework
webman的基础上扩展的一个服务层框架层
## 框架结构

1. service 层使用开源项目 [Biz Framework](https://github.com/codeages/biz-framework)
2. 在webman的资源层增加了service层
3. 在webman的增加biz的依赖注入
4. ...

## 实现功能
1. 满足biz所有的功能模块
- Container
- Config
- Database Connection
- Database Migration
- Cache
- Dao
- Service
- Event
- Validation
- Logger
- Exception
2. 数据库迁移，使用开源项目[phpmig](https://github.com/davedevelopment/phpmig)
3. 终端命令行，使用symfony的console组件
4. 快速生成service层代码（因为service基础功能，因此定制了基础的代码模板用于快速生成）


## 使用
1. migrate使用
```shell
bin/phpmig help
```
2. console使用
```shell
php console list
```
3. 生成service层
``` shell
 // 例如：生成用户user的服务层(业务层名称大驼峰要求）
 php console make:biz User
 // 例如：生成日志log，这时候日志的表名称是 setting_log
 php console make:biz Log setting_log
 // 暂不支持在同一业务层目录下生成相关连的service，比如：用户层user 关联的用户Token UserTokenService 需要放到Biz\User\Service 和 Biz\User\Dao 里，可以手动放入，该功能后期可以完善
```
# 问题
## 感谢
- [webman](https://www.workerman.net/doc/webman/)
- [biz](https://github.com/codeages/biz-framework)
- [symfony](https://symfony.com/)
- [phpmig](https://github.com/davedevelopment/phpmig)
- ...
