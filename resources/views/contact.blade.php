@extends('layouts.public')

@section('title', 'Contacto - VinylHub')

@section('content')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6 max-w-7xl">

        <h1 class="text-4xl font-bold text-gray-900 mb-8 text-center">
            Contacto
        </h1>

        <div
            x-data="{
                tab: 'contacto',
                tabs: ['contacto', 'nosotros', 'sostenibilidad', 'rgpd'],
                init() {
                    const hash = (window.location.hash || '').replace('#', '');
                    if (this.tabs.includes(hash)) this.tab = hash;

                    // Si el usuario cambia el hash (atrás/adelante), sincroniza la pestaña
                    window.addEventListener('hashchange', () => {
                        const h = (window.location.hash || '').replace('#', '');
                        if (this.tabs.includes(h)) this.tab = h;
                    });
                },
                setTab(t) {
                    this.tab = t;
                    history.replaceState(null, '', `#${t}`);
                }
            }"
            class="bg-white rounded-lg shadow-lg">

            {{-- Tabs (mobile-first) --}}
            <div class="border-b">
                <div class="grid grid-cols-2 sm:flex sm:flex-wrap">
                    <button
                        type="button"
                        @click="setTab('contacto')"
                        :class="tab === 'contacto'
                ? 'border-primary-600 text-primary-600 bg-primary-50'
                : 'border-transparent text-gray-600 hover:text-gray-900'"
                        class="px-4 py-4 text-sm font-semibold border-b-2 transition text-center">
                        Información de contacto
                    </button>

                    <button
                        type="button"
                        @click="setTab('nosotros')"
                        :class="tab === 'nosotros'
                ? 'border-primary-600 text-primary-600 bg-primary-50'
                : 'border-transparent text-gray-600 hover:text-gray-900'"
                        class="px-4 py-4 text-sm font-semibold border-b-2 transition text-center">
                        Sobre nosotros
                    </button>

                    <button
                        type="button"
                        @click="setTab('sostenibilidad')"
                        :class="tab === 'sostenibilidad'
                ? 'border-primary-600 text-primary-600 bg-primary-50'
                : 'border-transparent text-gray-600 hover:text-gray-900'"
                        class="px-4 py-4 text-sm font-semibold border-b-2 transition text-center">
                        Desarrollo sostenible
                    </button>

                    <button
                        type="button"
                        @click="setTab('rgpd')"
                        :class="tab === 'rgpd'
                ? 'border-primary-600 text-primary-600 bg-primary-50'
                : 'border-transparent text-gray-600 hover:text-gray-900'"
                        class="px-4 py-4 text-sm font-semibold border-b-2 transition text-center">
                        RGPD
                    </button>
                </div>
            </div>


            {{-- Contenido --}}
            <div class="p-8 space-y-8">

                {{-- TAB: Información de contacto --}}
                <div x-show="tab === 'contacto'" x-transition x-cloak>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                        Información de contacto
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

                        {{-- Columna izquierda: datos --}}
                        <div>
                            <dl class="space-y-4 text-gray-700">
                                <div class="grid grid-cols-[180px_1fr] gap-x-4 items-start">
                                    <dt class="font-semibold text-gray-900">Empresa:</dt>
                                    <dd>VinylHub</dd>
                                </div>

                                <div class="grid grid-cols-[180px_1fr] gap-x-4 items-start">
                                    <dt class="font-semibold text-gray-900">Dirección:</dt>
                                    <dd>
                                        Carrer de la Música, 12<br>
                                        46005 · València · España
                                    </dd>
                                </div>

                                <div class="grid grid-cols-[180px_1fr] gap-x-4 items-start">
                                    <dt class="font-semibold text-gray-900">Correo electrónico:</dt>
                                    <dd>
                                        <a href="mailto:{{ env('CONTACT_EMAIL') }}" class="text-primary-600 hover:underline">
                                            {{ env('CONTACT_EMAIL') }}
                                        </a>
                                    </dd>
                                </div>

                                <div class="grid grid-cols-[180px_1fr] gap-x-4 items-start">
                                    <dt class="font-semibold text-gray-900">Teléfono de atención al cliente:</dt>
                                    <dd>{{ env('CONTACT_PHONE') }}</dd>
                                </div>

                                <div class="grid grid-cols-[180px_1fr] gap-x-4 items-start">
                                    <dt class="font-semibold text-gray-900">Horario de atención:</dt>
                                    <dd>{{ env('CONTACT_HORARIO') }}</dd>
                                </div>
                            </dl>

                        </div>

                        {{-- Columna derecha: imagen + mapa --}}
                        <div class="space-y-4">
                            {{-- Imagen tienda --}}
                            <div class="rounded-xl overflow-hidden shadow-md bg-gray-200">
                                <x-product-image public-id="vinylhub-tienda-fisica_qfvhqn" alt="Tienda VinylHub en Valencia" class="w-full h-56 object-cover" />
                            </div>
                        </div>

                    </div>
                </div>

                {{-- TAB: Sobre nosotros --}}
                <div x-show="tab === 'nosotros'" x-transition x-cloak>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                        Sobre nosotros
                    </h2>

                    <div class="text-gray-700 space-y-4 leading-relaxed">
                        <p>
                            <strong>VinylHub</strong> es una tienda online especializada en la compraventa
                            de discos de vinilo y equipamiento musical de segunda mano, concebida como un
                            proyecto de comercio electrónico responsable.
                        </p>

                        <div class="flex items-center justify-center w-full py-16 px-4">
                            <img src="{{ asset('assets/vinylhub-logo.png') }}" alt="VinylHub" class="w-full max-w-[280px]">
                        </div>

                        <p>
                            Apostamos por prolongar la vida útil de los productos culturales y tecnológicos,
                            reduciendo el impacto ambiental asociado a la fabricación y distribución de
                            nuevos artículos, y fomentando un modelo de consumo más consciente.
                        </p>

                        <p>
                            Nuestro proyecto integra tecnologías digitales avanzadas para garantizar una
                            experiencia de usuario segura, accesible y eficiente, cumpliendo con los
                            estándares de protección de datos y las buenas prácticas de gobernanza digital.
                        </p>
                    </div>
                </div>

                {{-- TAB: Desarrollo sostenible --}}
                <div x-show="tab === 'sostenibilidad'" x-transition x-cloak>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                        Desarrollo sostenible
                    </h2>

                    <p class="text-gray-600 mb-6">
                        VinylHub desarrolla su actividad alineada con la Agenda 2030 de las Naciones Unidas,
                        contribuyendo de forma directa a los siguientes Objetivos de Desarrollo Sostenible:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div class="border rounded-xl p-5 bg-gray-50">
                            <span class="inline-block mb-2 px-3 py-1 text-sm font-semibold text-white rounded-full bg-red-700">ODS 8</span>
                            <h3 class="font-semibold text-gray-900">Trabajo decente y crecimiento económico</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Impulso de un modelo de negocio ético y culturalmente sostenible.
                            </p>
                        </div>

                        <div class="border rounded-xl p-5 bg-gray-50">
                            <span class="inline-block mb-2 px-3 py-1 text-sm font-semibold text-white rounded-full bg-orange-600">ODS 9</span>
                            <h3 class="font-semibold text-gray-900">Industria, innovación e infraestructura</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Plataforma tecnológica innovadora, segura y escalable.
                            </p>
                        </div>

                        <div class="border rounded-xl p-5 bg-gray-50">
                            <span class="inline-block mb-2 px-3 py-1 text-sm font-semibold rounded-full bg-yellow-500 text-yellow-900">ODS 12</span>
                            <h3 class="font-semibold text-gray-900">Producción y consumo responsables</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Economía circular basada en la reutilización de productos.
                            </p>
                        </div>

                        <div class="border rounded-xl p-5 bg-gray-50">
                            <span class="inline-block mb-2 px-3 py-1 text-sm font-semibold text-white rounded-full bg-green-600">ODS 13</span>
                            <h3 class="font-semibold text-gray-900">Acción por el clima</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Reducción de la huella ambiental digital y logística.
                            </p>
                        </div>

                    </div>
                </div>

                {{-- TAB: RGPD --}}
                <div x-show="tab === 'rgpd'" x-transition x-cloak>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                        RGPD y protección de datos
                    </h2>

                    <div class="text-gray-700 space-y-4 leading-relaxed">
                        <p>
                            En VinylHub nos tomamos muy en serio la privacidad. Tratamos los datos personales
                            conforme al <strong>Reglamento General de Protección de Datos (RGPD)</strong> y la
                            <strong>LOPDGDD</strong>.
                        </p>

                        <div class="border rounded-lg p-5 bg-gray-50 space-y-3">
                            <p><strong>Responsable del tratamiento:</strong> VinylHub</p>
                            <p><strong>Finalidad:</strong> atender consultas y gestionar comunicaciones relacionadas con la actividad de la tienda.</p>
                            <p><strong>Base legal:</strong> interés legítimo y/o ejecución de medidas precontractuales/contractuales según el caso.</p>
                            <p><strong>Conservación:</strong> durante el tiempo necesario para atender la solicitud y, en su caso, cumplir obligaciones legales.</p>
                            <p><strong>Cesiones:</strong> no se cederán datos a terceros salvo obligación legal.</p>
                            <p><strong>Derechos:</strong> acceso, rectificación, supresión, oposición, limitación y portabilidad.</p>
                        </div>

                        <p class="text-sm text-gray-500">
                            Para ejercer tus derechos puedes escribir a
                            <a href="mailto:privacy@vinylhub.es" class="text-primary-600 hover:underline">privacy@vinylhub.es</a>.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection