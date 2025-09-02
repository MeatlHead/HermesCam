<?php
$TOKEN = 'change_this_token_please';
$provided = $_GET['token'] ?? '';
if ($provided !== $TOKEN) {
  echo "<form method='get'><input name='token'><button>Open</button></form>";
  exit;
}
$dir = __DIR__ . '/photos';
$files = array_filter(scandir($dir), fn($n)=>preg_match('/\.(jpg|png|webp)$/i',$n));
rsort($files);
?><!doctype html><html><body>
<h1>HermesCam â€” Gallery</h1>
<?php foreach($files as $f): ?>
  <div><img src="photos/<?php echo htmlspecialchars($f); ?>" style="max-width:200px"><br><?php echo $f; ?></div>
<?php endforeach; ?>
</body></html>
