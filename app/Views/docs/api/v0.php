<?php $this->layout('base::template.docs', ['title' => 'v0 API Docs']); ?>

<?php $this->start('extra_js') ?>
  <script>
    Flatdoc.run({
      fetcher: Flatdoc.file("<?= $source ?>")
    });
  </script>
<?php $this->stop() ?>