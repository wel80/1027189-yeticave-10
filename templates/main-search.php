    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($category_list as $val) { ?>
          <li class="nav__item">
            <a href="all-lots.php?cat=<?=isset($val['id_cat']) ? $val['id_cat'] : ''; ?>"><?=isset($val['name_cat']) ? htmlspecialchars($val['name_cat']) : ''; ?></a>
          </li>
        <?php } ?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
        <?php if (isset($search_text) && isset($search_lot_list)) { ?>
        <h2>Результаты поиска по запросу «<span><?=htmlspecialchars($search_text); ?></span>»</h2>
        <ul class="lots__list">
            <?php foreach ($search_lot_list as $val) { ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?=isset($val["image_lot"]) ? htmlspecialchars($val["image_lot"]) : ''; ?>" width="350" height="260" alt="<?=isset($val["name_lot"]) ? htmlspecialchars($val["name_lot"]) : ''; ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=isset($val["name_cat"]) ? htmlspecialchars($val["name_cat"]) : ''; ?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=isset($val["id_lot"]) ? $val["id_lot"] : ''; ?>"><?=isset($val["name_lot"]) ? htmlspecialchars($val["name_lot"]) : ''; ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?=isset($val["rate_price"]) ? price_format($val["rate_price"]) : price_format(isset($val["initial_price"]) ? $val["initial_price"] : ''); ?></span>
                            </div>
                            <div class="lot__timer timer <?=isset($val["completion_date"]) && rest_time($val["completion_date"])[0] === '00' ? 'timer--finishing' : ''; ?>">
                                <?=isset($val["completion_date"]) ? rest_time($val["completion_date"])[0] . ' : ' . rest_time($val["completion_date"])[1] : ''; ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
        <?php } elseif (isset($search_text)) { 
          print('<h2>Ничего не найдено по запросу «' . htmlspecialchars($search_text) . '»</h2>'); 
        } else {
          print('<h2>Вы направили пустой запрос</h2>');
        } ?>
      </section>
      <?php if (isset($page_count) && $page_count > 1) { ?>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
          <a href="search.php?page=<?=isset($page_number) && $page_number > 1 ? htmlspecialchars($page_number) - 1 : htmlspecialchars($page_number); ?>&search=<?=isset($search_text) ? htmlspecialchars($search_text) : ''; ?>">Назад</a>
        </li>
        <?php foreach ($page_list as $val) { ?>
        <li class="pagination-item <?=isset($page_number) && $page_number === $val ? 'pagination-item-active' : ''; ?>">
          <a href="search.php?page=<?=$val; ?>&search=<?=isset($search_text) ? htmlspecialchars($search_text) : ''; ?>"><?=$val; ?></a>
        </li>
        <?php } ?>
        <li class="pagination-item pagination-item-next">
          <a href="search.php?page=<?=isset($page_number) && $page_number === max($page_list) ? htmlspecialchars($page_number) : htmlspecialchars($page_number) + 1; ?>&search=<?=isset($search_text) ? htmlspecialchars($search_text) : ''; ?>">Вперед</a>
        </li>
      </ul>
      <?php } ?>
    </div>