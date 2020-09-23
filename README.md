# ソーシャルログイン for EC-CUBE4

[Auth0](https://auth0.com/jp/)を使用してEC-CUBE4でソーシャルログインを実現するプラグイン（サンプル）です。  
Auth0を使用するとGoogleやFacebook、Apple、Twitter、LineなどのアカウントでEC-CUBE4にログインできるようになります。   
非公式プラグインですのでご利用は自己責任でお願いいいたします。  

## インストールと有効化
```
bin/console eccube:composer:require knpuniversity/oauth2-client-bundle:1.34.0
bin/console eccube:composer:require riskio/oauth2-auth0

git clone git@github.com:kurozumi/SocialLogin4.git

bin/console eccube:plugin:install --code SocialLogin4
bin/console eccube:plugin:enable --code SocialLogin4
```

## ClientIdとClientSecretを設定

Auth0でClientIdとClientSecret、Domainを取得して、環境変数（.env)に設定してください。

```
OAUTH_AUTH0_CLIENT_ID=****************************
OAUTH_AUTH0_CLIENT_SECRET=****************************
OAUTH_AUTH0_CUSTOM_DOMAIN=****************************
```

## 利用可能なソーシャルログイン一覧

![利用可能なソーシャルログイン一覧](https://github.com/kurozumi/SocialLogin4/blob/images/social_connections.png)
