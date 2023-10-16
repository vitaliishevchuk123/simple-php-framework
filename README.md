<h1 align="center" style="color: dodgerblue">Simple PHP framework</h1>

## Installation
``` 
    composer install
```
## Debugging
Для налагодження використовується `symfony/var-dumper` компонент. Він забезпечує кращу функцію dump() або dd(), яку можна використовувати замість var_dump().

## Request 
У PHP запит представлено деякими глобальними змінними ($_GET, $_POST, $_FILES, $_COOKIE, $_SESSION, ...), а відповідь генерується деякими функціями (echo, header(), setcookie(), ...).
<br> Для оброки запиту клієнта напишемо свій власний клас Request - аналог `symfony/http-foundation`

## Router 
В якості роутера використовується пакет `nikic/fast-route`