<!-- resources/views/trabajadores/index.blade.php -->
<h1>Lista de Trabajadores</h1>
<ul>
    @foreach ($trabajadores as $trabajador)
        <li>{{ $trabajador->nombre }}</li>
    @endforeach
</ul>
