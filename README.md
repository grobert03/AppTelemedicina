# AppTelemedicina 

## Propuesta ##

El proyeto consiste en una aplicación web, que proporciona una plataforma de mensajería donde los pacientes podrán intercambiar mensajes con sus médicos. A su vez los médicos podrán recetar recetas para sus pacientes.

## Especificación de requisitos ##

### Acceso y registro ###

Los usuarios se podrán registrar mediante el usuario, contraseña y correo electrónico. 
Si el correo contiene el dominio "**@comem.es**", automáticamente el usuario se considerará como médico. Al rellenar el formulario recibirán un **link de activación** al correo, que una vez pulsado, el usuario será añadido a la base de datos.

Las claves se añadirán cifradas a la base de datos, y, en caso de olvidarse la clave, podrán **generar una contraseña nueva** mediante un enlace enviado al correo electrónico.

Si se desea, los usuarios podrán **borrar** su cuenta después de verificar la operación mediante un enlace al correo electrónico.

La conexión a la base de datos y al servidor de correo se almacenará en un **fichero XML**.

### Usuarios ###

En la plataforma existen dos tipos de usuarios, **médicos** y **pacientes**. Los médicos, además de los datos introducidos al registrarse, podrán añadir otros datos a sus perfiles como el número de colegiado, la especialidad médica, su currículum o el hospital en el que trabajan. 

Opcionalmente, los dos tipos de usuarios pueden añadir/actualizar/quitar una **foto de perfil**, visible para todos los usuarios.

Además, existen usuarios **administradores**, que tendrán una zona de administración solo para ellos, donde podrán expulsar usuarios de la aplicación, cargar médicos en masa, manipular las valoraciones de los médicos o cambiar las contraseñas.

### Bandeja de entrada ###

Los pacientes/médicos tendrán una lista con los mensajes que les han enviado, diferenciados entre leídos o no. En la lista solo aparecerá **el principio del mensaje**. Para ver el mensaje completo o responder, tendrá que acceder a la conversación.

Si un médico tiene más de **20 mensajes sin leer**, al intentar contactar con él, el paciente recibirá un aviso de que **el médico no está disponible**, hasta que se lean parte de los mensajes no leídos.

### Mensajes ###

Los pacientes pueden enviar mensajes a los médicos con sus consultas (el destinatario se elige por el nombre de usuario). El mensaje se puede enviar a varios médicos. Los médicos. **no podrán iniciar conversaciones**, solo responder a los mensajes enviados por sus pacientes. 

Los mensajes podrán contener ficheros adjuntos, además de imágenes (sin adjuntar, vistas directamentes al acceder a la conversación).

### Bandeja de salida ###

Los usuarios podrán ver una tabla con sus mensajes enviados, similar a la de entrada. Los mensajes estarán envíados por fecha de envío, los más recientes primero.

En la tabla se verá el destinatario del mensaje, y si hay varios se indicará mediante la palabra "**varios**". Para ver la lista completa de los destinatarios, se tendrá que acceder a la conversación.

### Recetas

Los médicos podrán recetar medicamentos a los pacientes, con información como el nombre del paciente, principio activo, dosis, nombre del médico, etc. También tendrán la habilidad de borrar una receta en concreto.

Cada receta se va a indetificar por un código único, a la que podrán acceder todos los usuarios sin registrarse mediante un campo de texto en la página principal.

### Ranking ###

Cada paciente podrá calificar a su médico mediante una nota del 1 al 10. En el perfil de cada médico aparecerá la el promedio de las calificaciones.

En la web se podrá ver un **ranking** de los mejores médicos, en función de la calificación media.