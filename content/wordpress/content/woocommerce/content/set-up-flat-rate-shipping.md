# Установка фиксированной доставки

Поменяем местами зоны доставки, чтобы созданный ранее регион Denver был в самом верху.

![rearrange-shipping-zones.png](img/rearrange-shipping-zones.png)

Создадим два метода с фиксированной ставкой. Первый на следующий день.

![next-day-shipping.png](img/next-day-shipping.png)

Второй в течении 2 часов.

![two-hour-delivery.png](img/two-hour-delivery.png)

Как видим, у нас фиксированная ставка расчитывается динамически в зависимости от количества товара.

Проверяем чтобы у нас были включенны эти два метода.

![flat-rate-settings.png](img/flat-rate-settings.png)

Теперь при оформлении заказа мы можем выбрать необходимый метод доставки, который расчитает стоимость по указанной нами схеме.

![flat-rate-shipping-front-end.png](img/flat-rate-shipping-front-end.png)