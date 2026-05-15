<?php
// Página de diagnóstico temporal - ELIMINAR después de resolver el problema
ob_start();
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    if (!is_dir('C:/laragon/tmp')) { @mkdir('C:/laragon/tmp', 0777, true); }
    ini_set('session.save_path', 'C:/laragon/tmp');
}
ini_set('session.use_strict_mode', 0);

set_error_handler(function($errno, $errstr) {
    echo "<div style='background:#f8d7da;padding:8px;margin:4px 0;border-radius:4px;'><b>PHP Error [$errno]:</b> $errstr</div>";
});

$session_result = session_start();
restore_error_handler();

$db_user = "root";
$db_pass = "";
$db_name = "yulisrl_sistema";
$db_conn = @mysqli_connect("localhost", $db_user, $db_pass, $db_name);
$db_error = $db_conn ? null : mysqli_connect_error();

$users_count = null;
if ($db_conn) {
    $q = mysqli_query($db_conn, "SELECT COUNT(*) as total FROM Usuarios");
    if ($q) { $row = mysqli_fetch_assoc($q); $users_count = $row['total']; }
}
?>
<!DOCTYPE html><html><head><title>Diagnóstico VPS</title>
<style>
body { font-family: monospace; padding: 20px; background: #1a1a2e; color: #eee; }
h2 { color: #e94560; }
.ok { color: #4caf50; } .fail { color: #f44336; } .warn { color: #ff9800; }
table { border-collapse: collapse; width: 100%; margin: 10px 0; }
td, th { border: 1px solid #444; padding: 8px 12px; text-align: left; }
th { background: #16213e; }
tr:nth-child(even) { background: #0f3460; }
</style></head>
<body>
<h2>🔍 Diagnóstico del Sistema YuliSRL</h2>

<h3>1. Entorno PHP</h3>
<table>
<tr><th>Variable</th><th>Valor</th></tr>
<tr><td>Sistema Operativo</td><td><?= PHP_OS ?></td></tr>
<tr><td>PHP Version</td><td><?= PHP_VERSION ?></td></tr>
<tr><td>SAPI</td><td><?= php_sapi_name() ?></td></tr>
<tr><td>Server Software</td><td><?= $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' ?></td></tr>
</table>

<h3>2. Sesiones PHP</h3>
<table>
<tr><th>Variable</th><th>Valor</th><th>Estado</th></tr>
<tr>
  <td>session_start() result</td>
  <td><?= $session_result ? 'TRUE' : 'FALSE' ?></td>
  <td class="<?= $session_result ? 'ok' : 'fail' ?>"><?= $session_result ? '✅ OK' : '❌ FALLO' ?></td>
</tr>
<tr>
  <td>session_id()</td>
  <td><?= session_id() ?: '(vacío)' ?></td>
  <td class="<?= session_id() ? 'ok' : 'fail' ?>"><?= session_id() ? '✅ OK' : '❌ VACÍO' ?></td>
</tr>
<tr>
  <td>session.save_path</td>
  <td><?= ini_get('session.save_path') ?></td>
  <td class="<?= is_writable(ini_get('session.save_path') ?: sys_get_temp_dir()) ? 'ok' : 'fail' ?>">
    <?= is_writable(ini_get('session.save_path') ?: sys_get_temp_dir()) ? '✅ Escribible' : '❌ NO escribible' ?>
  </td>
</tr>
<tr>
  <td>session.save_handler</td>
  <td><?= ini_get('session.save_handler') ?></td>
  <td>-</td>
</tr>
<tr>
  <td>session.use_cookies</td>
  <td><?= ini_get('session.use_cookies') ?></td>
  <td>-</td>
</tr>
</table>

<h3>3. Base de Datos</h3>
<table>
<tr><th>Variable</th><th>Valor</th><th>Estado</th></tr>
<tr>
  <td>Conexión (<?= $db_user ?>@localhost/<?= $db_name ?>)</td>
  <td><?= $db_conn ? 'CONECTADO' : $db_error ?></td>
  <td class="<?= $db_conn ? 'ok' : 'fail' ?>"><?= $db_conn ? '✅ OK' : '❌ FALLO' ?></td>
</tr>
<?php if ($db_conn): ?>
<tr>
  <td>Usuarios en BD</td>
  <td><?= $users_count ?? 'Error en query' ?></td>
  <td class="<?= $users_count > 0 ? 'ok' : 'warn' ?>"><?= $users_count > 0 ? '✅ OK' : '⚠️ Sin usuarios' ?></td>
</tr>
<?php endif; ?>
</table>

<?php if (!$db_conn): ?>
<div style="background:#f8d7da;color:#721c24;padding:15px;border-radius:6px;margin:10px 0;">
<b>⚠️ La base de datos no conecta.</b><br>
En cPanel/VPS, el usuario de BD NO es <code>root</code>.<br>
Debes editar <code>includes/conexion.php</code> con el usuario real de tu cPanel.<br>
Ve a <b>cPanel → Bases de datos MySQL → Usuarios</b> y dime cuál es el usuario y el nombre de la BD.
</div>
<?php endif; ?>

<?php if ($session_result && !$db_conn): ?>
<div style="background:#fff3cd;color:#856404;padding:15px;border-radius:6px;margin:10px 0;">
<b>✅ Sesiones OK / ❌ Base de datos FALLA</b><br>
El problema en el VPS es la conexión a la base de datos. El login falla silenciosamente por esto.
</div>
<?php elseif (!$session_result && $db_conn): ?>
<div style="background:#fff3cd;color:#856404;padding:15px;border-radius:6px;margin:10px 0;">
<b>❌ Sesiones FALLAN / ✅ Base de datos OK</b><br>
El problema es el path de sesiones. Necesitamos corregir session.save_path en el VPS.
</div>
<?php elseif (!$session_result && !$db_conn): ?>
<div style="background:#f8d7da;color:#721c24;padding:15px;border-radius:6px;margin:10px 0;">
<b>❌ Sesiones FALLAN y Base de datos FALLA</b><br>
Hay dos problemas: el path de sesiones Y las credenciales de BD.
</div>
<?php else: ?>
<div style="background:#d4edda;color:#155724;padding:15px;border-radius:6px;margin:10px 0;">
<b>✅ Todo parece OK en este servidor.</b><br>
Si el login sigue fallando, el problema está en la lógica de la aplicación. Comparte esta página conmigo.
</div>
<?php endif; ?>
</body></html>
