<x-admin-layout>
    <x-slot name="title">Administrar usuarios</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Gestión de Usuarios
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Gestiona clientes y administradores.
                </p>
            </div>

            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                Nuevo usuario
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Filtros --}}
            <form method="GET" action="{{ route('admin.users.index') }}"
                class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label for="q" class="block text-sm font-medium text-gray-700">Buscar</label>
                        <input id="q" name="q" type="text"
                            value="{{ request('q') }}"
                            placeholder="Nombre o email…"
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                        <select id="role" name="role"
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-400 focus:ring-gray-400">
                            <option value="">Todos</option>
                            @foreach(($roles ?? []) as $role)
                            <option value="{{ $role->id }}" @selected(request('role')===$role->id)>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                            Filtrar
                        </button>

                        @if(request()->filled('q') || request()->filled('role'))
                        <a href="{{ route('admin.users.index') }}"
                            class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                            Limpiar
                        </a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Tabla --}}
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Rol</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Alta</th>
                                <th class="w-32 px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $user)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">#{{ $user->id }}</div>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $user->email }}
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    @php($userRoles = $user->role_names)

                                    @if($userRoles->isEmpty())
                                    <span class="text-gray-500">—</span>
                                    @else
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($userRoles as $roleKey)
                                        <span class="inline-flex items-center rounded-full border border-gray-200 bg-white px-2.5 py-1 text-xs font-medium text-gray-700">
                                            {{ $roles[$roleKey] ?? $roleKey }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                </td>


                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ optional($user->created_at)->format('d/m/Y') }}
                                </td>

                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <x-admin.admin-table-actions
                                        :edit-url="route('admin.users.edit', $user)"
                                        :delete-url="route('admin.users.destroy', $user)"
                                        :item-name="$user->name . ' (' . $user->email . ')'"
                                        delete-title="Eliminar usuario"
                                        delete-message-html="¿Seguro que quieres eliminar <code class='px-1 py-0.5 rounded bg-gray-100 text-gray-800'>{{ e($user->name) }}</code>?" />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-600">
                                    No hay usuarios que coincidan con los filtros.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if(method_exists($users, 'links'))
                <div class="border-t border-gray-200 bg-white px-6 py-4">
                    {{ $users->withQueryString()->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-admin-layout>