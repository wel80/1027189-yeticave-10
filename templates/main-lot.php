    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($category_list as $key => $val) { ?>
          <li class="nav__item">
            <a href="pages/all-lots.html"><?=htmlspecialchars($val['name_cat']); ?></a>
          </li>
        <?php }; ?>
      </ul>
    </nav>
    <section class="lot-item container">
      <h2><?=htmlspecialchars($is_lot["name"]); ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?=htmlspecialchars($is_lot["image"]); ?>" width="730" height="548" alt="<?=htmlspecialchars($is_lot["name"]); ?>">
          </div>
          <p class="lot-item__category">Категория: <span><?=htmlspecialchars($is_lot["category"]); ?></span></p>
          <p class="lot-item__description"><?=htmlspecialchars($is_lot["description_lot"]); ?></p>
        </div>
        <div class="lot-item__right">
          <?=$rate_content; ?>
          <?=$rate_list_content; ?>
        </div>
      </div>
    </section>

