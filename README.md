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

## Enlaces a información del proyecto

- **Fase de Análisis**: https://docs.google.com/document/d/1UZVIq9SyswF08_aqHI9GeD_xk4NUH3AHxCLdudrOO5s/edit?usp=sharing
- **Fase de Diseño**: https://docs.google.com/document/d/1lvQ-AfhBz3kvG6DJZdUviaEE7-0-rpduUCgfYxC5-l0/edit?usp=sharing
- **Tablas BBDD**: https://drive.google.com/file/d/1EsPfTWGQUdvxKTTNRMAff0owusajqMwJ/view?usp=sharing
- **Pizarra Online**: https://miro.com/welcomeonboard/ak5FSWNwd2N5VzhTUnkxZHZxbHp3dDRIMXhJeENRRDhleVNHN0pRN2lnNjQrSGROWGxPV3V6SmJVQkJ5b01uanVOMzJFTHZPa213ZkI0dmhhaXg4RFlKZlN5MUQ1S2grSWgzZ1RscDlJN2Y5MHlDc1p0OXE4c0FxbmZlNWY3bnJBd044SHFHaVlWYWk0d3NxeHNmeG9BPT0hdjE=?share_link_id=95043088779


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

3. **Copiar y cambiar el env**
    ```bash
    DB_DATABASE: Nombre de la base de datos.
    DB_USERNAME: Usuario de la base de datos.
    DB_PASSWORD: Contraseña de la base de datos.

4. **Generar la clave de la aplicación**
    ```bash
    php artisan key:generate

5. **Ejecutar las migraciones y seeders**
    ```bash
    php artisan migrate
    php artisan db:seed

6. **Compilar los assets**
    ```bash
    npm run dev

7. **Hacer el enlace simbólico con storage**
    ```bash
    php artisan storage:link

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
