<?php $this->layout('base::template.docs'); ?>

<?php $this->start('docs_nav') ?>
        <li><a href="<?= $base_url ?>"><?= $base_page ?></a></li>
<?php $this->stop() ?>

<?php $this->start('extra_js') ?>
  <script>
    Flatdoc.run({
      fetcher: Flatdoc.file("<?= $source ?>")
    });
  </script>
<?php $this->stop() ?>