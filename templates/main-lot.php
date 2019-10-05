    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($category_list as $val) { ?>
          <li class="nav__item">
            <a href="all-lots.php?cat=<?=isset($val['id_cat']) ? $val['id_cat'] : ''; ?>"><?=isset($val['name_cat']) ? htmlspecialchars($val['name_cat']) : ''; ?></a>
          </li>
        <?php } ?>
      </ul>
    </nav>
    <section class="lot-item container">
      <h2><?=isset($is_lot["name"]) ? htmlspecialchars($is_lot["name"]) : ''; ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?=isset($is_lot["image"]) ? htmlspecialchars($is_lot["image"]) : ''; ?>" width="730" height="548" alt="<?=isset($is_lot["name"]) ? htmlspecialchars($is_lot["name"]) : ''; ?>">
          </div>
          <p class="lot-item__category">Категория: <span><?=isset($is_lot["category"]) ? htmlspecialchars($is_lot["category"]) : ''; ?></span></p>
          <p class="lot-item__description"><?=isset($is_lot["description_lot"]) ? htmlspecialchars($is_lot["description_lot"]) : ''; ?></p>
        </div>
        <div class="lot-item__right">
          <?=isset($right_content) ? $right_content : ''; ?>
          <?=isset($rate_list_content) ? $rate_list_content : ''; ?>
        </div>
      </div>
    </section>

