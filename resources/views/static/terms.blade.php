@extends('layouts.app')

@section('title', 'Términos de Servicio')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-black uppercase font-display mb-8 text-center text-brand-yellow text-shadow-neo">
            Términos de Servicio
        </h1>

        <x-card class="prose max-w-none">
            <p class="font-bold">Última actualización: 25 de enero de 2026</p>

            <h3>1. Aceptación de los Términos</h3>
            <p>
                Al acceder y utilizar BlockBookster, aceptas y acuerdas estar sujeto a los términos y disposiciones de este acuerdo.
            </p>

            <h3>2. Conducta del Usuario</h3>
            <p>
                Aceptas usar el sitio únicamente para fines legales. Está específicamente prohibido:
            </p>
            <ul>
                <li>Publicar cualquier contenido que sea abusivo, amenazante u obsceno.</li>
                <li>Intentar interferir con el correcto funcionamiento del sitio.</li>
                <li>Crear múltiples cuentas con el propósito de manipular calificaciones.</li>
            </ul>

            <h3>3. Propiedad Intelectual</h3>
            <p>
                El contenido de BlockBookster, incluyendo, pero no limitado a texto, gráficos y logotipos, es propiedad de
                BlockBookster y está protegido por las leyes de derechos de autor.
            </p>

            <h3>4. Limitación de Responsabilidad</h3>
            <p>
                En ningún caso BlockBookster será responsable de ningún daño (incluyendo, sin limitación, daños por pérdida
                de datos o beneficios, o por interrupción del negocio) que surja del uso o la imposibilidad de usar los
                materiales en el sitio web de BlockBookster.
            </p>

            <h3>5. Ley Aplicable</h3>
            <p>
                Estos términos y condiciones se rigen e interpretan de acuerdo con las leyes de España, y aceptas
                irrevocablemente la jurisdicción exclusiva de los tribunales en ese lugar.
            </p>
        </x-card>
    </div>
@endsection
