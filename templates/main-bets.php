    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($category_list as $val) { ?>
          <li class="nav__item">
            <a href="all-lots.php?cat=<?=isset($val['id_cat']) ? $val['id_cat'] : ''; ?>"><?=isset($val['name_cat']) ? htmlspecialchars($val['name_cat']) : ''; ?></a>
          </li>
        <?php } ?>
      </ul>
    </nav>
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
        <?php foreach ($my_bet_list as $val) { ?>
        <tr class="rates__item <?=isset($val['completion_period']) ? rates_item($val['completion_period'],  $val['winner_id']) : ''; ?>">
          <td class="rates__info">
            <div class="rates__img">
              <img src="<?=isset($val['image_lot']) ? htmlspecialchars($val['image_lot']) : ''; ?>" width="54" height="40" alt="<?=isset($val['name_lot']) ? htmlspecialchars($val['name_lot']) : ''; ?>">
            </div>
            <div>
              <h3 class="rates__title"><a href="lot.php?id=<?=isset($val['id_lot']) ? $val['id_lot'] : ''; ?>"><?=isset($val['name_lot']) ? htmlspecialchars($val['name_lot']) : ''; ?></a></h3>
              <p><?=isset($val['completion_period']) && isset($val['winner_id']) && isset($val['contact']) ? rates_contact($val['completion_period'],  $val['winner_id'], $val['contact']) : ''; ?></p>
            </div>
          </td>
          <td class="rates__category">
            <?=isset($val['name_cat']) ? htmlspecialchars($val['name_cat']) : ''; ?>
          </td>
          <td class="rates__timer">
            <div class="timer <?=isset($val['completion_period']) ? rates_timer_class($val['completion_period'],  $val['winner_id']) : ''; ?>">
              <?=isset($val['completion_period']) && isset($val['completion_date']) ? rates_timer_content($val['completion_period'],  $val['winner_id'], $val['completion_date']) : ""; ?>
            </div>
          </td>
          <td class="rates__price">
            <?=isset($val['bet_amount']) ? price_format_rate($val['bet_amount']) : ''; ?>
          </td>
          <td class="rates__time">
            <?=isset($val['period_day']) && isset($val['period_min']) && isset($val['day_month_year']) && isset($val['hour_min']) ? passedTime($val['period_day'], $val['period_min'], $val['day_month_year'], $val['hour_min']) : ''; ?>
          </td>
        </tr>
        <?php } ?>
      </table>
    </section>
