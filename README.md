# 概要

slimフレームワークを使用したサンプルアプリケーション

# DB操作

直でSQLを発行する場合

```
# insert
$container['db']->getConnection()->insert('INSERT INTO user (name, created_at, updated_at) VALUES(:name, now(), now())',['name' => 'test1']);

# update
$container['db']->getConnection()->update('UPDATE user SET name = :name, updated_at = now() WHERE id = 3',['name' => 'test10']);

# delete
$container['db']->getConnection()->delete('DELETE FROM user WHERE name = :name',['name' => 'test4']);

# select
$container['db']->getConnection()->select('SELECT * FROM user WHERE id > :id', ['id' => 3]);

# 戻り値が返ってこないようなデータベース操作のSQL
$container['db']->getConnection()->statement('DROP TABLE user');

# トランザクション
$container['db']->getConnection()->beginTransaction();
try {
    $container['db']->getConnection()->insert('INSERT INTO user (name, created_at, updated_at) VALUES(:name, now(), now())',['name' => 'test']);
    $container['db']->getConnection()->commit();
} catch (\Exception $e) {
    $this->container['db']->getConnection()->rollback();
}
```

ELOQUENTのマニュアル

https://laravel.com/docs/5.5/eloquent

# query builder

https://www.ritolab.com/entry/93

# デザイン

Honoka v4.1.3を使用
http://honokak.osaka/

[@MITLicense](https://twitter.com/MITLicense)
