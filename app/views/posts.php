<?php
$this->layout('layout', ['title' => 'Posts']) ?>

<h1>User Profile</h1>
<?php foreach ($posts as $post ): ?>
<p><?= $post['title']?></p>
<?php endforeach; ?>
