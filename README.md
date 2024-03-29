# Auth0(ソーシャルログイン)プラグイン for EC-CUBE4.2

![Auth0ログインページ](https://github.com/kurozumi/auth0-eccube-plugin/blob/images/auth0-login-page.png)

[Auth0](https://auth0.com/jp/) を使用して EC-CUBE4.2でソーシャルログインを実現するプラグインです。  
[Auth0](https://auth0.com/jp/) を使用するとGoogleやFacebook、Apple、LineなどのアカウントでEC-CUBE4.2にログインできるようになります。　　

#### ご注意!
メールアドレスが取得できるサービスのみ利用可能です。  
Twitterは現在メールアドレスを提供していないので利用できません。

非公式プラグインですのでご利用は自己責任でお願いいいたします。  
本プラグインを導入したことによる不具合や被った不利益につきましては一切責任を負いません。

## インストールに失敗する場合

以下のコマンドをk実行してください。

```
composer config --no-plugins allow-plugins.php-http/discovery false
```

## Auth0の設定例

Application Login URI、Allowed Callback URLs、Allowed Logout URLsは任意のURLを設定してください。

![FireShot Capture 452 - Application Details - manage auth0 com](https://user-images.githubusercontent.com/1731851/206898004-bb9b2c7d-51d4-4308-80b8-5c59b6aeeedc.png)

## ソーシャルプロバイダーのClientIDとClientSecretをAuth0に登録

### ご注意！
Auth0のClientIDとClientSecretを使用すれば、ソーシャルプロバイダーのClientIDとClientSecretを登録せずにソーシャルログインのテストができます。  
本番環境では必ず各ソーシャルプロバイダー独自のClientIDとClientSecretをAuth0に登録してください。

### ソーシャルプロバイダーのClientIDとClientSecretの発行方法

ソーシャルプロバイダーに登録するウェブサイトURLとコールバックURLはAuth0で発行されたURLを登録してください。  
ウェブサイトURL例：https://a-zumi.us.auth0.com  
コールバックURL例：https://a-zumi.us.auth0.com/login/callback

### ソーシャルプロバイダーのClientIDとClientSecretをAuth0に登録

発行されたソーシャルプロバイダーのClientIDとClientSecretをAuth0に登録してください。

### 設定例

- [GitHubアカウントの連携方法](https://github.com/kurozumi/auth0-eccube-plugin/issues/22)
- [LINEアカウントの連携方法](https://github.com/kurozumi/auth0-eccube-plugin/issues/24)
- [Amazonアカウントの連携方法](https://github.com/kurozumi/auth0-eccube-plugin/issues/26)

## インストールと有効化
```
bin/console eccube:composer:require knpuniversity/oauth2-client-bundle
bin/console eccube:composer:require riskio/oauth2-auth0
bin/console eccube:composer:require auth0/auth0-php

git clone git@github.com:kurozumi/auth0-eccube-plugin.git app/Plugin/Auth0

bin/console eccube:plugin:install --code Auth0
bin/console eccube:plugin:enable --code Auth0
```


## Auth0で発行されたClientIDとClientSecret、DomainをEC-CUBEに登録

[Auth0](https://auth0.com/jp/) でClientIdとClientSecret、Domainを取得して、管理画面で設定してください。

![Auth0設定画面](https://github.com/kurozumi/auth0-eccube-plugin/blob/images/admin-auth0-setting.png)


## ソーシャルログインページへのリンク設定

```
<a href="{{ url('auth0_connect') }}">ソーシャルログイン</a>
```


## 利用可能なソーシャルログイン一覧

![利用可能なソーシャルログイン一覧](https://user-images.githubusercontent.com/1731851/207563214-54d26c6e-3469-4991-90f9-9e7d51ddd3b7.png)


## ユーザー名・パスワード認証停止

ユーザー名・パスワード認証（Username-Password-Authentication）は [Auth0](https://auth0.com/jp/) の管理画面で停止してください。

![ユーザー名・パスワードログイン停止](https://github.com/kurozumi/auth0-eccube-plugin/blob/images/disable-username-password-auth.png)


## ソーシャルログインサービスの追加

[Auth0のCustom Social Connectionを利用してYahoo! JAPANと接続する](https://qiita.com/hisashiyamaguchi/items/93516e371bbe279fffb9)
