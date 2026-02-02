# Diseño UI/UX & Frontend para **BlockBookster**

---

## 🎯 Rol que debes asumir

Actúa como un **Lead Product Designer + UI Engineer** senior con experiencia demostrable en:

* Diseño de **redes sociales de contenido** (Letterboxd, Goodreads, Medium).
* UX/UI moderno, **mobile-first**, altamente usable.
* Proyectos **Laravel (Blade)** con **Tailwind CSS**.
* Arquitecturas frontend escalables y mantenibles.

Tu objetivo es **diseñar y definir la estructura visual completa** de una aplicación web llamada **BlockBookster**.

---

## 📘 1. Contexto del proyecto

**BlockBookster** es una **red social de libros**, inspirada en:

* **Letterboxd** → enfoque moderno, visual, social-first.
* **Goodreads** → referencia funcional, evitando su complejidad obsoleta.

Características técnicas:

* Framework backend: **Laravel 12**
* Motor de vistas: **Blade (.blade.php)**
* Estilos: **Tailwind CSS**
* Arquitectura: **mobile-first**, responsive real
* Enfoque: UX/UI, accesibilidad, claridad y rendimiento

El nombre y la identidad visual rinden homenaje a la cadena de videoclubs **Blockbuster**.

---

## 🎨 2. Identidad visual

### Paleta de colores (OBLIGATORIA)

* Azul principal: `#0E3FA9` → color dominante
* Amarillo secundario: `#FFA903` → CTAs y acentos
* Negro: `#000000`
* Blanco: `#FFFFFF`
* Escala de grises neutros

### Estilo visual

* Moderno, limpio, editorial
* Diseño basado en **cards**
* Portadas de libros como elemento protagonista
* Microinteracciones sutiles
* Jerarquía tipográfica clara
* Inspiración directa en Letterboxd

---

## 👥 3. Usuarios y permisos

### Usuarios NO registrados

Pueden:

* Navegar por toda la plataforma
* Ver libros, autores, listas públicas y perfiles públicos
* Buscar y filtrar contenido
* Leer reviews y valoraciones

No pueden:

* Valorar libros
* Crear listas
* Escribir reviews
* Dar likes
* Seguir usuarios o autores

### Usuarios registrados

Además, pueden:

* Valorar libros
* Escribir reviews
* Crear y gestionar listas
* Seguir usuarios y autores
* Dar likes
* Configurar perfil público/privado

---

## 🧱 4. Layouts globales (Blade)

### Layouts base

* `layouts/app.blade.php` → layout principal
* `layouts/auth.blade.php` → login / registro
* `layouts/admin.blade.php` → panel de administración

Incluyen:

* Header
* Navbar principal (responsive)
* Footer completo
* Menú móvil (drawer o bottom-nav)

---

## 🏠 5. Páginas principales

### Home — `/`

Componentes:

* Hero con buscador principal
* CTA de login / registro
* Últimas novedades
* Libros mejor valorados
* Mejores libros por género
* Libros más buscados del último mes
* Listas destacadas
* Usuarios destacados

Vista:

* `pages/home/index.blade.php`

---

### Libros — `/books`

Página más importante del proyecto.

Zona superior:

* Buscador avanzado
* Filtros por:

    * Título
    * Autor
    * ISBN
    * Género
    * Idioma
    * Año
    * Valoración
    * Popularidad
    * Tags

Secciones:

* Más populares
* Mejor valorados
* Novedades
* Mejores por género

Vistas:

* `pages/books/index.blade.php`
* `pages/books/show.blade.php`

---

### Detalle de libro — `/books/{isbn}`

Incluye:

* Portada destacada
* Información completa
* Valoración media
* Reviews
* Likes / dislikes
* Estado de lectura (solo si estas autenticado)
* Enlaces afiliados de compra
* Autores relacionados

---

### Autores — `/authors`

* Listado de autores populares
* Buscador
* Página de autor con:

    * Biografía
    * Libros
    * Seguidores
    * Valoraciones
    * Botón seguir

Vistas:

* `pages/authors/index.blade.php`
* `pages/authors/show.blade.php`

---

### Listas — `/lists`

* Listas más seguidas
* Mejor valoradas
* Más compartidas

Página de lista:

* Libros ordenados
* Notas del creador
* Likes
* Visibilidad (pública/privada)

Vistas:

* `pages/lists/index.blade.php`
* `pages/lists/show.blade.php`

---

### Usuarios — `/users`

Rankings:

* Más seguidos
* Más listas creadas
* Más activos

Perfil público:

