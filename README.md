# AppTelemedicina 

## Propuesta ##

El proyeto consiste en una aplicación web, donde se pueden registrar médicos y pacientes. Los pacientes podrán enviar mensajes a los médicos, y los médicos podrán recetar recetas para los pacientes.

## Especificación de requisitos ##

### Acceso y registro ###

Los usuarios se podrán registrar mediante el usuario, contraseña y correo electrónico. 
Si el correo contiene el dominio "**@comem.es**", automáticamente el usuario se considerará como médico. Al rellenar el formulario recibirán un **link de activación** al correo, que una vez pulsado, el usuario será añadido a la base de datos.

Las claves se añadirán cifradas a la base de datos, y, en caso de olvidarse la clave, podrán **generar una contraseña nueva** mediante un enlace enviado al correo electrónico.

Si se desea, los usuarios podrán **borrar** su cuenta después de verificar la operación mediante un enlace al correo electrónico.