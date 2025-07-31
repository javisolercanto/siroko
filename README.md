# Siroko API

El siguiente proyecto es una representación de un prototipo de arquitectura para el desarrollo de un carrito de la compra y su posterior checkout.

En este prototipo se ha optado por usar una arquitectura hexagonal desacoplada del framework (Symfony). También se ha realizado una división de responsabilidades mediante BoundedContext completamente independientes entre ellos, y conectados mediante Domain Events, Commands y Query.

Pese a que el proyecto se ha realizado sobre una base de datos SQL he optado por no conectar las tablas mediante claves foráneas para así poder lograr una completa independencia entre entidades y, según requerimientos de negocio, construir diferentes Responses para la visualización de los datos, también así, mejorando el rendimiento de las consultas al no tener que realizar subconsultas para obtener los datos anidados.

## Tecnologías utilizadas

- **PHP** -> `8.2`
-  **Symfony** -> `7.3`
-  **MySql** -> `8.0`

## Resumen OpenAPI

**Versión**: 1.0.0  
**Formato**: OAS 3.0

---

### Autenticación
- `POST /api/login` – Inicia sesión (devuelve token)
- `GET /api/check` – Verifica si el usuario está autenticado

### Carritos
- `GET /api/cart` – Obtiene el carrito actual
- `POST /api/cart` – Crea un nuevo carrito
- `GET /api/cart/{id}` – Obtiene un carrito por ID

### Líneas del carrito
- `POST /api/cart-line` – Añade un producto al carrito
- `PUT /api/cart-line/{id}` – Actualiza la cantidad de un producto
- `DELETE /api/cart-line/{id}` – Elimina un producto del carrito
- `GET /api/cart-line/find-by-cart-id/{id}` – Lista productos por ID de carrito

### Pedidos
- `POST /api/order/create-from-cart/{id}` – Crea un pedido desde un carrito
- `GET /api/order` – Lista los pedidos del usuario

### Productos
- `GET /api/products` – Lista todos los productos
- `GET /api/product/{id}` – Obtiene información de un producto por ID

### Usuarios
- `POST /api/user` – Crea un nuevo usuario
- `GET /api/user/{id}` – Obtiene un usuario por ID

## Modelado del dominio

El modelado del dominio se ha construido en base a principios SOLID y a una arquitectura hexagonal y sobretodo DDD. Todos los campos que aparecen en los agregados son ValueObjects con sus correspondientes validaciones base y/o validaciones extra en función de la función que ejerzan en el agregado.

### Usuario

Representaria al usuario cliente que realiza la compra, es una representación muy sencilla con un id identificativo y un email para poder realizar la autenticación.

- `id` - UuidV7
- `name` - string
- `email` - string

### Producto

El producto representado por un id identificativo, un nombre, el precio, y dos stocks. `stock` hace referencia al stock real del producto en el almacén y `availableStock` haría referencia al stock disponible para agregar al carrito, teniendo en cuenta que otros ususarios han podido reservar parte de ese stock.

- `id` - UuidV7
- `name` - string
-  `price` - float
- `stock` - int
- `availableStock` - int

### Carrito

El carrito representa una entidad que se encarga de agrupar bajo un id las distintas combinaciones de productos que crea un usuario. El campo `processed` nos permite diferenciar que carrito ha sido ya procesado, es decir, se ha creado una orden sobre él y por lo tanto no se pueden realizar más moficaciones sobre él.

- `id` - UuidV7
- `ownerId` - UuidV7
- `processed` - bool

### Líneas de carrito

Las líneas de carrito permiten especificar que cantidad de producto y a que carrito pertenecen.

- `id` - UuidV7
- `ownerId` - UuidV7
- `cartId` - UuidV7
- `productId` - UuidV7
- `quantity` - int

### Pedido

El pedido simplemente agrupa la id del carrito y del usuario al que pertenece junto a una fecha que sería la de creación del pedido.

- `id` - UuidV7
- `ownerId` - UuidV7
- `cartId` - UuidV7
- `date` - DateTime

## Instrucciones para iniciar el proyecto

- Clonar el proyecto `git clone`
- `docker-compose up --build -d`
- `docker exec -it symfony_php /bin/bash`
- `composer install`
- `php bin/console doctrine:migrations:migrate --env=dev`
- `php bin/console doctrine:fixtures:load --env=dev`
- `php bin/console lexik:jwt:generate-keypair`

El proyecto estará disponible en `http:localhost:8009/api/doc`

### Probar en API DOC

Este sería un ejemplo básico de como funciona la solución propuesta. Se pueden probar todas las llamadas descritas en el API doc pero las mencionadas son las principales.  

- Ejecutar llamada `POST` - `/api/user/` - para crear un usuario.
	 ```
	 {
		 "name": "test",
		 "email": "test@siroko.com"
	 }
	 ```
- Ejecutar llamada `POST` - `/api/login/` - para obtener un token válido.
	 ```
	 {
		 "email": "test@siroko.com"
	 }
	 ```
	 El token obtenido hay que introducirlo en el botón `Authorize` de la parte superior derecha (cuidado con copiar las comillas).
- Ejecutar llamada `GET` - `/api/check/` - la cual nos devolverá el usuario actual para comprobar si hemos introducido bien el token.
- Ejecutar llamada `POST` - `/api/cart/` - la cual nos devolverá un uuid que hace referencia al carro.
- Ejecutar llamada `POST` - `/api/cart-line/` - para añadir un ítem al carrito.
	 ```
	 {
		 "cart_id": "",
		 "product_id": "019850bc-0cc9-74f3-886f-ff13031f2fc8",
		 "quantity": 1
	 }
	 ```
- Ejecutar llamada `GET` - `/api/cart-line/find-by-cart-id/${id}` - indicándole el id del carrito para obtener el listado de productos asociados a él.
- Ejecutar llamad `POST` - `/api/order/create-from-cart/${id}` - indicándole el id del carrito para persistir el listado de productos seleccionado.
- Ejecutar llamada `GET` - `/api/order` - devolverá el listado de pedidos asociados al usuario actual

## Instrucciones para ejecutar los tests
- `php bin/console doctrine:migrations:migrate --env=test`
- `php bin/console doctrine:fixtures:load --env=test`
- `sh run-tests.sh`

Los tests generarán dos archivos html, un archivo en `coverage/` que indicará la cobertura del proyecto y otro en `/var/performance_report.html` que mostrará un pequeño de resumen de los tiempos de ejecución de los tests.

## Valoración personal

La prueba me ha parecido muy correcta, es un supuesto que deja relucir la arquitectura propuesta y sobretodo los eventos de dominio. Me he sentido bastante cómodo durante el desarrollo de la misma. Respecto a los tests de perfomance, nunca había hecho algo similar, he optado por una propuesta muy sencilla midiendo los tiempos de ejecución de los tests y luego gracias a ChatGPT los he representado en un html sencillo para poder visualizarlos mejor, inspirado por la opción de coverage de phpunit que tanto me gusta. De esta prueba me llevo el interés de aprender sobre los tests de performance. Me hubiera gustado aplicar patrón mother para realizar los tests pero debido a la limitación de tiempo he priorizado otros desarollos.