* Reviews
* Lecturas (pasadas, actuales, futuras)
* Listas
* Seguidores / seguidos
* Likes

Vistas:

* `pages/users/index.blade.php`
* `pages/users/show.blade.php`

---

## 🔐 6. Autenticación

Vistas:

* `auth/login.blade.php`
* `auth/register.blade.php`
* `auth/forgot-password.blade.php`

Diseño enfocado a conversión y claridad.

---

## 👤 7. Panel de usuario

Vistas:

* `pages/dashboard/index.blade.php`
* `pages/dashboard/profile.blade.php`
* `pages/dashboard/lists.blade.php`
* `pages/dashboard/reviews.blade.php`
* `pages/dashboard/settings.blade.php`

Funciones:

* Gestión de perfil
* Mis listas
* Mis reviews
* Libros leídos / leyendo / pendientes
* Privacidad

---

## 🛠️ 8. Panel de administración

Layout:

* `layouts/admin.blade.php`

Vistas:

* `admin/dashboard.blade.php`
* `admin/books/index.blade.php`
* `admin/books/edit.blade.php`
* `admin/authors/index.blade.php`
* `admin/users/index.blade.php`
* `admin/reviews/moderation.blade.php`
* `admin/lists/reports.blade.php`

Funciones:

* CRUD libros
* CRUD autores
* Moderación de reviews
* Bloqueo de usuarios
* Gestión de enlaces afiliados

---

## 📄 9. Páginas estáticas

Vistas:

* `static/privacy.blade.php`
* `static/terms.blade.php`
* `static/faq.blade.php`
* `static/contact.blade.php`

---

## 🧩 10. Componentes Blade (NAMING EXACTO)

Ubicación: `resources/views/components/`

### Layout / Navegación

* `header.blade.php`
* `navbar.blade.php`
* `footer.blade.php`
* `mobile-menu.blade.php`

### Cards

* `book-card.blade.php`
* `author-card.blade.php`
* `user-card.blade.php`
* `list-card.blade.php`
* `review-card.blade.php`

### UI Elements

* `rating-stars.blade.php`
* `like-button.blade.php`
* `follow-button.blade.php`
* `primary-button.blade.php`
* `secondary-button.blade.php`

### Forms

* `search-bar.blade.php`
* `filter-panel.blade.php`
* `form-input.blade.php`
* `form-textarea.blade.php`

### Feedback

* `alert.blade.php`
* `modal.blade.php`
* `empty-state.blade.php`

---

## 📂 11. Estructura final de carpetas (OBLIGATORIA)

```
resources/views/
├── layouts/
├── components/
├── pages/
│   ├── home/
│   ├── books/
│   ├── authors/
│   ├── lists/
│   ├── users/
│   ├── dashboard/
├── auth/
├── admin/
├── static/
└── partials/
```

---

## ✅ 12. Resultado esperado

Debes entregar:

1. Diseño conceptual de todas las páginas
2. Jerarquía clara de layouts y componentes
3. Razonamiento UX
4. Naming consistente Blade
5. Mobile-first real
6. Coherencia visual total con BlockBookster

No generes backend. **Solo UX/UI, estructura visual y organización frontend.**

# Design System — **BlockBookster**

**Estilo visual:** Neo‑Brutalism UI
**Stack objetivo:** Laravel 12 · Blade · Tailwind CSS
**Propósito:** garantizar coherencia visual, rapidez de desarrollo y una identidad fuerte, honesta y funcional.

---

## 1. Filosofía de diseño

BlockBookster adopta un **Neo‑Brutalism design style**:

* Prioriza **funcionalidad directa** sobre ornamento.
* Interfaces **crudas, contrastadas y legibles**.
* Bordes visibles, sombras duras, jerarquía clara.
* Nada de efectos difusos, gradientes suaves o glassmorphism.
* El diseño **no intenta esconder que es una interfaz**.

Inspiración:

* Neo‑Brutalism web
* shadcn/ui
* Interfaces editoriales modernas
* Letterboxd (conceptual, no estético)

---

## 2. Design Tokens

### 2.1 Colores

#### Core

| Token         | Valor     | Uso                           |
| ------------- | --------- | ----------------------------- |
| `--bb-blue`   | `#0E3FA9` | Navegación, links, identidad  |
| `--bb-yellow` | `#FFA903` | CTA primarios, acciones clave |
| `--bb-black`  | `#000000` | Texto principal, bordes       |
| `--bb-white`  | `#FFFFFF` | Fondos                        |

#### Neutrales

* `gray-50` → fondos suaves
* `gray-200` → separadores
* `gray-500` → texto secundario
* `gray-900` → texto fuerte

