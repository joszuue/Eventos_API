# API REST - Sistema de Gestión de Eventos

Este documento describe los endpoints disponibles para el sistema de gestión de eventos. A continuación, se listan las rutas y métodos HTTP correspondientes a cada operación CRUD para usuarios, eventos y comentarios.

---

## 🔐 Autenticación

### Iniciar Sesión  
**POST** `https://tarea.transforma.edu.sv/Login/login`

```json
{
  "usuario": "imjosuu",
  "contra": "Test"
}
```

### Cerrar Sesión  
**POST** `https://tarea.transforma.edu.sv/Login/logOut`

---

## 👤 CRUD Usuarios

### Crear Usuario  
**POST** `https://tarea.transforma.edu.sv/Usuarios/usuarios/`

```json
{
  "nombres": "Josué",
  "apellidos": "Rodríguez",
  "usuario": "josue",
  "contra": "test"
}
```

### Obtener Lista de Usuarios  
**GET** `https://tarea.transforma.edu.sv/Usuarios/usuarios`

### Modificar Usuario  
**PUT** `https://tarea.transforma.edu.sv/Usuarios/usuarios/`

```json
{
  "nombres": "Josué",
  "apellidos": "Vásquez",
  "usuario": "imjosuu",
  "contra": "$2y$10$aA9NasN0EcLg9mcIXyHlau1rRDFYwxf3WKgfBhQs/UzRxEBlHazq."
}
```

### Obtener Usuario por ID  
**GET** `https://tarea.transforma.edu.sv/Usuarios/usuarios/1`

### Eliminar Usuario  
**DELETE** `https://tarea.transforma.edu.sv/Usuarios/usuarios/1`

---

## 📅 CRUD Eventos

### Crear Evento  
**POST** `https://tarea.transforma.edu.sv/Eventos/eventos`

```json
{
  "descripcion": "Ingresando una descripción",
  "fecha": "2025-5-23",
  "hora": "20:54",
  "ubicacion": "xd"
}
```

### Obtener Lista de Eventos  
**GET** `https://tarea.transforma.edu.sv/Eventos/eventos`

### Modificar Evento  
**PUT** `https://tarea.transforma.edu.sv/Eventos/eventos/1`

```json
{
  "descripcion": "Ingresando una descripción UPDATE",
  "fecha": "2025-5-22",
  "hora": "20:50",
  "ubicacion": "xdDDDD"
}
```

### Obtener Evento por ID  
**GET** `https://tarea.transforma.edu.sv/Eventos/eventos/1`

### Eliminar Evento  
**DELETE** `https://tarea.transforma.edu.sv/Eventos/eventos/1`

---

## 💬 Comentarios

### Crear Comentario  
**POST** `https://tarea.transforma.edu.sv/Comentarios/comentarios`

```json
{
  "id_evento": 1,
  "id_user": 3,
  "comentario": "Gran evento, me encantó."
}
```

### Obtener Comentarios de un Evento  
**GET** `https://tarea.transforma.edu.sv/Comentarios/comentarios/1`

---

**📌 Notas:**
- Asegúrate de enviar los datos en formato JSON.
- La contraseña en la modificación debe estar encriptada (ej. bcrypt).
- Los endpoints siguen un diseño RESTful estándar.

---

**Desarrollado por:** Denis Josué Vásquez Rodríguez
