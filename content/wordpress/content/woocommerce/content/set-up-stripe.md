# Настройка Stripe

[WooCommerce Stripe Payment Gateway](https://wordpress.org/plugins/woocommerce-gateway-stripe/) - можно установить отдельно, но также можно было установить автоматически во время первоначальной настройки WooCommerce.

В настройках нужно добавить ключи для live и test режима.

Создадим аккаунт и скопируем ключи.

![get-live-keys-stripe.png](img/get-live-keys-stripe.png)

Добавим их в настройки.

![keys-stripe.png](img/keys-stripe.png)

Для получения ключей в демо режиме нужно вводить дополнительные данные, которых у меня нет, потому что я живу в Украине, где Stripe не поддерживается. Поэтому я активировал демо режим в настройках Stripe WooCommerce, добавил ключи для live режима, оформил заказ.

![checkout-demo-mode-stripe.png](img/checkout-demo-mode-stripe.png)

Тестовая оплата прошла.

![order-received-stripe.png](img/order-received-stripe.png)

Статус заказа изменился.

![order-status-stripe.png](img/order-status-stripe.png)