    <nav class="nav">
      <ul class="nav__list container">
      <?php foreach ($category_list as $key => $val) { ?>
          <li class="nav__item">
            <a href="pages/all-lots.html"><?=htmlspecialchars($val['name_cat']); ?></a>
          </li>
        <?php }; ?>
      </ul>
    </nav>
    <form class="form container <?=count($error_list) ? 'form--invalid' : ''; ?>" action="login.php" method="post"> <!-- form--invalid -->
      <h2>Вход</h2>
      <div class="form__item <?=isset($error_list['email']) ? 'form__item--invalid' : ''; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=getPostVal('email'); ?>">
        <span class="form__error"><?=$error_list['email'] ?? ""; ?></span>
      </div>
      <div class="form__item form__item--last <?=isset($error_list['password']) ? 'form__item--invalid' : ''; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?=getPostVal('password'); ?>">
        <span class="form__error"><?=$error_list['password'] ?? ""; ?></span>
      </div>
      <button type="submit" class="button">Войти</button>
    </form>
