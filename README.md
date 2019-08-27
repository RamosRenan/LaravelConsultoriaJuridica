# bood4l
## Plataforma Padrão DDTQ com login do Expresso (Laravel)

> As dependências **`LDAP`** e **`FILEINFO`** do PHP devem estar ativas

Dentro da pasta em que foi descompactado o App, execute:
```
composer install
```

Após a instalação do App, renomeie o arquivo "env.example" para ".env" e preencha os dados do banco de dados (preferencialmente POSTGRESQL) com os dados do seu banco. Após isso, execute:
```
php artisan key:generate
php artisan migrate --seed
sudo a2enmod rewrite
sudo service apache2 restart
php artisan serve
```

#### Ao efetuar o login no sistema, o primeiro usuário que o acessar garantirá acesso de SUPERADMINISTRADOR.

- Duvidas e sugestões, contato com [budal@pm.pr.gov.br](budal@pm.pr.gov.br).
