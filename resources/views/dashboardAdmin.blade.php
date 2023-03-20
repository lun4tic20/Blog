<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('DashboardAdmin') }}
        </h2>
    </x-slot>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
        }

            th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

            th {
            background-color: #eee;
            font-weight: bold;
        }

        form{
            display: none;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
        }

        form input {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        form .btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        form .btn:hover {
            background-color: #0069d9;
        }

    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Fecha Creación</th>
                        <th>Fecha Modificación</th>
                        <th></th>
                        <th></th>
                    </tr>
                    @foreach($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->created_at}}</td>
                        <td>{{$user->updated_at}}</td>
                        <td>
                            <button type="button" class="btn btn-primary editar" data-id="{{ $user->id }}">
                                Editar
                            </button>
                            <form id="form-{{ $user->id }}" method="POST" action="{{ route('users.update', $user->id) }}" style="display:none">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                                </div>
                                <button type="submit" class="btn btn-primary guardar">Guardar cambios</button>
                            </form>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger eliminar" data-id="{{ $user->id }}" style="color: red">Eliminar</button>
                            <form id="form-delete-{{ $user->id }}" method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:none">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger guardar">Eliminar Usuario</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </table>
                <br>
                <div id="formContainer"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let formActual = null;
            const editarBotones = document.querySelectorAll('.editar');
            const formContainer = document.querySelector('#formContainer');

            for (let i = 0; i < editarBotones.length; i++) {
                editarBotones[i].addEventListener('click', function() {
                    // Obtener el formulario de edición correspondiente
                    const form = document.querySelector(`#form-${editarBotones[i].dataset.id}`);

                    // Si el formulario actual es el mismo que el que se acaba de abrir, ocultarlo y salir
                    if (formActual === form) {
                        formActual.style.display = 'none';
                        formActual = null;
                        return;
                    }

                    // Ocultar el formulario actual, si lo hay
                    if (formActual !== null) {
                        formActual.style.display = 'none';
                    }

                    // Insertar el formulario dentro del contenedor formContainer
                    formContainer.appendChild(form);

                    // Mostrar el formulario
                    form.style.display = 'block';

                    // Actualizar la referencia del formulario actual
                    formActual = form;
                });
            }
        });

        const eliminarBotones = document.querySelectorAll('.eliminar');
        for (let k = 0; k < eliminarBotones.length; k++) {
            eliminarBotones[k].addEventListener('click', function() {
                if (confirm('¿Estás seguro de que quieres eliminar esta cuenta?')) {
                    const form = document.querySelector(`#form-delete-${eliminarBotones[k].dataset.id}`);
                    form.submit();
                }
            });
}

</script>


</x-app-layout>
