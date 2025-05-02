# 🎨 CreatorSync - Tu red social creativa

**CreatorSync** es una red social pensada para creadores de contenido de todo tipo. Aquí puedes compartir tus proyectos, conectar con otros usuarios, colaborar en ideas nuevas y descubrir talento creativo.

## ✨ Funcionalidades principales

- 📸 Publicación de contenido multimedia (imágenes, videos, música, textos, etc.)  
- 💬 Interacción con otros usuarios (comentarios, likes, seguidores)  
- 🤝 Colaboraciones y trabajo en equipo  
- 📈 Seguimiento de tu evolución creativa  
- 🌍 Descubrimiento de nuevos creadores y tendencias

**CreatorSync** es el lugar donde la creatividad se sincroniza.  
¡Únete y empieza a compartir lo que haces con el mundo!

---

## Requisitos Previos

Antes de comenzar, asegúrate de tener instalados los siguientes requisitos:

- **PHP**: Versión 8.1 o superior.
- **Composer**: Para gestionar las dependencias de PHP.
- **Node.js**: Versión 16 o superior (para gestionar assets con Laravel Mix).
- **MySQL**: Para la base de datos.
- **Git**: Para clonar el repositorio.

---

## Instalación

Sigue estos pasos para instalar y configurar el proyecto:

1. **Clonar el repositorio**:
   ```bash
   git clone https://github.com/AlejandroHzdClr/CreatorSyncV2
   cd CreatorSync

2. **Descargar lo necesario**:
    ```bash
    composer install
    npm install

3. **Cambiar el env**
    ```bash
    DB_DATABASE: Nombre de la base de datos.
    DB_USERNAME: Usuario de la base de datos.
    DB_PASSWORD: Contraseña de la base de datos.

4. **Generar la clave de la aplicación**
    ```bash
    php artisan key:generate

5. **Ejecutar las migraciones y seeders**
    ```bash
    php artisan migrate --seed

6. **Compilar los assets**
    ```bash
    npm run dev

## Uso
1. **Iniciar el servidor de desarrollo:**
    ```bash
    php artisan serve

2. **Acceder a la aplicación:**
    Abre tu navegador y ve a: http://localhost:8000

## Test
1. **Iniciar los test**
    ```bash
    php artisan test

## Licencia
Este proyecto está licenciado bajo la Licencia MIT. Consulta el archivo LICENSE para más detalles.

MIT License

Copyright (c) 2025 Alejandro

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.