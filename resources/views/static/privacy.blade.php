@extends('layouts.app')

@section('title', 'Política de Privacidad')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-black uppercase font-display mb-8 text-center text-brand-blue">
            Política de Privacidad
        </h1>

        <x-card class="prose max-w-none">
            <p class="font-bold">Última actualización: 25 de enero de 2026</p>

            <h3>1. Introducción</h3>
            <p>
                Bienvenido a BlockBookster. Valoramos tu privacidad y nos comprometemos a proteger tus datos personales.
                Esta política de privacidad te informará sobre cómo cuidamos tus datos personales cuando visitas nuestro
                sitio web y te explicará cuáles son tus derechos en materia de privacidad.
            </p>

            <h3>2. Datos que recopilamos</h3>
            <p>
                Podemos recopilar, utilizar, almacenar y transferir diferentes tipos de datos personales sobre ti,
                que hemos agrupado de la siguiente manera:
            </p>
            <ul>
                <li>
                    <strong>Datos de Identidad</strong>: incluyen nombre, apellidos, nombre de usuario u otro identificador similar.
                </li>
                <li>
                    <strong>Datos de Contacto</strong>: incluyen dirección de correo electrónico.
                </li>
                <li>
                    <strong>Datos Técnicos</strong>: incluyen dirección de protocolo de Internet (IP), datos de inicio de sesión,
                    tipo y versión del navegador.
                </li>
                <li>
                    <strong>Datos de Perfil</strong>: incluyen tu nombre de usuario y contraseña, libros leídos, listas creadas
                    y reseñas realizadas.
                </li>
            </ul>

            <h3>3. Cómo utilizamos tus datos</h3>
            <p>
                Solo utilizaremos tus datos personales cuando la ley nos lo permita. Normalmente, utilizaremos tus datos
                personales en las siguientes circunstancias:
            </p>
            <ul>
                <li>
                    Cuando necesitemos ejecutar el contrato que estamos a punto de celebrar o que ya hemos celebrado contigo.
                </li>
                <li>
                    Cuando sea necesario para nuestros intereses legítimos (o los de un tercero) y tus intereses y derechos
                    fundamentales no prevalezcan sobre dichos intereses.
                </li>
            </ul>

            <h3>4. Seguridad de los datos</h3>
            <p>
                Hemos implementado medidas de seguridad adecuadas para evitar que tus datos personales se pierdan,
                utilicen o accedan de forma no autorizada, se modifiquen o se divulguen accidentalmente.
            </p>

            <h3>5. Contacto</h3>
            <p>
                Si tienes alguna pregunta sobre esta política de privacidad o sobre nuestras prácticas en materia de
                privacidad, puedes contactarnos en privacy@blockbookster.com.
            </p>
        </x-card>
    </div>
@endsection
