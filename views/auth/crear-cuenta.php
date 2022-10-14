<h1 class="nombre-pagina">Crear Cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php include_once __DIR__ . "/../templates/alertas.php"; ?>

<form  method="POST" class="formulario">
    <div class="campo">
        <label for="nombre">Nombre</label>
            <input type="text" id="nombre" placeholder="Tu Nombre" name="nombre" value="<?php echo s($usuario->nombre);?>">
        </label>
    </div>
    <div class="campo">
        <label for="Apellido">Apellido</label>
            <input type="text" id="apellido" placeholder="Tu Apellido" name="apellido" value="<?php echo s($usuario->apellido);?>">
        </label>
    </div>
    <div class="campo">
        <label for="Teléfono">Teléfono</label>
            <input type="tel" id="telefono" placeholder="Tu Teléfono" name="telefono" value="<?php echo s($usuario->telefono);?>">
        </label>
    </div>
    <div class="campo">
        <label for="Email">Email</label>
            <input type="email" id="email" placeholder="Tu Email" name="email" value="<?php echo s($usuario->email);?>">
        </label>
    </div>
    <div class="campo">
        <label for="password">Password</label>
            <input type="password" id="password" placeholder="Tu Password" name="password">
        </label>
    </div>
    <input type="submit" class="boton" value="Crear Cuenta">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>