# ⚡ Calculadora IMC — Laravel 12 + MySQL

Aplicación web desarrollada en **Laravel 12** con **MySQL** para el registro del IMC (Índice de Masa Corporal), historial de mediciones y generación de sugerencias automáticas de dieta y rutina según el perfil del usuario.

---

## 📋 Funcionalidades principales

- Registro de usuarios y perfil (nombre, género, edad, estatura, condiciones de salud).
- Cálculo automático del IMC.
- Clasificación del IMC en categorías **en español** (Bajo peso, Normal, Sobrepeso, Obesidad I/II/III).
- Sugerencias personalizadas de dieta y rutina de ejercicios basadas en edad, género y condiciones.
- Historial de mediciones por usuario.
- Interfaz con **Bootstrap 5.3** (sidebar + navbar + cards).
- Soporte de zonas horarias configurado para **América/Bogotá**.
- Usuario demo precargado para pruebas.

---

## 🚀 Requisitos previos

Antes de instalar, asegúrate de tener en tu entorno:

- **Laragon Full 6.0** (incluye PHP, MySQL y utilidades listas para usar) 👉 [Descargar aquí](https://laragon.org/download/)  
- **Composer** 2.x  
- **Node.js** 18+ y **npm** o **yarn** (para los assets front-end si los recompilas)  
- **Git** (opcional, para clonar el proyecto)  

> ⚠️ Se recomienda Laragon Full 6.0 porque ya trae **PHP 8.0.30**, **MySQL 8.x**, Apache/Nginx y gestor de servicios para Windows.

---

## 🔧 Instalación

### 1. Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/imc-app.git
cd imc-app
