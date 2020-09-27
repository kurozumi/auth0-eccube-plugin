# ソーシャルログインプラグイン for EC-CUBE4

![Auth0ログインページ](https://github.com/kurozumi/SocialLogin4/blob/images/auth0-login-page.png)

[Auth0](https://auth0.com/jp/) を使用してEC-CUBE4でソーシャルログインを実現するプラグイン（サンプル）です。  
[Auth0](https://auth0.com/jp/) を使用するとGoogleやFacebook、Apple、Twitter、LineなどのアカウントでEC-CUBE4にログインできるようになります。   

本プラグインの利用には EC-CUBE 4.0.5 以上へのアップデートが必要になります。

非公式プラグインですのでご利用は自己責任でお願いいいたします。  

## Auth0の設定例
![Auth0](https://github.com/kurozumi/SocialLogin4/blob/images/auth0-setting-sample.png)

## インストールと有効化
```
bin/console eccube:composer:require knpuniversity/oauth2-client-bundle:1.34.0
bin/console eccube:composer:require riskio/oauth2-auth0

git clone git@github.com:kurozumi/SocialLogin4.git

bin/console eccube:plugin:install --code SocialLogin4
bin/console eccube:plugin:enable --code SocialLogin4
```


## ClientIdとClientSecret、Domainを設定

[Auth0](https://auth0.com/jp/) でClientIdとClientSecret、Domainを取得して、管理画面で設定してください。

![Auth0設定画面](https://github.com/kurozumi/SocialLogin4/blob/images/admin-auth0-setting.png)


## ソーシャルログインページへのリンク設定

```
<a href="{{ url('auth0_connect') }}">ソーシャルログイン</a>
```


## 利用可能なソーシャルログイン一覧

![利用可能なソーシャルログイン一覧](https://github.com/kurozumi/SocialLogin4/blob/images/social_connections.png)


## ユーザー名・パスワード認証停止

ユーザー名・パスワード認証（Username-Password-Authentication）は [Auth0](https://auth0.com/jp/) の管理画面で停止してください。

![ユーザー名・パスワードログイン停止](https://github.com/kurozumi/SocialLogin4/blob/images/disable-username-password-auth.png)
