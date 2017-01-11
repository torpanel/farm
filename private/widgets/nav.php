<?php

$pages = [
  'home' => 'Home',
  'faq' => 'FAQ',
  'login' => 'Login',
  'register' => 'Register'
];

$current = $this->getPage();

?>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">TorPanel</a>
    </div>

    <div class="collapse navbar-collapse" id="navbar-collapse">
      <ul class="nav navbar-nav">
        <?php foreach ($pages as $page => $label): ?>
          <li <?php htmlattr('class', ($page === $current) ? 'active' : '') ?>>
            <a <?php href($page) ?>>
              <?php html($label) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">Link</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </li>
      </ul>
    </div> <!-- .navbar-collapse -->
  </div> <!-- .container-fluid -->
</nav>
