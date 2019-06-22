<!DOCTYPE html>
<html>
  <head>
    <?php echo $this->Html->charset(); ?>
    <title>Fave - まだ、知らないお店へ</title>
    <meta name="description" content="Fave（フェイブ）は、自分と同じお店が好きな人って、他にどんなお店へ行ってるんだろう。をテーマに、あなたが好きなお店を探すことができます。" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0", content="width=device-width" />
    <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <?= $this->Html->css('main'); ?>
  </head>
  <body>
    <?php echo $this->fetch('content'); ?>
  </body>
</html>