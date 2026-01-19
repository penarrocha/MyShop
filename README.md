# Vinylhub 🛒  
Tienda online desarrollada como proyecto final del CFGS Desarrollo de Aplicaciones Web (DAW).

## 📌 Descripción
Vinylhub es una aplicación web de comercio electrónico desarrollada con **Laravel 12**, orientada a un entorno académico.  
Incluye catálogo de productos, carrito de compra, wishlist persistente, gestión de pedidos y un panel de administración con control de acceso por roles.

El proyecto está dockerizado y desplegado en **Amazon AWS EC2** con IP elástica (sin dominio, uso académico).

🔗 **Demo (entorno académico):**  
http://18.214.56.106/

---

## 🛠️ Tecnologías utilizadas
- **Backend:** Laravel 12 (PHP)
- **Frontend:** Blade + Tailwind CSS + Vite
- **Base de datos:** MySQL
- **Autenticación:** Laravel Breeze
- **Autorización:** RBAC (Role-Based Access Control)
- **Contenedores:** Docker + Laravel Sail
- **Despliegue:** Amazon AWS EC2
- **Testing:** PHPUnit (Feature & Unit tests)

---

## APIs externas
- **Google ReCAPTCHA v3**: en las pantallas de login y registro de usuario
- **Cloudinary**: Servicio de alojamiento de imágenes

## API propia
- La aplicación expone un endpoint propio que devuelve información del carrito en formato JSON (/cart/state), consumido mediante peticiones AJAX desde el frontend. Este endpoint actúa como una API interna que separa lógica de presentación y datos.

---

## ⚙️ Funcionalidades principales
### Usuario
- Registro e inicio de sesión
- Wishlist persistente
- Gestión de pedidos propios
- Carrito de compra por sesión

### Catálogo
- Productos organizados por categorías
- Productos en oferta
- Visualización de detalle de producto

### Carrito y checkout
- Añadir, actualizar y eliminar productos del carrito
- Cálculo de totales
- Pago **simulado** (sin pasarela real)
- Estados de pedido (pending, etc.)

### Administración (Backoffice)
- CRUD de categorías
- Gestión de ofertas
- Gestión de pedidos
- Acceso restringido mediante roles

---

## 🔐 Roles y permisos (RBAC)
- **Customer:** acceso a catálogo, carrito, wishlist y pedidos propios
- **Admin:** acceso al panel de administración y gestión global

---

## 🧪 Testing
El proyecto incluye **pruebas automáticas** desarrolladas con PHPUnit.

### Tipos de pruebas
- **Feature Tests**
  - Registro de usuarios (con reCAPTCHA simulado)
  - Carrito de compra (flujo completo)
  - Wishlist persistente
  - Control de acceso por roles (RBAC)
  - Flujos principales del backoffice
- **Unit Tests**
  - Lógica de negocio
  - Métodos de modelos

### Servicios externos
- El servicio **Google reCAPTCHA v3** se **mockea** en los tests para evitar dependencias externas.

### Ejecutar los tests
```bash
./vendor/bin/sail test