**Regla:** siempre alto contraste. No texto gris claro sobre blanco.

---

### 2.2 Tipografía

**Fuente recomendada:** `Inter` o `Space Grotesk`

#### Escala

| Uso           | Clase Tailwind                    | Comentario |
| ------------- | --------------------------------- | ---------- |
| Hero title    | `text-3xl md:text-5xl font-black` | Impacto    |
| Section title | `text-xl font-bold`               | Claridad   |
| Card title    | `text-lg font-semibold`           | Jerarquía  |
| Body          | `text-sm md:text-base`            | Lectura    |
| Meta          | `text-xs uppercase tracking-wide` | Datos      |

**Regla:** títulos siempre fuertes, sin semibold ambiguo.

---

### 2.3 Bordes y sombras (clave Neo‑Brutalism)

* Bordes siempre visibles:

  ```
  border-2 border-black
  ```

* Sombras duras (no blur):

  ```
  shadow-[4px_4px_0px_#000]
  ```

* Hover:

  ```
  hover:translate-x-[-2px] hover:translate-y-[-2px]
  hover:shadow-[6px_6px_0px_#000]
  ```

---

### 2.4 Radios y espaciado

* `rounded-none` o `rounded-sm`
* Nada de bordes redondeados grandes
* Espaciado generoso:

    * Cards: `p-4 md:p-6`
    * Secciones: `gap-6 md:gap-8`

---

## 3. Componentes Base (Blade)

Todos los componentes viven en:

```
resources/views/components/
```

---

### 3.1 Botones

#### `primary-button.blade.php`

Uso: acciones principales (CTA)

Clases base:

```txt
bg-[#FFA903] text-black border-2 border-black
shadow-[4px_4px_0px_#000]
font-bold uppercase
```

---

#### `secondary-button.blade.php`

Uso: acciones secundarias

```txt
bg-white text-black border-2 border-black
shadow-[4px_4px_0px_#000]
```

---

#### `neutral-button.blade.php`

Uso: login social, acciones neutras

```txt
bg-gray-100 border-2 border-black
```

---

### 3.2 Cards

#### `card.blade.php`

Base para libros, listas, usuarios, reviews.

```txt
bg-white border-2 border-black
shadow-[6px_6px_0px_#000]
```

**Regla:** no existen cards sin borde.

---

### 3.3 Book Card

#### `book-card.blade.php`

Incluye:

* Portada
* Título
* Rating
* Acciones

Variantes:

* `compact`
* `default`
* `featured`

---

### 3.4 Inputs y formularios

#### `form-input.blade.php`

```txt
border-2 border-black
focus:outline-none focus:ring-0
focus:shadow-[2px_2px_0px_#000]
```

Nunca:

* Bordes invisibles
* Sombras suaves

---

### 3.5 Rating

#### `rating-stars.blade.php`

* Estrellas sólidas
* Amarillo BlockBookster
* Siempre visibles

---

## 4. Layouts

### 4.1 App Layout

`layouts/app.blade.php`

* Navbar superior fuerte
* Footer editorial
* Grid simple
* Nada de fondos complejos

---

### 4.2 Auth Layout

`layouts/auth.blade.php`

Inspirado directamente en el componente proporcionado:

* Card centrada
* Bordes negros
* Jerarquía clara
* CTA dominante

---

### 4.3 Admin Layout

`layouts/admin.blade.php`

* Estilo más denso
* Sidebar visible
* Misma identidad visual

---

## 5. UX Rules (obligatorias)

* Toda la card es clicable
* CTA primario siempre amarillo
* Acciones destructivas → rojo puro
* No iconos sin texto en acciones críticas
* Feedback inmediato (hover, active)

---

## 6. Mobile‑First

* Navegación simple
* Botones grandes
* Cards verticales
* Filtros colapsables

Nunca esconder funcionalidad clave en desktop-only.

---

## 7. Lo que **NO** se permite

* Gradientes suaves
* Glassmorphism
* Sombras difusas
* Bordes invisibles
* Animaciones largas

---

## 8. Resultado del Design System

Este sistema debe permitir:

* Implementar cualquier vista sin decidir estilos nuevos
* Coherencia total
* Identidad BlockBookster reconocible
* Escalabilidad frontend

---

## 9. Referencia de estilo

El componente React proporcionado es **correcto en espíritu**:

* Card clara
* Jerarquía textual
* Inputs honestos
* Botones contundentes

La traducción a Blade + Tailwind debe respetar esa **honestidad visual**, llevándola al **Neo‑Brutalism**.

---

**Este Design System es ley visual del proyecto.**
