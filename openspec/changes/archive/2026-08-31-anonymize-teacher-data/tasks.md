## 1. Comando de Anonimización

- [x] 1.1 Crear el comando Artisan `php artisan make:command AnonymizeTeachersCsv` y configurar su firma a `app:anonymize-teachers-csv`. Verificar verificando que el comando aparezca al ejecutar `php artisan list`.
- [x] 1.2 Implementar la lectura del archivo CSV `database/seeders/data/teachers.csv` dentro del comando (respetando la cabecera y el separador `;`). Verificar arrojando salida a consola con las primeras 5 líneas leídas para comprobar que parsea correctamente.
- [x] 1.3 Integrar `FakerPHP` en el comando para sustituir las columnas sensibles (`name`, `surName`, `cdi`, `phone`, `email`, `user_email`) y escribir los datos resultantes a un archivo temporal. Verificar ejecutando el comando y leyendo que el archivo temporal existe y sus datos están ofuscados, manteniendo el resto (Sede, Área) estático.
- [x] 1.4 Modificar el comando para que reemplace directamente el archivo `teachers.csv` (o renombree el temporal). Verificar comprobando visualmente el CSV modificado.

## 2. Ejecución y Carga de Base de Datos

- [x] 2.1 Ejecutar `php artisan migrate:fresh --seed` (o comando análogo configurado en el sistema) para purgar y recargar la base de datos usando el nuevo archivo anonimizado. Verificar que el proceso termina sin errores de base de datos o de llave foránea.
- [x] 2.2 Iniciar sesión en el sistema con el usuario administrador (comprobar credenciales conocidas en `DatabaseSeeder`). Verificar validando en el panel administrativo que existen docentes cargados, pero todos ellos poseen nombres aleatorizados.
