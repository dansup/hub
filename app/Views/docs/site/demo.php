<?php $this->layout('base::template.docs', ['title' => 'Docs Demo']); ?>

<?php $this->start('extra_js') ?>
  <script>
    Flatdoc.run({
      fetcher: Flatdoc.file("<?= $source ?>")
    });
  </script>
<?php $this->stop() ?>