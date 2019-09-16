    <nav class="nav">
      <ul class="nav__list container">
      <?php foreach ($category_list as $key => $val) { ?>
          <li class="nav__item">
            <a href="all-lots.php?cat=<?=$val['id_cat']; ?>"><?=$val['name_cat']; ?></a>
          </li>
        <?php }; ?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
        <h2>Все лоты в категории <span>«<?=$category_lot_list[0]['name_cat']; ?>»</span></h2>
        <ul class="lots__list">
            <?php foreach ($category_lot_list as $val) { ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?=$val["image_lot"]; ?>" width="350" height="260" alt="<?=$val["name_lot"]; ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=$val["name_cat"]; ?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$val["id_lot"]; ?>"><?=$val["name_lot"]; ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?=price_format($val["rate_price"] ? $val["rate_price"] : $val["initial_price"]); ?></span>
                            </div>
                            <div class="lot__timer timer <?=(rest_time($val["completion_date"])[0] == '00') ? 'timer--finishing' : ''; ?>">
                                <?php print((rest_time($val["completion_date"])[0] . ' : ' . rest_time($val["completion_date"])[1])); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php }; ?>
        </ul>
      </section>
      <?php if ($page_count > 1) { ?>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
          <a href="all-lots.php?page=<?=($page_number > 1) ? ($page_number - 1) : $page_number; ?>&cat=<?=$cat_number; ?>">Назад</a>
        </li>
        <?php foreach ($page_list as $val) { ?>
        <li class="pagination-item<?=($page_number == $val) ? ' pagination-item-active' : ''; ?>">
          <a href="all-lots.php?page=<?=$val; ?>&cat=<?=$cat_number; ?>"><?=$val; ?></a>
        </li>
        <?php }; ?>
        <li class="pagination-item pagination-item-next">
          <a href="all-lots.php?page=<?=($page_number == max($page_list)) ? $page_number : ($page_number + 1); ?>&cat=<?=$cat_number; ?>">Вперед</a>
        </li>
      </ul>
      <?php }; ?>
    </div>